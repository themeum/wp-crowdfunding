<?php
defined( 'ABSPATH' ) || exit;

$page_numb = max( 1, get_query_var('paged') );
$posts_per_page = get_option( 'posts_per_page',10 );
$args = array(
    'post_type' 		=> 'product',
    'post_status'		=> array('publish', 'draft'),
    'author'    		=> get_current_user_id(),
    'tax_query' 		=> array(
        array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => 'crowdfunding',
        ),
    ),
    'posts_per_page'    => 12,
    'paged'             => $page_numb
);

$html .= '<div class="wpcf-row">';
$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) :
    global $post;
    $i = 1;
    while ( $the_query->have_posts() ) : $the_query->the_post(); 
        $permalink      = wpcf_function()->is_published() ? get_permalink() : '#';
        $word_count     = get_option('number_of_words_show_in_listing_description', 130);
        $desc           = wpcf_function()->limit_word_text(strip_tags(get_the_content()), $word_count);

        // days remaining
        $days_remaining = apply_filters('date_expired_msg', __('0', 'wp-crowdfunding'));
        if (wpcf_function()->get_date_remaining()) {
            $days_remaining = apply_filters('date_remaining_msg', __(wpcf_function()->get_date_remaining(), 'wp-crowdfunding'));
        }

        $end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);

        // raised percentage
        $raised_percent = wpcf_function()->get_fund_raised_percent_format();
        $raised = 0;
        $total_raised = wpcf_function()->get_total_fund();
        if ($total_raised) {
            $raised = $total_raised;
        }
        $funding_goal = get_post_meta($post->ID, '_nf_funding_goal', true);
        
        ob_start();
        ?>
        <div class="wpcf-col-lg-4">
            <div class="wpcf-card">
                <div class="wpcf-card-thumbnail">
                    <a href="<?php echo $permalink; ?>" title="<?php  echo get_the_title(); ?>"><?php echo woocommerce_get_product_thumbnail(); ?></a>
                    <div class="wpcf-card-overlay">
                        <a class="wpcf-button-outline-inverse" href="<?php echo $permalink; ?>"><?php _e('View Campaign', 'wp-crowdfunding'); ?></a>
                    </div>
                </div>

                <h4 class="wpcf-mt-4 wpcf-mb-0"><a href="<?php echo $permalink; ?> "><?php echo get_the_title(); ?></a></h4>

                <?php if(!empty($desc)) : ?>
                    <div class="wpcf-campaign-intro wpcf-mt-4">
                        <?php echo $desc; ?>
                    </div>
                <?php endif; ?>

                <div class="wpcf-card-divider"></div>

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
                                        <div class="wpcf-text-gray-600 wpcf-mt-1"><?php _e('Days to Go', 'wp-crowdfunding'); ?></div>
                                    <?php else: ?>
                                        <div class="wpcf-fw-bolder"><?php echo wpcf_function()->days_until_launch(); ?></div>
                                        <div class="wpcf-text-gray-600 wpcf-mt-1"><?php _e('Days Until Launch', 'wp-crowdfunding'); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php do_action('wpcf_dashboard_campaign_loop_item_after_content'); ?>
        </div>
        <?php $i++;
        $html .= ob_get_clean();
    endwhile;
    wp_reset_postdata();
else :
    $html .= "<div class='wpcf-alert-warning'>".__( 'Sorry, no Campaign Found.', 'wp-crowdfunding' )."</div>";
endif;
$html .= '</div>';
$html .= wpcf_function()->get_pagination( $page_numb, $the_query->max_num_pages );