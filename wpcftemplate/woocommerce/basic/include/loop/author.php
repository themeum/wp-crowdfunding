<?php
defined( 'ABSPATH' ) || exit;
?>
<p class="wpneo-author"><?php esc_html_e( 'by', 'wp-crowdfunding' ); ?> 
	<a href="<?php echo esc_url( wpcf_function()->get_author_url( get_the_author_meta( 'user_login' ) ) ); ?>"><?php echo esc_html( wpcf_function()->get_author_name() ); ?></a>
</p>