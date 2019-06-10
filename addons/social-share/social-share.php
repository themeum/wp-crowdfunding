<?php
defined( 'ABSPATH' ) || exit;

/**
 * Defined the tutor main file
 */
define('WPCF_SOCIAL_SHARE_FILE', __FILE__);

/**
 * Showing config for addons central lists
 */
add_filter('wpcf_addons_lists_config', 'wpcf_social_share_config');
function wpcf_social_share_config($config){
	$newConfig = array(
		'name'          => __( 'Social Share', 'wp-crowdfunding' ),
		'description'   => __( 'WP Crowdfunding Social Share', 'wp-crowdfunding' ),
	);

	$basicConfig = (array) WPCF_SOCIAL_SHARE();
	$newConfig = array_merge($newConfig, $basicConfig);

	$config[plugin_basename( WPCF_SOCIAL_SHARE_FILE )] = $newConfig;
	return $config;
}

if ( ! function_exists('WPCF_SOCIAL_SHARE')) {
	function WPCF_SOCIAL_SHARE() {
		$info = array(
			'path'              => plugin_dir_path( WPCF_SOCIAL_SHARE_FILE ),
			'url'               => plugin_dir_url( WPCF_SOCIAL_SHARE_FILE ),
			'basename'          => plugin_basename( WPCF_SOCIAL_SHARE_FILE ),
			'nonce_action'      => 'wpcf_nonce_action',
			'nonce'             => '_wpnonce',
		);
		return (object) $info;
	}
}


