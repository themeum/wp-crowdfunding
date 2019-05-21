<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! empty($_GET['payment_campaign_id'])){
    include WPCF_DIR_PATH.'addons/wallet/pages/payment_details_by_campaign.php';
} else{
    include WPCF_DIR_PATH.'addons/wallet/pages/all_campaigns.php';
}