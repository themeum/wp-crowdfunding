<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wpneo-listing-img">
	<a href="<?php echo esc_attr( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"> <?php echo wp_kses_post( woocommerce_get_product_thumbnail() ); ?></a>
	<div class="overlay">
		<div>
			<div>
				<a href="<?php echo esc_attr( get_permalink() ); ?>"><?php esc_html_e( 'View Campaign', 'wp-crowdfunding' ); ?></a>
			</div>
		</div>
	</div>
</div>