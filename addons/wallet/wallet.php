<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * Include necessary version
 */
if (WPCF_TYPE === 'enterprise'){
    $load_tab = WPCF_DIR_PATH.'addons/wallet/wpneo-crowdfunding-wallet.php';
}else{
    $load_tab = WPCF_DIR_PATH.'addons/wallet/wpneo-crowdfunding-wallet-demo.php';
}
include_once $load_tab;