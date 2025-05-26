<?php
defined( 'ABSPATH' ) || exit;
$raised_percent = wpcf_function()->get_fund_raised_percent_format();
$raised         = 0;
$total_raised   = wpcf_function()->get_total_fund();
if ( $total_raised ) {
	$raised = $total_raised;
}
?>
	<div class="wpneo-fund-raised">
		<div class="wpneo-meta-desc"><?php echo wp_kses_post( wc_price( $raised ) ); ?></div>
		<div class="wpneo-meta-name"><?php esc_html_e( 'Fund Raised', 'wp-crowdfunding' ); ?></div>
	</div>
</div>