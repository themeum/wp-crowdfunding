<?php
defined( 'ABSPATH' ) || exit;
global $post;
$campaign_id = $post->ID;
?>
<div id="campaign_loved_html">
    <?php is_campaign_loved_html(); ?>
</div>