if ( ! class_exists('Neo_Social_Share_Init')) {
    class Neo_Social_Share_Init{
        /**
         * @var null
         *
         * Instance of this class
         */
        protected static $_instance = null;

        /**
         * @return null|Wpneo_Crowdfunding
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct(){
            add_action( 'init',                             array($this, 'wpneo_embed_data') );
            add_action( 'wp_enqueue_scripts',               array($this, 'wpneo_social_share_enqueue_frontend_script') ); //Add social share js in footer
            add_filter('wpcf_settings_tabs',                array($this, 'add_social_share_tab_to_wpneo_crowdfunding_settings')); //Hook to add social share field with user registration form

            add_action( 'init',                             array($this, 'wpneo_social_share_save_settings') ); // Social Share Settings
            add_action( 'wp_ajax_wpcf_embed_action',        array($this, 'wpneo_embed_campaign_action') );
            add_action( 'wp_ajax_nopriv_wpcf_embed_action', array($this, 'wpneo_embed_campaign_action') );
        }

        public function add_social_share_tab_to_wpneo_crowdfunding_settings($tabs){
            $tabs['social-share'] = array(
                'tab_name' => __('Social Share','wp-crowdfunding'),
                'load_form_file' => WPCF_DIR_PATH . 'pages/tab-social-share.php'
            );
            return $tabs;
        }

        public function wpneo_social_share_enqueue_frontend_script(){
            if ( get_option('wpneo_enable_social_share') == 'true') {
                wp_enqueue_script('wp-neo-jquery-social-share-front', WPCF_DIR_URL .'addons/social-share/jquery.prettySocial.min.js', array('jquery'), WPCF_VERSION, true);
            }
        }

          /**
         * All settings will be save in this method
         */
        public function wpneo_social_share_save_settings(){
            if (isset($_POST['wpneo_admin_settings_submit_btn']) && isset($_POST['wpneo_varify_social_share']) && wp_verify_nonce( $_POST['wpneo_settings_page_nonce_field'], 'wpneo_settings_page_action' ) ){
                // Checkbox
                $wpneo_enable_social_share = sanitize_text_field(wpneo_post('wpneo_enable_social_share'));
                wpcf_update_checkbox('wpneo_enable_social_share', $wpneo_enable_social_share);

                $wpneo_twitter_social_share = sanitize_text_field(wpneo_post('wpneo_twitter_social_share'));
                wpcf_update_checkbox('wpneo_twitter_social_share', $wpneo_twitter_social_share);

                $wpneo_facebook_social_share = sanitize_text_field(wpneo_post('wpneo_facebook_social_share'));
                wpcf_update_checkbox('wpneo_facebook_social_share', $wpneo_facebook_social_share);

                $wpneo_facebook_social_share = sanitize_text_field(wpneo_post('wpneo_facebook_social_share'));
                wpcf_update_checkbox('wpneo_facebook_social_share', $wpneo_facebook_social_share);

                $wpneo_googleplus_social_share = sanitize_text_field(wpneo_post('wpneo_googleplus_social_share'));
                wpcf_update_checkbox('wpneo_googleplus_social_share', $wpneo_googleplus_social_share);

                $wpneo_pinterest_social_share = sanitize_text_field(wpneo_post('wpneo_pinterest_social_share'));
                wpcf_update_checkbox('wpneo_pinterest_social_share', $wpneo_pinterest_social_share);

                $wpneo_linkedin_social_share = sanitize_text_field(wpneo_post('wpneo_linkedin_social_share'));
                wpcf_update_checkbox('wpneo_linkedin_social_share', $wpneo_linkedin_social_share);

                //Text Field
                $wpneo_twitter_via = sanitize_text_field(wpneo_post('wpneo_twitter_via'));
                wpcf_update_checkbox('wpneo_twitter_via', $wpneo_twitter_via);

                $wpneo_linkedin_via = sanitize_text_field(wpneo_post('wpneo_linkedin_via'));
                wpcf_update_checkbox('wpneo_linkedin_via', $wpneo_linkedin_via);

                $wpneo_embed_social_share = sanitize_text_field(wpneo_post('wpneo_embed_social_share'));
                wpcf_update_checkbox('wpneo_embed_social_share', $wpneo_embed_social_share);
            }
        }


        // Data Post Embed Code
        public function wpneo_embed_data(){
            $url = $_SERVER["REQUEST_URI"];
            $embed = strpos($url, 'themeumembed');
            if ($embed!==false){
                $end_part = explode('/', rtrim($url, '/'));
                if( $end_part ){
                    global $post;
                    $post_id = end( $end_part );
                    $args = array( 'p' => $post_id, 'post_type' => 'product','post_status' => 'publish' );
                    $myposts = get_posts( $args );
                    foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
                        <!DOCTYPE html>
                        <html>
                            <head>
                                <style type="text/css">
                                    .wpneo-listings{
                                        width: 300px;
                                        border: 1px solid #e9e9e9;
                                        border-radius: 3px;
                                    }
                                    .wpneo-listing-img {
                                        position: relative;
                                    }
                                    .wpneo-listing-img img {
                                        width: 100%;
                                        height: auto;
                                    }
                                    .wpneo-listing-content{
                                        padding: 15px;
                                    }
                                    .wpneo-listing-content h4{
                                        margin: 0;
                                    }
                                    .wpneo-listing-content h4 a {
                                        color: #000;
                                        font-size: 24px;
                                        font-weight: normal;
                                        line-height: 28px;
                                        box-shadow: none;
                                        text-decoration: none;
                                        letter-spacing: normal;
                                        text-transform: capitalize;
                                    }
                                    .wpneo-author {
                                        color: #737373;
                                        font-size: 16px;
                                        line-height: 18px;
                                        margin: 0;
                                    }
                                    .wpneo-author a {
                                        color: #737373;
                                        text-decoration: none;
                                        box-shadow: none;
                                    }
        
                                    #neo-progressbar {
                                        overflow: hidden;
                                        background-color: #f2f2f2;
                                        border-radius: 7px;
                                        padding: 0px;
                                    }
                                    #neo-progressbar > div {
                                        background-color: #4C76FF;
                                        height: 10px;
                                        border-radius: 10px;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="wpneo-listings">
                                    <div class="wpneo-listing-img">
                                        <a target="_top" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php echo woocommerce_get_product_thumbnail(); ?></a>
                                    </div>
                                    <div class="wpneo-listing-content">
        
                                        <?php $author_name = wpneo_crowdfunding_get_author_name(); ?>
                                        <h4><a target="_top" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        <p class="wpneo-author"><?php _e('by','wp-crowdfunding'); ?> 
                                            <a target="_top" href="<?php echo wpneo_crowdfunding_campaign_listing_by_author_url( get_the_author_meta( 'user_login' ) ); ?>"><?php echo $author_name; ?></a>
                                        </p>
        
                                        <?php if (wpneo_crowdfunding_get_campaigns_location()){ ?>
                                            <div class="wpneo-location-wrapper">
                                                <span><?php echo wpneo_crowdfunding_get_campaigns_location(); ?></span>
                                            </div>
                                        <?php } ?>
        
                                        <p class="wpneo-short-description"><?php echo WPNEOCF()->limit_word_text(strip_tags(get_the_content()), 130); ?></p>
        
                                        <?php $raised_percent = WPNEOCF()->getFundRaisedPercentFormat(); ?>
                                        <div class="wpneo-raised-percent">
                                            <div class="wpneo-meta-name"><?php _e('Raised Percent', 'wp-crowdfunding'); ?> :</div>
                                            <div class="wpneo-meta-desc" ><?php echo $raised_percent; ?></div>
                                        </div>
        
                                        <div class="wpneo-raised-bar">
                                            <div id="neo-progressbar">
                                                <?php $css_width = WPNEOCF()->getFundRaisedPercent(); if( $css_width >= 100 ){ $css_width = 100; } ?>
                                                <div style="width: <?php echo $css_width; ?>%"></div>
                                            </div>
                                        </div>
        
                                        <div class="wpneo-funding-data">
                                            
                                            <?php $funding_goal = get_post_meta( get_the_ID() , '_nf_funding_goal', true); ?>
                                            <div class="wpneo-funding-goal">
                                                <div class="wpneo-meta-desc"><?php echo wc_price( $funding_goal ); ?></div>
                                                <div class="wpneo-meta-name"><?php _e('Funding Goal', 'wp-crowdfunding'); ?></div>
                                            </div>
        
                                            <?php
                                            $wpneo_campaign_end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);
                                            $days_remaining = apply_filters('date_expired_msg', __('0', 'wp-crowdfunding'));
                                            if (WPNEOCF()->dateRemaining()){
                                                $days_remaining = apply_filters('date_remaining_msg', __(WPNEOCF()->dateRemaining(), 'wp-crowdfunding'));
                                            }
                                            if ($wpneo_campaign_end_method != 'never_end'){ ?>
                                                <?php if (WPNEOCF()->is_campaign_started()){ ?>
                                                    <div class="wpneo-meta-desc"><?php echo WPNEOCF()->dateRemaining(); ?></div>
                                                    <div class="wpneo-meta-name float-left"><?php _e( 'Days to go','wp-crowdfunding' ); ?></div>
                                                <?php } else { ?>
                                                    <div class="wpneo-meta-desc"><?php echo WPNEOCF()->days_until_launch(); ?></div>
                                                    <div class="iwpneo-meta-name float-left"><?php _e( 'Days Until Launch','wp-crowdfunding' ); ?></div>
                                                <?php } ?>
                                            <?php } ?>

                                            

                                            <?php
                                            $raised = 0;
                                            $total_raised = WPNEOCF()->totalFundRaisedByCampaign();
                                            if ($total_raised){ $raised = $total_raised; }
                                            ?>
                                            <div class="wpneo-fund-raised">
                                                <div class="wpneo-meta-desc"><?php echo wc_price($raised); ?></div>
                                                <div class="wpneo-meta-name"><?php _e('Fund Raised', 'wp-crowdfunding'); ?></div>
                                            </div>
        
                                        </div>                     
                                    </div>
                                </div>
                            </body>
                        </html>
                    <?php endforeach; 
                    wp_reset_postdata();
                }
                exit();
            }
        }


        // Odrer Data View 
        public function wpneo_embed_campaign_action(){
            
            $html = '';
            $title = __("Embed Code","wp-crowdfunding");
            $postid = sanitize_text_field($_POST['postid']);
            if( $postid ){
                $html .= '<div>';
                $html .= '<textarea><iframe width="310" height="656" src="'.esc_url( home_url( "/" ) ).'themeumembed/'.$postid.'" frameborder="0" scrolling="no"></iframe></textarea>';
                $html .= '<i>'.__("Copy this code and paste inside your content.","wp-crowdfunding").'</i>';
                $html .= '</div>';
            }
            die(json_encode(array('success'=> 1, 'message' => $html, 'title' => $title )));
        }
    }
}
Neo_Social_Share_Init::instance();

/**
 * Add to hook share option.
 */
if ( ! function_exists( 'wpneo_crowdfunding_campaign_single_social_share' ) ) {
    function wpneo_crowdfunding_campaign_single_social_share() {
        wpneo_crowdfunding_load_template('include/social-share');
    }
}
if(get_option('wpneo_enable_social_share') == 'true') {
    add_action('wpneo_crowdfunding_single_campaign_summery', 'wpneo_crowdfunding_campaign_single_social_share', 11);
}
