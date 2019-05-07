<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_shortcode( 'wpneo_search_shortcode','wpcf_search_callback' ); //@comparability
add_shortcode( 'wpcf_search','wpcf_search_callback' );

function wpcf_search_callback() {
    ob_start(); ?>
    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <input type="search" class="search-field" placeholder="<?php _e( "Search","wp-crowdfunding" ); ?>" value="<?php if( isset( $_GET['s'] ) ){ echo $_GET['s']; } ?>" name="s">
        <input type="hidden" name="post_type" value="product">
        <input type="hidden" name="product_type" value="croudfunding">
        <button type="submit"><?php _e( "Search" , "wp-crowdfunding" ); ?></button>
    </form>
    <?php
    return ob_get_clean();
}
