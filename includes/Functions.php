<?php
namespace WPCF;

defined( 'ABSPATH' ) || exit;

class Functions {

    public function generator( $arr ){
        require_once WPCF_DIR_PATH . 'settings/Generator.php';
        $generator = new \WPCF\settings\Settings_Generator();
        $generator->generator( $arr );
    }


    public function post($post_item){
        if (!empty($_POST[$post_item])) {
            return $_POST[$post_item];
        }
        return null;
    }


    public function is_free(){
        if (is_plugin_active('wp-crowdfunding-pro/wp-crowdfunding-pro.php')) {
            return false;
        } else {
            return true;
        }
    }


    public function update_text($option_name = '', $option_value = null){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        }
    }


    public function update_checkbox($option_name = '', $option_value = null, $checked_default_value = 'false'){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        } else{
            update_option($option_name, $checked_default_value);
        }
    }
    

    public function update_meta($post_id, $meta_name = '', $meta_value = '', $checked_default_value = ''){
        if (!empty($meta_value)) {
            update_post_meta( $post_id, $meta_name, $meta_value);
        }else{
            update_post_meta( $post_id, $meta_name, $checked_default_value);
        }
    }


    public function get_pages(){
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
    

    // wpcf_function()->wc_version();
    public function wpcf_wc_version_check($version = '3.0'){ //@compatibility
        $this->version_check( $version );
    }
    public function wc_version($version = '3.0'){
        if (class_exists('WooCommerce')) {
            global $woocommerce;
            if (version_compare($woocommerce->version, $version, ">=")) {
                return true;
            }
        }
        return false;
    }
    

    public function is_woocommerce(){
        $vendor = get_option('vendor_type', 'woocommerce');
        if( $vendor == 'woocommerce' ){
            return true;
        }else{
            return false;
        }
    }
    
    
    public function screen_id(){
        $screen_ids = array(
            'toplevel_page_wpneo-crowdfunding',
            'crowdfunding_page_wpneo-crowdfunding-reports',
            'crowdfunding_page_wpneo-crowdfunding-withdraw',
        );
        return apply_filters('wpcf_screen_id', $screen_ids);
    }
    

    public function addon_config($addon_field = null){
        if ( ! $addon_field){
            return false;
        }
        $addonsConfig = maybe_unserialize(get_option('wpcf_addons_config'));
        if (isset($addonsConfig[$addon_field])){
            return $addonsConfig[$addon_field];
        }
        return false;
    }


    public function avalue_dot($key = null, $array = array()){
        $array = (array) $array;
        if ( ! $key || ! count($array) ){
            return false;
        }
        $option_key_array = explode('.', $key);
        $value = $array;
        foreach ($option_key_array as $dotKey){
            if (isset($value[$dotKey])){
                $value = $value[$dotKey];
            }else{
                return false;
            }
        }
        return $value;
    }


    public function get_wpcf_author_campaigns_url($author_id = 0, $author_nicename = ''){ //@compatibility
        $this->campaigns_url( $author_id, $author_nicename );
    }
    public function campaigns_url($author_id = 0, $author_nicename = ''){
        $author_id = $author_id ? $author_id : get_current_user_id();
        if (! $author_id){
            return false;
        }
        $url = get_author_posts_url($author_id, $author_nicename);
        return trailingslashit($url).'campaigns';
    }


    public function get_products_id_by_user($user_id = 0){
		if ( ! $user_id){
			$user_id = get_current_user_id();
		}
		global $wpdb;
		$results = $wpdb->get_col( "SELECT ID from {$wpdb->posts} WHERE post_author = {$user_id} AND post_type = 'product' " );

		return $results;
	}


    public function range_pladges_received($from_date = null, $to_date = null){
        if ( ! $from_date){
            $from_date = date('Y-m-d 00:00:00', strtotime('-6 days'));
        }
        if ( ! $to_date ){
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


	public function get_order_ids_by_product_ids( $product_ids , $order_status = array( 'wc-completed' ) ){
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
    

    public function author_name(){
		global $post;
		$author = get_user_by('id', $post->post_author);
		$author_name = $author->first_name . ' ' . $author->last_name;
		if (empty($author->first_name))
			$author_name = $author->display_name;

		return $author_name;
	}


	public function author_name_by_login($author_login){
		$author = get_user_by('login', $author_login);

		$author_name = $author->first_name . ' ' . $author->last_name;
		if (empty($author->first_name))
			$author_name = $author->user_login;

		return $author_name;
	}


	public function campaign_location(){
		global $post;
		$wpneo_country = get_post_meta($post->ID, 'wpneo_country', true);
		$location = get_post_meta($post->ID, '_nf_location', true);
		$country_name = '';
		if (class_exists('WC_Countries')) {
			//Get Country name from WooCommerce
			$countries_obj = new WC_Countries();
			$countries = $countries_obj->__get('countries');

			if ($wpneo_country){
				$country_name = $countries[$wpneo_country];
				$location = $location . ', ' . $country_name;
			}
		}
		return $location;
    }
    

    public function template($template = '404'){
		$template_class = new \WPCF\woocommerce\Templating;
		$locate_file = $template_class->_theme_in_themes_path.$template.'.php';

		if (file_exists($locate_file)){
			return $locate_file;
		}
		return $template_class->_theme_in_plugin_path.$template.'.php';
    }


    public function fund_raised($campaign_id = 0){
		global $wpdb, $post;
		$db_prefix = $wpdb->prefix;

		if ($campaign_id == 0)
			$campaign_id = $post->ID;

		// WPML compatibility.
		if ( apply_filters( 'wpml_setting', false, 'setup_complete' ) ) {
			$type = apply_filters( 'wpml_element_type', get_post_type( $campaign_id ) );
			$trid = apply_filters( 'wpml_element_trid', null, $campaign_id, $type );
			$translations = apply_filters( 'wpml_get_element_translations', null, $trid, $type );
			$campaign_ids = wp_list_pluck( $translations, 'element_id' );
		} else {
				$campaign_ids = array( $campaign_id );
		}
		$placeholders = implode( ',', array_fill( 0, count( $campaign_ids ), '%d' ) );
		

		$query = "SELECT
                    SUM(ltoim.meta_value) as total_sales_amount
                FROM
                    {$wpdb->prefix}woocommerce_order_itemmeta woim
			    LEFT JOIN
                    {$wpdb->prefix}woocommerce_order_items oi ON woim.order_item_id = oi.order_item_id
			    LEFT JOIN
                    {$wpdb->prefix}posts wpposts ON order_id = wpposts.ID
			    LEFT JOIN
                    {$wpdb->prefix}woocommerce_order_itemmeta ltoim ON ltoim.order_item_id = oi.order_item_id AND ltoim.meta_key = '_line_total'
			    WHERE
                    woim.meta_key = '_product_id' AND woim.meta_value IN ($placeholders) AND wpposts.post_status = 'wc-completed';";

		$wp_sql = $wpdb->get_row($wpdb->prepare($query, $campaign_ids));

		return $wp_sql->total_sales_amount;
    }
    

    public function campaign_loved($echo = true){
		global $post;
		$campaign_id = $post->ID;

		$html = '';
		if (is_user_logged_in()){
			//Get Current user id
			$user_id = get_current_user_id();
			//empty array
			$loved_campaign_ids = array();
			$prev_campaign_ids = get_user_meta($user_id, 'loved_campaign_ids', true);

			if ($prev_campaign_ids){
				$loved_campaign_ids = json_decode($prev_campaign_ids, true);
			}

			//If found previous liked
			if (in_array($campaign_id, $loved_campaign_ids)){
				$html .= '<a href="javascript:;" id="remove_from_love_campaign" data-campaign-id="'.$campaign_id.'"><i class="wpneo-icon wpneo-icon-love-full"></i></a>';
			} else {
				$html .= '<a href="javascript:;" id="love_this_campaign" data-campaign-id="'.$campaign_id.'"><i class="wpneo-icon wpneo-icon-love-empty"></i></a>';
			}
		} else {
			$html .= '<a href="javascript:;" id="love_this_campaign" data-campaign-id="'.$campaign_id.'"><i class="wpneo-icon wpneo-icon-love-empty"></i></a>';
		}

		if ($echo){
			echo $html;
		}else{
			return $html;
		}
    }
    
    public function login_form(){
		$html = '';
		$html .= '<div class="wpneo_login_form_div" style="display: none;">';
		$html .= wp_login_form(array('echo' => false, 'hidden' => true));
		$html .= '</div>';
		return $html;
    }

    public function loved_count($user_id = 0){
		global $post;
		$campaign_id = $post->ID;
		if ($user_id == 0) {
			if (is_user_logged_in()) {
				$user_id = get_current_user_id();
				$loved_campaign_ids = array();
				$prev_campaign_ids = get_user_meta($user_id, 'loved_campaign_ids', true);

				if ($prev_campaign_ids) {
					$loved_campaign_ids = json_decode($prev_campaign_ids, true);
					return count($loved_campaign_ids);
				}
			}
		}
		return 0;
    }
    

	public function author_campaigns($author_id = 0){
		if ( ! $author_id){
			$author_id = get_current_user_id();
		}

		$args = array(
			'post_status' => 'publish',
			'post_type' => 'product',
			'author' => $author_id,
			'tax_query' => array(
				array(
					'taxonomy' => 'product_type',
					'field' => 'slug',
					'terms' => 'crowdfunding',
				),
			),
			'posts_per_page' => -1
		);
		$the_query = new WP_Query($args);

		return $the_query;
	}


	public function url($url){
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}

	public function embeded_video($url){
		if (! empty($url)) {
			$embeded = wp_oembed_get($url);

			if ($embeded == false) {
				$url = strtolower($url);

				$format = '';
				if (strpos($url, '.mp4')) {
					$format = 'mp4';
				} elseif (strpos($url, '.ogg')) {
					$format = 'ogg';
				} elseif (strpos($url, '.webm')) {
					$format = 'WebM';
				}

				$embeded = '<video controls><source src="' . $url . '" type="video/' . $format . '">'.__('Your browser does not support the video tag.', 'wp-crowdfunding').'</video>';
			}
			return '<div class="wpneo-video-wrapper">' . $embeded . '</div>';
		} else{
			return false;
		}
    }
    
    public function total_goal($campaign_id){
		return $funding_goal = get_post_meta($campaign_id, '_nf_funding_goal', true);
    }
    
    public function price($price, $args = array()){
		return wc_price( $price, $args = array() );
    }
    
    public function user_meta($url){
        $shipping_first_name     = ( $_POST['shipping_first_name'] ) ? sanitize_text_field($_POST['shipping_first_name']) : "";
        update_user_meta($user_id,'shipping_first_name', $shipping_first_name);
    }

}