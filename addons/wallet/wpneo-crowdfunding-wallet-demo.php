<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('WPNeo_Crowdfunding_Wallet')) {
    class WPNeo_Crowdfunding_Wallet {
        /**
         * @var null
         * $_instance
         */
        protected static $_instance = null;

        /**
         * @return null|WPNeo_Crowdfunding_Wallet
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * WPNeo_Crowdfunding_Wallet constructor.
         */
        public function __construct(){
            add_filter('wpcf_settings_tabs', array($this, 'wpcf_wallet_settings')); //Hook to add social share field
        }

        public function wpcf_wallet_settings($tabs){
            if (WPCF_TYPE === 'enterprise'){
                $load_tab = WPCF_DIR_PATH.'addons/wallet/pages/tab-wallet.php';
            }else{
                $load_tab = WPCF_DIR_PATH.'addons/wallet/pages/tab-wallet-demo.php';
            }

            $tabs['wallet'] = array(
                'tab_name' => __('Wallet','wp-crowdfunding'),
                'load_form_file' => $load_tab
            );
            return $tabs;
        }

    }
}
WPNeo_Crowdfunding_Wallet::instance();