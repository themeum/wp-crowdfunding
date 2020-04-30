<?php 

defined( 'ABSPATH' ) || exit;

# Register api hook
add_action('rest_api_init','register_api_hook');
function register_api_hook(){
    $post_types = get_post_types();

    # Column.
    register_rest_field(
        'product', 'column',
        array(
            'get_callback'      => 'wpcf_campaign_get_column',
            'update_callback'   => null,
            'schema'            => array(
                'description'   => __('Product column'),
                'type'          => 'string',
            ),
        )
    ); 
    # Campaign Author name.
    register_rest_field(
        'product', 'wpcf_product',
        array(
            'get_callback'    => 'wpcf_get_prodcut_info',
            'update_callback' => null,
            'schema'          => null,
        )
    );
} 

# Callback functions: Author Name
function wpcf_get_prodcut_info( $object ) {

    $author['display_name'] = wpcf_function()->get_author_name();
    $author['location']     = get_post_meta( get_the_ID(), '_nf_location', true ); 
    $author['funding_goal'] = get_post_meta( get_the_ID(), '_nf_funding_goal', true ); 

    # Fund raised
    $raised = wpcf_function()->get_total_fund();
    $author['total_raised'] = $raised ? $raised : 0;

    # Fund raised percent
    $author['raised_percent'] = wpcf_function()->get_fund_raised_percent_format();

    # Product Description.
    $text_limit = get_option('number_of_words_show_in_listing_description');
    $author['desc']         = wpcf_function()->limit_word_text(strip_tags(get_the_content()), $text_limit);

    # Days remaining
    $author['days_remaining'] = apply_filters('date_remaining_msg', __(wpcf_function()->get_date_remaining(), 'wp-crowdfunding'));

    $author['product_thumb'] = woocommerce_get_product_thumbnail();

    return $author;
}

# Callback function: Column.
function wpcf_campaign_get_column($object) {
    $col_num = get_option('number_of_collumn_in_row', 3);
    return $col_num;
}
