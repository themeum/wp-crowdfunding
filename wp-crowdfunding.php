<?php
/*
* Plugin Name:       WP Crowdfunding
* Plugin URI:        https://www.themeum.com/product/wp-crowdfunding-plugin/
* Description:       The Ultimate Fundraising and Backer Plugin for WordPress.
* Version:           2.2.3
* Author:            Themeum
* Author URI:        https://themeum.com
* Text Domain:       wp-crowdfunding
* Requires at least: 5.9
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Domain Path:       /languages
*/

defined( 'ABSPATH' ) || exit;

/**
* Support for Multi Network Site
*/
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	require_once ABSPATH . '/wp-admin/includes/plugin.php';
}

/**
* @Type
* @Version
* @Directory URL
* @Directory Path
* @Plugin Base Name
*/
define( 'WPCF_FILE', __FILE__ );
define( 'WPCF_VERSION', '2.2.3' );
define( 'WPCF_DIR_URL', plugin_dir_url( WPCF_FILE ) );
define( 'WPCF_DIR_PATH', plugin_dir_path( WPCF_FILE ) );
define( 'WPCF_BASENAME', plugin_basename( WPCF_FILE ) );
/**
* Load Text Domain Language
*/
add_action( 'init', 'wpcf_language_load' );
function wpcf_language_load() {
	load_plugin_textdomain( 'wp-crowdfunding', false, basename( dirname( WPCF_FILE ) ) . '/languages/' );
}

/**
 * Declare compatibility with WooCommerce High-Performance Order Storage (HPOS).
 * All order data is read and written through the WooCommerce order API.
 */
add_action( 'before_woocommerce_init', 'wpcf_declare_hpos_compatibility' );
function wpcf_declare_hpos_compatibility() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', WPCF_FILE, true );
	}
}

if ( ! function_exists( 'wpcf_function' ) ) {
	function wpcf_function() {
		require_once WPCF_DIR_PATH . 'includes/Functions.php';
		return new \WPCF\Functions();
	}
}

if ( ! class_exists( 'Crowdfunding' ) ) {
	require_once WPCF_DIR_PATH . 'includes/Crowdfunding.php';
	require_once WPCF_DIR_PATH . 'includes/register_api_hook.php';
	new \WPCF\Crowdfunding();
}

// wp_login_form() to display login form in a jQuery dialog window.
add_action( 'wp_login_failed', 'wpcf_front_end_login_fail' );  // hook failed login
function wpcf_front_end_login_fail( $username ) {
	$referrer = wp_get_referer();
	if ( ! empty( $referrer ) && ! strstr( $referrer, 'wp-login' ) && ! strstr( $referrer, 'wp-admin' ) ) {
		wp_safe_redirect( add_query_arg( 'login', 'failed', $referrer ) );
		exit;
	}
}
