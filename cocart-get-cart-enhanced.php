<?php
/*
 * Plugin Name: CoCart - Get Cart Enhanced
 * Plugin URI:  https://cocart.xyz
 * Description: Enhances the get cart response to return the cart totals, coupons applied and additional product details.
 * Author:      Sébastien Dumont
 * Author URI:  https://sebastiendumont.com
 * Version:     1.0.0
 * Text Domain: cocart-get-cart-enhanced
 * Domain Path: /languages/
 *
 * WC requires at least: 3.6.0
 * WC tested up to: 3.9.2
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

			// Load translation files.
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		} // END __construct()

		/**
		 * Returns additional product details for each item in the cart.
		 *
		 * @access public
		 * @param  array  $cart_contents
		 * @param  int    $item_key
		 * @param  array  $cart_item
		 * @param  object $_product
		 * @return array  $cart_contents
		 */
		public function return_product_details( $cart_contents, $item_key, $cart_item, $_product ) {
			// Return permalink if product is visible.
			if ( $_product->is_visible() ) {
				$cart_contents[ $item_key ]['permalink'] = $_product->get_permalink( $cart_item );
			}

			// Returns the product categories.
			$cart_contents[ $item_key ]['categories'] = get_the_terms( $_product->get_category_ids(), 'product_cat' );

			// Returns the product tags.
			$cart_contents[ $item_key ]['tags'] = get_the_terms( $_product->get_tag_ids(), 'product_tag' );

			// Returns the product SKU.
			$cart_contents[ $item_key ]['sku'] = $_product->get_sku();

			// Returns the product weight.
			$cart_contents[ $item_key ]['weight'] = $_product->get_weight();

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

			// If product is not sold individually return the maximum quantity that can be purchased.
			if ( ! $_product->is_sold_individually() ) {
				$cart_contents[ $item_key ]['max_purchase_quantity'] = $_product->get_max_purchase_quantity();
			}

			// Returns product gallery images.
			$gallery_ids = $_product->get_gallery_image_ids();

			if ( ! empty( $gallery_ids ) ) {
				$cart_contents[ $item_key ]['gallery'] = array();

				foreach( $gallery_ids as $image_id ) {
					$cart_contents[ $item_key ]['gallery'][ $image_id ] = wp_get_attachment_image_src( $image_id, apply_filters( 'cocart_item_gallery_thumbnail_size', 'woocommerce_thumbnail' ) );
				}
			}

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
		 * 1. Returns the cart hash.
		 * 2. Places the cart contents under a new array called `items`.
		 * 3. Returns the item count of all items in the cart.
		 * 4. Returns the shipping status of the cart.
		 * 5. Returns the payment status of the cart.
		 * 6. Returns coupons applied to the cart if enabled.
		 * 7. Returns additional fees applied to the cart.
		 * 8. Returns the cart totals.
		 *
		 * @access public
		 * @param  array $cart_contents
		 * @return array $new_cart_contents
		 */
		public function enhance_cart_contents( $cart_contents ) {
			$new_cart_contents = array();

			// Get Cart.
			$cart = WC()->cart;

			// Cart hash.
			$new_cart_contents['cart_hash'] = $cart->get_cart_hash();

			// Places the cart contents under a new array.
			$new_cart_contents['items'] = $cart_contents;

			// Returns item count of all items.
			$new_cart_contents['items_counted'] = $cart->get_cart_contents_count();

			// Returns the shipping status of the cart.
			$new_cart_contents['needs_shipping'] = $cart->needs_shipping();

			// Returns the payment status of the cart.
			$new_cart_contents['needs_payment'] = $cart->needs_payment();

			// Returns each coupon applied and coupon total applied.
			$coupons = wc_coupons_enabled() ? $cart->get_applied_coupons() : array();

			if ( ! empty( $coupons ) ) {
				$new_cart_contents['coupons'] = array();

				foreach ( $coupons as $code => $coupon ) {
					$new_cart_contents['coupons'][ $code ] = array(
						'code'  => esc_attr( sanitize_title( $code ) ),
						'label' => esc_attr( wc_cart_totals_coupon_label( $coupon ) ),
						'total' => wc_cart_totals_coupon_html( $coupon )
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
						'fee'  => wc_cart_totals_fee_html( $fee )
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

			$new_cart_contents['totals'] = $new_totals;

			return $new_cart_contents;
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

	} // END class

} // END if class exists

new CoCart_Get_Cart_Enhanced();
