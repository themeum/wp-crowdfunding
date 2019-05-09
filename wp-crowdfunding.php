<?php
/*
 * Plugin Name:       WP Crowdfunding
 * Plugin URI:        https://www.themeum.com/product/wp-crowdfunding-plugin/
 * Description:       WP crowdfunding (Free) for collect fund and investment
 * Version:           1.8.8
 * Author:            Themeum
 * Author URI:        https://themeum.com
 * Text Domain:       wp-crowdfunding
 * Requires at least: 4.5
 * Tested up to: 5.1
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

/**
 * Support for Multi Network Site
 */
if( !function_exists('is_plugin_active_for_network') ){
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

 /**
  * @Type`
  * @Version
  * @Directory URL
  * @Directory Path
  * @Plugin Base Name
  */
  define('WPCF_TYPE', 'free');
  define('WPCF_VERSION', '1.8.8');
  define('WPCF_DIR_URL', plugin_dir_url(__FILE__));
  define('WPCF_DIR_PATH', plugin_dir_path(__FILE__));
  define('WPCF_BASENAME', plugin_basename(__FILE__));

/**
 * Load Text Domain Language
 */
add_action('init', 'wpcf_language_load');
function wpcf_language_load(){
    $plugin_dir = basename(dirname(__FILE__))."/languages/";
    load_plugin_textdomain('wp-crowdfunding', false, $plugin_dir);
}

if( !class_exists( 'Crowdfunding' ) ){
    require_once WPCF_DIR_PATH . 'includes/Crowdfunding.php';
    new \WPCF\Crowdfunding();
}

// function wpcf_plugins(){
// 	return \WPCF\Crowdfunding::instance();
// }
// $GLOBALS['crowdfunding'] = wpcf_plugins();

/**
 * Include Require File
 */
// include_once WPCF_DIR_PATH . 'includes/wpneo-crowdfunding-general-functions.php';
// include_once WPCF_DIR_PATH . 'admin/menu-settings.php';


/**
 * Checking vendor
 */
// if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
// 	if ( wpcf_wc_version_check() ) {
// 		require_once WPCF_DIR_PATH . 'includes/class-wpneo-crowdfunding-base.php';
// 		require_once WPCF_DIR_PATH . 'includes/woocommerce/class-wpneo-crowdfunding.php';
// 		require_once WPCF_DIR_PATH . 'includes/class-wpneo-crowdfunding-frontend-dashboard.php';
// 		Wpneo_Crowdfunding();
// 	} else {
// 		add_action( 'admin_notices', array( 'WPCF_Initial_Setup', 'wc_low_version' ) );
// 		deactivate_plugins( plugin_basename( __FILE__ ) );
// 	}
// } else {
// 	add_action( 'admin_notices', array( 'WPCF_Initial_Setup', 'no_vendor_notice' ) );
// }
