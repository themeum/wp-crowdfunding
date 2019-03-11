<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$wpneo_campaign_end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);
?>

<div class="campaign-funding-info">
    <ul>
        <li><p class="funding-amount"><?php echo wpneo_crowdfunding_price(wpneo_crowdfunding_get_total_goal_by_campaign(get_the_ID())); ?></p>
            <span class="info-text"><?php _e('Funding Goal', 'wp-crowdfunding') ?></span>
        </li>
        <li>
            <p class="funding-amount"><?php echo wpneo_crowdfunding_price(wpneo_crowdfunding_get_total_fund_raised_by_campaign()); ?></p>
            <span class="info-text"><?php _e('Funds Raised', 'wp-crowdfunding') ?></span>
        </li>
        <?php if ($wpneo_campaign_end_method != 'never_end'){
            ?>
            <li>

                <?php
                if (WPNEOCF()->is_campaign_started()){
                    ?>
                    <p class="funding-amount"><?php echo WPNEOCF()->dateRemaining(); ?></p>
                    <span class="info-text"><?php _e( 'Days to go','wp-crowdfunding' ); ?></span>
                    <?php
                }else{
                    ?>
                    <p class="funding-amount"><?php echo WPNEOCF()->days_until_launch(); ?></p>
                    <span class="info-text"><?php _e( 'Days Until Launch','wp-crowdfunding' ); ?></span>

                <?php
                }

                ?>


            </li>
        <?php } ?>



        <li>
		    <?php $method =  (($wpneo_campaign_end_method == 'target_goal') ? 'Target Goal' : (($wpneo_campaign_end_method == 'target_date') ? 'Target Date' : (($wpneo_campaign_end_method == 'target_goal_and_date') ? 'Goal and Date' : 'Campaign Never Ends'))); ?>
            <p class="funding-amount"><?php echo $method; ?></p>
            <span class="info-text"><?php _e('Campaign End Method', 'wp-crowdfunding') ?></span>

        </li>

    </ul>
</div>