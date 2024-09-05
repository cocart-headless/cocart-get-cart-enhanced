<?php
/**
 * Filters CoCart cart response to add additional data for "API v2".
 *
 * @author  Sébastien Dumont
 * @package Filters
 * @since   3.0.0
 * @version 4.0.5
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
			add_filter( 'cocart_cart_items', array( $this, 'return_product_details' ), 10, 4 );

			// Filters in discount status of items and the discounted price if any.
			add_filter( 'cocart_cart_items', array( $this, 'is_item_discounted' ), 10, 4 );
		} // END __construct()

		/**
		 * Returns additional product details for each item in the cart.
		 *
		 * @access public
		 *
		 * @since 3.0.0 Introduced.
		 * @since 4.0.0 Added parent ID for a variation and visible product attributes for all products that is not a variation product.
		 *
		 * @param array  $cart_contents Cart contents before modifications.
		 * @param int    $item_key      Unique identifier for item in cart.
		 * @param array  $cart_item     Item details.
		 * @param object $product       Product data.
		 *
		 * @return array $cart_contents Cart contents after modifications.
		 */
		public function return_product_details( $cart_contents, $item_key, $cart_item, $product ) {
			// Additional meta.
			if ( isset( $cart_contents[ $item_key ]['meta'] ) ) {
				if ( ! empty( $cart_contents[ $item_key ]['meta']['variation'] ) ) {
					$cart_contents[ $item_key ]['meta']['variation']['parent_id'] = $product->get_parent_id();
				}
				$cart_contents[ $item_key ]['meta']['attributes']   = function_exists( 'cocart_format_attribute_data' ) ? cocart_format_attribute_data( $product ) : array();
				$cart_contents[ $item_key ]['meta']['virtual']      = $product->get_virtual();
				$cart_contents[ $item_key ]['meta']['downloadable'] = $product->get_downloadable();
			}

			// Categories and Tags.
			$cart_contents[ $item_key ]['categories'] = get_the_terms( $product->get_id(), 'product_cat' );
			$cart_contents[ $item_key ]['tags']       = get_the_terms( $product->get_id(), 'product_tag' );

			// Product stock status.
			$status = $product->get_stock_status();
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
				'stock_quantity' => $product->get_stock_quantity(),
				'hex_color'      => $color,
			);

			// Product gallery images.
			$gallery_ids = $product->get_gallery_image_ids();

			$cart_contents[ $item_key ]['gallery'] = array();

			if ( ! empty( $gallery_ids ) ) {
				foreach ( $gallery_ids as $image_id ) {
					/**
					 * Filters the gallery ID for found image.
					 *
					 * @param int    $image_id  Product thumbnail ID.
					 * @param array  $cart_item The cart item data.
					 * @param string $item_key  Generated ID based on the product information when added to the cart.
					 */
					$gallery_id = apply_filters( 'cocart_item_gallery', $image_id, $cart_item, $item_key );

					if ( empty( $gallery_id ) ) {
						continue;
					}

					$thumbnail_src = wp_get_attachment_image_src( $gallery_id, $this->get_gallery_thumbnail_size() );
					$thumbnail_src = ! empty( $thumbnail_src[0] ) ? $thumbnail_src[0] : '';

					/**
					 * Filters the source of the product gallery image.
					 *
					 * @param string $thumbnail_src URL of the product thumbnail.
					 * @param array  $cart_item     The cart item data.
					 * @param string $item_key      Generated ID based on the product information when added to the cart.
					 */
					$gallery_src = apply_filters( 'cocart_item_thumbnail_src', $thumbnail_src, $cart_item, $item_key );

					$cart_contents[ $item_key ]['gallery'][ $gallery_id ] = esc_url( $gallery_src );
				}
			}

			// Permalink of product if visible.
			if ( version_compare( COCART_VERSION, '5.0.0', '<' ) ) {
				$cart_contents[ $item_key ]['permalink'] = $product->is_visible() ? $product->get_permalink( $cart_item ) : '';
			} else {
				$cart_contents[ $item_key ]['permalink'] = $product->is_visible() ? function_exists( 'cocart_get_permalink' ) ? cocart_get_permalink( get_permalink( $product->get_id() ) ) : $product->get_permalink( $cart_item ) : '';
			}

			return $cart_contents;
		} // END return_product_details()

		/**
		 * Returns the discount status of items and the discounted price if any.
		 *
		 * @access public
		 *
		 * @since 3.0.1 Introduced.
		 *
		 * @param array  $cart_contents Cart contents before modifications.
		 * @param int    $item_key      Unique identifier for item in cart.
		 * @param array  $cart_item     Item details.
		 * @param object $product       Product data.
		 *
		 * @return array $cart_contents Cart contents after modifications.
		 */
		public function is_item_discounted( $cart_contents, $item_key, $cart_item, $product ) {
			$regular_price    = $product->get_regular_price();
			$sale_price       = $product->get_sale_price();
			$quantity         = (int) $cart_contents[ $item_key ]['quantity']['value'];
			$discounted_price = 0;

			if ( $product->is_on_sale() ) {
				$discounted_price += ( $regular_price - $sale_price ) * $quantity;
			}

			if ( $discounted_price > 0 ) {
				$cart_contents[ $item_key ]['is_discounted'] = true;
			} else {
				$cart_contents[ $item_key ]['is_discounted'] = false;
			}

			// Identify version of CoCart installed to return the following values correctly.
			if ( version_compare( COCART_VERSION, '4.4.0', '>=' ) ) {
				$cart_contents[ $item_key ]['price_regular']    = function_exists( 'cocart_format_money' ) ? cocart_format_money( $regular_price ) : cocart_prepare_money_response( $regular_price );
				$cart_contents[ $item_key ]['price_sale']       = function_exists( 'cocart_format_money' ) ? cocart_format_money( $sale_price ) : cocart_prepare_money_response( $sale_price );
				$cart_contents[ $item_key ]['price_discounted'] = function_exists( 'cocart_format_money' ) ? cocart_format_money( $discounted_price ) : cocart_prepare_money_response( $discounted_price );
			} else {
				$cart_contents[ $item_key ]['price_regular']    = function_exists( 'cocart_prepare_money_response' ) ? cocart_prepare_money_response( $regular_price ) : wc_format_decimal( $regular_price, wc_get_price_decimals() );
				$cart_contents[ $item_key ]['price_sale']       = function_exists( 'cocart_prepare_money_response' ) ? cocart_prepare_money_response( $sale_price ) : wc_format_decimal( $sale_price, wc_get_price_decimals() );
				$cart_contents[ $item_key ]['price_discounted'] = function_exists( 'cocart_prepare_money_response' ) ? cocart_prepare_money_response( $discounted_price ) : wc_format_decimal( $discounted_price, wc_get_price_decimals() );
			}

			return $cart_contents;
		} // END is_item_discounted()

		/**
		 * Get thumbnail size.
		 *
		 * @access public
		 *
		 * @since 4.0.5 Introduced.
		 *
		 * @return string $thumbnail_size Thumbnail size.
		 */
		public function get_gallery_thumbnail_size() {
			/**
			 * Filters the thumbnail size of the product image.
			 *
			 * @since 3.0.0 Introduced.
			 *
			 * @param string $thumbnail_size Thumbnail size.
			 */
			$thumbnail_size = apply_filters( 'cocart_item_gallery_thumbnail_size', 'woocommerce_thumbnail' );

			return $thumbnail_size;
		} // END get_thumbnail_size()
	} // END class

} // END if class exists

new CoCart_Cart_Enhanced_v2();
