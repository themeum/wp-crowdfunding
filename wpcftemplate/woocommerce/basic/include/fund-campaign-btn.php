<?php
	defined( 'ABSPATH' ) || exit;
	global $post, $product;
	$currency = '$';
?>
<div class="wpcf-campaign-back-actions wpcf-mt-5">
	<?php if ($product->get_type() == 'crowdfunding') : ?>
		<?php if (wpcf_function()->is_campaign_valid()) : ?>
			<?php
				$recommended_price = get_post_meta($post->ID, 'wpneo_funding_recommended_price', true);
				$min_price = get_post_meta($post->ID, 'wpneo_funding_minimum_price', true);
				$max_price = get_post_meta($post->ID, 'wpneo_funding_maximum_price', true);
				$predefined_price = get_post_meta($post->ID, 'wpcf_predefined_pledge_amount', true);

				if (!empty($predefined_price)) {
					$predefined_price = apply_filters('wpcf_predefined_pledge_amount_array_a', explode(',', $predefined_price));
				}

				if(function_exists( 'get_woocommerce_currency_symbol' )) {
					$currency = get_woocommerce_currency_symbol();
				}

				if (! empty($_GET['reward_min_amount'])) {
					$recommended_price = (int) esc_html($_GET['reward_min_amount']);
				}
			?>

			<?php
			if (is_array($predefined_price) && count($predefined_price)){
				echo '<ul class="wpcf_predefined_pledge_amount">';
				foreach ($predefined_price as $price){
					$price = trim($price);
					$wooPrice = wc_price($price);
					echo "<li><a href='javascript:;' data-predefined-price='{$price}'>{$wooPrice}</a></li>";
				}
				echo "</ul>";
			}
			?>

            <form enctype="multipart/form-data" method="post">
				<?php do_action('before_wpneo_donate_field'); ?>
				<div class="wpcf-d-flex wpcf-align-items-center">
					<div class="wpcf-mr-3"><?php echo get_woocommerce_currency_symbol(); ?></div>
					<div class="wpcf-mr-3">
						<input type="number" name="wpneo_donate_amount_field" oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')" step="any" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" placeholder="<?php echo $recommended_price; ?>" class="wpcf-form-control wpcf_donate_amount_field" value="<?php echo ($recommended_price ? $recommended_price : $min_price); ?>">
					</div>
					<?php do_action('after_wpneo_donate_field'); ?>
					<div>
						<button type="submit" class="<?php echo apply_filters('add_to_donate_button_class', 'wpcf-button-primary wpcf-donate-button'); ?>"><?php _e('Back Campaign', 'wp-crowdfunding'); ?></button>
					</div>
				</div>
                <input type="hidden" value="<?php echo esc_attr($post->ID); ?>" name="add-to-cart">
            </form>

		<?php else :
			if ( ! wpcf_function()->is_campaign_started()) :
				wpcf_function()->campaign_start_countdown();
			else :
				if( wpcf_function()->is_reach_target_goal() ) :
					_e('The campaign is successful.','wp-crowdfunding');
				else :
					_e('This campaign has been invalid or not started yet.','wp-crowdfunding');
				endif;
			endif;
		endif;
	endif;
	?>
</div>