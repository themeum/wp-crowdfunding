<?php
    defined( 'ABSPATH' ) || exit;
?>
<div class="wpcf-card-thumbnail">
    <?php echo woocommerce_get_product_thumbnail(); ?>
    <div class="wpcf-card-overlay">
        <a class="wpcf-button-outline-inverse" href="<?php echo get_permalink(); ?>"><?php _e('View Campaign', 'wp-crowdfunding'); ?></a>
    </div>
</div>