<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$wpneo_campaign_end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);

if ($wpneo_campaign_end_method == 'target_date' || $wpneo_campaign_end_method == 'target_goal_and_date'){ ?>
	<div class="wpneo-single-sidebar">
		<?php if (WPNEOCF()->is_campaign_started()){ ?>
			<h3><?php echo WPNEOCF()->dateRemaining(); ?></h3>
			<p><?php _e( 'Days to go','wp-crowdfunding' ); ?></p>
		<?php } else { ?>
			<h3><?php echo WPNEOCF()->days_until_launch(); ?></h3>
			<p><?php _e( 'Days Until Launch','wp-crowdfunding' ); ?></p>
		<?php } ?>
	</div>
<?php } ?>