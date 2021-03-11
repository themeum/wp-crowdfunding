<?php
defined( 'ABSPATH' ) || exit;

global $post;
$funding_goal = get_post_meta($post->ID, '_nf_funding_goal', true);

$raised = 0;
$total_raised = wpcf_function()->get_total_fund();

if ($total_raised) {
    $raised = $total_raised;
}

$days_remaining = apply_filters('date_expired_msg', __('0', 'wp-crowdfunding'));
if (wpcf_function()->get_date_remaining()) {
    $days_remaining = apply_filters('date_remaining_msg', __(wpcf_function()->get_date_remaining(), 'wp-crowdfunding'));
}

$end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);


$location       = wpcf_function()->campaign_location();
$raised_percent = wpcf_function()->get_fund_raised_percent_format();
$col_num        = get_option('number_of_words_show_in_listing_description', 130);
$desc           = wpcf_function()->limit_word_text(strip_tags(get_the_content()), $col_num);

$product = new WC_Product($post->ID);
?>

<div class="wpcf-campaign-ratings">
    <?php if (wpcf_function()->wc_version()) : ?>
        <?php echo wc_get_rating_html($product->get_average_rating()); ?>
    <?php else: ?>
        <?php echo $product->get_rating_html(); ?>
    <?php endif; ?>
</div>

<h4 class="wpcf-mt-4 wpcf-mb-0"><a href="<?php  echo get_permalink(); ?> "><?php echo get_the_title(); ?></a></h4>

<div class="wpcf-campaign-author wpcf-mt-3">
    <span><?php _e('By','wp-crowdfunding'); ?></span> <a href="<?php echo wpcf_function()->get_author_url( get_the_author_meta( 'user_login' ) ); ?>"><?php echo wpcf_function()->get_author_name(); ?></a>
</div>

<?php if(!empty($location)) : ?>
    <div class="wpcf-campaign-location wpcf-text-gray-600 wpcf-mt-3">
        <span class="wpcf-svg-icon">
            <svg width="10" height="14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 .333A4.663 4.663 0 00.333 5C.333 8.5 5 13.667 5 13.667S9.666 8.5 9.666 5A4.663 4.663 0 005 .333zm0 6.334a1.667 1.667 0 110-3.335 1.667 1.667 0 010 3.335z" /></svg>
        </span>
        <span><?php echo wpcf_function()->campaign_location(); ?></span>
    </div>
<?php endif; ?>

<?php if(!empty($desc)) : ?>
    <div class="wpcf-campaign-intro wpcf-mt-4">
        <?php echo $desc; ?>
    </div>
<?php endif; ?>

<div class="wpcf-card-divider"></div>

<div class="wpcf-campaign-progress">
    <div class="wpcf-text-small wpcf-mb-2">
        <span class="wpcf-text-gray-600"><?php _e('Raised Percent', 'wp-crowdfunding'); ?>:</span>
        <span class="wpcf-ml-2 wpcf-fw-bolder"><?php echo $raised_percent; ?></span>
    </div>

    <div class="wpcf-progress">
        <?php
            $progress_width = wpcf_function()->get_raised_percent();
            if( $progress_width >= 100 ) {
                $progress_width = 100;
            }
        ?>
        <div class="wpcf-progress-bar" style="width: <?php echo $progress_width; ?>%;" area-hidden="true"></div>
    </div>
</div>

<div class="wpcf-campaign-meta wpcf-mt-4">
    <div class="wpcf-row wpcf-text-small">
        <div class="wpcf-col-6 wpcf-col-lg-4">
            <div class="wpcf-campaign-meta-goal">
                <div class="wpcf-fw-bolder"><?php echo wc_price( $funding_goal ); ?></div>
                <div class="wpcf-text-gray-600 wpcf-mt-1"><?php _e('Funding Goal', 'wp-crowdfunding'); ?></div>
            </div>
        </div>

        <div class="wpcf-col-6 wpcf-col-lg-4">
            <div class="wpcf-campaign-meta-raised">
                <div class="wpcf-fw-bolder"><?php echo wc_price( $raised ); ?></div>
                <div class="wpcf-text-gray-600 wpcf-mt-1"><?php _e('Fund Raised', 'wp-crowdfunding'); ?></div>
            </div>
        </div>
        
        <?php if($end_method != 'never_end') : ?>
            <div class="wpcf-col-lg-4 wpcf-mt-3 wpcf-mt-lg-0">
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
    </div>
</div>