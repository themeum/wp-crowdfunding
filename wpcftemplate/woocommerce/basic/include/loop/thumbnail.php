<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="cf-card-thumbnail">
    <?php echo woocommerce_get_product_thumbnail(); ?>
    <div class="cf-card-overlay">
		<a class="cf-button-outline-inverse" href="<?php echo get_permalink(); ?>"><?php _e('View Campaign', 'wp-crowdfunding'); ?></a>
	</div>
</div>