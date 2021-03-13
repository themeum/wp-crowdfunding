<?php
defined( 'ABSPATH' ) || exit;
global $post;
?>

<div class="wpcf-row">
    <?php if($post->post_content) : ?>
        <div class="wpcf-col-lg-8">
            <div class="wpcf-campaign-description">
                <h2><?php _e('Campaign Story', 'wp-crowdfunding') ?></h2>
                <div class="wpcf-campaign-description-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="wpcf-col-lg-4">
        <?php do_action('wpcf_campaign_story_right_sidebar'); ?>
    </div>
</div>