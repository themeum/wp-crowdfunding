<?php
namespace WPCF\woocommerce;

defined( 'ABSPATH' ) || exit;

class Template_Hooks {

    public function __construct(){
        add_action('wpneo_before_crowdfunding_single_campaign_summary', array($this, 'campaign_single_feature_image'));
        add_action('wpneo_crowdfunding_after_feature_img',              array($this, 'campaign_single_description'));
        
        // Single campaign Template hook
        add_action('wpneo_crowdfunding_single_campaign_summary',        array($this, 'campaign_title'));
        add_action('wpneo_crowdfunding_single_campaign_summary',        array($this, 'campaign_author'));
        add_action('wpneo_crowdfunding_single_campaign_summary',        array($this, 'loop_item_rating'));
        add_action('wpneo_crowdfunding_single_campaign_summary',        array($this, 'single_fund_raised'));
        add_action('wpneo_crowdfunding_single_campaign_summary',        array($this, 'single_item_fund_raised_percent'));
        add_action('wpneo_crowdfunding_single_campaign_summary',        array($this, 'single_fund_this_campaign_btn'));
        add_action('wpneo_crowdfunding_single_campaign_summary',        array($this, 'campaign_location'));
        add_action('wpneo_crowdfunding_single_campaign_summary',        array($this, 'creator_info'), 12);
        add_filter('wpneo_crowdfunding_default_single_campaign_tabs',   array($this, 'single_campaign_tabs'), 10);
        add_action('wpneo_after_crowdfunding_single_campaign_summary',  array($this, 'campaign_single_tab'));
        //Campaign Story Right Sidebar
        add_action('wpneo_campaign_story_right_sidebar',                array($this, 'story_right_sidebar'));
        //Listing Loop
        add_action('wpneo_campaign_loop_item_before_content',           array($this, 'loop_item_thumbnail'));
        add_action('wpneo_campaign_loop_item_content',                  array($this, 'loop_item_rating'));
        add_action('wpneo_campaign_loop_item_content',                  array($this, 'loop_item_title'));
        add_action('wpneo_campaign_loop_item_content',                  array($this, 'loop_item_author'));
        add_action('wpneo_campaign_loop_item_content',                  array($this, 'loop_item_location'));
        add_action('wpneo_campaign_loop_item_content',                  array($this, 'loop_item_short_description'));
        add_action('wpneo_campaign_loop_item_content',                  array($this, 'loop_item_fund_raised_percent'));
        add_action('wpneo_campaign_loop_item_content',                  array($this, 'loop_item_funding_goal'));
        add_action('wpneo_campaign_loop_item_content',                  array($this, 'loop_item_time_remaining'));
        add_action('wpneo_campaign_loop_item_content',                  array($this, 'loop_item_fund_raised'));
        add_action('wpneo_campaign_loop_item_content',                  array($this, 'loop_item_button'));
        //Dashboard Campaigns
        add_action('wpneo_dashboard_campaign_loop_item_content',        array($this, 'loop_item_title'));
        add_action('wpneo_dashboard_campaign_loop_item_content',        array($this, 'loop_item_author'));
        add_action('wpneo_dashboard_campaign_loop_item_content',        array($this, 'loop_item_location'));
        add_action('wpneo_dashboard_campaign_loop_item_content',        array($this, 'loop_item_fund_raised_percent'));
        add_action('wpneo_dashboard_campaign_loop_item_content',        array($this, 'loop_item_funding_goal'));
        add_action('wpneo_dashboard_campaign_loop_item_content',        array($this, 'loop_item_time_remaining'));
        add_action('wpneo_dashboard_campaign_loop_item_content',        array($this, 'loop_item_fund_raised'));
        add_action('wpneo_dashboard_campaign_loop_item_content',        array($this, 'loop_item_button'));
        add_action('wpneo_dashboard_campaign_loop_item_before_content', array($this, 'loop_item_thumbnail'));
        // Filter Search for Crowdfunding campaign
        add_filter('pre_get_posts' ,                                    array($this, 'search_shortcode_filter'));
        add_action('get_the_generator_html',                            array($this, 'tag_generator'), 10, 2 ); // Single Page Html
        add_action('get_the_generator_xhtml',                           array($this, 'tag_generator'), 10, 2 );
        add_action('wp',                                                array($this, 'woocommerce_single_page' ));
    }


    public function woocommerce_single_page(){
        if (is_product()){
            global $post;
            $product = wc_get_product($post->ID);
            if ($product->get_type() == 'crowdfunding'){
                add_action('woocommerce_single_product_summary',        array($this, 'single_fund_raised'), 20);
                add_action('woocommerce_single_product_summary',        array($this, 'loop_item_fund_raised_percent'), 20);
                add_action('woocommerce_single_product_summary',        array($this, 'single_fund_this_campaign_btn'), 20);
                add_action('woocommerce_single_product_summary',        array($this, 'campaign_location'), 20);
                add_action('woocommerce_single_product_summary',        array($this, 'creator_info'), 20);
                add_filter('woocommerce_single_product_image_html',     array($this, 'overwrite_product_feature_image'), 20);
            }
        }
    }


