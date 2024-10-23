<?php
	defined( 'ABSPATH' ) || exit;
	global $post;

	$campaign_rewards   = get_post_meta( $post->ID, 'wpneo_reward', true );
	$campaign_rewards_a = json_decode( $campaign_rewards, true );
?>

<?php if ( is_array( $campaign_rewards_a ) && count( $campaign_rewards_a ) > 0 ) : ?>
	<?php
		$amount = array();

	foreach ( $campaign_rewards_a as $key => $row ) {
		$amount[ $key ] = $row['wpneo_rewards_pladge_amount'];
	}
		array_multisort( $amount, SORT_ASC, $campaign_rewards_a );
	?>

	<div class="wpcf-campaign-rewards">
		<h3 class="wpcf-mt-0 wpcf-mb-4"><?php _e( 'Rewards', 'wp-crowdfunding' ); ?></h3>
		<?php foreach ( $campaign_rewards_a as $key => $value ) : ?>
			<?php
				$quantity = '';
				$post_id  = get_the_ID();
				$min_data = $value['wpneo_rewards_pladge_amount'];
				$max_data = '';
				$orders   = 0;

			if ( empty( $campaign_rewards_a[ $key ]['wpneo_rewards_pladge_amount'] ) ) {
				$max_data = $campaign_rewards_a[ $key ]['wpneo_rewards_pladge_amount'] - 1;
			} else {
				$max_data = 9000000000;
			}

			if ( $min_data != '' ) {
				$orders = wpcf_campaign_order_number_data( $min_data, $max_data, $post_id );
			}

			if ( $value['wpneo_rewards_item_limit'] ) {
				$quantity = 0;
				if ( $value['wpneo_rewards_item_limit'] >= $orders ) {
					$quantity = $value['wpneo_rewards_item_limit'] - $orders;
				}
			}
			?>
	
			<div class="wpcf-campaign-reward <?php echo ( $quantity === 0 ) ? ' wpcf-is-disabled' : ''; ?> wpcf-mb-4">
				<div class="wpcf-card">
					<h4 class="wpcf-m-0">
						<?php
						if ( function_exists( 'wc_price' ) ) :
							_e( 'Pledge ', 'wp-crowdfunding' );
							echo wc_price( $value['wpneo_rewards_pladge_amount'] );

							if ( 'true' != get_option( 'wpneo_reward_fixed_price', '' ) ) :
								echo ( ! empty( $campaign_rewards_a[ $key ]['wpneo_rewards_pladge_amount'] ) ) ? ' - ' . wc_price( $campaign_rewards_a[ $key ]['wpneo_rewards_pladge_amount'] - 1 ) : __( ' or more', 'wp-crowdfunding' );
							endif;
						endif;
						?>
					</h4>

					<?php if ( $value['wpneo_rewards_image_field'] ) : ?>
						<div class="wpcf-reward-image">
							<img src="<?php echo wp_get_attachment_url( $value['wpneo_rewards_image_field'] ); ?>" loading="lazy">
						</div>
					<?php endif; ?>

					<div class="wpcf-reward-description wpcf-mt-3">
						<?php echo wpautop( wp_unslash( esc_html( $value['wpneo_rewards_description'] ) ) ); ?>
					</div>

					<?php
					if ( ! empty( $value['wpneo_rewards_endmonth'] ) || ! empty( $value['wpneo_rewards_endyear'] ) ) :
						$month = date_i18n( 'F', strtotime( $value['wpneo_rewards_endmonth'] ) );
						$year  = date_i18n( 'Y', strtotime( $value['wpneo_rewards_endyear'] . '-' . $month . '-15' ) );
						?>
						<div class="wpcf-reward-delivery-date">
							<div><?php _e( 'Estimated Delivery', 'wp-crowdfunding' ); ?></div>
							<strong><?php echo $month . ', ' . $year; ?></strong>
						</div>
					<?php endif; ?>

					<?php if ( $value['wpneo_rewards_item_limit'] ) : ?>
						<div class="wpcf-rewards-availability wpcf-mt-3">
							<strong><?php echo $quantity; ?></strong> <?php _e( ' rewards left', 'wp-crowdfunding' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $min_data != '' ) : ?>
						<div class="wpcf-reward-backer-numbers wpcf-mt-3">
							<span class="wpcf-badge"><?php echo $orders . ' ' . __( 'backers', 'wp-crowdfunding' ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( wpcf_function()->is_campaign_valid() ) : ?>
						<?php if ( wpcf_function()->is_campaign_started() ) : ?>
							<div class="wpcf-card-overlay">
								<?php if ( $quantity === 0 ) : ?>
									<div class="wpcf-alert-error wpcf-mb-0"><?php _e( 'Reward no longer available.', 'wp-crowdfunding' ); ?></div>
								<?php else : ?>
									<form enctype="multipart/form-data" method="post" class="cart">
										<input type="hidden" value="<?php echo $value['wpneo_rewards_pladge_amount']; ?>" name="wpneo_donate_amount_field" />
										<input type="hidden" value='<?php echo json_encode( $value ); ?>' name="wpneo_selected_rewards_checkout" />
										<input type="hidden" value="<?php echo $key; ?>" name="wpneo_rewards_index" />
										<input type="hidden" value="<?php echo esc_attr( $post->post_author ); ?>" name="_cf_product_author_id">
										<input type="hidden" value="<?php echo esc_attr( $post->ID ); ?>" name="add-to-cart">
										<button type="submit" class="wpcf-button-outline-inverse select_rewards_button"><?php _e( 'Select this reward', 'wp-crowdfunding' ); ?></button>
									</form>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php else : ?>
						<div class="wpcf-card-overlay">
							<div class="wpcf-text-white">
								<?php if ( wpcf_function()->is_reach_target_goal() ) : ?>
									<?php _e( 'Campaign already completed.', 'wp-crowdfunding' ); ?>
								<?php else : ?>
									<?php if ( wpcf_function()->is_campaign_started() ) : ?>
										<?php _e( 'Reward is not valid.', 'wp-crowdfunding' ); ?>
									<?php else : ?>
										<?php _e( 'Campaign is not started.', 'wp-crowdfunding' ); ?>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
endif;
