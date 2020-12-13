<?php
/*
 * Plugin Name: CoCart - Get Cart Enhanced
 * Plugin URI:  https://cocart.xyz
 * Description: Enhances the get cart response to return the cart totals, coupons applied, additional product details and notices.
 * Author:      Sébastien Dumont
 * Author URI:  https://sebastiendumont.com
 * Version:     2.0.0
 * Text Domain: cocart-get-cart-enhanced
 * Domain Path: /languages/
 *
 * Requires at least: 5.3
 * Requires PHP: 7.0
 * WC requires at least: 4.3
 * WC tested up to: 4.8
 *
 * Copyright: © 2020 Sébastien Dumont, (mailme@sebastiendumont.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'COCART_GET_CART_ENHANCED' ) ) {
	define( 'COCART_GET_CART_ENHANCED', __FILE__ );
}

// Include the main CoCart Get Cart Enhanced class.
if ( ! class_exists( 'CoCart_Get_Cart_Enhanced', false ) ) {
	include_once untrailingslashit( plugin_dir_path( COCART_GET_CART_ENHANCED ) ) . '/includes/class-cocart-get-cart-enhanced.php';
}

/**
 * Returns the main instance of CoCart Get Cart Enhanced and only runs if it does not already exists.
 *
 * @return CoCart Get Cart Enhanced
 */
if ( ! function_exists( 'CoCart_Get_Cart_Enhanced' ) ) {
	function CoCart_Get_Cart_Enhanced() {
		return CoCart_Get_Cart_Enhanced::init();
	}

	CoCart_Get_Cart_Enhanced();
}
