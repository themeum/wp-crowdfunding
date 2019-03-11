<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! function_exists('wpneo_crowdfunding_search_shortcode_filter')) {
	function wpneo_crowdfunding_search_shortcode_filter($query){
		if (!empty($_GET['product_type'])) {
			$product_type = $_GET['product_type'];
			if ($product_type == 'croudfunding') {
				if ($query->is_search) {
					$query->set('post_type', 'product');
					$taxquery = array(
						array(
							'taxonomy' => 'product_type',
							'field' => 'slug',
							'terms' => 'crowdfunding',
						)
					);
					$query->set('tax_query', $taxquery);
				}
			}
		}
		return $query;
	}
}

if ( ! function_exists('wpneo_crowdfunding_get_author_name')){
	function wpneo_crowdfunding_get_author_name(){
		global $post;
		$author = get_user_by('id', $post->post_author);

		$author_name = $author->first_name . ' ' . $author->last_name;
		if (empty($author->first_name))
			$author_name = $author->display_name;

		return $author_name;
	}
}



if ( ! function_exists('wpneo_crowdfunding_get_author_name_by_login')){
	function wpneo_crowdfunding_get_author_name_by_login($author_login){
		$author = get_user_by('login', $author_login);

		$author_name = $author->first_name . ' ' . $author->last_name;
		if (empty($author->first_name))
			$author_name = $author->user_login;

		return $author_name;
	}
}

/**
 * @return mixed|string
 *
 * get campaigns location
 */
