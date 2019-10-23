<?php
/**
 * WP-Crowdfunding dashboard Shortcodes
 *
 * @category   Crowdfunding
 * @package    Shortcode
 * @author     Themeum <www.themeum.com>
 * @copyright  2019 Themeum <www.themeum.com>
 * @since      2.1.0
 */

namespace WPCF\shortcode;
defined( 'ABSPATH' ) || exit;

class Dashboard {
    /**
     * @constructor
     * @since 2.1.0
     */
    function __construct() {
        add_action( 'wp_enqueue_scripts',    array($this, 'dashboard_assets') );
        add_shortcode( 'wpcf_dashboard', array( $this, 'dashboard_callback' ) );
    }

    /**
     * Enqueue dashboard assets
     * @since   2.1.0
     * @access  public
     */
    function dashboard_assets() {

        //dashboard scripts
        $api_namespace = WPCF_API_NAMESPACE . WPCF_API_VERSION;
        $page_id = get_option('wpneo_crowdfunding_dashboard_page_id');
        if( get_the_ID() && get_the_ID() == $page_id ) {
            wp_enqueue_script( 'chart.bundle.min.js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js', array('jquery'), '', true );
            wp_enqueue_script( 'wpcf-dashboard-script', WPCF_DIR_URL.'assets/js/dashboard.js', array('jquery'), WPCF_VERSION, true );
            wp_localize_script( 'wpcf-dashboard-script', 'WPCF', array (
                'dashboard_url' => get_permalink(),
                'assets'        => WPCF_DIR_URL.'assets/',
                'ajax_url'      => admin_url( 'admin-ajax.php' ),
                'rest_url'      => rest_url( $api_namespace ),
                'nonce'         => wp_create_nonce( 'wpcf_api_nonce' ),
                'wc_currency_symbol' => get_woocommerce_currency_symbol()
            ) );
        }

        wp_enqueue_script('fontawesome', 'https://kit.fontawesome.com/7617ec241a.js', array(), WPCF_VERSION, true);

        // Dashboard Styles
        if('false' !== get_option('wpcf_enable_google_fonts', 'true')){
            wp_enqueue_style('roboto', $this->google_fonts(), array(), WPCF_VERSION);
        }

    }

    /**
     * Dashboard shortcode callback
     * @since     2.1.0
     * @access    public
     * @param     {object}  attr
     * @return    {html}    mixed
     */
    function dashboard_callback($attr) {
        return '<div id="wpcf-dashboard"></div>';
    }

    /**
     * Register Google fonts.
     *
     * @since 2.1.0
     * @return string Google fonts URL for the plugin.
     */
    public function google_fonts() {
        $google_fonts = apply_filters(
            'wpcf_google_font_families', array(
                'roboto' => 'Roboto:300,400,400i,500,500i,700',
            )
        );
        $query_args = array(
            'family' => implode( '|', $google_fonts ),
            'subset' => rawurlencode( 'latin,latin-ext' ),
            'display' => 'swap'
        );
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
        return $fonts_url;
    }

}
