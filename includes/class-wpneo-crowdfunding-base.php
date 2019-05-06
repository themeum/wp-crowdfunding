<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('Wpneo_Crowdfunding_Base')) {

    class Wpneo_Crowdfunding_Base{

        /**
         * @var null
         *
         * Instance of this class
         */
        protected static $_instance = null;

        /**
         * @return null|Wpneo_Crowdfunding
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Wpneo_Crowdfunding constructor.
         *
         * @hook
         */
        public function __construct() {

            add_action( 'admin_enqueue_scripts',    array($this,'wpneo_enqueue_admin_script')); //Add Additional backend js and css
            add_action( 'wp_enqueue_scripts',       array($this, 'wpneo_enqueue_frontend_script') ); //Add frontend js and css
            add_action( 'init',                     array($this, 'load_wpneo_crowdfunding_functions')); //Load all wpneo crowdfunding functions to init
            add_action( 'init',                     array($this, 'wpneo_template_hook'));
            add_action( 'init',                     array($this, 'wpneo_crowdfunding_get_user_own_media_after_load_pluggable'));
            add_action( 'admin_init',               array($this, 'wpneo_crowdfunding_network_disable_notice'));
            add_action( 'admin_head',               array($this, 'wpneo_crowdfunding_add_mce_button'));
            add_filter( 'plugin_action_links_' . WPCF_BASENAME, array($this, 'wpneo_crowdfunding_settings_link'), 10, 5);

            //Ajax action
            add_action( 'wp_ajax_wpcf_settings_reset',  array($this, 'wpcf_settings_reset'));


            //Disable plugin update notification
            if (WPCF_TYPE != 'free'){
                //add_filter( 'site_transient_update_plugins', array($this,'wpnew_crowdfunding_disable_plugin_updates') );
            }

            if (WPCF_TYPE == 'free'){
                //Footer text, asking rating.
                add_filter('admin_footer_text', array($this, 'wpneo_crowdfunding_admin_footer_text'), 2);
                add_action('wp_ajax_wpcf_rated', array($this, 'wpcf_admin_footer_text_rated'));
            }

        }

        public function load_wpneo_crowdfunding_functions(){
            require_once WPCF_DIR_PATH.'includes/wpneo-crowdfunding-template-functions.php';
        }

        public function wpneo_template_hook(){
            require_once WPCF_DIR_PATH.'includes/wpneo-crowdfunding-template-hook.php';
        }

        /**
         * Registering necessary jquery script,js and css
         * @backend
         */
        public function wpneo_enqueue_admin_script(){
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-neo-jquery-scripts', WPCF_DIR_URL .'assets/js/crowdfunding.js', array('jquery','wp-color-picker'), WPCF_VERSION, true);
            wp_register_style( 'neo-crowdfunding-css', WPCF_DIR_URL .'assets/css/crowdfunding.css', false, WPCF_VERSION );
            wp_enqueue_style( 'neo-crowdfunding-css' );
        }

        /**
         * Registering necessary js and css
         * @frontend
         */
        public function wpneo_enqueue_frontend_script(){
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery' ) );
            wp_register_style( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
            wp_enqueue_style( 'jquery-ui' );
            //wp_enqueue_script( 'wp-neo-Chart.bundle', WPCF_DIR_URL .'assets/js/Chart.bundle.min.js', array('jquery'), WPCF_VERSION, true);            
            wp_enqueue_script( 'jquery.easypiechart', WPCF_DIR_URL .'assets/js/jquery.easypiechart.min.js', array('jquery'), WPCF_VERSION, true);
            wp_enqueue_script( 'wp-neo-jquery-scripts-front', WPCF_DIR_URL .'assets/js/crowdfunding-front.js', array('jquery'), WPCF_VERSION, true);
            wp_localize_script( 'wp-neo-jquery-scripts-front', 'wpcf_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
            wp_register_style( 'neo-crowdfunding-css-front', WPCF_DIR_URL .'assets/css/crowdfunding-front.css', false, WPCF_VERSION );
            wp_enqueue_style( 'neo-crowdfunding-css-front' );
            wp_enqueue_media();
        }

        public function wpneo_crowdfunding_settings_link($links){
            $new_link = array('settings' => '<a href="'.admin_url('admin.php?page=wpneo-crowdfunding').'">Settings</a>');
            return array_merge($new_link, $links);
        }

        public function wpneo_crowdfunding_get_user_own_media_after_load_pluggable(){
            if (is_user_logged_in()) {
                if(is_admin()){
                    if (current_user_can('campaign_form_submit')) {
                        add_action('pre_get_posts', array($this, 'wpneo_get_user_own_media'));
                    }
                }
            }
        }

        // Attachment Filter
        public function wpneo_get_user_own_media($query){
            if ($query) {
                if (! empty($query->query['post_type'])) {
                    if(!current_user_can('administrator')){
                        if ($query->query['post_type'] == 'attachment') {
                            $user = wp_get_current_user();
                            $query->set('author', $user->ID);
                        }
                    }
                }
            }
        }



        // Hooks your functions into the correct filters
        function wpneo_crowdfunding_add_mce_button() {
            // check user permissions
            if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
                return;
            }
            // check if WYSIWYG is enabled
            if ( 'true' == get_user_option( 'rich_editing' ) ) {
                add_filter( 'mce_external_plugins', array($this, 'wpneo_crowdfunding_add_tinymce_js') );
                add_filter( 'mce_buttons', array($this, 'wpneo_crowdfunding_register_mce_button') );
            }
        }
        // Declare script for new button
        function wpneo_crowdfunding_add_tinymce_js( $plugin_array ) {
            $plugin_array['crowdfunding_button'] = WPCF_DIR_URL .'assets/js/mce-button.js';
            return $plugin_array;
        }
        // Register new button in the editor
        function wpneo_crowdfunding_register_mce_button( $buttons ) {
            array_push( $buttons, 'crowdfunding_button' );
            return $buttons;
        }

        public function wpneo_crowdfunding_admin_footer_text($footer_text){
            if ( ! function_exists('wc_get_screen_ids')){
                return $footer_text;
            }

            $current_screen = get_current_screen();
            $crowdfunding_screen_ids = wpcf_screen_id();

            if ( ! in_array($current_screen->id, $crowdfunding_screen_ids)){
                return $footer_text;
            }

            if ( ! get_option( 'wpcf_admin_footer_text_rated' ) ) {
                $footer_text = sprintf(__('If you like <strong>WP Crowdfunding</strong> please leave us a 5-stars %s rating. A huge thanks in advance!', 'wp-crowdfunding'), '<a href="https://wordpress.org/support/plugin/wp-crowdfunding/reviews?rate=5#new-post" target="_blank" class="wpcf-rating-link" data-rated="' . esc_attr__('Thanks :)', 'woocommerce') . '">&#9733;&#9733;&#9733;&#9733;&#9733;</a>');
                wc_enqueue_js("
                    jQuery( 'a.wpcf-rating-link' ).click( function() {
                        jQuery.post( '" . admin_url('admin-ajax.php') . "', { action: 'wpcf_rated' } );
                        jQuery( this ).parent().text( jQuery( this ).data( 'rated' ) );
                    });
                ");
            }else{
                $footer_text = sprintf( __( 'Thank you for raise funds with <strong>WP Crowdfunding</strong> by %s.', 'wp-crowdfunding' ), '<a href="https://www.themeum.com/?utm_source=wp_crowdfunding_plugin_admin" target="_blank">Themeum</a>');
            }

            return $footer_text;
        }

        /**
         * Added rated
         */
        function wpcf_admin_footer_text_rated(){
            update_option('wpcf_admin_footer_text_rated', 'true');
        }

        /**
         * Set notice for disable in network
         */

        public function wpneo_crowdfunding_network_disable_notice(){
            if (is_plugin_active_for_network(WPCF_BASENAME)){
                add_action('admin_notices', array($this, 'disable_from_network_notice'));
            }
        }

        /**
         * Disable Notice
         */
        public static function disable_from_network_notice(){
            $html = '';
            $html .= '<div class="notice notice-error is-dismissible">';
                $html .= '<p>'.__('WP Crowdfunding will not work properly if you activate it from network, please deactivate from network and activate again from individual site admin.', 'wp-crowdfunding').'</p>';
            $html .= '</div>';
            echo $html;
        }

        /**
         * Reset method
         */

        public function wpcf_settings_reset(){
            $initial_setup = new Wpneo_Crowdfunding_Initial_Setup();
            $initial_setup->wpcf_settings_reset();
        }


        /**
         * @param $value
         * @return mixed
         * Disable plugin update notification for paid version
         */
        function wpnew_crowdfunding_disable_plugin_updates( $value ) {
            if( ! empty($value->response['wp-crowdfunding/wp-crowdfunding.php']) ){
                unset( $value->response['wp-crowdfunding/wp-crowdfunding.php'] );
            }
            return $value;
        }
    }
}
Wpneo_Crowdfunding_Base::instance(); //Call base class

require_once WPCF_DIR_PATH.'includes/class-wpneo-crowdfunding-templating.php';
require_once WPCF_DIR_PATH.'includes/class-wpneo-crowdfunding-user-registration.php';
require_once WPCF_DIR_PATH.'includes/class-wpneo-crowdfunding-product-search.php';
require_once WPCF_DIR_PATH.'includes/class-wpneo-crowdfunding-common.php';
require_once WPCF_DIR_PATH.'includes/class-wp-crowdfunding-shortcode.php';

// Shortcode Add to the plugins
include_once WPCF_DIR_PATH.'shortcode/dashboard.php';
include_once WPCF_DIR_PATH.'shortcode/project-listing.php';
include_once WPCF_DIR_PATH.'shortcode/registration.php';
include_once WPCF_DIR_PATH.'shortcode/search.php';
include_once WPCF_DIR_PATH.'shortcode/submit-form.php';

// Include Addons directory and there main file
$addons_dir = array_filter(glob(WPCF_DIR_PATH.'addons/*'), 'is_dir');
if (count($addons_dir) > 0) {
    foreach ($addons_dir as $key => $value) {
        $addon_dir_name = str_replace(dirname($value).'/', '', $value);
        $file_name = WPCF_DIR_PATH . 'addons/'.$addon_dir_name.'/'.$addon_dir_name.'.php';
        if ( file_exists($file_name) ){
            include_once $file_name;
        }
    }
}
