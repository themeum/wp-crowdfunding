<?php
defined( 'ABSPATH' ) || exit;
$location = wpcf_function()->campaign_location();
?>
<?php if ($location) : ?>
    <div class="wpcf-campaign-location wpcf-text-gray-600 wpcf-mt-3">
        <span class="wpcf-svg-icon">
            <svg width="10" height="14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 .333A4.663 4.663 0 00.333 5C.333 8.5 5 13.667 5 13.667S9.666 8.5 9.666 5A4.663 4.663 0 005 .333zm0 6.334a1.667 1.667 0 110-3.335 1.667 1.667 0 010 3.335z" /></svg>
        </span>
        <span><?php echo $location; ?></span>
    </div>
<?php endif; ?>