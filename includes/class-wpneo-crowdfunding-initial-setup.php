<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('Wpneo_Crowdfunding_Initial_Setup')) {

    class Wpneo_Crowdfunding_Initial_Setup{

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
         * Some task during plugin activate
         */
        public static function initial_plugin_setup(){
            //Check is plugin used before or not
            if (get_option('wpneo_crowdfunding_is_used'))
            	return false;

            update_option( 'wpneo_crowdfunding_is_used'         , WPNEO_CROWDFUNDING_VERSION); //Insert plugin version name into Option
            update_option( 'wpneo_cf_selected_theme',           'basic'); //Select a basic theme
            update_option( 'vendor_type',                       'woocommerce'); //Select default payment type to WooCommerce
            update_option( 'wpneo_default_campaign_status',     'draft'); //Select default campaign status
            update_option( 'wpneo_campaign_edit_status',        'pending'); //Select default campaign status
            update_option( 'wpneo_enable_color_styling',        'true'); //Set check true at Enable color styling option for custom color layout.
            update_option( 'wpneo_show_min_price',              'true'); //Set check true at min price show during campaign add
            update_option( 'wpneo_show_max_price',              'true'); //Set check true at max price show during campaign add
            update_option( 'wpneo_show_recommended_price',      'true'); //Set check true at recommended price show during campaign add
            update_option( 'wpneo_show_target_goal',            'true'); //Set check at campaign end method
            update_option( 'wpneo_show_target_date',            'true');
            update_option( 'wpneo_show_target_goal_and_date',   'true');
            update_option( 'wpneo_show_campaign_never_end',     'true');
            update_option( 'wpneo_enable_paypal_per_campaign_email', 'true');
            update_option( 'wpneo_single_page_template',   'in_wp_crowdfunding'); //Single page rewards
            update_option( 'wpneo_single_page_reward_design',   '1'); //Single page rewards
            update_option( 'hide_cf_campaign_from_shop_page',    'false'); //Hide campaign form shop page initial value
            update_option( 'wpneo_crowdfunding_add_to_cart_redirect', 'checkout_page'); // Redirect Add to cart

            //WooCommerce Settings
            update_option( 'wpneo_single_page_id', 'true'); // Redirect Add to cart

            /**
             * reCaptcha Page Settings
             */
            update_option( 'wpneo_enable_recaptcha',            'false');
            update_option( 'wpneo_enable_recaptcha_in_user_registration', 'false');
            update_option( 'wpneo_enable_recaptcha_campaign_submit_page', 'false');
            update_option( 'wpneo_requirement_agree_title',      'I agree with the terms and conditions.'); //accept agreement during add campaign

            // Create page object
            $crowdfunding_dashboard_page = array(
                'post_title'    => 'CF Dashboard',
                'post_content'  => '[wpneo_crowdfunding_dashboard]',
                'post_type'     => 'page',
                'post_status'   => 'publish',
            );
            $crowdfunding_form_page = array(
                'post_title'    => 'CF campaign form',
                'post_content'  => '[wpneo_crowdfunding_form]',
                'post_type'     => 'page',
                'post_status'   => 'publish',
            );
            $crowdfunding_listing_page = array(
                'post_title'    => 'CF Listing Page',
                'post_content'  => '[wpneo_crowdfunding_listing]',
                'post_type'     => 'page',
                'post_status'   => 'publish',
            );
            $wpneo_registration_page_arg = array(
                'post_title'    => 'CF User Registration',
                'post_content'  => '[wpneo_registration]',
                'post_type'     => 'page',
                'post_status'   => 'publish',
            );
            // Insert the page into the database
            $insert_dashboard_page = wp_insert_post( $crowdfunding_dashboard_page );
            wp_insert_post( $wpneo_registration_page_arg );
            $insert_frm_page = wp_insert_post( $crowdfunding_form_page );
            wp_insert_post( $crowdfunding_listing_page );

            /**
             * Update option wpneo crowdfunding dashboard page
             */
            if ($insert_dashboard_page){
                update_option( 'wpneo_crowdfunding_dashboard_page_id', $insert_dashboard_page );
            }

            /**
             * add or update option
             */
            if ($insert_frm_page){
                update_option( 'wpneo_form_page_id', $insert_frm_page );
            }

            //Upload Permission
            update_option( 'wpneo_user_role_selector', array('administrator', 'editor', 'author', 'shop_manager') );
            $role_list = get_option( 'wpneo_user_role_selector' );
            if( is_array( $role_list ) ){
                if( !empty( $role_list ) ){
                    foreach( $role_list as $val ){
                        $role = get_role( $val );
                        if ($role){
	                        $role->add_cap( 'campaign_form_submit' );
	                        $role->add_cap( 'upload_files' );
                        }
                    }
                }
            }
        }

        /**
         * Reset method, the ajax will call that method
         */


        public function wpneo_crowdfunding_reset(){
            update_option( 'wpneo_crowdfunding_is_used'         , WPNEO_CROWDFUNDING_VERSION); //Insert plugin version name into Option
            update_option( 'wpneo_cf_selected_theme',           'basic'); //Select a basic theme
            update_option( 'vendor_type',                       'woocommerce'); //Select default payment type to WooCommerce
            update_option( 'wpneo_default_campaign_status',      'draft'); //Select default campaign status
            update_option( 'wpneo_show_min_price',              'true'); //Set check true at min price show during campaign add
            update_option( 'wpneo_show_max_price',              'true'); //Set check true at max price show during campaign add
            update_option( 'wpneo_show_recommended_price',      'true'); //Set check true at recommended price show during campaign add
            update_option( 'wpneo_show_campaign_end_method',    'true'); //set check at campaign end method additional settings box
            update_option( 'wpneo_show_target_goal',            'true'); //Set check at campaign end method
            update_option( 'wpneo_show_target_date',            'true');
            update_option( 'wpneo_show_target_goal_and_date',   'true');
            update_option( 'wpneo_show_campaign_never_end',     'true');
            update_option( 'wpneo_enable_paypal_per_campaign_email', 'true');
            update_option( 'wpneo_single_page_reward_design',   '1'); //Single page rewards
            update_option( 'hide_cf_campaign_from_shop_page',    'false'); //Hide campaign form shop page initial value
            update_option( 'wpneo_crowdfunding_add_to_cart_redirect', 'checkout_page'); // Redirect Add to cart

            /**
             * reCaptcha Page Settings
             */
            update_option( 'wpneo_enable_recaptcha',            'false');
            update_option( 'wpneo_enable_recaptcha_in_user_registration', 'false');
            update_option( 'wpneo_enable_recaptcha_campaign_submit_page', 'false');
            update_option( 'wpneo_requirement_agree_title',      'I agree with the terms and conditions.'); //accept agreement during add campaign

            /**
             * Add new role for user
             */

            // Init Setup Action
            update_option( 'wpneo_user_role_selector', array('administrator', 'editor', 'author', 'shop_manager') );
            $role_list = get_option( 'wpneo_user_role_selector' );
            if( is_array( $role_list ) ){
                if( !empty( $role_list ) ){
                    foreach( $role_list as $val ){
                        $role = get_role( $val );
                        $role->add_cap( 'campaign_form_submit' );
                        $role->add_cap( 'upload_files' );
                    }
                }
            }
        }


        /**
         * Show notice if there is no vendor
         */
        public static function no_vendor_notice(){
            $html = '';
            $html .= '<div class="notice notice-error is-dismissible">
                        <p>'.__('Please install & activate WooCommerce in order to use WP Crowdfunding plugin.', 'wp-crowdfunding').'</p>
                    </div>';
            echo $html;
        }

        public static function wc_low_version(){
            $html = '';
            $html .= '<div class="notice notice-error is-dismissible">
                        <p>'.__('Your WooCommerce version is below then 3.0, please update', 'wp-crowdfunding').'</p>
                    </div>';
            echo $html;
        }

    }
}