<?php
namespace WPCF\shortcode;

defined( 'ABSPATH' ) || exit;


class Campaign_Submit_Form {
    public function __construct() {
        add_action('wp_enqueue_scripts',    array($this, 'campaign_form_assets')); 
        add_shortcode('wpcf_form',          array($this, 'campaign_form_callback'));
    }
    
    function campaign_form_assets() {
        $api_namespace = WPCF_API_NAMESPACE . WPCF_API_VERSION;
        $page_id = get_option('wpneo_form_page_id');
        if( get_the_ID() && get_the_ID() == $page_id ) {
            wp_enqueue_style( 'wpcf-campaign-style', WPCF_DIR_URL.'assets/css/campaign-form.css', false, WPCF_VERSION );
            wp_enqueue_script( 'wpcf-campaign-script', WPCF_DIR_URL.'assets/js/campaign-form.js', array('jquery'), WPCF_VERSION, true );
            wp_localize_script( 'wpcf-campaign-script', 'WPCF', array (
                'rest_url'      => rest_url( $api_namespace ),
                'nonce'         => wp_create_nonce( 'wpcf_form_nonce' )
            ) );
        }
    }

    // Shortcode for Forntend Submission Form
    function campaign_form_callback($atts) {
        return '<div id="wpcf-live-form"></div>';
    }
}


