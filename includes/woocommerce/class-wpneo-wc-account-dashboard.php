<?php
defined( 'ABSPATH' ) || exit;

if (! class_exists('WPNEO_WC_Account_Dashboard')) {

    class WPNEO_WC_Account_Dashboard {

        protected static $_instance;
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct(){            
            add_action( 'init',                                                 array( $this, 'wpneo_crowdfunding_endpoints') );
            add_filter( 'query_vars',                                           array( $this, 'wpneo_crowdfunding_query_vars'), 0 );
            add_filter( 'woocommerce_account_menu_items',                       array( $this, 'wpneo_crowdfunding_menu_items') );

            add_action( 'woocommerce_account_crowdfunding-dashboard_endpoint',  array( $this, 'wpneo_dashboard_endpoint_content' ) );
            add_action( 'woocommerce_account_profile_endpoint',                 array( $this, 'wpneo_profile_endpoint_content') );
            add_action( 'woocommerce_account_my-campaigns_endpoint',            array( $this, 'wpneo_my_campaigns_endpoint_content') );
            add_action( 'woocommerce_account_backed-campaigns_endpoint',        array( $this, 'wpneo_backed_campaigns_endpoint_content') );
            add_action( 'woocommerce_account_pledges-received_endpoint',        array( $this, 'wpneo_pledges_received_endpoint_content') );
            add_action( 'woocommerce_account_bookmarks_endpoint',               array( $this, 'wpneo_bookmarks_endpoint_content') );
        }


        // Rewrite Rules For Woocommerce My Account Page
        public function wpneo_crowdfunding_endpoints() {
            add_rewrite_endpoint( 'crowdfunding-dashboard', EP_ROOT | EP_PAGES );
            add_rewrite_endpoint( 'profile', EP_ROOT | EP_PAGES );
            add_rewrite_endpoint( 'my-campaigns', EP_ROOT | EP_PAGES );
            add_rewrite_endpoint( 'backed-campaigns', EP_ROOT | EP_PAGES );
            add_rewrite_endpoint( 'pledges-received', EP_ROOT | EP_PAGES );
            add_rewrite_endpoint( 'bookmarks', EP_ROOT | EP_PAGES );
        }

        // Query Variable
        public function wpneo_crowdfunding_query_vars( $vars ) {
            $vars[] = 'crowdfunding-dashboard';
            $vars[] = 'profile';
            $vars[] = 'my-campaigns';
            $vars[] = 'backed-campaigns';
            $vars[] = 'pledges-received';
            $vars[] = 'bookmarks';
            return $vars;
        }

        // Woocommerce Menu Items
        public function wpneo_crowdfunding_menu_items( $items ) {
            $new_items = array(
                'crowdfunding-dashboard'=> __( 'Crowdfunding Dashboard', 'wp-crowdfunding' ),
                'profile'               => __( 'Profile', 'wp-crowdfunding' ),
                'my-campaigns'           => __( 'My Campaigns', 'wp-crowdfunding' ),
                'backed-campaigns'       => __( 'Backed Campaigns', 'wp-crowdfunding' ),
                'pledges-received'      => __( 'Pledges Received', 'wp-crowdfunding' ),
                'bookmarks'             => __( 'Bookmarks', 'wp-crowdfunding' ),
            );
            $items = array_merge( $new_items,$items );
            return $items;
        }


        // Crowdfunding Dashboard
        public function wpneo_dashboard_endpoint_content() {
           $html = '';
           require_once WPCF_DIR_PATH.'includes/woocommerce/dashboard/dashboard.php';
           echo $html;
        }

        // Profile
        public function wpneo_profile_endpoint_content() {
           $html = '';
           require_once WPCF_DIR_PATH.'includes/woocommerce/dashboard/profile.php';
           echo $html;
        }

        // My Profile
        public function wpneo_my_campaigns_endpoint_content() {
           $html = '';
           require_once WPCF_DIR_PATH.'includes/woocommerce/dashboard/campaign.php';
           echo $html;
        }

        // Backed Campaigns
        public function wpneo_backed_campaigns_endpoint_content() {
           $html = '';
           require_once WPCF_DIR_PATH.'includes/woocommerce/dashboard/investment.php';
           echo $html;
        }

        // Pledges Received
        public function wpneo_pledges_received_endpoint_content() {
           $html = '';
           require_once WPCF_DIR_PATH.'includes/woocommerce/dashboard/order.php';
           echo $html;
        }

        // Bookmarks
        public function wpneo_bookmarks_endpoint_content() {
           $html = '';
           require_once WPCF_DIR_PATH.'includes/woocommerce/dashboard/bookmark.php';
           echo $html;
        }
    }
}
WPNEO_WC_Account_Dashboard::instance();