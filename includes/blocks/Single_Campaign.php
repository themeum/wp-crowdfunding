<?php
namespace WPCF\blocks;

defined( 'ABSPATH' ) || exit;

class Single_Campaign{
    
    public function __construct(){
        $this->register_single_campaign();
    }

    public function register_single_campaign(){
        register_block_type(
            'wp-crowdfunding/singlecampaign',
            array(
                'attributes' => array(
                    'majorColorpalette'    => array(
                        'type'          => 'string',
                        'default'       => '#1adc68',
                    ),
                    'titlecolor'    => array(
                        'type'          => 'string',
                        'default'       => '#ffffff',
                    ),
                    'campaignID'    => array(
                        'type'          => 'string',
                        'default'       => '',
                    ),
                ),
                'render_callback' => array( $this, 'single_campaign_block_callback' ),
            )
        );
    }

    public function single_campaign_block_callback( $att ){
        $majorColor     = isset( $att['majorColorpalette']) ? $att['majorColorpalette'] : '';
        $textcolor      = isset( $att['titlecolor']) ? $att['titlecolor'] : '';
        $campaignID      = isset( $att['campaignID']) ? $att['campaignID'] : '';
    
        $atts = array(
            'campaign_id' => $campaignID,
        );

        $args = array(
            'posts_per_page'      => 1,
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'no_found_rows'       => 1,
        );

        if ( isset( $atts['campaign_id'] ) ) {
            $args['p'] = absint( $atts['campaign_id'] );
        }

        $single_product = new \WP_Query( $args );

        // For "is_single" to always make load comments_template() for reviews.
        $single_product->is_single = true;

        ob_start();
        
        global $wp_query;

        // Backup query object so following loops think this is a product page.
        $previous_wp_query = $wp_query;
        $wp_query          = $single_product;

        wp_enqueue_script( 'wc-single-product' );

        while ( $single_product->have_posts() ) {
            $single_product->the_post();
            wpcf_function()->template('single-crowdfunding-content-only');
        }

        // restore $previous_wp_query and reset post data.
        $wp_query = $previous_wp_query;
        wp_reset_postdata();
        $final_content = ob_get_clean();

        return '<div class="woocommerce">' . $final_content . '</div>';
        
    }
}
