<?php
namespace WPCF;

if ( ! defined( 'ABSPATH' ) )
	exit;

final class Crowdfunding{

	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	function __construct() {

		$this->initial_activation();

		$this->includes();

		do_action('wpcf_before_load');

		// $this->addons = new Addons();
		// $this->post_types = new Post_types();
		// $this->assets = new Assets();
		// $this->admin = new Admin();
		// $this->ajax = new Ajax();
		// $this->options = new Options();
		// $this->shortcode = new Shortcode();
		// $this->course = new Course();
		// $this->lesson = new Lesson();
		// $this->rewrite_rules = new Rewrite_Rules();
		// $this->template = new Template();
		// $this->instructor = new  Instructor();
		// $this->student = new Student();
		// $this->q_and_a = new Q_and_A();
		// $this->quiz = new Quiz();
		// $this->question = new Question();
		// $this->tools = new Tools();
		// $this->user = new User();
		// $this->theme_compatibility = new Theme_Compatibility();
		// $this->gutenberg = new Gutenberg();
		// $this->woocommerce = new WooCommerce();
		// $this->edd = new TutorEDD();
		// $this->withdraw = new Withdraw();


		do_action('wpcf_after_load');
	}

	// Checking Vendor
	public function vendor(){

		if( wpcf_is_woocommerce() ){
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
				if ( wpcf_wc_version_check() ) {
					require_once WPCF_DIR_PATH . 'includes/class-wpneo-crowdfunding-base.php';
					require_once WPCF_DIR_PATH . 'includes/woocommerce/class-wpneo-crowdfunding.php';
					require_once WPCF_DIR_PATH . 'includes/class-wpneo-crowdfunding-frontend-dashboard.php';
					Wpneo_Crowdfunding();
				} else {
					add_action( 'admin_notices', array( 'WPCF_Initial_Setup', 'wc_low_version' ) );
					deactivate_plugins( plugin_basename( __FILE__ ) );
				}
			} else {
				add_action( 'admin_notices', array( 'WPCF_Initial_Setup', 'no_vendor_notice' ) );
			}
		}
	}


	// Initial Include
	public function includes(){
		require_once WPCF_DIR_PATH . 'includes/General_Functions.php';
		require_once WPCF_DIR_PATH . 'includes/Initial_Setup.php';
		require_once WPCF_DIR_PATH . 'settings/Menu_Settings.php';
	}


	// Activation & Deactivation Hook
	public function initial_activation(){
		register_activation_hook( __FILE__, array( 'WPCF_Initial_Setup', 'initial_plugin_activation' ) );
		register_deactivation_hook( __FILE__ , array( 'WPCF_Initial_Setup', 'initial_plugin_deactivation') );
	}

}