<?php
/**
 * This file is designed to be used to load as package NOT a WP plugin!
 *
 * @version 4.0.5
 * @package CoCart - Cart API Enhanced
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'COCART_GET_CART_ENHANCED' ) ) {
	define( 'COCART_GET_CART_ENHANCED', __FILE__ );
}

// Include the main CoCart - Cart API Enhanced class.
if ( ! class_exists( 'CoCart_Cart_API_Enhanced', false ) ) {
	include_once untrailingslashit( plugin_dir_path( COCART_GET_CART_ENHANCED ) ) . '/includes/class-cocart-get-cart-enhanced.php';
}

/**
 * Returns the main instance of CoCart - Cart API Enhanced and only runs if it does not already exists.
 *
 * @return CoCart_Cart_API_Enhanced - Cart API Enhanced
 */
if ( ! function_exists( 'CoCart_Cart_API_Enhanced' ) ) {
	/**
	 * Initialize CoCart Cart API Enhanced.
	 */
	function CoCart_Cart_API_Enhanced() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		return CoCart_Cart_API_Enhanced::init();
	}

	CoCart_Cart_API_Enhanced();
}
