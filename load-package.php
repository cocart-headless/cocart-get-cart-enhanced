<?php
/**
 * This file is designed to be used to load as package NOT a WP plugin!
 *
 * @version 3.2.0
 * @package CoCart Cart Enhanced
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'COCART_GET_CART_ENHANCED' ) ) {
	define( 'COCART_GET_CART_ENHANCED', __FILE__ );
}

// Include the main CoCart - Cart Enhanced class.
if ( ! class_exists( 'CoCart_Get_Cart_Enhanced', false ) ) {
	include_once untrailingslashit( plugin_dir_path( COCART_GET_CART_ENHANCED ) ) . '/includes/class-cocart-get-cart-enhanced.php';
}

/**
 * Returns the main instance of CoCart - Cart Enhanced and only runs if it does not already exists.
 *
 * @return CoCart_Get_Cart_Enhanced - Cart Enhanced
 */
if ( ! function_exists( 'CoCart_Get_Cart_Enhanced' ) ) {
	/**
	 * Initialize CoCart Cart Enhanced.
	 */
	function CoCart_Get_Cart_Enhanced() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		return CoCart_Get_Cart_Enhanced::init();
	}

	CoCart_Get_Cart_Enhanced();
}
