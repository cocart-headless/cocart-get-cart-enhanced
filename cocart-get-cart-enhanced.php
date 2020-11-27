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

if ( ! class_exists( 'CoCart_Get_Cart_Enhanced' ) ) {
	class CoCart_Get_Cart_Enhanced {

		/**
		 * Load the plugin.
		 *
		 * @access public
		 */
		public function __construct() {
			// Load filters.
			add_action( 'init', array( $this, 'include_filters' ) );

			// Load translation files.
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			// Plugin activation and deactivation.
			register_activation_hook( __FILE__, array( $this, 'activated' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivated' ) );
		} // END __construct()

		/**
		 * Load filters to enhance the cart and items.
		 *
		 * @access public
		 */
		public function include_filters() {
			include_once dirname( __FILE__ ) . '/' . 'filters/filter-v1.php';

			// If enabled, cart will enhance with API v2 preview.
			if ( apply_filters( 'cocart_preview_api_v2', false ) ) {
				include_once dirname( __FILE__ ) . '/' . 'filters/filter-v2.php';
			}
		} // include_filters()

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
