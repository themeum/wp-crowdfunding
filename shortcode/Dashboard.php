<?php
namespace WPCF\shortcode;

defined( 'ABSPATH' ) || exit;

class Dashboard {

    function __construct() {
        add_shortcode( 'wpcf_dashboard', array( $this, 'dashboard_callback' ) );

        add_action( 'wp_enqueue_scripts',    array($this, 'dashboard_assets'));

        add_action( 'wp_ajax_wpcf_dashbord_profile', array( $this, 'dashbord_profile' ) );
        add_action( 'wp_ajax_wpcf_dashbord_my_campaigns', array( $this, 'my_campaigns' ) );
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

    function my_campaigns() {
        $page_numb = max( 1, get_query_var('paged') );
        $posts_per_page = get_option( 'posts_per_page',10 );
        $args = array(
            'post_type' 		=> 'product',
            'post_status'		=> array('publish', 'draft'),
            'author'    		=> get_current_user_id(),
            'tax_query' 		=> array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'crowdfunding',
                ),
            ),
            'posts_per_page'    => $posts_per_page,
            'paged'             => $page_numb
        );
        $the_query = new \WP_Query( $args );

        $data = array();
        if ( $the_query->have_posts() ) : global $post; $i = 0;
            while ( $the_query->have_posts() ) : $the_query->the_post();

                $total_raised = wpcf_function()->get_total_fund();
                $total_raised = ($total_raised) ? $total_raised : 0;
                $funding_goal = get_post_meta($post->ID, '_nf_funding_goal', true);
                $end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);

                $data[$i] = array(
                    'title'             => get_the_title(),
                    'permalink'         => get_permalink(),
                    'thumbnail'         => woocommerce_get_product_thumbnail(),
                    'author_name'       => wpcf_function()->get_author_name(),
                    'location'          => wpcf_function()->campaign_location(),
                    'raised_percent'    => wpcf_function()->get_fund_raised_percent_format(),
                    'total_raised'      => wc_price( $total_raised ),
                    'funding_goal'      => wc_price( $funding_goal ),
                    'end_method'        => $end_method,
                    'is_started'        => wpcf_function()->is_campaign_started(),
                    'days_remaining'    => wpcf_function()->get_date_remaining(),
                    'days_until_launch' => wpcf_function()->days_until_launch()
                );
                $i++;
            endwhile;
        endif;

        die(json_encode(array('success'=> 0, 'data' => $data )));
    }

}