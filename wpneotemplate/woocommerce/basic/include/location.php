<?php
defined( 'ABSPATH' ) || exit;
?>
<?php if (wpneo_crowdfunding_get_campaigns_location()){ ?>
    <div class="wpneo-location-wrapper">
        <i class="wpneo-icon wpneo-icon-location"></i>
        <span><?php echo wpneo_crowdfunding_get_campaigns_location(); ?></span>
    </div>
<?php } ?>