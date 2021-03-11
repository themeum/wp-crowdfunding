<?php
defined( 'ABSPATH' ) || exit;
$col_num = (int) get_option('number_of_collumn_in_row', 3);
?>

<div class="wpneo-wrapper">
    <div class="wpneo-container">
        <?php do_action('wpcf_campaign_listing_before_loop'); ?>
        <div class="wpneo-wrapper-inner">
            <?php if (have_posts()): ?>
                <div class="wpcf-row">
                    <?php $i = 1; ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="wpcf-col-lg-<?php echo round(12/$col_num); ?>">
                            <div class="wpcf-campaign-item">
                                <div class="wpcf-card">
                                    <?php do_action('wpcf_campaign_loop_item_before_content'); ?>
                                    <?php do_action('wpcf_campaign_loop_item_content'); ?>
                                    <?php do_action('wpcf_campaign_loop_item_after_content'); ?>
                                </div>
                            </div>
                        </div>
                    <?php $i++; endwhile; ?>
                </div>
            <?php else: ?>
                <?php wpcf_function()->template('include/loop/no-campaigns'); ?>
            <?php endif; ?>
        </div>
        <?php
            do_action('wpcf_campaign_listing_after_loop');
            wpcf_function()->template('include/pagination');
        ?>
    </div>
</div>