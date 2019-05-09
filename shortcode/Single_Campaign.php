<?php
defined( 'ABSPATH' ) || exit;

add_shortcode( 'wp_crowdfunding_single_campaign', 'wpcf_single_campaign_callback_old' ); //@comparability
add_shortcode( 'wpcf_single_campaign', 'wpcf_single_campaign_callback_new' );

function wpcf_single_campaign_callback_old( $attr ){
    wpcf_single_campaign_callback( $attr, 'wp_crowdfunding_single_campaign' );
}

function wpcf_single_campaign_callback_new( $attr ){
    wpcf_single_campaign_callback( $attr, 'wpcf_single_campaign' );
}

function wpcf_single_campaign_callback( $atts, $shortcode ){
    $atts = shortcode_atts( array(
        'campaign_id' => 0,
    ), $atts, $shortcode );

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

    $single_product = new WP_Query( $args );

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
        wpneo_crowdfunding_load_template('single-crowdfunding-content-only');
    }

    // restore $previous_wp_query and reset post data.
    $wp_query = $previous_wp_query;
    wp_reset_postdata();
    $final_content = ob_get_clean();

    return '<div class="woocommerce">' . $final_content . '</div>';
}