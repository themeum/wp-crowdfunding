<?php
defined( 'ABSPATH' ) || exit;
$raised_percent = WPNEOCF()->getFundRaisedPercentFormat();
$raised = 0;
$total_raised = WPNEOCF()->totalFundRaisedByCampaign();
if ($total_raised){
    $raised = $total_raised;
}
?>
	<div class="wpneo-fund-raised">
		<div class="wpneo-meta-desc"><?php echo wc_price($raised); ?></div>
	    <div class="wpneo-meta-name"><?php _e('Fund Raised', 'wp-crowdfunding'); ?></div>
	</div>
</div>