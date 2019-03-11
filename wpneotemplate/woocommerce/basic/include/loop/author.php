<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$author_name = wpneo_crowdfunding_get_author_name();
?>
<p class="wpneo-author"><?php _e('by','wp-crowdfunding'); ?> 
	<a href="<?php echo wpneo_crowdfunding_campaign_listing_by_author_url( get_the_author_meta( 'user_login' ) ); ?>"><?php echo $author_name; ?></a>
</p>
