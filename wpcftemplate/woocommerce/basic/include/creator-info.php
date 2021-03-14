<?php
defined( 'ABSPATH' ) || exit;

global $post;
$user_info = get_user_meta($post->post_author);
$creator = get_user_by('id', $post->post_author);

?>

<div class="wpcf-campaign-author wpcf-d-flex">
    <?php if ( $post->post_author ) :
        $img_src    = '';
        $image_id = get_user_meta( $post->post_author,'profile_image_id', true );

        if( $image_id != '' ) :
            $img_src = wp_get_attachment_image_src( $image_id, 'backer-portfo' )[0];
        endif;
    ?>
        <div class="wpcf-mr-3">
            <?php if( $img_src ) : ?>
                <img src="<?php echo $img_src; ?>" class="wpcf-avatar" alt="">
            <?php else: ?>
                <?php echo get_avatar($post->post_author, 48, '', '', array( 'class' => array( 'wpcf-avatar' ) )); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="wpcf-campaign-author-info">
        <h4 class="wpcf-campaign-author-name wpcf-fw-bold wpcf-mb-0"><a href="#" data-author="<?php echo $post->post_author; ?>" action-show-author-bio><?php echo wpcf_function()->get_author_name(); ?></a></h4>
        
        <div class="wpcf-d-flex wpcf-align-items-center wpcf-text-small wpcf-text-lighter wpcf-mt-2">
            <span><?php echo wpcf_function()->author_campaigns($post->post_author)->post_count; ?> <?php _e("Campaigns","wp-crowdfunding"); ?></span>
            <span class="wpcf-meta-separator wpcf-mx-3" area-hidden="true"></span>
            <span><?php echo wpcf_function()->loved_count(); ?> <?php _e("Loved campaigns","wp-crowdfunding"); ?></span>
        </div>

        <?php if ( ! empty($user_info['profile_website'][0])) : ?>
            <div class="wpcf-campaign-author-website wpcf-text-small wpcf-text-lighter wpcf-mt-2">
                <span><?php _e("Website","wp-crowdfunding"); ?>: </span> <a href="<?php echo wpcf_function()->url($user_info['profile_website'][0]); ?>" rel="nofollow noopener noreferrer"><strong> <?php echo wpcf_function()->url($user_info['profile_website'][0]); ?></strong></a>
            </div>
        <?php endif; ?>

        <div class="wpcf-campaign-author-bio-link wpcf-mt-3">
            <a href="#" class="wpcf-fw-bold" data-author="<?php echo $post->post_author; ?>" action-show-author-bio><?php _e('See full bio', 'wp-crowdfunding'); ?></a>
        </div>
    </div>
</div>