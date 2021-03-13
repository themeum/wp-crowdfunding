<?php
defined( 'ABSPATH' ) || exit;
$end_method     = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);
$funding_goal   = get_post_meta(get_the_ID(), '_nf_funding_goal', true);

$total_raised   = wpcf_function()->get_total_fund();
$raised         = $total_raised ? $total_raised : 0;

$days_remaining = apply_filters('date_expired_msg', __('0', 'wp-crowdfunding'));
if (wpcf_function()->get_date_remaining()) {
    $days_remaining = apply_filters('date_remaining_msg', __(wpcf_function()->get_date_remaining(), 'wp-crowdfunding'));
}

$end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);
?>

<div class="wpcf-campaign-meta wpcf-mt-4">
    <div class="wpcf-row wpcf-text-small">
        <div class="wpcf-col-6 wpcf-col-lg-3">
            <div class="wpcf-campaign-meta-goal">
                <div class="wpcf-fw-bolder"><?php echo wc_price( $funding_goal ); ?></div>
                <div class="wpcf-text-gray-600 wpcf-mt-1"><?php _e('Funding Goal', 'wp-crowdfunding'); ?></div>
            </div>
        </div>

        <div class="wpcf-col-6 wpcf-col-lg-3">
            <div class="wpcf-campaign-meta-raised">
                <div class="wpcf-fw-bolder"><?php echo wc_price( $raised ); ?></div>
                <div class="wpcf-text-gray-600 wpcf-mt-1"><?php _e('Fund Raised', 'wp-crowdfunding'); ?></div>
            </div>
        </div>
        
        <?php if($end_method != 'never_end') : ?>
            <div class="wpcf-col-6 wpcf-col-lg-3 wpcf-mt-3 wpcf-mt-lg-0">
                <div class="wpcf-campaign-meta-duration">
                    <?php if (wpcf_function()->is_campaign_started()) : ?>
                        <div class="wpcf-fw-bolder"><?php echo wpcf_function()->get_date_remaining(); ?></div>
                        <div class="wpcf-text-gray-600 wpcf-mt-1"><?php _e('Days to go', 'wp-crowdfunding'); ?></div>
                    <?php else: ?>
                        <div class="wpcf-fw-bolder"><?php echo wpcf_function()->days_until_launch(); ?></div>
                        <div class="wpcf-text-gray-600 wpcf-mt-1"><?php _e('Days Until Launch', 'wp-crowdfunding'); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="wpcf-col-6 wpcf-col-lg-3 wpcf-mt-3 wpcf-mt-lg-0">
            <div class="wpcf-campaign-meta-end-methods">
                <div class="wpcf-fw-bolder">
                    <?php
                        if( $end_method == 'target_goal' ) :
                            _e('Target Goal', 'wp-crowdfunding');
                        elseif( $end_method == 'target_date' ) :
                            _e('Target Date', 'wp-crowdfunding');
                        elseif( $end_method == 'target_goal_and_date' ) :
                            _e('Goal and Date', 'wp-crowdfunding');
                        else :
                            _e('Campaign Never Ends', 'wp-crowdfunding');
                        endif;
                    ?>
                </div>
                <div class="wpcf-text-gray-600 wpcf-mt-1"><?php _e('Campaign End Method', 'wp-crowdfunding'); ?></div>
            </div>
        </div>

    </div>
</div>