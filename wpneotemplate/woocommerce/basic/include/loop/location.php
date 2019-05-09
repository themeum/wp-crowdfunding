<?php
defined( 'ABSPATH' ) || exit;
$location = wpneo_crowdfunding_get_campaigns_location();
?>
<div class="wpneo-location">
    <i class="wpneo-icon wpneo-icon-location"></i>
    <div class="wpneo-meta-desc"><?php echo $location; ?></div>
</div>
