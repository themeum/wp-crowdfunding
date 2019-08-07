<?php
namespace WPCF\shortcode;

defined( 'ABSPATH' ) || exit;

class Dashboard {

    function __construct() {
        add_shortcode( 'wpcf_dashboard', array( $this, 'dashboard_callback' ) );

        add_action( 'wp_enqueue_scripts',    array($this, 'dashboard_assets'));

        add_action( 'wp_ajax_wpcf_dashbord_profile', array( $this, 'dashbord_profile' ) );
    }
    
    public function dashboard_assets($atts) {
        $page_id = get_option('wpneo_crowdfunding_dashboard_page_id');
        if( get_the_ID() && get_the_ID() == $page_id ) {
            wp_enqueue_script( 'wpcf-dashboard-script', WPCF_DIR_URL.'assets/js/dashboard.js', array('jquery'), WPCF_VERSION, true );
            wp_localize_script( 'wpcf-dashboard-script', 'WPCF', array (
                'dashboard_url' => get_permalink(),
                'ajax_url' => admin_url( 'admin-ajax.php' )
            ) );
        }
    }

    function dashboard_callback($attr) {
        return '<div id="wpcf-dashboard"></div>';
    }

    function dashbord_profile() {
        $current_user_id = get_current_user_id();
        $user = get_user_by('ID', $current_user_id);
        $data = ( object ) get_user_meta($current_user_id);
        $data->display_name = $user->display_name;
        $data->first_name = $user->first_name;
        $data->last_name = $user->last_name;

        $data->img_src = get_avatar_url( $current_user_id );
        $image_id = get_user_meta( $current_user_id, 'profile_image_id', true );
        if ( $image_id && $image_id > 0 ) {
            $data->img_src = wp_get_attachment_image_src($image_id, 'full')[0];
        }

        die(json_encode(array('success'=> 0, 'data' => $data )));
    }


}