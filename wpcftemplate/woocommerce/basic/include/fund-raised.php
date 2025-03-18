<?php
defined( 'ABSPATH' ) || exit;
$end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);
?>
<div class="campaign-funding-info">
    <ul>
        <li>
            <p class="funding-amount">
                <?php 
                $price = wpcf_function()->total_goal(get_the_ID());
                if($price){
                    echo wpcf_function()->price($price);
                }else{
                    esc_html_e('Not Set', 'wp-crowdfunding');
                }
                ?>
            </p>
            <span class="info-text"><?php esc_html_e('Funding Goal', 'wp-crowdfunding') ?></span>
        </li>
        <li>
            <p class="funding-amount"><?php echo wpcf_function()->price(wpcf_function()->fund_raised()); ?></p>
            <span class="info-text"><?php esc_html_e('Funds Raised', 'wp-crowdfunding') ?></span>
        </li>
        <?php if ($end_method != 'never_end'){
            ?>
            <li>
                <?php if (wpcf_function()->is_campaign_started()){ ?>
                    <p class="funding-amount"><?php echo wpcf_function()->get_date_remaining(); ?></p>
                    <span class="info-text"><?php esc_html_e( 'Days to go','wp-crowdfunding' ); ?></span>
                <?php } else { ?>
                    <p class="funding-amount"><?php echo wpcf_function()->days_until_launch(); ?></p>
                    <span class="info-text"><?php esc_html_e( 'Days Until Launch','wp-crowdfunding' ); ?></span>
                <?php } ?>
            </li>
        <?php } ?>

        <li>
            <p class="funding-amount">
                <?php
                    if( $end_method == 'target_goal' ){
                        esc_html_e('Target Goal', 'wp-crowdfunding');
                    }else if( $end_method == 'target_date' ){
                        esc_html_e('Target Date', 'wp-crowdfunding');
                    }else if( $end_method == 'target_goal_and_date' ){
                        esc_html_e('Goal and Date', 'wp-crowdfunding');
                    }else{
                        esc_html_e('Campaign Never Ends', 'wp-crowdfunding');
                    }
                ?>
            </p>
            <span class="info-text"><?php esc_html_e('Campaign End Method', 'wp-crowdfunding') ?></span>
        </li>
    </ul>
</div>