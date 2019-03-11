<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (! class_exists('WP_Crowdfunding_Shortcode')) {

	class WP_Crowdfunding_Shortcode {

		/**
		 * @var null
		 *
		 * Instance of this class
		 */
		protected static $_instance = null;

		/**
		 * @return null|WP_Crowdfunding_Shortcode
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			add_shortcode('wp_crowdfunding_single_campaign', array($this, 'wp_crowdfunding_single_campaign'));
			add_shortcode('wp_crowdfunding_campaign_box', array($this, 'wp_crowdfunding_campaign_box'));

			add_shortcode('wp_crowdfunding_popular_campaigns', array($this, 'wp_crowdfunding_popular_campaigns'));
			add_shortcode('wp_crowdfunding_donate', array($this, 'wp_crowdfunding_donate'));
		}

		/**
		 * @param $atts
		 * @param string $content
		 *
		 * @return string
		 *
		 * Single Campaign Details
		 */
		public function wp_crowdfunding_single_campaign( $atts, $content = "" ){
			$atts = shortcode_atts( array(
				'campaign_id' => 0,
			), $atts, 'wp_crowdfunding_single_campaign' );

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


		public function wp_crowdfunding_campaign_box( $atts, $content = "" ){
			$atts = shortcode_atts( array(
				'campaign_id' => 0,
			), $atts, 'wp_crowdfunding_campaign_box' );

			if ( !  $atts['campaign_id'] ){
				return false;
			}

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

			while ( $single_product->have_posts() ) {
				$single_product->the_post();
				?>

                <div class="wpneo-listings three ">
					<?php do_action('wpneo_campaign_loop_item_before_content'); ?>
                    <div class="wpneo-listing-content">
						<?php do_action('wpneo_campaign_loop_item_content'); ?>
                    </div>
					<?php do_action('wpneo_campaign_loop_item_after_content'); ?>
                </div>
				<?php
			}
			// restore $previous_wp_query and reset post data.
			$wp_query = $previous_wp_query;
			wp_reset_postdata();
			$final_content = ob_get_clean();

			return $final_content;
		}

		/**
		 * @param $atts
		 * @param string $content
		 *
		 * @return string
		 *
		 * Get Popular Campaigns
		 */
		public function wp_crowdfunding_popular_campaigns( $atts, $content = "" ){
			$atts = shortcode_atts( array(
				'limit' => 4,
				'column' => 4,
				'order' => 'DESC',
				'class' => '',
			), $atts, 'wp_crowdfunding_popular_campaigns' );

			$args = array(
				'post_type' 			=> 'product',
				'post_status' 			=> 'publish',
				'ignore_sticky_posts'   => 1,
				'posts_per_page'		=> $atts['limit'],
				'meta_key' 		 		=> 'total_sales',
				'orderby' 		 		=> 'meta_value_num',
				'order'                 => $atts['order'],
				'fields'                => 'ids',

				'meta_query' => array(
					array(
						'key' 		=> 'total_sales',
						'value' 	=> 0,
						'compare' 	=> '>',
					)
				),

				'tax_query' => array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => 'crowdfunding',
					),
				),
			);

			$columns  = $atts['column'];

			$classes    = array('woocommerce', 'columns-'.$columns);
			$attr_class = explode(',', $atts['class']);
			$classes    = array_merge($classes, $attr_class);

			$query = new WP_Query($args);

			$paginated = ! $query->get( 'no_found_rows' );
			$products = (object) array(
				'ids'          => wp_parse_id_list( $query->posts ),
				'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
				'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
				'per_page'     => (int) $query->get( 'posts_per_page' ),
				'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
			);

			ob_start();

			if ( $products && $products->ids ) {
				// Prime meta cache to reduce future queries.
				update_meta_cache( 'post', $products->ids );
				update_object_term_cache( $products->ids, 'product' );

				// Setup the loop.
				wc_setup_loop( array(
					'columns'      => $columns,
					'name'         => 'products',
					'is_shortcode' => true,
					'is_search'    => false,
					'is_paginated' => false,
					'total'        => $products->total,
					'total_pages'  => $products->total_pages,
					'per_page'     => $products->per_page,
					'current_page' => $products->current_page,
				) );

				$original_post = $GLOBALS['post'];

				woocommerce_product_loop_start();

				if ( wc_get_loop_prop( 'total' ) ) {
					foreach ( $products->ids as $product_id ) {
						$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
						setup_postdata( $GLOBALS['post'] );

						// Render product template.
						wc_get_template_part( 'content', 'product' );
					}
				}

				$GLOBALS['post'] = $original_post; // WPCS: override ok.
				woocommerce_product_loop_end();

				wp_reset_postdata();
				wc_reset_loop();
			}

			return '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . ob_get_clean() . '</div>';
		}


		/**
		 * @param $atts
		 * @param string $content
		 *
		 * @return string
         *
         * Add Embeded Form for taking donate from any where, even in the post or page, wheres shortcode support
		 */
		public function wp_crowdfunding_donate($atts, $content = ""){
			$atts = shortcode_atts( array(
				'campaign_id'           => null,
				'amount'                => '',
				'show_input_box'        => 'true',
				'min_amount'            => '',
				'max_amount'            => '',
				'donate_button_text'    => __('Back Campaign', 'wp-crowdfunding'),
			), $atts, 'wp_crowdfunding_donate' );

			if ( ! $atts['campaign_id']){
				return '<p class="wpcf-donate-form-response">'.__('Campaign ID required', 'wp-crowdfunding').'</p>';
			}

			$campaign = wc_get_product($atts['campaign_id']);
			if ( ! $campaign || $campaign->get_type() !== 'crowdfunding'){
				return '<p class="wpcf-donate-form-response">'.__('Invalid Campaign ID', 'wp-crowdfunding').'</p>';
			}
			?>

            <div class="wpcf-donate-form-wrap">
                <form enctype="multipart/form-data" method="post" class="cart">
					<?php
					if ($atts['show_input_box'] == 'true') {
						echo get_woocommerce_currency_symbol(); ?>

                        <input type="number" step="any" min="0" placeholder="<?php echo $atts['amount']; ?>"
                               name="wpneo_donate_amount_field" class="input-text amount wpneo_donate_amount_field text"
                               value="<?php echo $atts['amount']; ?>" data-min-price="<?php echo $atts['min_amount'] ?>"
                               data-max-price="<?php echo $atts['max_amount'] ?>">
						<?php
					}else{
						echo '<input type="hidden" name="wpneo_donate_amount_field" value="'.$atts['amount'].'" />';
					}
					?>

                    <input type="hidden" value="<?php echo esc_attr($atts['campaign_id']); ?>" name="add-to-cart">
                    <button type="submit" class="<?php echo apply_filters('add_to_donate_button_class', 'wpneo_donate_button'); ?>">
						<?php
						echo $atts['donate_button_text'];

						if ($atts['show_input_box'] != 'true'){
							echo ' ('.wc_price($atts['amount']).') ';
						}
						?>
                    </button>
                </form>
            </div>
			<?php
			return ob_get_clean();
		}

	}


	WP_Crowdfunding_Shortcode::instance();

}