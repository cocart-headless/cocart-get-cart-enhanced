<?php
/**
 * Filters CoCart cart response to add additional data for "API v2".
 *
 * @since 3.0.0
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
		} // END __construct()

		/**
		 * Returns additional product details for each item in the cart.
		 *
		 * @access  public
		 * @param   array  $cart_contents - Cart contents before modifications.
		 * @param   int    $item_key
		 * @param   array  $cart_item
		 * @param   object $_product
		 * @return  array  $cart_contents - Cart contents after modifications.
		 */
		public function return_product_details( $cart_contents, $item_key, $cart_item, $_product ) {
			// Additional meta.
			$cart_contents[ $item_key ]['meta' ]['virtual'] = $_product->get_virtual();
			$cart_contents[ $item_key ]['meta' ]['downloadable'] = $_product->get_downloadable();

			// Categories and Tags.
			$cart_contents[ $item_key ]['categories'] = get_the_terms( $_product->get_id(), 'product_cat' );
			$cart_contents[ $item_key ]['tags'] = get_the_terms( $_product->get_id(), 'product_tag' );

			// Product stock status.
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

			// Product gallery images.
			$gallery_ids = $_product->get_gallery_image_ids();

			$cart_contents[ $item_key ]['gallery'] = array();

			if ( ! empty( $gallery_ids ) ) {
				foreach( $gallery_ids as $image_id ) {
					$gallery_id = apply_filters( 'cocart_item_gallery', $image_id, $cart_item, $item_key );

					$gallery_src = wp_get_attachment_image_src( $gallery_id, apply_filters( 'cocart_item_gallery_thumbnail_size', 'woocommerce_thumbnail' ) );

					/**
					 * Filters the source of the product gallery image.
					 *
					 * @param   string $gallery_src URL of the product gallery image.
					 */
					$gallery_src = apply_filters( 'cocart_item_thumbnail_src', $gallery_src[0], $cart_item, $item_key, $removed_item );

					$cart_contents[ $item_key ]['gallery'][ $gallery_id ] = $gallery_src;
				}
			}

			// Permalink of product if visible.
			$cart_contents[ $item_key ]['permalink'] = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';

			return $cart_contents;
		} // END return_product_details()

	} // END class

} // END if class exists

new CoCart_Cart_Enhanced_v2();
