<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $post, $woocommerce, $product;
?>
<div class="wpneo-campaign-single-left-info">
    <div class="wpneo-post-img">   
        <?php
        $wpneo_funding_video = trim(get_post_meta($post->ID, 'wpneo_funding_video', true));
        if (! empty($wpneo_funding_video)){
            echo wpcf_function()->get_embeded_video($wpneo_funding_video);
        } else {
            if (has_post_thumbnail()) {
                $image_caption = get_post(get_post_thumbnail_id())->post_excerpt;
                $image_link = wp_get_attachment_url(get_post_thumbnail_id());
                $image = get_the_post_thumbnail($post->ID, apply_filters('single_product_large_thumbnail_size', 'shop_single'), array(
                    'title' => get_the_title(get_post_thumbnail_id())
                ));

                /**
                *  WooCommerce deprecated support since @var 3.0
                */
                if (wpcf_function()->wc_version()) {
                    $attachment_count = $product->get_gallery_image_ids();
                }else{
                    $attachment_count = count($product->get_gallery_attachment_ids());
                }

                if ($attachment_count > 0) {
                    $gallery = '[product-gallery]';
                } else {
                    $gallery = '';
                }
                echo apply_filters('woocommerce_single_product_image_html', sprintf('<a href="%s" itemprop="image" class="wpneo-single-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_caption, $image), $post->ID);
            } else {
                echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), __('Placeholder', 'backer')), $post->ID);
            }
        }
        ?>
        <?php do_action( 'woocommerce_product_thumbnails' ); ?>
    </div>
    
    <?php do_action( 'wpcf_after_feature_img' ); ?>
</div>