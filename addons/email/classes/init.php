<?php
/**
 * Send email after various event
 *
 * @package : mail
 * @plugin wp-crowdfunding
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('WPCF_Email')) {
    class WPCF_Email
    {
        /**
         * @var null
         *
         * Instance of this class
         */
        protected static $_instance = null;

        /**
         * @return null|WPCF
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * WPCF_Email constructor.
         */
        public function __construct() {
			//Email classes
			add_filter( 'woocommerce_email_classes', array($this, 'add_email_classes') );
			
			/* general actions */
            add_filter( 'woocommerce_locate_core_template', array( $this, 'filter_woocommerce_template' ), 10, 3 );
            add_filter( 'woocommerce_locate_template', array( $this, 'filter_woocommerce_template' ), 10, 3 );
		}
		

	    /**
	     * @param $email_classes
	     *
	     * @return mixed
	     *
	     * Add email classes to WC Email Settings
	     *
	     * @since v.10.20
	     */
        public function add_email_classes($email_classes){
	        // include our custom email class
	        require_once( WPCF_EMAIL_DIR_PATH.'classes/wpcf-new-user.php' );
			require_once( WPCF_EMAIL_DIR_PATH.'classes/wpcf-new-backed.php' );
			require_once( WPCF_EMAIL_DIR_PATH.'classes/wpcf-campaign-submit.php' );
			require_once( WPCF_EMAIL_DIR_PATH.'classes/wpcf-campaign-accept.php' );
			require_once( WPCF_EMAIL_DIR_PATH.'classes/wpcf-campaign-update.php' );
	        require_once( WPCF_EMAIL_DIR_PATH.'classes/wpcf-target-reached.php' );
	        require_once( WPCF_EMAIL_DIR_PATH.'classes/wpcf-withdraw-request.php' );
	        
	        // add the email class to the list of email classes that WooCommerce loads
	        $email_classes['WPCF_New_User'] = new WPCF_New_User();
	        $email_classes['WPCF_New_Backed'] = new WPCF_New_Backed();
	        $email_classes['WPCF_Campaign_Submit'] = new WPCF_Campaign_Submit();
	        $email_classes['WPCF_Campaign_Accept'] = new WPCF_Campaign_Accept();
	        $email_classes['WPCF_Campaign_Update'] = new WPCF_Campaign_Update();
	        $email_classes['WPCF_Target_Reached'] = new WPCF_Target_Reached();
	        $email_classes['WPCF_Withdraw_Request'] = new WPCF_Withdraw_Request();

	        return $email_classes;
		}
		
        /**
         * Locate default templates of woocommerce in plugin, if exists
         *
         * @param $core_file     string
         * @param $template      string
         * @param $template_base string
         *
         * @return string
         * @since  1.0.0
         */
        public function filter_woocommerce_template( $core_file, $template, $template_base ) {
            $located = wpcf_locate_template( $template );
            if( $located ) {
                return $located;
            } else{
                return $core_file;
            }
        }

    }
}
WPCF_Email::instance();

add_action('pre_post_update', 'wpcf_load_wc_email_class_instance', 10);

if ( ! function_exists('wpcf_load_wc_email_class_instance')){
	function wpcf_load_wc_email_class_instance(){
		WC()->mailer();
	}
}


if ( !function_exists( 'wpcf_locate_template' ) ) {
	/**
	 * Locate the templates and return the path of the file found
	 *
	 * @param string $path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function wpcf_locate_template( $path ) {

		if ( function_exists( 'WC' ) ) {
			$woocommerce_base = WC()->template_path();
		} elseif ( defined( 'WC_TEMPLATE_PATH' ) ) {
			$woocommerce_base = WC_TEMPLATE_PATH;
		} else {
			$woocommerce_base = WC()->plugin_path() . '/templates/';
		}

		$template_woocommerce_path = $woocommerce_base . $path;
		$template_path             = '/' . $path;
		$plugin_path               = WPCF_EMAIL_DIR_PATH . 'templates/' . $path;

		$located = locate_template( array(
			$template_woocommerce_path, // Search in <theme>/woocommerce/
			$template_path,             // Search in <theme>/
			$plugin_path                // Search in <plugin>/templates/
		) );

		if ( !$located && file_exists( $plugin_path ) ) {
			return apply_filters( 'wpcf_locate_template', $plugin_path, $path );
		}

		return apply_filters( 'wpcf_locate_template', $located, $path );
	}
}