	public function search_shortcode_filter($query){
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
					if( wpcf_function()->wc_version() ){
						$taxquery['relation'] = 'AND';
						$taxquery[] = array(
							'taxonomy' => 'product_visibility',
							'field'    => 'name',
							'terms'    => 'exclude-from-search',
							'operator' => 'NOT IN',
						);
					}
					$query->set('tax_query', $taxquery);
				}
			}
		}
		return $query;
	}

	public function campaign_title() {
		wpcf_function()->template('include/campaign-title');
	}

	public function campaign_author() {
		wpcf_function()->template('include/author');
	}

	public function campaign_location() {
		wpcf_function()->template('include/location');
	}

	public function wpneo_crowdfunding_campaign_single_left_div_start() {
		wpcf_function()->template('include/single-left-div-start');
	}

	public function wpneo_crowdfunding_campaign_single_left_div_end() {
		wpcf_function()->template('include/single-left-div-end');
	}

	public function campaign_single_tab() {
		wpcf_function()->template('include/campaign-tab');
    }
    
	public function campaign_single_feature_image() {
		wpcf_function()->template('include/feature-image');
	}

	public function campaign_single_description() {
		wpcf_function()->template('include/description');
	}

	public function single_fund_raised() {
		wpcf_function()->template('include/fund-raised');
	}

	public function wpneo_crowdfunding_campaign_single_bakers_count_html() {
		wpcf_function()->template('include/single-bakers-counter');
	}

	public function wpneo_crowdfunding_campaign_single_days_remaining() {
		wpcf_function()->template('include/days-remaining');
	}

	public function single_item_fund_raised_percent() {
		wpcf_function()->template('include/fund_raised_percent');
	}

	public function single_fund_this_campaign_btn() {
		wpcf_function()->template('include/fund-campaign-btn');
	}

	public function single_campaign_tabs( $tabs = array() ) {
		global $product, $post;

		// Description tab - shows product content
		if ( $post->post_content ) {
			$tabs['description'] = array(
				'title'     => __( 'Campaign Story', 'wp-crowdfunding' ),
				'priority'  => 10,
				'callback'  => array($this, 'campaign_story_tab')
			);
		}

		$saved_campaign_update = get_post_meta($post->ID, 'wpneo_campaign_updates', true);
		$saved_campaign_update = json_decode($saved_campaign_update, true);
		if (is_array($saved_campaign_update) && count($saved_campaign_update) > 0) {
			$tabs['update'] = array(
				'title'     => __('Updates', 'wp-crowdfunding'),
				'priority'  => 10,
				'callback'  => array($this ,'campaign_update_tab')
			);
		}

		$show_table = get_post_meta($post->ID, 'wpneo_show_contributor_table', true);
		if($show_table == '1') {
			$baker_list = wpcf_function()->get_customers_product();
			if (count($baker_list) > 0) {
				$tabs['baker_list'] = array(
					'title' => __('Backer List', 'wp-crowdfunding'),
					'priority' => 10,
					'callback' => array($this, 'campaign_baker_list_tab')
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

	public function campaign_story_tab() {
		wpcf_function()->template('include/tabs/story-tab');
	}

	public function wpneo_crowdfunding_campaign_rewards_tab() {
		wpcf_function()->template('include/tabs/rewards-tab');
	}

	public function campaign_update_tab() {
		wpcf_function()->template('include/tabs/update-tab');
	}

	public function campaign_baker_list_tab() {
		wpcf_function()->template('include/tabs/baker-list-tab');
    }
    
	public function creator_info() {
		wpcf_function()->template('include/creator-info');
	}

	public function overwrite_product_feature_image($img_html) {
		global $post;
		$url = trim(get_post_meta($post->ID, 'wpneo_funding_video', true));
		if ( !empty($url) ) {
			wpcf_function()->get_embeded_video( $url );
		} else {
			return $img_html;
		}
	}


	public function wpcf_toggle_login_form(){
		$html = '';
		$html .= '<div class="woocommerce">';
		$html .= '<div class="woocommerce-info">' . __("Please log in first?", "wp-crowdfunding") . ' <a class="wpneoShowLogin" href="#">' . __("Click here to login", "wp-crowdfunding") . '</a></div>';
		$html .= wpneo_crowdfunding_wc_login_form();
		$html .= '</div>';
		return $html;
	}

	public function loop_item_thumbnail()  {
		wpcf_function()->template('include/loop/thumbnail');
	}

	public function loop_item_button() {
		wpcf_function()->template('include/loop/details_button');
	}

	public function loop_item_title() {
		wpcf_function()->template('include/loop/title');
	}

	public function loop_item_author() {
		wpcf_function()->template('include/loop/author');
	}

	public function loop_item_rating() {
		wpcf_function()->template('include/loop/rating_html');
	}

	public function loop_item_short_description(){
		wpcf_function()->template('include/loop/description');
	}

	public function loop_item_location() {
		wpcf_function()->template('include/loop/location');
	}

	public function loop_item_funding_goal() {
		wpcf_function()->template('include/loop/funding_goal');
	}

	public function loop_item_fund_raised() {
		wpcf_function()->template('include/loop/fund_raised');
	}

	public function loop_item_fund_raised_percent() {
		wpcf_function()->template('include/loop/fund_raised_percent');
	}

	public function loop_item_time_remaining() {
		wpcf_function()->template('include/loop/time_remaining');
	}

	public function story_right_sidebar() {
		wpcf_function()->template('include/tabs/rewards-sidebar-form');
	}


	public function tag_generator( $gen, $type ) {
		switch ( $type ) {
			case 'html':
				$gen .= "\n" . '<meta name="generator" content="WP Crowdfunding ' . esc_attr( WPCF_VERSION ) . '">';
				break;
			case 'xhtml':
				$gen .= "\n" . '<meta name="generator" content="WP Crowdfunding ' . esc_attr( WPCF_VERSION ) . '" />';
				break;
		}
		return $gen;
	}


}