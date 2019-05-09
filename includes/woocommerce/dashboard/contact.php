<?php
defined( 'ABSPATH' ) || exit;

$id 		= get_current_user_id();
// Billing Data
$f_name 	= get_user_meta( $id,'shipping_first_name',true );
$l_name 	= get_user_meta( $id,'shipping_last_name',true );
$company 	= get_user_meta( $id,'shipping_company',true );
$address1 	= get_user_meta( $id,'shipping_address_1',true );
$address2 	= get_user_meta( $id,'shipping_address_2',true );
$city 		= get_user_meta( $id,'shipping_city',true );
$postcode 	= get_user_meta( $id,'shipping_postcode',true );
$country 	= get_user_meta( $id,'shipping_country',true );
$state 		= get_user_meta( $id,'shipping_state',true );
// Shipping Data
$b_f_name       = get_user_meta( $id,'billing_first_name',true );
$b_l_name       = get_user_meta( $id,'billing_last_name',true );
$b_company      = get_user_meta( $id,'billing_company',true );
$b_address1     = get_user_meta( $id,'billing_address_1',true );
$b_address2     = get_user_meta( $id,'billing_address_2',true );
$b_city         = get_user_meta( $id,'billing_city',true );
$b_postcode     = get_user_meta( $id,'billing_postcode',true );
$b_country      = get_user_meta( $id,'billing_country',true );
$b_state        = get_user_meta( $id,'billing_state',true );
$b_phone        = get_user_meta( $id,'billing_phone',true );
$b_email        = get_user_meta( $id,'billing_email',true );


$html .= '<div class="wpneo-content">';

    $html .= '<form id="wpneo-dashboard-form" action="" method="" class="wpneo-form">';



    $html .= '<div class="wpneo-row">';

        $html .= '<div class="wpneo-col6">';  
            $html .= '<div class="wpneo-shadow wpneo-padding25 wpneo-clearfix">';
                // Shipping Address
                $html .= '<h4>'.__("Shipping Address","wp-crowdfunding").'</h4>';
        		// First Name ( Shipping )
        		$html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "First Name:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                    $html .= '<input type="hidden" name="action" value="wpneo_contact_form">';
                        $html .= '<input type="text" name="shipping_first_name" value="'.$f_name.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

        		// Last Name ( Shipping )
        		$html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Last Name:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="shipping_last_name" value="'.$l_name.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

        		// Company ( Shipping )
        		$html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Company:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="shipping_company" value="'.$company.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

        		// Address 1 ( Shipping )
        		$html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Address 1:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="shipping_address_1" value="'.$address1.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

        		// Address 2 ( Shipping )
        		$html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Address 2:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="shipping_address_2" value="'.$address2.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

                // City ( Shipping )
        		$html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "City:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="shipping_city" value="'.$city.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

                // Postcode ( Shipping )
        		$html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Postcode:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="shipping_postcode" value="'.$postcode.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

        		// Country ( Shipping )
        		$html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Country:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $countries_obj   = new WC_Countries();
                        $countries   = $countries_obj->__get('countries');
                        array_unshift($countries, __('Select a country','wp-crowdfunding'));
                        $html .= '<select name="shipping_country" disabled>';
                        foreach ($countries as $key=>$value) {
                            if( $country==$key ){
                                $html .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
                            }else{
                                $html .= '<option value="'.$key.'">'.$value.'</option>';
                            }
                        }
                        $html .= '</select>';
                        //$html .= '<input type="text" name="shipping_country" value="'.$country.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

                // State ( Shipping )
        		$html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "State:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="shipping_state" value="'.$state.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

            $html .= '</div>';//wpneo-shadow    
        $html .= '</div>';//wpneo-col6    

        $html .= '<div class="wpneo-col6">'; 
            $html .= '<div class="wpneo-shadow wpneo-padding25 wpneo-clearfix">';
                // Billing Address
                $html .= '<h4>'.__("Billing Address","wp-crowdfunding").'</h4>';
                // First Name ( Billing )
                $html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "First Name:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="billing_first_name" value="'.$b_f_name.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';


                // Last Name ( Billing )
                $html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Last Name:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="billing_last_name" value="'.$b_l_name.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';


                // Company ( Billing )
                $html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Company:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="billing_company" value="'.$b_company.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';


                // Address 1 ( Billing )
                $html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Address 1:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="billing_address_1" value="'.$b_address1.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';


                // Address 2 ( Billing )
                $html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Address 2:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="billing_address_2" value="'.$b_address2.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';


                // City ( Billing )
                $html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "City:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="billing_city" value="'.$b_city.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';


                // Postcode ( Billing )
                $html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Postcode:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="billing_postcode" value="'.$b_postcode.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';


                // Country ( Billing )
                $html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Country:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $countries_obj   = new WC_Countries();
                        $countries   = $countries_obj->__get('countries');
                        array_unshift($countries, __('Select a country','wp-crowdfunding'));
                        $html .= '<select name="billing_country" disabled>';
                        foreach ($countries as $key=>$value) {
                            if( $b_country==$key ){
                                $html .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
                            }else{
                                $html .= '<option value="'.$key.'">'.$value.'</option>';
                            }
                        }
                        $html .= '</select>';
                    $html .= '</div>';
                $html .= '</div>';


                // State ( Billing )
                $html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "State:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="billing_state" value="'.$b_state.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';


                // Telephone ( Billing )
                $html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Telephone:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="text" name="billing_phone" value="'.$b_phone.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';


                // Email ( Billing )
                $html .= '<div class="wpneo-single">';
                    $html .= '<div class="wpneo-name float-left">';
                        $html .= '<p>'.__( "Email:" , "wp-crowdfunding" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="wpneo-fields">';
                        $html .= '<input type="email" name="billing_email" value="'.$b_email.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

            $html .= '</div>';//wpneo-shadow    
        $html .= '</div>';//wpneo-col6   

    $html .= '</div>';//wpneo-row


        $html .= wp_nonce_field( 'wpneo_crowdfunding_dashboard_form_action', 'wpneo_crowdfunding_dashboard_nonce_field', true, false );


		//Save Button
        $html .= '<div class="wpneo-buttons-group float-right">';
            $html .= '<button id="wpneo-edit" class="wpneo-edit-btn">'.__( "Edit" , "wp-crowdfunding" ).'</button>';
            $html .= '<button id="wpneo-dashboard-btn-cancel" class="wpneo-cancel-btn wpneo-hidden" type="submit">'.__( "Cancel" , "wp-crowdfunding" ).'</button>';
            $html .= '<button id="wpneo-contact-save" class="wpneo-save-btn wpneo-hidden" type="submit">'.__( "Save" , "wp-crowdfunding" ).'</button>';
        $html .= '</div>';
        $html .= '<div class="clear-float"></div>';

	$html .= '</form>';

$html .= '</div>';