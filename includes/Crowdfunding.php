<?php
namespace WPCF;

defined( 'ABSPATH' ) || exit;

final class Crowdfunding {

	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	function __construct() {
		$this->includes_core();
		$this->include_shortcode();
		$this->include_addons();
		$this->initial_activation();
		do_action('wpcf_before_load');
		$this->run();
		do_action('wpcf_after_load');
	}

	// Include Core
	public function includes_core() {
		require_once WPCF_DIR_PATH.'includes/Compatibility.php';
		require_once WPCF_DIR_PATH.'includes/Initial_Setup.php';
		require_once WPCF_DIR_PATH.'settings/Admin_Menu.php';
		new settings\Admin_Menu();
	}

	//Checking Vendor
	public function run() {
		if( wpcf_function()->is_woocommerce() ) {
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
				if ( wpcf_function()->wc_version() ) {
					require_once WPCF_DIR_PATH.'includes/woocommerce/Base.php';
					require_once WPCF_DIR_PATH.'includes/woocommerce/Common.php';
					require_once WPCF_DIR_PATH.'includes/woocommerce/Templating.php';
					require_once WPCF_DIR_PATH.'includes/woocommerce/Woocommerce.php';
					require_once WPCF_DIR_PATH.'includes/woocommerce/Actions.php';
					require_once WPCF_DIR_PATH.'includes/woocommerce/Template_Hooks.php';
					new \WPCF\woocommerce\Base();
					new \WPCF\woocommerce\Common();
					new \WPCF\woocommerce\Templating();
					new \WPCF\woocommerce\Woocommerce();
					new \WPCF\woocommerce\Actions();
					new \WPCF\woocommerce\Template_Hooks();
				} else {
					add_action( 'admin_notices', array( 'WPCF_Initial_Setup', 'wc_low_version' ) );
					deactivate_plugins( plugin_basename( __FILE__ ) );
				}
			} else {
				add_action( 'admin_notices', array( 'WPCF_Initial_Setup', 'no_vendor_notice' ) );
			}
		}else{
			// Local Code
		}
	}

	// Include Shortcode
	public function include_shortcode() {
		include_once WPCF_DIR_PATH.'shortcode/Dashboard.php';
		include_once WPCF_DIR_PATH.'shortcode/Project_Listing.php';
		include_once WPCF_DIR_PATH.'shortcode/Registration.php';
		include_once WPCF_DIR_PATH.'shortcode/Search.php';
		include_once WPCF_DIR_PATH.'shortcode/Submit_Form.php';
		include_once WPCF_DIR_PATH.'shortcode/Campaign_Box.php';
		include_once WPCF_DIR_PATH.'shortcode/Single_Campaign.php';
		include_once WPCF_DIR_PATH.'shortcode/Popular_Campaigns.php';
		include_once WPCF_DIR_PATH.'shortcode/Donate.php';

		new \WPCF\shortcode\Dashboard();
		new \WPCF\shortcode\Project_Listing();
		new \WPCF\shortcode\Registration();
		new \WPCF\shortcode\Campaign_Submit_Form();
		new \WPCF\shortcode\Search();
		new \WPCF\shortcode\Campaign_Box();
		new \WPCF\shortcode\Single_Campaign();
		new \WPCF\shortcode\Popular_Campaigns();
		new \WPCF\shortcode\Donate();
	}

	// Include Addons directory
	public function include_addons() {
		$addons_dir = array_filter(glob(WPCF_DIR_PATH.'addons/*'), 'is_dir');
		if (count($addons_dir) > 0) {
			foreach( $addons_dir as $key => $value ) {
				$addon_dir_name = str_replace(dirname($value).'/', '', $value);
				$file_name = WPCF_DIR_PATH . 'addons/'.$addon_dir_name.'/'.$addon_dir_name.'.php';
				if ( file_exists($file_name) ) {
					include_once $file_name;
				}
			}
		}
	}

	// Activation & Deactivation Hook
	public function initial_activation() {
		register_activation_hook( WPCF_FILE, array( 'WPCF_Initial_Setup', 'initial_plugin_activation' ) );
		register_deactivation_hook( WPCF_FILE , array( 'WPCF_Initial_Setup', 'initial_plugin_deactivation') );
	}

}