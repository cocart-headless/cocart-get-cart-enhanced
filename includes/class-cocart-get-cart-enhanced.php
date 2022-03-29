<?php
/**
 * CoCart - Cart Enhanced core setup.
 *
 * @author  Sébastien Dumont
 * @package Cart Enhanced
 * @version 3.0.3
 * @license GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main CoCart Get Cart Enhanced class.
 *
 * @class CoCart_Get_Cart_Enhanced
 */
final class CoCart_Get_Cart_Enhanced {

	/**
	 * Plugin Version
	 *
	 * @access public
	 * @static
	 * @var string
	 */
	public static $version = '3.2.0';

	/**
	 * Initiate CoCart Get Cart Enhanced.
	 *
	 * @access public
	 * @static
	 */
	public static function init() {
		// Load translation files.
		add_action( 'init', array( __CLASS__, 'load_plugin_textdomain' ), 0 );

		// Load filters.
		add_action( 'init', array( __CLASS__, 'include_filters' ) );

		// Plugin activation and deactivation.
		register_activation_hook( COCART_GET_CART_ENHANCED, array( __CLASS__, 'activated' ) );
		register_deactivation_hook( COCART_GET_CART_ENHANCED, array( __CLASS__, 'deactivated' ) );
	} // END init()

	/**
	 * Return the name of the package.
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	public static function get_name() {
		return 'CoCart - Cart Enhanced';
	}

	/**
	 * Return the version of the package.
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	public static function get_version() {
		return self::$version;
	}

	/**
	 * Return the path to the package.
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	public static function get_path() {
		return dirname( __DIR__ );
	}

	/**
	 * Load the plugin translations if any ready.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/cocart-get-cart-enhanced/cocart-get-cart-enhanced-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/cocart-get-cart-enhanced-LOCALE.mo
	 *
	 * @access public
	 * @static
	 */
	public static function load_plugin_textdomain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			$locale = is_admin() ? get_user_locale() : get_locale();
		}

		$locale = apply_filters( 'plugin_locale', $locale, 'cocart-get-cart-enhanced' );

		unload_textdomain( 'cocart-get-cart-enhanced' );
		load_textdomain( 'cocart-get-cart-enhanced', WP_LANG_DIR . '/cocart-get-cart-enhanced/cocart-get-cart-enhanced-' . $locale . '.mo' );
		load_plugin_textdomain( 'cocart-get-cart-enhanced', false, plugin_basename( dirname( COCART_GET_CART_ENHANCED ) ) . '/languages' );
	} // END load_plugin_textdomain()

	/**
	 * Load filters to enhance the cart and items.
	 *
	 * @access public
	 * @static
	 */
	public static function include_filters() {
		include_once dirname( COCART_GET_CART_ENHANCED ) . '/includes/filters/filter-v1.php';
		include_once dirname( COCART_GET_CART_ENHANCED ) . '/includes/filters/filter-v2.php';

		// If enabled, cart will enhance API v1.
		if ( apply_filters( 'cocart_preview_api_v2', false ) ) {
			include_once dirname( COCART_GET_CART_ENHANCED ) . '/includes/filters/filter-v2-preview.php';
		}
	} // include_filters()

	/**
	 * Runs when the plugin is activated.
	 *
	 * Adds plugin to list of installed CoCart add-ons.
	 *
	 * @access public
	 * @static
	 */
	public static function activated() {
		$addons_installed = get_site_option( 'cocart_addons_installed', array() );

		$plugin = plugin_basename( COCART_GET_CART_ENHANCED );

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
	 * @static
	 */
	public static function deactivated() {
		$addons_installed = get_site_option( 'cocart_addons_installed', array() );

		$plugin = plugin_basename( COCART_GET_CART_ENHANCED );

		// Remove plugin from list of installed add-ons.
		if ( in_array( $plugin, $addons_installed, true ) ) {
			$addons_installed = array_diff( $addons_installed, array( $plugin ) );
			update_site_option( 'cocart_addons_installed', $addons_installed );
		}
	} // END deactivated()

} // END class
