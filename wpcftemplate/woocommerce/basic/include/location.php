<?php
defined( 'ABSPATH' ) || exit;
$location = wpcf_function()->campaign_location();
if ( $location ) { ?>
	<div class="wpneo-location-wrapper">
		<i class="wpneo-icon wpneo-icon-location"></i>
		<span><?php echo esc_html( $location ); ?></span>
	</div>
<?php } ?>