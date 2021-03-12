<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();

$html .= '<div class="wpcf-card wpcf-mb-5">';
    ob_start();
    include_once WPCF_DIR_PATH . 'includes/woocommerce/dashboard/reports.php';
    $html .= ob_get_clean();
$html .= '</div>';

ob_start();
include_once WPCF_DIR_PATH . 'includes/woocommerce/dashboard/campaigns.php';
$html .= ob_get_clean();



$html .= '<div class="wpneo-row">';
    $html .= '<div class="wpneo-col6">';
    

    global $wp;
    $html .= '<div class="wpneo-shadow wpneo-padding25 wpneo-clearfix">'; 
        $html .= '<h4>'.__( "Export Data" , "wp-crowdfunding" ).'</h4>';
        $html .= '<br/><a href="'.home_url( $wp->request ).'/?download_data=personal" class="wpneo-edit-btn">'.__('Export Campaign Data', 'wp-crowdfunding').'</a>';
    $html .= '</div>';

    $html .= '</div>';
    $html .= '<div class="wpneo-col6">';

    ob_start();
    do_action('wpcf_dashboard_place_3');
    $html .= ob_get_clean();

    $html .= '<div class="wpneo-content wpneo-shadow wpneo-padding25 wpneo-clearfix">'; 
        $html .= '<form id="wpneo-dashboard-form" action="" method="" class="wpneo-form">';
                

            $html .= '<h4>'.__('Payment Info', 'wp-crowdfunding').'</h4>';
            ob_start();
            do_action('wpcf_dashboard_after_dashboard_form');
            $html .= ob_get_clean();

            $html .= wp_nonce_field( 'wpneo_crowdfunding_dashboard_form_action', 'wpneo_crowdfunding_dashboard_nonce_field', true, false );
            //Save Button
            $html .= '<div class="wpneo-buttons-group float-right">';
                $html .= '<button id="wpneo-edit" class="wpneo-edit-btn">'.__( "Edit" , "wp-crowdfunding" ).'</button>';
                $html .= '<button id="wpneo-dashboard-btn-cancel" class="wpneo-cancel-btn wpneo-hidden" type="submit">'.__( "Cancel" , "wp-crowdfunding" ).'</button>';
                $html .= '<button id="wpneo-dashboard-save" class="wpneo-save-btn wpneo-hidden" type="submit">'.__( "Save" , "wp-crowdfunding" ).'</button>';
            $html .= '</div>';
            $html .= '<div class="clear-float"></div>';
        $html .= '</form>';
    $html .= '</div>';
    $html .= '</div>';
$html .= '</div>';