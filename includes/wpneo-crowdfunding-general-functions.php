<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! function_exists('wpneo_post')){
    function wpneo_post($post_item){
        if (!empty($_POST[$post_item])) {
            return $_POST[$post_item];
        }
        return null;
    }
}

if (! function_exists('wpneo_crowdfunding_update_option_text')){
    function wpneo_crowdfunding_update_option_text($option_name = '', $option_value = null){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        }
    }
}

if (! function_exists('wpneo_crowdfunding_update_option_checkbox')){
    function wpneo_crowdfunding_update_option_checkbox($option_name = '', $option_value = null, $checked_default_value = 'false'){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        } else{
            update_option($option_name, $checked_default_value);
        }
    }
}

if (! function_exists('wpneo_crowdfunding_update_post_meta_text')){
    function wpneo_crowdfunding_update_post_meta_text($post_id, $meta_name = '', $meta_value = null){
        //if (!empty($meta_value)) {
            update_post_meta( $post_id, $meta_name, $meta_value);
        //}
    }
}

if (! function_exists('wpneo_crowdfunding_update_post_meta_checkbox')){
    function wpneo_crowdfunding_update_post_meta_checkbox($post_id, $meta_name = '', $meta_value = null, $checked_default_value = 'false'){
        if (!empty($meta_value)) {
            update_post_meta( $post_id, $meta_name, $meta_value);
        }else{
            update_post_meta( $post_id, $meta_name, $checked_default_value);
        }
    }
}

if (! function_exists('wpneo_get_published_pages')) {
    function wpneo_get_published_pages(){

        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'child_of' => 0,
            'parent' => -1,
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        return $pages;
    }
}

/**
 * @param string $version
 * @return bool
 */
function wpneo_wc_version_check( $version = '3.0' ) {
    if ( class_exists( 'WooCommerce' ) ) {
        global $woocommerce;
        if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
            return true;
        }
    }
    return false;
}

/**
 * @return mixed|void
 *
 * @return Crowdfunding Admin Page ID
 */

if ( ! function_exists('wpcf_screen_id')){
    function wpcf_screen_id(){
        $screen_ids = array(
            'toplevel_page_wpneo-crowdfunding',
            'crowdfunding_page_wpneo-crowdfunding-reports',
            'crowdfunding_page_wpneo-crowdfunding-withdraw',
        );

        return apply_filters('wpcf_screen_id', $screen_ids);
    }
}

/**
 * @param $from_date
 * @param $to_date
 * @return array
 */
if ( ! function_exists('get_date_range_pladges_received')){
    function get_date_range_pladges_received($from_date = null, $to_date = null){

        if ( ! $from_date){
            $from_date = date('Y-m-d 00:00:00', strtotime('-6 days'));
        }
        if ( ! $to_date){
            $to_date = date('Y-m-d 23:59:59');
        }

        $args = array(
            'post_type' 		=> 'product',
            'author'    		=> get_current_user_id(),
            'tax_query' 		=> array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'crowdfunding',
                ),
            ),
            'posts_per_page'    => -1
        );
        $id_list = get_posts( $args );
        $id_array = array();
        foreach ($id_list as $value) {
            $id_array[] = $value->ID;
        }

        $order_ids = array();
        if( is_array( $id_array ) ){
            if(!empty($id_array)){
                $id_array = implode( ', ', $id_array );
                global $wpdb;
                $prefix = $wpdb->prefix;

                $query = "SELECT order_id 
						FROM {$wpdb->prefix}woocommerce_order_items oi 
						LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta woim 
						ON woim.order_item_id = oi.order_item_id 
						WHERE woim.meta_key='_product_id' AND woim.meta_value IN ( {$id_array} )";
                $order_ids = $wpdb->get_col( $query );
                if(is_array($order_ids)){
                    if(empty($order_ids)){
                        $order_ids = array( '9999999' );
                    }
                }
            }else{
                $order_ids = array( '9999999' );
            }
        }

        $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
            'numberposts' => -1, // Chnage Number
            'post__in'	  => $order_ids,
            'meta_key'    => '_customer_user',
            'post_type'   => wc_get_order_types( 'view-orders' ),
            'post_status' => array_keys( wc_get_order_statuses() ),

            'date_query' => array(
                array(
                    'after'     => date('F jS, Y', strtotime($from_date)),
                    'before'    =>  array(
                        'year'  => date('Y', strtotime($to_date)),
                        'month' => date('m', strtotime($to_date)),
                        'day'   => date('d', strtotime($to_date)),
                    ),
                    'inclusive' => true,
                ),
            ),
        ) ) );

        return $customer_orders;
    }
}


/**
 * @param $product_ids
 * @param array $order_status
 *
 * @return array
 */

if ( ! function_exists('get_orders_ids_by_product_ids')){
	function get_orders_ids_by_product_ids( $product_ids , $order_status = array( 'wc-completed' ) ){
		global $wpdb;

		$results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value IN ( '" . implode( "','", $product_ids ) . "' )
    ");
		return $results;
	}
}

/**
 * @param int $user_id
 *
 * @return array
 */

if ( ! function_exists('get_products_ids_by_user')){
	function get_products_ids_by_user($user_id = 0){
		if ( ! $user_id){
			$user_id = get_current_user_id();
		}
		global $wpdb;
		$results = $wpdb->get_col( "SELECT ID from {$wpdb->posts} WHERE post_author = {$user_id} AND post_type = 'product' " );

		return $results;
	}
}

if ( ! function_exists('wp_crowdfunding_license_info')){
	function wp_crowdfunding_license_info(){
		$blank_license_info = array(
			'activated'     => false,
			'license_key'   => '',
			'license_to'    => '',
			'expires_at'    => '',
			'msg'  => 'A valid license is required to unlock available features',
		);

		$saved_license_info = maybe_unserialize(get_option(WPCF_BASENAME.'_license_info'));

		if ($saved_license_info && is_array($saved_license_info)){
			return (object) array_merge($blank_license_info, $saved_license_info);
		}
		return (object) $blank_license_info;
	}
}

$GLOBALS['wp_crowdfunding_license_info'] = wp_crowdfunding_license_info();

/**
 * @param int $author_id
 * @param string $author_nicename
 *
 * @return bool|string
 */
function get_wpcf_author_campaigns_url($author_id = 0, $author_nicename = ''){
	$author_id = $author_id ? $author_id : get_current_user_id();
	if (! $author_id){
		return false;
	}

	$url = get_author_posts_url($author_id, $author_nicename);
	return trailingslashit($url).'campaigns';
}