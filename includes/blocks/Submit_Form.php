<?php
namespace WPCF\blocks;

defined( 'ABSPATH' ) || exit;

class Submit_Form{
    
    public function __construct(){
        $this->register_submit_form();
    }

    public function register_submit_form(){
        register_block_type(
            'wp-crowdfunding/submitform',
            array(
                'attributes' => array(
                    'formSize'   => array(
                        'type'      => 'string',
                        'default'   => 'small'
                    ),
                    'bgColorpalette'    => array(
                        'type'          => 'string',
                        'default'       => '#0073a8',
                    ),
                    'titlecolor'    => array(
                        'type'          => 'string',
                        'default'       => '#ffffff',
                    ),
                    'fontSize'    => array(
                        'type'          => 'number',
                        'default'       => 16,
                    ),
                    'fontWeight'    => array(
                        'type'          => 'number',
                        'default'       => 400,
                    ),
                    'SearchfontSize' => array(
                        'type'          => 'number',
                        'default'       => 14,
                    ),

                    'campaignID'   => array(
                        'type'      => 'string',
                        'default'   => ''
                    ),
                ),
                'render_callback' => array( $this, 'donate_block_callback' ),
            )
        );
    }

    public function donate_block_callback( $att ){
        $formSize           = isset($att['formSize']) ? $att['formSize'] : '';
        $bgColor            = isset( $att['bgColorpalette']) ? $att['bgColorpalette'] : 'all';
        $titlecolor         = isset( $att['titlecolor']) ? $att['titlecolor'] : 'all';
        $fontSize 		    = isset( $att['fontSize']) ? $att['fontSize'] : '16';
        $fontWeight 	    = isset( $att['fontWeight']) ? $att['fontWeight'] : '400';
        $SearchfontSize     = isset( $att['SearchfontSize']) ? $att['SearchfontSize'] : '14';
        $campaignID         = isset( $att['campaignID']) ? $att['campaignID'] : 'all';
    
        $atts = array(
            'campaign_id'           => $campaignID,
            'amount'                => '',
            'show_input_box'        => 'true',
            'min_amount'            => '',
            'max_amount'            => '',
            'donate_button_text'    => __('Back Campaign', 'wp-crowdfunding'),
        );

        if ( ! $atts['campaign_id']){
            return '<p class="wpcf-donate-form-response">'.__('Campaign ID required', 'wp-crowdfunding').'</p>';
        }
 
        $campaign = wc_get_product($atts['campaign_id']);
        if ( ! $campaign || $campaign->get_type() !== 'crowdfunding'){
            return '<p class="wpcf-donate-form-response">'.__('Invalid Campaign ID', 'wp-crowdfunding').'</p>';
        }

        $html = '';
        $html .= '<div class="wpcf-form-field '. $formSize .'">';
            $html .= '<div class="wpcf-donate-form-wrap">';
                $html .= '<form enctype="multipart/form-data" method="post" class="cart">';
                    if ($atts['show_input_box'] == 'true') {
                        $html .= get_woocommerce_currency_symbol();
                        $html .= '<input type="number" step="any" min="0" placeholder="'.$atts['amount'].'"
                            name="wpneo_donate_amount_field" class="search-field input-text amount text"
                            value="'.$atts['amount'].'" data-min-price="'.$atts['min_amount'].'"
                            data-max-price="'.$atts['max_amount'].'" style="font-size: '. $SearchfontSize .'px;">';
                    }else{
                        $html .= '<input type="hidden" name="wpneo_donate_amount_field" value="'.$atts['amount'].'" />';
                    }
                    $html .= '<input type="hidden" value="'.esc_attr($atts['campaign_id']).'" name="add-to-cart">';
                    $html .= '<button type="submit" class="'.apply_filters('add_to_donate_button_class', 'wpneo_donate_button').'" style="background: '.$bgColor.'; color: '.$titlecolor.'; font-size: '. $fontSize .'px; font-weight: '.$fontWeight.'">';
                        $html .= $atts['donate_button_text'];;
                        if ($atts['show_input_box'] != 'true'){
                            $html .= ' ('.wc_price($atts['amount']).') ';
                        }
                    $html .= '</button>';
                $html .= '</form>';
            $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
