<?php
defined( 'ABSPATH' ) || exit;

$raised_percent = wpcf_function()->get_fund_raised_percent_format();
?>
<div class="wpcf-campaign-progress">
    <div class="wpcf-text-small wpcf-mb-2">
        <span class="wpcf-text-gray-600"><?php _e('Raised Percent', 'wp-crowdfunding'); ?>:</span>
        <span class="wpcf-ml-2 wpcf-fw-bolder"><?php echo $raised_percent; ?></span>
    </div>

    <div class="wpcf-progress">
        <?php
            $progress_width = wpcf_function()->get_raised_percent();
            if( $progress_width >= 100 ) {
                $progress_width = 100;
            }
        ?>
        <div class="wpcf-progress-bar" style="width: <?php echo $progress_width; ?>%;" area-hidden="true"></div>
    </div>
</div>