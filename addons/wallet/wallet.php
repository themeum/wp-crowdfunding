<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * Include necessary version
 */
if (WPNEO_CROWDFUNDING_TYPE === 'enterprise'){
    $load_tab = WPNEO_CROWDFUNDING_DIR_PATH.'addons/wallet/wpneo-crowdfunding-wallet.php';
}else{
    $load_tab = WPNEO_CROWDFUNDING_DIR_PATH.'addons/wallet/wpneo-crowdfunding-wallet-demo.php';
}
include_once $load_tab;