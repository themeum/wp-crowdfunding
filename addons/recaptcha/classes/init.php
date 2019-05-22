<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('WPCF_Recaptcha')) {
    class WPCF_Recaptcha
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

        public function __construct(){
            add_action( 'init', array($this, 'wpcf_recaptcha_save_settings') );
            add_action( 'wp_enqueue_scripts', array($this, 'wpcf_recaptcha_enqueue_frontend_script') ); // Add recaptcha js in footer
            add_shortcode( 'wpcf_recaptcha', array($this, 'wpcf_recaptcha_shortcode_generator')); // Short code for HTML section google reCAPTCHA
            add_filter( 'wpcf_user_registration_fields', array($this, 'wpcf_recaptcha_add_user_registration_form')); // Hook to add recaptcha field with user registration form
            add_filter( 'wpcf_before_closing_crowdfunding_campaign_form', array($this, 'wpcf_recaptcha_add_campaign_form'));
            add_filter( 'wpcf_settings_panel_tabs', array($this, 'add_recaptcha_tab_to_wpcf_settings'));
        }

        /**
         * Some task during plugin activate
         */
        public static function initial_plugin_setup(){
            //Check is plugin used before or not
            if (get_option('wpneo_recaptcha_is_used')){ return false; }

            update_option( 'wpneo_recaptcha_is_used', WPCF_RECAPTCHA_VERSION );
            update_option( 'wpneo_enable_recaptcha', 'false');
            update_option( 'wpneo_enable_recaptcha_in_user_registration', 'false');
            update_option( 'wpneo_enable_recaptcha_campaign_submit_page', 'false');
        }

        public function wpcf_recaptcha_shortcode_generator(){
            $wpcf_recaptcha_site_key = get_option('wpcf_recaptcha_site_key');
            $html = '<div class="g-recaptcha" data-sitekey="'.$wpcf_recaptcha_site_key.'"></div>';
            return $html;
        }

        public function wpcf_recaptcha_add_user_registration_form($registration_fields){
            if ( get_option('wpcf_enable_recaptcha') == 'true' && get_option('wpcf_enable_recaptcha_in_user_registration') == 'true') {
                $registration_fields[] =  array(
                                                'type' => 'shortcode',
                                                'shortcode' => '[wpcf_recaptcha]',
                                            );
            }
            return $registration_fields;
        }

        public function wpcf_recaptcha_add_campaign_form(){
            $html = '';
            if ( get_option('wpcf_enable_recaptcha') == 'true' && get_option('wpcf_enable_recaptcha_campaign_submit_page') == 'true') {
                $html .= '<div class="text-right">';
                $html .= do_shortcode('[wpcf_recaptcha]');
                $html .= '</div>';
            }
            return $html;
        }

        public function wpcf_recaptcha_enqueue_frontend_script(){
            if ( get_option('wpcf_enable_recaptcha') == 'true') {
                wp_enqueue_script('wpneo-recaptcha-js', 'https://www.google.com/recaptcha/api.js', null, wpcf_VERSION, true);
            }
        }

        public function add_recaptcha_tab_to_wpcf_settings($tabs){
            $tabs['recaptcha'] = array(
                'tab_name' => __('reCAPTCHA','wp-crowdfunding'),
                'load_form_file' => WPCF_RECAPTCHA_DIR_PATH.'pages/tab-recaptcha.php'
            );
            return $tabs;
        }

        /**
         * All settings will be save in this method
         */
        public function wpcf_recaptcha_save_settings() {
            if (isset($_POST['wpneo_admin_settings_submit_btn']) && isset($_POST['wpneo_recaptcha_activation']) && wp_verify_nonce( $_POST['wpneo_settings_page_nonce_field'], 'wpneo_settings_page_action' ) ){
                //Checkbox
                update_option( 'wpneo_enable_recaptcha', 'false');
                update_option( 'wpneo_enable_recaptcha_in_user_registration', 'false');
                update_option( 'wpneo_enable_recaptcha_campaign_submit_page', 'false');

                if (!empty($_POST['wpneo_enable_recaptcha'])) {
                    update_option('wpneo_enable_recaptcha', $_POST['wpneo_enable_recaptcha']);
                }
                if (!empty($_POST['wpneo_enable_recaptcha_in_user_registration'])) {
                    update_option('wpneo_enable_recaptcha_in_user_registration', $_POST['wpneo_enable_recaptcha_in_user_registration']);
                }
                if (!empty($_POST['wpneo_enable_recaptcha_campaign_submit_page'])) {
                    update_option('wpneo_enable_recaptcha_campaign_submit_page', $_POST['wpneo_enable_recaptcha_campaign_submit_page']);
                }

                //Text Field
                if (!empty($_POST['wpneo_recaptcha_site_key'])) {
                    update_option('wpneo_recaptcha_site_key', $_POST['wpneo_recaptcha_site_key']);
                }
                if (!empty($_POST['wpneo_recaptcha_secret_key'])) {
                    update_option('wpneo_recaptcha_secret_key', $_POST['wpneo_recaptcha_secret_key']);
                }
            }
        }
    }
}

WPCF_Recaptcha::instance();

