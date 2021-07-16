<?php
/**
 * Filters CoCart to enhance the get-cart response for "v2" of the REST API.
 *
 * This is still a working progress and is by no means complete.
 * It is a preview of the new cart response in the coming v3 of CoCart for v2 of it's REST API.
 * Use only for experimenting.
 *
 * @author  Sébastien Dumont
 * @package Filters
 * @since   2.0.0
 * @version 3.0.3
 * @license GPL-2.0+
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
			add_filter( 'cocart_cart_contents', array( $this, 'return_product_details' ), 10, 4 );

			// Enhances the returned cart contents.
			add_filter( 'cocart_return_cart_contents', array( $this, 'enhance_cart_contents' ), 99 );

			// Returns cross sells under extras.
			add_filter( 'cocart_enhanced_extras', array( $this, 'return_cross_sells' ) );
		} // END __construct()

		/**
		 * Returns additional product details for each item in the cart.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 2.0.0
		 * @param   array  $cart_contents - Cart contents before modifications.
		 * @param   int    $item_key - Unique identifier for item in cart.
		 * @param   array  $cart_item - Item details.
		 * @param   object $_product - Product data.
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
				'meta'           => array(
					'type'                  => $_product->get_type(),
					'virtual'               => $_product->get_virtual(),
					'downloadable'          => $_product->get_downloadable(),
					'sku'                   => $_product->get_sku(),
					'dimensions'            => array(),
					'min_purchase_quantity' => $_product->get_min_purchase_quantity(),
					'max_purchase_quantity' => $_product->get_max_purchase_quantity(),
					'weight'                => ! empty( $_product->get_weight() ) ? array(
						'weight' => $_product->get_weight(),
						'unit'   => get_option( 'woocommerce_weight_unit' ),
					) : array(),
					'variation_data'        => CoCart_Cart_Enhanced_v1::format_variation_data( $cart_item['variation'], $_product ),
				),
				'categories'     => get_the_terms( $_product->get_id(), 'product_cat' ),
				'tags'           => get_the_terms( $_product->get_id(), 'product_tag' ),
				'cart_item_data' => apply_filters( 'cocart_cart_item_data', array() ),
			);

			$dimensions = $_product->get_dimensions( false );
			if ( ! empty( $dimensions ) ) {
				$cart_contents[ $item_key ]['meta']['dimensions'] = array(
					'length' => $dimensions['length'],
					'width'  => $dimensions['width'],
					'height' => $dimensions['height'],
					'unit'   => get_option( 'woocommerce_dimension_unit' ),
				);
			}

			// Returns the product stock status.
			$status = $_product->get_stock_status();
			$color  = '#a46497';

			switch ( $status ) {
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
				'hex_color'      => $color,
			);

			// Returns product gallery images.
			$gallery_ids = $_product->get_gallery_image_ids();

			$cart_contents[ $item_key ]['gallery'] = array();

			if ( ! empty( $gallery_ids ) ) {
				foreach ( $gallery_ids as $image_id ) {
					$cart_contents[ $item_key ]['gallery'][ $image_id ] = wp_get_attachment_image_src( $image_id, apply_filters( 'cocart_item_gallery_thumbnail_size', 'woocommerce_thumbnail' ) );
				}
			}

			// Return permalink if product is visible.
			$cart_contents[ $item_key ]['permalink'] = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';

			return $cart_contents;
		} // END return_product_details()

		/**
		 * Enhances the returned cart contents.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 2.0.3
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
				'cart_hash'      => $cart->get_cart_hash(),
				'cart_key'       => CoCart_Cart_Enhanced_v1::get_cart_key(),
				'currency'       => $this->get_store_currency(),
				'items'          => $cart_contents['items'],
				'item_count'     => $cart->get_cart_contents_count(),
				'items_weight'   => array(
					'total'       => wc_get_weight( $cart->get_cart_contents_weight(), get_option( 'woocommerce_weight_unit' ) ),
					'weight_unit' => get_option( 'woocommerce_weight_unit' ),
				),
				'needs_shipping' => $cart->needs_shipping(),
				'needs_payment'  => $cart->needs_payment(),
				'shipping'       => $this->get_shipping_details(),
				'coupons'        => array(),
			);

			// If coupons are enabled then get applied coupons.
			$coupons = wc_coupons_enabled() ? $cart->get_applied_coupons() : array();

			// If coupons are applied to the cart then expose each coupon applied.
			if ( ! empty( $coupons ) ) {
				foreach ( $coupons as $i => $coupon ) {
					$new_cart_contents['coupons'][] = array(
						'coupon'      => wc_format_coupon_code( wp_unslash( $coupon ) ),
						'label'       => esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ),
						'saving'      => CoCart_Cart_Enhanced_v1::coupon_html( $coupon, false ),
						'saving_html' => CoCart_Cart_Enhanced_v1::coupon_html( $coupon ),
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
						'fee'  => html_entity_decode( wp_strip_all_tags( CoCart_Cart_Enhanced_v1::fee_html( $fee ) ) ),
					);
				}
			}

			// Returns the cart totals.
			$new_cart_contents['totals'] = $cart->get_totals();

			// Returns extra cart data and can be filtered.
			$new_cart_contents['extras'] = apply_filters( 'cocart_enhanced_extras', array(
				'removed_items' => $cart->get_removed_cart_contents(),
			) );

			return $new_cart_contents;
		} // END enhance_cart_contents()

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

		/**
		 * Returns cross sells based on the items in the cart.
		 *
		 * @access  public
		 * @since   1.6.0
		 * @version 2.0.2
		 * @param   array $extras - Cart extras before filtered.
		 * @return  array $extras - Cart extras after filtered.
		 */
		public function return_cross_sells( $extras = array() ) {
			// Remove previous cross sells.
			unset( $extras['cross_sells'] );

			// Get visible cross sells then sort them at random.
			$cross_sells = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );

			// Handle orderby and limit results.
			$orderby     = apply_filters( 'cocart_cross_sells_orderby', 'rand' );
			$order       = apply_filters( 'cocart_cross_sells_order', 'desc' );
			$cross_sells = wc_products_array_orderby( $cross_sells, $orderby, $order );
			$limit       = apply_filters( 'cocart_cross_sells_total', 3 );
			$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;

			$extras['cross_sells'] = array();

			foreach ( $cross_sells as $cross_sell ) {
				$id = $cross_sell->get_id();

				$thumbnail_id  = apply_filters( 'cocart_cross_sell_item_thumbnail', $cross_sell->get_image_id() );
				$thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, apply_filters( 'cocart_cross_sell_item_thumbnail_size', 'woocommerce_thumbnail' ) );
				$thumbnail_src = apply_filters( 'cocart_item_thumbnail_src', $thumbnail_src[0] );

				$extras['cross_sells'][ $id ] = array(
					'id'             => $id,
					'name'           => $cross_sell->get_name(),
					'title'          => $cross_sell->get_title(),
					'slug'           => CoCart_Cart_Enhanced_v1::get_product_slug( $cross_sell ),
					'price'          => $cross_sell->get_price(),
					'regular_price'  => $cross_sell->get_regular_price(),
					'sale_price'     => $cross_sell->get_sale_price(),
					'image'          => esc_url( $thumbnail_src ),
					'average_rating' => $cross_sell->get_average_rating() > 0 ? sprintf(
						/* translators: %s: average rating */
						esc_html__( 'Rated %s out of 5', 'cocart-get-cart-enhanced' ), esc_html( $cross_sell->get_average_rating() )
					) : '',
					'on_sale'        => $cross_sell->is_on_sale() ? true : false,
					'type'           => $cross_sell->get_type(),
				);
			}

			$extras['cross_sells'] = apply_filters( 'cocart_cross_sell_contents', $extras['cross_sells'] );

			return $extras;
		} // END return_cross_sells()

		/**
		 * Returns shipping details.
		 *
		 * @access public
		 * @static
		 * @since  2.0.3
		 * @return array.
		 */
		public static function get_shipping_details() {
			// Get shipping packages.
			$packages = WC()->shipping->get_packages();

			$details = array(
				'total_packages'          => count( (array) $packages ),
				'show_package_details'    => count( (array) $packages ) > 1,
				'has_calculated_shipping' => WC()->customer->has_calculated_shipping(),
				'packages'                => array(),
			);

			$package_key = 1;

			foreach ( $packages as $i => $package ) {
				$chosen_method = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : '';
				$product_names = array();

				if ( count( (array) $packages ) > 1 ) {
					foreach ( $package['contents'] as $item_id => $values ) {
						$product_names[ $item_id ] = $values['data']->get_name() . ' x' . $values['quantity'];
					}

					$product_names = apply_filters( 'cocart_shipping_package_details_array', $product_names, $package );
				}

				$rates = array();

				$details['packages'][ $package_key ] = array(
					/* translators: %d: shipping package number */
					'package_name'          => apply_filters( 'cocart_shipping_package_name', ( ( $i + 1 ) > 1 ) ? sprintf( _x( 'Shipping %d', 'shipping packages', 'cocart-get-cart-enhanced' ), ( $i + 1 ) ) : _x( 'Shipping', 'shipping packages', 'cocart-get-cart-enhanced' ), $i, $package ),
					'rates'                 => $package['rates'],
					'package_details'       => implode( ', ', $product_names ),
					'index'                 => $i,
					'chosen_method'         => $chosen_method,
					'formatted_destination' => WC()->countries->get_formatted_address( $package['destination'], ', ' ),
				);

				// Check that there are rates available for the package.
				if ( count( (array) $package['rates'] ) > 0 ) {
					// Return each rate.
					foreach ( $package['rates'] as $key => $method ) {
						$rates[ $key ] = array(
							'key'           => $key,
							'method_id'     => $method->get_method_id(),
							'instance_id'   => $method->instance_id,
							'label'         => $method->get_label(),
							'cost'          => $method->cost,
							'html'          => html_entity_decode( wp_strip_all_tags( wc_cart_totals_shipping_method_label( $method ) ) ),
							'taxes'         => $method->taxes,
							'chosen_method' => ( $chosen_method === $key ),
						);
					}
				}

				$details['packages'][ $package_key ]['rates'] = $rates;

				$package_key++;
			}

			return $details;
		} // END get_shipping_details()

	} // END class

} // END if class exists

new CoCart_Cart_Enhanced_v2();
