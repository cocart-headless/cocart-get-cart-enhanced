<?php
/**
 * Filters CoCart to enhance the get-cart response for "v2" of the REST API.
 *
 * This is still a working progress and is by no means complete.
 * It is a preview of the new cart response in the coming v3 of CoCart for v2 of it's REST API.
 * Use only for experimenting.
 * 
 * @since 2.0.0
 */
if ( ! class_exists( 'CoCart_Cart_Enhanced_v2' ) ) {

	class CoCart_Cart_Enhanced_v2 {

		/**
		 * Filter API v2.
		 *
		 * @access public
		 */
		public function __construct() {
			// Filters in additional product data for all items.
			remove_filter( 'cocart_cart_contents', array( 'CoCart_Cart_Enhanced_v1', 'return_product_details' ), 10 );
			add_filter( 'cocart_cart_contents', array( $this, 'return_product_details' ), 10, 4 );

			// Check cart items.
			add_filter( 'cocart_return_cart_contents', array( $this, 'check_cart_items' ), 97 );

			// Check cart coupons.
			add_filter( 'cocart_return_cart_contents', array( $this, 'check_cart_coupons' ), 98 );

			// Enhances the returned cart contents.
			remove_filter( 'cocart_return_cart_contents', array( 'CoCart_Cart_Enhanced_v1', 'enhance_cart_contents' ), 99 );
			add_filter( 'cocart_return_cart_contents', array( $this, 'enhance_cart_contents' ), 99 );
		} // END __construct()

		/**
		 * Returns additional product details for each item in the cart.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 2.0.0
		 * @param   array  $cart_contents - Cart contents before modifications.
		 * @param   int    $item_key
		 * @param   array  $cart_item
		 * @param   object $_product
		 * @return  array  $cart_contents - Cart contents after modifications.
		 */
		public function return_product_details( $cart_contents, $item_key, $cart_item, $_product ) {
			$cart_contents[ $item_key ] = array(
				'id'             => $_product->get_id(),
				'name'           => apply_filters( 'cocart_product_name', $_product->get_name(), $_product, $cart_item, $item_key ),
				'title'          => apply_filters( 'cocart_product_title', $_product->get_title(), $_product, $cart_item, $item_key ),
				'price'          => $_product->get_price(),
				'line_price'     => isset( $cart_item['line_total'] ) ? $cart_item['line_total'] : $_product->get_price() * wc_stock_amount( $cart_item['quantity'] ),
				'quantity'       => $cart_item['quantity'],
				'slug'           => CoCart_Cart_Enhanced_v1::get_product_slug( $_product ),
				'meta' => array(
					'type'                  => $_product->get_type(),
					'virtual'               => $_product->get_virtual(),
					'downloadable'          => $_product->get_downloadable(),
					'sku'                   => $_product->get_sku(),
					'dimensions'            => array(),
					'min_purchase_quantity' => $_product->get_min_purchase_quantity(),
					'max_purchase_quantity' => $_product->get_max_purchase_quantity(),
					'weight'                => ! empty( $_product->get_weight() ) ? array(
						'weight'            => $_product->get_weight(),
						'unit'              => get_option( 'woocommerce_weight_unit' )
					) : array(),
					'variation_data'        => CoCart_Cart_Enhanced_v1::format_variation_data( $cart_item['variation'], $_product )
				),
				'categories'     => get_the_terms( $_product->get_id(), 'product_cat' ),
				'tags'           => get_the_terms( $_product->get_id(), 'product_tag' ),
				'cart_item_data' => apply_filters( 'cocart_cart_item_data', array() )
			);

			$dimensions = $_product->get_dimensions( false );
			if ( ! empty( $dimensions ) ) {
				$cart_contents[ $item_key ]['meta']['dimensions'] = array(
					'length' => $dimensions['length'],
					'width'  => $dimensions['width'],
					'height' => $dimensions['height'],
					'unit'   => get_option( 'woocommerce_dimension_unit' )
				);
			}

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
		 * Enhances the returned cart contents.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 2.0.0
		 * @param   array $cart_contents     - Cart contents before modifications.
		 * @return  array $new_cart_contents - Cart contents after modifications.
		 */
		public function enhance_cart_contents( $cart_contents = array() ) {
			// Get Cart.
			$cart = WC()->cart;

			// Calculate totals again to be sure they are correct.
			WC()->cart->calculate_totals();

			// Format new cart contents.
			$new_cart_contents = array(
				'cart_hash'        => $cart->get_cart_hash(),
				'cart_key'         => CoCart_Cart_Enhanced_v1::get_cart_key(),
				'currency'         => $this->get_store_currency(),
				'items'            => $cart_contents,
				'item_count'       => $cart->get_cart_contents_count(),
				'items_weight'     => array(
					'total'       => wc_get_weight( $cart->get_cart_contents_weight(), get_option( 'woocommerce_weight_unit' ) ),
					'weight_unit' => get_option( 'woocommerce_weight_unit' )
				),
				'needs_shipping'   => $cart->needs_shipping(),
				'needs_payment'    => $cart->needs_payment(),
				'shipping_methods' => CoCart_Cart_Enhanced_v1::get_available_shipping_methods(),
				'coupons'          => array()
			);

			// If coupons are enabled then get applied coupons.
			$coupons = wc_coupons_enabled() ? $cart->get_applied_coupons() : array();

			// If coupons are applied to the cart then expose each coupon applied.
			if ( ! empty( $coupons ) ) {
				foreach ( $coupons as $code => $coupon ) {
					$new_cart_contents['coupons'][ $code ] = array(
						'coupon'      => wc_format_coupon_code( wp_unslash( $coupon ) ),
						'label'       => esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ),
						'saving'      => CoCart_Cart_Enhanced_v1::coupon_html( $coupon, false ),
						'saving_html' => CoCart_Cart_Enhanced_v1::coupon_html( $coupon )
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
						'fee'  => html_entity_decode( strip_tags( CoCart_Cart_Enhanced_v1::fee_html( $fee ) ) )
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

			// Returns extra cart data and can be filtered.
			$new_cart_contents['extras'] = apply_filters( 'cocart_enhanced_extras', array(
				'removed_items' => $cart->get_removed_cart_contents()
			) );

			return $new_cart_contents;
		} // END enhance_cart_contents()

		/**
		 * Check all cart items for validity and stock.
		 *
		 * @access public
		 * @since  2.0.0
		 * @return $cart_contents
		 */
		public function check_cart_items( $cart_contents = array() ) {
			$result = $this->check_cart_item_validity();

			if ( is_wp_error( $result ) ) {
				wc_add_notice( $result->get_error_message(), 'error' );
				$return = false;
			}

			$result = $this->check_cart_item_stock();

			if ( is_wp_error( $result ) ) {
				wc_add_notice( $result->get_error_message(), 'error' );
			}

			return $cart_contents;
		} // END check_cart_items()

		/**
		 * Looks through cart items and checks the products are not trashed or deleted.
		 *
		 * @access public
		 * @since  2.0.0
		 * @return bool|WP_Error
		 */
		public function check_cart_item_validity() {
			$return = true;

			$cart = WC()->cart;

			foreach ( $cart->get_cart() as $cart_item_key => $values ) {
				$product = $values['data'];

				if ( ! $product || ! $product->exists() || 'trash' === $product->get_status() ) {
					$cart->set_quantity( $cart_item_key, 0 );
					$return = new WP_Error( 'invalid', __( 'An item which is no longer available was removed from your cart.', 'cocart-get-cart-enhanced' ) );
				}
			}

			return $return;
		} // END check_cart_item_validity()

		/**
		 * Looks through the cart to check each item is in stock. If not, add an error.
		 *
		 * @access public
		 * @since  2.0.0
		 * @return bool|WP_Error
		 */
		public function check_cart_item_stock() {
			$cart = WC()->cart;

			$error                    = new WP_Error();
			$product_qty_in_cart      = $cart->get_cart_item_quantities();
			$current_session_order_id = isset( WC()->session->order_awaiting_payment ) ? absint( WC()->session->order_awaiting_payment ) : 0;

			foreach ( $cart->get_cart() as $cart_item_key => $values ) {
				$product = $values['data'];

				// Check stock based on stock-status.
				if ( ! $product->is_in_stock() ) {
					/* translators: %s: product name */
					$error->add( 'out-of-stock', sprintf( __( 'Sorry, "%s" is not in stock. Please edit your cart and try again. We apologize for any inconvenience caused.', 'cocart-get-cart-enhanced' ), $product->get_name() ) );
					return $error;
				}

				// We only need to check products managing stock, with a limited stock qty.
				if ( ! $product->managing_stock() || $product->backorders_allowed() ) {
					continue;
				}

				// Check stock based on all items in the cart and consider any held stock within pending orders.
				$held_stock     = wc_get_held_stock_quantity( $product, $current_session_order_id );
				$required_stock = $product_qty_in_cart[ $product->get_stock_managed_by_id() ];

				/**
				 * Allows filter if product have enough stock to get added to the cart.
				 *
				 * @param bool       $has_stock If have enough stock.
				 * @param WC_Product $product   Product instance.
				 * @param array      $values    Cart item values.
				 */
				if ( apply_filters( 'cocart_cart_item_required_stock_is_not_enough', $product->get_stock_quantity() < ( $held_stock + $required_stock ), $product, $values ) ) {
					/* translators: 1: product name 2: quantity in stock */
					$error->add( 'out-of-stock', sprintf( __( 'Sorry, we do not have enough "%1$s" in stock to fulfill your order (%2$s available). We apologize for any inconvenience caused.', 'cocart-get-cart-enhanced' ), $product->get_name(), wc_format_stock_quantity_for_display( $product->get_stock_quantity() - $held_stock, $product ) ) );
					return $error;
				}
			}

			return true;
		} // END check_cart_item_stock()

		/**
		 * Check cart coupons for errors.
		 *
		 * @access public
		 * @since  2.0.0
		 */
		public function check_cart_coupons() {
			$cart = WC()->cart;

			foreach ( $cart->get_applied_coupons() as $code ) {
				$coupon = new WC_Coupon( $code );

				if ( ! $coupon->is_valid() ) {
					$coupon->add_coupon_message( 101 );
					$cart->remove_coupon( $code );
				}
			}
		} // END check_cart_coupons()

		/**
		 * Prepares a list of store currency data to return in responses.
		 *
		 * @access protected
		 * @since  2.0.0
		 * @return array
		 */
		protected function get_store_currency() {
			$position = get_option( 'woocommerce_currency_pos' );
			$symbol   = html_entity_decode( get_woocommerce_currency_symbol() );
			$prefix   = '';
			$suffix   = '';

			switch ( $position ) {
				case 'left_space':
					$prefix = $symbol . ' ';
					break;
				case 'left':
					$prefix = $symbol;
					break;
				case 'right_space':
					$suffix = ' ' . $symbol;
					break;
				case 'right':
					$suffix = $symbol;
					break;
			}

			return array(
				'currency_code'               => get_woocommerce_currency(),
				'currency_symbol'             => $symbol,
				'currency_minor_unit'         => wc_get_price_decimals(),
				'currency_decimal_separator'  => wc_get_price_decimal_separator(),
				'currency_thousand_separator' => wc_get_price_thousand_separator(),
				'currency_prefix'             => $prefix,
				'currency_suffix'             => $suffix,
			);
		} // END get_store_currency()

	} // END class

} // END if class exists

new CoCart_Cart_Enhanced_v2();