if ( ! function_exists('wpneo_crowdfunding_get_campaigns_location')){
	function wpneo_crowdfunding_get_campaigns_location(){
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
}

if ( ! function_exists('wpneo_crowdfunding_get_total_fund_raised_by_campaign')) {
	function wpneo_crowdfunding_get_total_fund_raised_by_campaign($campaign_id = 0){
		global $wpdb, $post;
		$db_prefix = $wpdb->prefix;

		if ($campaign_id == 0)
			$campaign_id = $post->ID;

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
                    woim.meta_key = '_product_id' AND woim.meta_value = %d AND wpposts.post_status = 'wc-completed';";

		$wp_sql = $wpdb->get_row($wpdb->prepare($query, $campaign_id));

		return $wp_sql->total_sales_amount;
	}
}

if ( ! function_exists('wpneo_crowdfunding_get_total_goal_by_campaign')) {
	function wpneo_crowdfunding_get_total_goal_by_campaign($campaign_id){
		return $funding_goal = get_post_meta($campaign_id, '_nf_funding_goal', true);
	}
}


if (!function_exists('wpneo_crowdfunding_price')){
	function wpneo_crowdfunding_price($price, $args = array()){
		return wc_price( $price, $args = array() );
	}
}

if ( ! function_exists('wpneo_crowdfunding_load_template')){
	function wpneo_crowdfunding_load_template($template = '404'){
		$template_class = new Wpneo_Crowdfunding_Templating();
		$locate_file = $template_class->_theme_in_themes_path.$template.'.php';

		if (file_exists($locate_file)){
			include $locate_file;
		}else{
			include $template_class->_theme_in_plugin_path.$template.'.php';
		}
	}
}


if ( ! function_exists('wpneo_crowdfunding_template')){
	function wpneo_crowdfunding_template($template = '404'){
		$template_class = new Wpneo_Crowdfunding_Templating();
		$locate_file = $template_class->_theme_in_themes_path.$template.'.php';

		if (file_exists($locate_file)){
			return $locate_file;
		}
		return $template_class->_theme_in_plugin_path.$template.'.php';
	}
}

// Pagination
if ( ! function_exists('wpneo_crowdfunding_pagination')) {
	function wpneo_crowdfunding_pagination($page_numb, $max_page){
		$html = '';
		$big = 999999999; // need an unlikely integer
		$html .= '<div class="wpneo-pagination">';
		$html .= paginate_links(array(
			'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'format' => '?paged=%#%',
			'current' => $page_numb,
			'total' => $max_page,
			'type' => 'list',
			'after_page_number' => '',
		));
		$html .= '</div>';
		return $html;
	}
}



if ( ! function_exists( 'wpneo_crowdfunding_template_campaign_title' ) ) {
	function wpneo_crowdfunding_template_campaign_title() {
		wpneo_crowdfunding_load_template('include/campaign-title');
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_template_campaign_author' ) ) {
	function wpneo_crowdfunding_template_campaign_author() {
		wpneo_crowdfunding_load_template('include/author');
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_template_campaign_location' ) ) {
	function wpneo_crowdfunding_template_campaign_location() {
		wpneo_crowdfunding_load_template('include/location');
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_campaign_single_left_div_start' ) ) {
	function wpneo_crowdfunding_campaign_single_left_div_start() {
		wpneo_crowdfunding_load_template('include/single-left-div-start');
	}
}


if ( ! function_exists( 'wpneo_crowdfunding_campaign_single_left_div_end' ) ) {
	function wpneo_crowdfunding_campaign_single_left_div_end() {
		wpneo_crowdfunding_load_template('include/single-left-div-end');
	}
}


if ( ! function_exists( 'wpneo_crowdfunding_campaign_single_tab' ) ) {
	function wpneo_crowdfunding_campaign_single_tab() {
		wpneo_crowdfunding_load_template('include/campaign-tab');
	}
}


if ( ! function_exists( 'wpneo_crowdfunding_campaign_single_feature_image' ) ) {
	function wpneo_crowdfunding_campaign_single_feature_image() {
		wpneo_crowdfunding_load_template('include/feature-image');
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_campaign_single_description' ) ) {
	function wpneo_crowdfunding_campaign_single_description() {
		wpneo_crowdfunding_load_template('include/description');
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_campaign_single_fund_raised_html' ) ) {
	function wpneo_crowdfunding_campaign_single_fund_raised_html() {
		wpneo_crowdfunding_load_template('include/fund-raised');
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_campaign_single_bakers_count_html' ) ) {
	function wpneo_crowdfunding_campaign_single_bakers_count_html() {
		wpneo_crowdfunding_load_template('include/single-bakers-counter');
	}
}


if ( ! function_exists( 'wpneo_crowdfunding_campaign_single_days_remaining' ) ) {
	function wpneo_crowdfunding_campaign_single_days_remaining() {
		wpneo_crowdfunding_load_template('include/days-remaining');
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_campaign_single_loop_item_fund_raised_percent' ) ) {
	function wpneo_crowdfunding_campaign_single_loop_item_fund_raised_percent() {
		wpneo_crowdfunding_load_template('include/fund_raised_percent');
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_campaign_single_fund_this_campaign_btn' ) ) {
	function wpneo_crowdfunding_campaign_single_fund_this_campaign_btn() {
		wpneo_crowdfunding_load_template('include/fund-campaign-btn');
	}
}


if (! function_exists('wpneo_crowdfunding_campaign_single_love_this')) {
	function wpneo_crowdfunding_campaign_single_love_this() {
		global $post;
		if (is_product()){
			if( function_exists('get_product') ){
				$product = wc_get_product( $post->ID );
				if( $product->is_type( 'crowdfunding' ) ){
					wpneo_crowdfunding_load_template('include/love_campaign');
				}
			}
		}
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_default_single_campaign_tabs' ) ) {

	function wpneo_crowdfunding_default_single_campaign_tabs( $tabs = array() ) {
		global $product, $post;

		// Description tab - shows product content
		if ( $post->post_content ) {
			$tabs['description'] = array(
				'title'     => __( 'Campaign Story', 'wp-crowdfunding' ),
				'priority'  => 10,
				'callback'  => 'wpneo_crowdfunding_campaign_story_tab'
			);
		}

		$saved_campaign_update = get_post_meta($post->ID, 'wpneo_campaign_updates', true);
		$saved_campaign_update = json_decode($saved_campaign_update, true);
		if (is_array($saved_campaign_update) && count($saved_campaign_update) > 0) {
			$tabs['update'] = array(
				'title'     => __('Updates', 'wp-crowdfunding'),
				'priority'  => 10,
				'callback'  => 'wpneo_crowdfunding_campaign_update_tab'
			);
		}

		$wpneo_show_contributor_table = get_post_meta($post->ID, 'wpneo_show_contributor_table', true);
		if($wpneo_show_contributor_table == '1') {
			$baker_list = WPNEOCF()->getCustomersByProduct();
			if (count($baker_list) > 0) {
				$tabs['baker_list'] = array(
					'title' => __('Backer List', 'wp-crowdfunding'),
					'priority' => 10,
					'callback' => 'wpneo_crowdfunding_campaign_baker_list_tab'
				);
			}
		}

		// Reviews tab - shows comments
		if ( comments_open() ) {
			$tabs['reviews'] = array(
				'title'    => sprintf( __( 'Reviews (%d)', 'wp-crowdfunding' ), $product->get_review_count() ),
				'priority' => 30,
				'callback' => 'comments_template'
			);
		}

		return $tabs;
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_campaign_story_tab' ) ) {
	function wpneo_crowdfunding_campaign_story_tab() {
		wpneo_crowdfunding_load_template('include/tabs/story-tab');
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_campaign_rewards_tab' ) ) {
	function wpneo_crowdfunding_campaign_rewards_tab() {
		wpneo_crowdfunding_load_template('include/tabs/rewards-tab');
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_campaign_update_tab' ) ) {
	function wpneo_crowdfunding_campaign_update_tab() {
		wpneo_crowdfunding_load_template('include/tabs/update-tab');
	}
}

if ( ! function_exists( 'wpneo_crowdfunding_campaign_baker_list_tab' ) ) {
	function wpneo_crowdfunding_campaign_baker_list_tab() {
		wpneo_crowdfunding_load_template('include/tabs/baker-list-tab');
	}
}
if ( ! function_exists( 'wpneo_crowdfunding_campaign_creator_info' ) ) {
	function wpneo_crowdfunding_campaign_creator_info() {
		wpneo_crowdfunding_load_template('include/creator-info');
	}
}
if ( ! function_exists('wpneo_crowdfunding_author_all_campaigns')) {
	function wpneo_crowdfunding_author_all_campaigns($author_id = 0){
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
}
if ( ! function_exists('wpneo_crowdfunding_add_http')) {
	function wpneo_crowdfunding_add_http($url){
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}
}

/**
 * Embeded video
 */

if ( ! function_exists('wpneo_crowdfunding_embeded_video')) {
	function wpneo_crowdfunding_embeded_video($url){
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
}
if (! function_exists('wpneo_crowdfunding_wc_login_form')) {
	function wpneo_crowdfunding_wc_login_form(){
		$html = '';
		$html .= '<div class="wpneo_login_form_div" style="display: none;">';
		$html .= wp_login_form(array('echo' => false, 'hidden' => true));
		$html .= '</div>';
		return $html;
	}
}
if (! function_exists('wpneo_crowdfunding_wc_toggle_login_form')) {
	function wpneo_crowdfunding_wc_toggle_login_form(){
		$html = '';
		$html .= '<div class="woocommerce">';
		$html .= '<div class="woocommerce-info">' . __("Please log in first?", "wp-crowdfunding") . ' <a class="wpneoShowLogin" href="#">' . __("Click here to login", "wp-crowdfunding") . '</a></div>';
		$html .= wpneo_crowdfunding_wc_login_form();
		$html .= '</div>';
		return $html;
	}
}


if (! function_exists('wpneo_crowdfunding_campaign_listing_by_author_url')) {
	function wpneo_crowdfunding_campaign_listing_by_author_url($user_login) {
		return esc_url(add_query_arg(array('author' => $user_login)));
	}
}
if (! function_exists('wpneo_crowdfunding_loop_item_thumbnail')) {
	function wpneo_crowdfunding_loop_item_thumbnail()  {
		wpneo_crowdfunding_load_template('include/loop/thumbnail');
	}
}
if (! function_exists('wpneo_crowdfunding_loop_item_button')) {
	function wpneo_crowdfunding_loop_item_button() {
		wpneo_crowdfunding_load_template('include/loop/details_button');
	}
}
if (! function_exists('wpneo_crowdfunding_loop_item_title')) {
	function wpneo_crowdfunding_loop_item_title() {
		wpneo_crowdfunding_load_template('include/loop/title');
	}
}
if (! function_exists('wpneo_crowdfunding_loop_item_author')) {
	function wpneo_crowdfunding_loop_item_author() {
		wpneo_crowdfunding_load_template('include/loop/author');
	}
}
if (! function_exists('wpneo_crowdfunding_loop_item_rating_html')) {
	function wpneo_crowdfunding_loop_item_rating_html() {
		wpneo_crowdfunding_load_template('include/loop/rating_html');
	}
}
if (! function_exists('wpneo_crowdfunding_loop_item_short_description')) {
	function wpneo_crowdfunding_loop_item_short_description(){
		wpneo_crowdfunding_load_template('include/loop/description');
	}
}
if (! function_exists('wpneo_crowdfunding_loop_item_location')) {
	function wpneo_crowdfunding_loop_item_location() {
		wpneo_crowdfunding_load_template('include/loop/location');
	}
}
if (! function_exists('wpneo_crowdfunding_loop_item_funding_goal')) {
	function wpneo_crowdfunding_loop_item_funding_goal() {
		wpneo_crowdfunding_load_template('include/loop/funding_goal');
	}
}
if (! function_exists('wpneo_crowdfunding_loop_item_fund_raised')) {
	function wpneo_crowdfunding_loop_item_fund_raised() {
		wpneo_crowdfunding_load_template('include/loop/fund_raised');
	}
}
if (! function_exists('wpneo_crowdfunding_loop_item_fund_raised_percent')) {
	function wpneo_crowdfunding_loop_item_fund_raised_percent() {
		wpneo_crowdfunding_load_template('include/loop/fund_raised_percent');
	}
}
if (! function_exists('wpneo_crowdfunding_loop_item_time_remaining')) {
	function wpneo_crowdfunding_loop_item_time_remaining() {
		wpneo_crowdfunding_load_template('include/loop/time_remaining');
	}
}
if (! function_exists('wpneo_campaign_story_right_sidebar')) {
	function wpneo_campaign_story_right_sidebar() {
		wpneo_crowdfunding_load_template('include/tabs/rewards-sidebar-form');
	}
}
if ( ! function_exists('is_campaign_loved_html')){
	function is_campaign_loved_html($echo = true){
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
}
if ( ! function_exists('wpneo_loved_campaign_count')){
	function wpneo_loved_campaign_count($user_id = 0){
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
}

/**
 * @since 01-02-17
 * @var 10.9
 */
if ( ! function_exists( 'wpneo_crowdfunding_overwrite_product_feature_image' ) ) {
	function wpneo_crowdfunding_overwrite_product_feature_image($img_html) {
		global $post;

		$wpneo_funding_video = trim(get_post_meta($post->ID, 'wpneo_funding_video', true));
		if (! empty($wpneo_funding_video)){
			return wpneo_crowdfunding_embeded_video($wpneo_funding_video);
		}else{
			return $img_html;
		}

		//wpneo_crowdfunding_load_template('include/feature-image');
	}
}

if ( ! function_exists('wp_crowdfunding_generator_tag')){
	function wp_crowdfunding_generator_tag( $gen, $type ) {
		switch ( $type ) {
			case 'html':
				$gen .= "\n" . '<meta name="generator" content="WP Crowdfunding ' . esc_attr( WPNEO_CROWDFUNDING_VERSION
					) . '">';
				break;
			case 'xhtml':
				$gen .= "\n" . '<meta name="generator" content="WP Crowdfunding ' . esc_attr( WPNEO_CROWDFUNDING_VERSION ) . '" />';
				break;
		}
		return $gen;
	}
}