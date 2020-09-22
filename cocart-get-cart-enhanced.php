<?php
/*
 * Plugin Name: CoCart - Get Cart Enhanced
 * Plugin URI:  https://cocart.xyz
 * Description: Enhances the get cart response to return the cart totals, coupons applied, additional product details and notices.
 * Author:      Sébastien Dumont
 * Author URI:  https://sebastiendumont.com
 * Version:     1.9.2
 * Text Domain: cocart-get-cart-enhanced
 * Domain Path: /languages/
 *
 * Requires at least: 5.2
 * Requires PHP: 7.0
 * WC requires at least: 4.0.0
 * WC tested up to: 4.5.2
 *
 * Copyright: © 2020 Sébastien Dumont, (mailme@sebastiendumont.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! class_exists( 'CoCart_Get_Cart_Enhanced' ) ) {
	class CoCart_Get_Cart_Enhanced {

		/**
		 * Load the plugin.
		 *
		 * @access public
		 */
		public function __construct() {
			// Filters in additional product data for all items.
			add_filter( 'cocart_cart_contents', array( $this, 'return_product_details' ), 10, 4 );

			// Returns the cart contents without the cart item key as the parent array.
			add_filter( 'cocart_return_cart_contents', array( $this, 'remove_parent_cart_item_key' ), 0 );
			add_filter( 'cocart_return_removed_cart_contents', array( $this, 'remove_parent_cart_item_key' ), 0 );

			// Enhances the returned cart contents.
			add_filter( 'cocart_return_cart_contents', array( $this, 'enhance_cart_contents' ), 99 );
			add_filter( 'cocart_return_cart_contents', array( $this, 'maybe_return_notices' ), 100 );

			// Enhances an empty cart response.
			add_filter( 'cocart_return_empty_cart', array( $this, 'enhance_cart_contents' ), 99 );

			// Returns cross sells under extras.
			add_filter( 'cocart_enhanced_extras', array( $this, 'return_cross_sells' ) );

			// Load translation files.
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			// Plugin activation and deactivation.
			register_activation_hook( __FILE__, array( $this, 'activated' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivated' ) );
		} // END __construct()

		/**
		 * Returns additional product details for each item in the cart.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 1.9.0
		 * @param   array  $cart_contents - Cart contents before modifications.
		 * @param   int    $item_key
		 * @param   array  $cart_item
		 * @param   object $_product
		 * @return  array  $cart_contents - Cart contents after modifications.
		 */
		public function return_product_details( $cart_contents, $item_key, $cart_item, $_product ) {
			// Returns product slug.
			$cart_contents[ $item_key ]['slug'] = $this->get_product_slug( $_product );

			// Returns product type.
			$cart_contents[ $item_key ]['product_type'] = $_product->get_type();

			// Returns the product categories.
			$cart_contents[ $item_key ]['categories'] = get_the_terms( $_product->get_id(), 'product_cat' );

			// Returns the product tags.
			$cart_contents[ $item_key ]['tags'] = get_the_terms( $_product->get_id(), 'product_tag' );

			// Returns the product SKU.
			$cart_contents[ $item_key ]['sku'] = $_product->get_sku();

			// Returns the product weight if any.
			$cart_contents[ $item_key ]['weight'] = ! empty( $_product->get_weight() ) ? array(
				'weight' => $_product->get_weight(),
				'unit'   => get_option( 'woocommerce_weight_unit' )
			) : array();

			// Returns the product dimensions.
			$cart_contents[ $item_key ]['dimensions'] = array();

			$dimensions = $_product->get_dimensions( false );
			if ( ! empty( $dimensions ) ) {
				$cart_contents[ $item_key ]['dimensions'] = array(
					'length' => $dimensions['length'],
					'width'  => $dimensions['width'],
					'height' => $dimensions['height'],
					'unit'   => get_option( 'woocommerce_dimension_unit' )
				);
			}

			// Returns product price and line price.
			$cart_contents[ $item_key ]['price_raw'] = $_product->get_price();

			$cart_contents[ $item_key ]['price'] = wc_format_decimal( $_product->get_price(), wc_get_price_decimals() );

			$cart_contents[ $item_key ]['line_price'] = wc_format_decimal( isset( $cart_item['line_total'] ) ? $cart_item['line_total'] : $_product->get_price() * wc_stock_amount( $cart_item['quantity'] ), wc_get_price_decimals() );

			// Returns variation data formatted.
			$cart_contents[ $item_key ]['variation_data'] = $this->format_variation_data( $cart_item['variation'], $_product );

			// Returns the product stock status.
			$status = $_product->get_stock_status();
			$color  = '#a46497';

			switch( $status ) {
				case 'instock':
					$status = __( 'In Stock', 'cocart-get-cart-enhanced' );
					$color  = '#7ad03a';
					break;
				case 'outofstock':
					$status = __( 'Out of Stock', 'cocart-get-cart-enhanced' );
					$color  = '#a00';
					break;
				case 'onbackorder':
					$status = __( 'Available on backorder', 'cocart-get-cart-enhanced' );
					break;
			}

			$cart_contents[ $item_key ]['stock_status'] = array(
				'status'         => $status,
				'stock_quantity' => $_product->get_stock_quantity(),
				'hex_color'      => $color
			);

			// Returns the minimum and maximum quantity that can be purchased.
			$cart_contents[ $item_key ]['min_purchase_quantity'] = $_product->get_min_purchase_quantity();
			$cart_contents[ $item_key ]['max_purchase_quantity'] = $_product->get_max_purchase_quantity();

			// Returns product gallery images.
			$gallery_ids = $_product->get_gallery_image_ids();

			$cart_contents[ $item_key ]['gallery'] = array();

			if ( ! empty( $gallery_ids ) ) {
				foreach( $gallery_ids as $image_id ) {
					$cart_contents[ $item_key ]['gallery'][ $image_id ] = wp_get_attachment_image_src( $image_id, apply_filters( 'cocart_item_gallery_thumbnail_size', 'woocommerce_thumbnail' ) );
				}
			}

			// Return permalink if product is visible.
			$cart_contents[ $item_key ]['permalink'] = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';

			return $cart_contents;
		}

		/**
		 * Returns the cart contents without the cart item key as the parent array.
		 *
		 * @access public
		 * @param  array $cart_contents
		 * @return array $cart_contents
		 */
		public function remove_parent_cart_item_key( $cart_contents ) {
			$new_cart_contents = array();

			foreach ( $cart_contents as $item_key => $cart_item ) {
				$new_cart_contents[] = $cart_item;
			}

			return $new_cart_contents;
		}

		/**
		 * Enhances the returned cart contents.
		 *
		 * 1.  Returns cart currency.
		 * 2.  Returns the cart hash.
		 * 3.  Returns the cart key.
		 * 4.  Places the cart contents under a new array called `items`.
		 * 5.  Returns the item count of all items in the cart.
		 * 6.  Returns the shipping status of the cart.
		 * 7.  Returns the payment status of the cart.
		 * 8.  Returns coupons applied to the cart if enabled.
		 * 9.  Returns additional fees applied to the cart.
		 * 10. Returns the cart totals.
		 * 11. Returns the total weight of the cart.
		 * 12. Returns cart extras.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 1.9.1
		 * @param   array $cart_contents     - Cart contents before modifications.
		 * @return  array $new_cart_contents - Cart contents after modifications.
		 */
		public function enhance_cart_contents( $cart_contents = array() ) {
			$new_cart_contents = array();

			// Get Cart.
			$cart = WC()->cart;

			// Currency.
			$new_cart_contents['currency'] = get_woocommerce_currency();

			// Cart Key.
			$new_cart_contents['cart_key'] = $this->get_cart_key();

			// Cart hash.
			$new_cart_contents['cart_hash'] = $cart->get_cart_hash();

			// Places the cart contents under a new array.
			$new_cart_contents['items'] = $cart_contents;

			// Returns item count of all items.
			$new_cart_contents['item_count'] = $cart->get_cart_contents_count();

			// Returns the shipping status of the cart.
			$new_cart_contents['needs_shipping'] = $cart->needs_shipping();

			// Returns the payment status of the cart.
			$new_cart_contents['needs_payment'] = $cart->needs_payment();

			// If coupons are enabled then get applied coupons.
			$coupons = wc_coupons_enabled() ? $cart->get_applied_coupons() : array();

			$new_cart_contents['coupons'] = array();

			// If coupons are applied to the cart then expose each coupon applied.
			if ( ! empty( $coupons ) ) {
				foreach ( $coupons as $code => $coupon ) {
					$new_cart_contents['coupons'][ $code ] = array(
						'coupon'      => wc_format_coupon_code( wp_unslash( $coupon ) ),
						'label'       => esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ),
						'saving'      => $this->coupon_html( $coupon, false ),
						'saving_html' => $this->coupon_html( $coupon )
					);
				}
			}

			// Returns the cart fees.
			$cart_fees = $cart->get_fees();

			if ( ! empty( $cart_fees ) ) {
				$new_cart_contents['cart_fees'] = array();

				foreach ( $cart_fees as $key => $fee ) {
					$new_cart_contents['cart_fees'][ $key ] = array(
						'name' => esc_html( $fee->name ),
						'fee'  => html_entity_decode( strip_tags( $this->fee_html( $fee ) ) )
					);
				}
			}

			// Returns the cart totals.
			$totals = $cart->get_totals();

			$new_totals = array();

			$ignore_convert = array(
				'shipping_taxes',
				'cart_contents_taxes',
				'fee_taxes'
			);

			foreach( $totals as $type => $sum ) {
				if ( in_array( $type, $ignore_convert ) ) {
					$new_totals[ $type ] = $sum;
				} else {
					if ( is_string( $sum ) ) {
						$new_totals[ $type ] = html_entity_decode( strip_tags( wc_price( $sum ) ) );
					}
					else {
						$new_totals[ $type ] = html_entity_decode( strip_tags( wc_price( strval( $sum ) ) ) );
					}
				}
			}

			/**
			 * Filters totals.
			 *
			 * @since 1.6.6
			 * @param array $new_totals All cart totals
			 */
			$new_totals = apply_filters( 'cocart_enhanced_totals', $new_totals );

			$new_cart_contents['totals'] = $new_totals;

			// Returns total weight of cart.
			$new_cart_contents['total_weight'] = array(
				'total'       => wc_get_weight( $cart->get_cart_contents_weight(), get_option( 'woocommerce_weight_unit' ) ),
				'weight_unit' => get_option( 'woocommerce_weight_unit' )
			);

			// Returns extra cart data and can be filtered.
			$new_cart_contents['extras'] = apply_filters( 'cocart_enhanced_extras', array(
				'removed_items' => $cart->get_removed_cart_contents()
			) );

			return $new_cart_contents;
		}

		/**
		 * Returns cross sells based on the items in the cart.
		 *
		 * @access  public
		 * @since   1.6.0
		 * @version 1.9.0
		 * @param   array $extras - Cart extras before filtered.
		 * @return  array $extras - Cart extras after filtered.
		 */
		public function return_cross_sells( $extras = array() ) {
			// Get visible cross sells then sort them at random.
			$cross_sells = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );

			// Handle orderby and limit results.
			$orderby     = apply_filters( 'cocart_cross_sells_orderby', 'rand' );
			$order       = apply_filters( 'cocart_cross_sells_order', 'desc' );
			$cross_sells = wc_products_array_orderby( $cross_sells, $orderby, $order );
			$limit       = apply_filters( 'cocart_cross_sells_total', 3 );
			$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;

			$extras['cross_sells'] = array();

			foreach( $cross_sells as $cross_sell ) {
				$id = $cross_sell->get_id();

				$thumbnail_id  = apply_filters( 'cocart_cross_sell_item_thumbnail', $cross_sell->get_image_id() );
				$thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, apply_filters( 'cocart_cross_sell_item_thumbnail_size', 'woocommerce_thumbnail' ) );
				$thumbnail_src = apply_filters( 'cocart_item_thumbnail_src', $thumbnail_src[0] );

				$extras['cross_sells'][ $id ] = array(
					'product_id'             => $id,
					'product_name'           => $cross_sell->get_name(),
					'product_title'          => $cross_sell->get_title(),
					'product_slug'           => $this->get_product_slug( $cross_sell ),
					'product_price'          => html_entity_decode( strip_tags( wc_price( $cross_sell->get_price() ) ) ),
					'product_regular_price'  => html_entity_decode( strip_tags( wc_price( $cross_sell->get_regular_price() ) ) ),
					'product_sale_price'     => html_entity_decode( strip_tags( wc_price( $cross_sell->get_sale_price() ) ) ),
					'product_image'          => esc_url( $thumbnail_src ),
					'product_average_rating' => $cross_sell->get_average_rating() > 0 ? sprintf(
						/* translators: %s: average rating */
						esc_html__( 'Rated %s out of 5', 'cocart-get-cart-enhanced' ), esc_html( $cross_sell->get_average_rating() )
					) : '',
					'product_on_sale'        => $cross_sell->is_on_sale() ? true : false,
					'product_type'           => $cross_sell->get_type()
				);
			}

			$extras['cross_sells'] = apply_filters( 'cocart_cross_sell_contents', $extras['cross_sells'] );

			return $extras;
		}

		/**
		 * Returns the cart key.
		 *
		 * @access  public
		 * @since   1.3.0
		 * @version 1.6.2
		 * @return  string
		 */
		public function get_cart_key() {
			if ( ! class_exists( 'CoCart_Session_Handler' ) || ! WC()->session instanceof CoCart_Session_Handler ) {
				return;
			}

			// Current user ID.
			$cart_key = strval( get_current_user_id() );

			// Get cart cookie... if any.
			$cookie = WC()->session->get_session_cookie();

			// Does a cookie exist?
			if ( $cookie ) {
				$cart_key = $cookie[0];
			}

			// Check if we requested to load a specific cart.
			if ( isset( $_REQUEST['cart_key'] ) || isset( $_REQUEST['id'] ) ) {
				$cart_key = isset( $_REQUEST['cart_key'] ) ? $_REQUEST['cart_key'] : $_REQUEST['id'];
			}

			return $cart_key;
		}

		/**
		 * Return notices in cart if any.
		 *
		 * @access  public
		 * @since   1.1.0
		 * @version 1.6.4
		 * @param   array $cart_contents - Cart contents before modifications.
		 * @return  array $cart_contents - Cart contents after modifications.
		 */
		public function maybe_return_notices( $cart_contents = array() ) {
			$notice_count = 0;
			$all_notices  = WC()->session->get( 'wc_notices', array() );

			foreach ( $all_notices as $notices ) {
				$notice_count += count( $notices );
			}

			$cart_contents['notices'] = $notice_count > 0 ? $this->cocart_print_notices() : array();

			return $cart_contents;
		}

		/**
		 * Returns messages and errors which are stored in the session, then clears them.
		 *
		 * @access  public
		 * @since   1.1.0
		 * @version 1.6.4
		 * @return  array
		 */
		protected function cocart_print_notices() {
			$all_notices  = WC()->session->get( 'wc_notices', array() );
			$notice_types = apply_filters( 'cocart_notice_types', array( 'error', 'success', 'notice', 'info' ) );
			$notices      = array();

			foreach ( $notice_types as $notice_type ) {
				if ( wc_notice_count( $notice_type ) > 0 ) {
					foreach ( $all_notices[ $notice_type ] as $key => $notice ) {
						$notices[ $notice_type ][$key] = wc_kses_notice( $notice['notice'] );
					}
				}
			}

			wc_clear_notices();

			return $notices;
		}

		/**
		 * Format variation data, for example convert slugs such as attribute_pa_size to Size.
		 *
		 * @access  protected
		 * @since   1.4.0
		 * @version 1.6.5
		 * @param   array      $variation_data Array of data from the cart.
		 * @param   WC_Product $product Product data.
		 * @return  array
		 */
		protected function format_variation_data( $variation_data, $product ) {
			$return = array();

			if ( empty( $variation_data ) ) {
				return $return;
			}

			foreach ( $variation_data as $key => $value ) {
				$taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $key ) ) );

				if ( taxonomy_exists( $taxonomy ) ) {
					// If this is a term slug, get the term's nice name.
					$term = get_term_by( 'slug', $value, $taxonomy );
					if ( ! is_wp_error( $term ) && $term && $term->name ) {
						$value = $term->name;
					}
					$label = wc_attribute_label( $taxonomy );
				} else {
					// If this is a custom option slug, get the options name.
					$value = apply_filters( 'cocart_variation_option_name', $value, null, $taxonomy, $product );
					$label = wc_attribute_label( str_replace( 'attribute_', '', $key ), $product );
				}

				$return[ $label ] = $value;
			}

			return $return;
		}

		/**
		 * Get coupon in HTML.
		 *
		 * @access public
		 * @since  1.4.0
		 * @param  string|WC_Coupon $coupon Coupon data or code.
		 * @param  bool             $formatted Formats the saving amount.
		 * @return string           The coupon in HTML.
		 */
		public function coupon_html( $coupon, $formatted = true ) {
			if ( is_string( $coupon ) ) {
				$coupon = new WC_Coupon( $coupon );
			}

			$discount_amount_html = '';

			$amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );

			if ( $formatted ) {
				$savings = wc_price( $amount );
			}
			else {
				$savings = wc_format_decimal( $amount, wc_get_price_decimals() );
			}

			$discount_amount_html = '-' . html_entity_decode( strip_tags( $savings ) );

			if ( $coupon->get_free_shipping() && empty( $amount ) ) {
				$discount_amount_html = __( 'Free shipping coupon', 'cocart-get-cart-enhanced' );
			}

			$discount_amount_html = apply_filters( 'cocart_coupon_discount_amount_html', $discount_amount_html, $coupon );

			return $discount_amount_html;
		}

		/**
		 * Get the fee value.
		 * 
		 * @access public
		 * @since 1.5.0
		 * @param object $fee Fee data.
		 */
		public function fee_html( $fee ) {
			$cart_totals_fee_html = WC()->cart->display_prices_including_tax() ? wc_price( $fee->total + $fee->tax ) : wc_price( $fee->total );

			return apply_filters( 'cocart_cart_totals_fee_html', $cart_totals_fee_html, $fee );
		}

		/**
		 * Get the main product slug even if the product type is a variation.
		 *
		 * @access public
		 * @since  1.9.0
		 * @param  WC_Product $object
		 * @return string
		 */
		public function get_product_slug( $object ) {
			$product_type = $object->get_type();

			if ( 'variable' === $product_type || 'variation' === $product_type ) {
				$product = wc_get_product( $object->get_parent_id() );

				$product_slug = $product->get_slug();
			} else {
				$product_slug = $object->get_slug();
			}

			return $product_slug;
		}

		/**
		 * Make the plugin translation ready.
		 *
		 * Translations should be added in the WordPress language directory:
		 *      - WP_LANG_DIR/plugins/cocart-get-cart-enhanced-LOCALE.mo
		 *
		 * @access public
		 * @return void
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'cocart-get-cart-enhanced', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Runs when the plugin is activated.
		 *
		 * Adds plugin to list of installed CoCart add-ons.
		 *
		 * @access public
		 */
		public function activated() {
			$addons_installed = get_site_option( 'cocart_addons_installed', array() );

			$plugin = plugin_basename( __FILE__ );

			// Check if plugin is already added to list of installed add-ons.
			if ( ! in_array( $plugin, $addons_installed, true ) ) {
				array_push( $addons_installed, $plugin );
				update_site_option( 'cocart_addons_installed', $addons_installed );
			}
		} // END activated()

		/**
		 * Runs when the plugin is deactivated.
		 *
		 * Removes plugin from list of installed CoCart add-ons.
		 *
		 * @access public
		 */
		public function deactivated() {
			$addons_installed = get_site_option( 'cocart_addons_installed', array() );

			$plugin = plugin_basename( __FILE__ );
			
			// Remove plugin from list of installed add-ons.
			if ( in_array( $plugin, $addons_installed, true ) ) {
				$addons_installed = array_diff( $addons_installed, array( $plugin ) );
				update_site_option( 'cocart_addons_installed', $addons_installed );
			}
		} // END deactivated()

	} // END class

} // END if class exists

new CoCart_Get_Cart_Enhanced();
