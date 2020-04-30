<?php 

defined( 'ABSPATH' ) || exit;

# Register api hook
add_action('rest_api_init','register_api_hook');
function register_api_hook(){
    $post_types = get_post_types();

    register_rest_field( $post_types, 'wpcf_image_urls',
        array(
            'get_callback'          => 'wpcf_featured_image_urls',
            'update_callback'       => null,
            'schema'                => array(
                'description'       => __( 'Different sized featured images' ),
                'type'              => 'array',
            ),
        )
    );
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
        'product', 'wpcf_author',
        array(
            'get_callback'    => 'wpcf_get_author_info',
            'update_callback' => null,
            'schema'          => null,
        )
    );
} 

# Callback functions: Author Name
function wpcf_get_author_info( $object ) {
    global $authordata;
    $author['url']          = get_avatar_url(get_current_user_id(), 'thumbnail');
    $author['display_name'] = get_the_author_meta('display_name', $object->ID);
    $author['author_link']  = get_author_posts_url($object->ID);
    $author['author_name']  = get_the_author($authordata->ID);
    $author['location'] = get_post_meta( get_the_ID(), '_nf_location', true ); 

    return $author;
}

# Callback function: Column.
function wpcf_campaign_get_column($object) {
    $col_num = get_option('number_of_collumn_in_row', 3);
    return $col_num;
}

# Callback Function: Feature Image.
function wpcf_featured_image_urls( $object, $field_name, $request ) {
    $image = wp_get_attachment_image_src( $object['featured_media'], 'full', false );
    return array(
        'full'          => is_array( $image ) ? $image : '',
        'portrait'      => is_array( $image ) ? wp_get_attachment_image_src( $object['featured_media'], 'wpcf-portrait', false ) : '',
        'thumbnail'     => is_array( $image ) ? wp_get_attachment_image_src( $object['featured_media'], 'wpcf-thumbnail', false ) : '',
    );
}
