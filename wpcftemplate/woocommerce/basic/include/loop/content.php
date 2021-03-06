<?php
defined( 'ABSPATH' ) || exit;

global $post;
$funding_goal = get_post_meta($post->ID, '_nf_funding_goal', true);




$raised_percent = wpcf_function()->get_fund_raised_percent_format();
$raised = 0;
$total_raised = wpcf_function()->get_total_fund();
if ($total_raised){
    $raised = $total_raised;
}


$days_remaining = apply_filters('date_expired_msg', __('0', 'wp-crowdfunding'));
if (wpcf_function()->get_date_remaining()){
    $days_remaining = apply_filters('date_remaining_msg', __(wpcf_function()->get_date_remaining(), 'wp-crowdfunding'));
}

$end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);






$location       = wpcf_function()->campaign_location();
$raised_percent = wpcf_function()->get_fund_raised_percent_format();
$col_num        = get_option('number_of_words_show_in_listing_description', 130);
$desc           = wpcf_function()->limit_word_text(strip_tags(get_the_content()), $col_num);


$product = new WC_Product($post->ID);
?>

<div class="cf-campaign-ratings">
    <?php if (wpcf_function()->wc_version()) : ?>
        <?php echo wc_get_rating_html($product->get_average_rating()); ?>
    <?php else: ?>
        <?php echo $product->get_rating_html(); ?>
    <?php endif; ?>
</div>

<h4><a href="<?php  echo get_permalink(); ?> "><?php echo get_the_title(); ?></a></h4>

<p class="wpneo-author"><?php _e('by','wp-crowdfunding'); ?> 
	<a href="<?php echo wpcf_function()->get_author_url( get_the_author_meta( 'user_login' ) ); ?>"><?php echo wpcf_function()->get_author_name(); ?></a>
</p>

<?php if(!empty($location)) : ?>
    <div class="cf-campaign-location cf-text-gray-600">
        <span class="cf-svg-icon">
            <svg width="10" height="14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 .333A4.663 4.663 0 00.333 5C.333 8.5 5 13.667 5 13.667S9.666 8.5 9.666 5A4.663 4.663 0 005 .333zm0 6.334a1.667 1.667 0 110-3.335 1.667 1.667 0 010 3.335z" /></svg>
        </span>
        <span><?php echo wpcf_function()->campaign_location(); ?></span>
    </div>
<?php endif; ?>

<?php if(!empty($desc)) : ?>
    <p class="wpneo-short-description"><?php echo $desc; ?></p>
<?php endif; ?>

<div class="cf-card-divider"></div>

<div class="wpneo-raised-percent">
    <div class="wpneo-meta-name"><?php _e('Raised Percent', 'wp-crowdfunding'); ?> :</div>
    <div class="wpneo-meta-desc" ><?php echo $raised_percent; ?></div>
</div>

<div class="cf-progress">
    <?php
        $css_width = wpcf_function()->get_raised_percent();
        if( $css_width >= 100 ) {
            $css_width = 100;
        }
    ?>
    <div class="cf-progress-bar" style="width: <?php echo $css_width; ?>%;" area-hidden="true"></div>
</div>






<div class="cf-row">
    <div class="cf-col-lg-4">
        <div class="wpneo-funding-goal">
            <div class="wpneo-meta-desc"><?php echo wc_price( $funding_goal ); ?></div>
            <div class="wpneo-meta-name"><?php _e('Funding Goal', 'wp-crowdfunding'); ?></div>
        </div>
    </div>

    <div class="cf-col-lg-4">
        <div class="wpneo-fund-raised">
            <div class="wpneo-meta-desc"><?php echo wc_price($raised); ?></div>
            <div class="wpneo-meta-name"><?php _e('Fund Raised', 'wp-crowdfunding'); ?></div>
        </div>
    </div>
    
    <?php if($end_method != 'never_end') : ?>
        <div class="cf-col-lg-4">
            <div class="wpneo-time-remaining">
                <?php if (wpcf_function()->is_campaign_started()){ ?>
                    <div class="wpneo-meta-desc"><?php echo wpcf_function()->get_date_remaining(); ?></div>
                    <div class="wpneo-meta-name float-left"><?php _e( 'Days to go','wp-crowdfunding' ); ?></div>
                <?php } else { ?>
                    <div class="wpneo-meta-desc"><?php echo wpcf_function()->days_until_launch(); ?></div>
                    <div class="wpneo-meta-name float-left"><?php _e( 'Days Until Launch','wp-crowdfunding' ); ?></div>
                <?php } ?>
            </div>
        </div>
    <?php endif; ?>
</div>