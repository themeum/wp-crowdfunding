<?php
defined( 'ABSPATH' ) || exit;
?>
<p class="wpneo-author"><?php _e('by','wp-crowdfunding'); ?> 
	<a href="<?php echo wpcf_function()->author_url( get_the_author_meta( 'user_login' ) ); ?>"><?php echo wpcf_function()->author_name(); ?></a>
</p>
