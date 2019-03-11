<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_shortcode( 'wpneo_search_shortcode','wpneo_search_shortcode_data' );
function wpneo_search_shortcode_data() {
    ob_start(); ?>
    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <input type="search" class="search-field" placeholder="<?php _e( "Search","wp-crowdfunding" ); ?>" value="" name="s">
        <input type="hidden" name="post_type" value="product">
        <input type="hidden" name="product_type" value="croudfunding">
        <button type="submit"><?php _e( "Search" , "wp-crowdfunding" ); ?></button>
    </form>
    <?php
    return ob_get_clean();
}
