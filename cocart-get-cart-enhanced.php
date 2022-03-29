<?php
/**
 * Plugin Name: CoCart - Cart Enhanced
 * Plugin URI:  https://cocart.xyz
 * Description: Enhances the cart response to return additional details.
 * Author:      Sébastien Dumont
 * Author URI:  https://sebastiendumont.com
 * Version:     3.2.0
 * Text Domain: cocart-get-cart-enhanced
 * Domain Path: /languages/
 * Requires at least: 5.6
 * Requires PHP: 7.3
 * WC requires at least: 4.3
 * WC tested up to: 6.3
 *
 * Copyright: © 2022 Sébastien Dumont, (mailme@sebastiendumont.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
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
 * @return CoCart - Cart Enhanced
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
