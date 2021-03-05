<?php
defined( 'ABSPATH' ) || exit;

$current_user_id = get_current_user_id();

/**
 * If user can manage options
 */
$logged_user_info = true;
if (user_can($current_user_id, 'manage_options')){
	if (isset($_GET['show_user_id'])){
		$current_user_id = (int) sanitize_text_field($_GET['show_user_id']);
		$logged_user_info = false;
	}
}

$data = get_user_meta($current_user_id);
$user = get_user_by('ID', $current_user_id);

$html .= '<div class="wpneo-content">';
    $html .= '<form id="wpneo-dashboard-form" class="wpneo-form">';
        $html .= '<div class="wpneo-row">';

            $html .= '<div class="wpneo-col6">';
                $html .= '<div class="wpneo-shadow wpneo-padding25 wpneo-clearfix">';
                    $html .= '<h4>'.__("Profile Picture","wp-crowdfunding").'</h4>';
                    $html .= '<div class="cf-form-fields">';
                    $html .= '<input type="hidden" name="action" value="wpneo_profile_form">';
                        
                        $img_src = get_avatar_url( $current_user_id );
                        $image_id = get_user_meta( $current_user_id, 'profile_image_id', true );
                        if ($image_id && $image_id > 0) {
                            $img_src = wp_get_attachment_image_src($image_id, 'full')[0];
                        }
                        $html .= '<img class="profile-form-img" src="'.$img_src.'" alt="'.__( "Profile Image:" , "wp-crowdfunding" ).'">';

                        $html .= '<span id="wpneo-image-show"></span>';
                        $html .= '<input type="hidden" name="profile_image_id" class="wpneo-form-image-id" value="'.$image_id.'">';
                        $html .= '<input type="hidden" name="wpneo-form-image-url" class="wpneo-form-image-url" value="">';
                        $html .= '<button name="wpneo-upload" id="cc-image-upload-file-button" class="cf-button cf-button-primary wpneo-image-upload" style="display: none;">'.__( "Upload" , "wp-crowdfunding" ).'</button>';
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="wpneo-col6">';
                
                // Basic info
                $html .= '<div class="wpneo-shadow wpneo-padding25 wpneo-clearfix">';
                    $html .= '<h4>'.__("Basic Info","wp-crowdfunding").'</h4>';
				    $html .= '<label class="cf-form-label">';
					$html .= __( "Name:" , "wp-crowdfunding" );
					$html .= '</label>';
					$html .= '<div class="cf-form-fields">';
					$html .= "<p>".wpcf_function()->get_author_name($current_user_id)."</p>";
                    $html .= '</div>';

					$html .= '<div class="cf-form-group">';
                    $html .= '<label class="cf-form-label">';
                    $html .= __( "First Name:" , "wp-crowdfunding" );
                    $html .= '</label>';
                    $html .= '<div class="cf-form-fields">';
                    $html .= '<input type="text" name="first_name" value="'.$user->first_name.'" disabled>';
					$html .= '</div>';
                    
					$html .= '<label class="cf-form-label">';
					$html .= __( "Last Name:" , "wp-crowdfunding" );
					$html .= '</label>';
					$html .= '<div class="cf-form-fields">';
					$html .= '<input type="text" name="last_name" value="'.$user->last_name.'" disabled>';
					$html .= '</div>';
				$html .= '</div>';

                // About Us
                $html .= '<label class="cf-form-label">';
                    $html .= __( "About Us:" , "wp-crowdfunding" );
                $html .= '</label>';
                $html .= '<div class="cf-form-fields">';
                    $value = ''; if(isset($data['profile_about'][0])){ $value = esc_textarea($data['profile_about'][0]); }
                    $html .= '<textarea name="profile_about" rows="3" disabled>'.$value.'</textarea>';
                $html .= '</div>';

                // Profile Information
                $html .= '<div class="cf-form-group">';
                    $html .= '<label class="cf-form-label">';
                        $html .= __( "User Bio:" , "wp-crowdfunding" );
                    $html .= '</label>';
                    $html .= '<div class="cf-form-fields">';
                        $value = ''; if(isset($data['profile_portfolio'][0])){ $value = esc_textarea($data['profile_portfolio'][0]); }
                        $html .= '<textarea name="profile_portfolio" rows="3" disabled>'.$value.'</textarea>';
                    $html .= '</div>';
                $html .= '</div>';

                $html .= '</div>';
            $html .= '</div>';

            // Mobile Number
            $html .= '<div class="wpneo-col6">';
                $html .= '<div class="wpneo-shadow wpneo-padding25 wpneo-clearfix">';
                    $html .= '<h4>'.__("Contact Info","wp-crowdfunding").'</h4>';
                    $html .= '<div class="cf-form-group">';
                    $html .= '<label class="cf-form-label">' . __( "Mobile Number:" , "wp-crowdfunding" ) . '</label>';
                    $html .= '<div class="cf-form-fields">';
                        $value = '';
                        if(isset($data['profile_mobile1'][0])) {
                            $value = esc_attr($data['profile_mobile1'][0]);
                        }
                        $html .= '<input type="text" name="profile_mobile1" value="'.$value.'" disabled>';
                    $html .= '</div>';
                    // Email
                    $html .= '<div class="cf-form-group">';
                        $html .= '<label class="cf-form-label">' . __( "Email:" , "wp-crowdfunding" ) . '</label>';
                        $html .= '<div class="cf-form-fields">';
                            $value = '';
                            if(isset($data['profile_email1'][0])) {
                                $value = esc_attr($data['profile_email1'][0]);
                            }
                            $html .= '<input type="text" name="profile_email1" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';
                    // Fax
                    $html .= '<div class="cf-form-group">';
                        $html .= '<label class="cf-form-label">' . __( "Fax:" , "wp-crowdfunding" ) . '</label>';
                        $html .= '<div class="cf-form-fields">';
                            $value = '';
                            if(isset($data['profile_fax'][0])) {
                                $value = esc_attr($data['profile_fax'][0]);
                            }
                            $html .= '<input type="text" name="profile_fax" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';
                    // Website
                    $html .= '<div class="cf-form-group">';
                        $html .= '<label class="cf-form-label">' . __( "Website:" , "wp-crowdfunding" ) . '</label>';
                        $html .= '<div class="cf-form-fields">';
                            $value = ''; if(isset($data['profile_website'][0])){ $value = esc_url($data['profile_website'][0]); }
                            $html .= '<input type="text" name="profile_website" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';

                    // Address
                    $html .= '<div class="cf-form-group">';
                        $html .= '<label class="cf-form-label">' . __( "Address:" , "wp-crowdfunding" ) . '</label>';
                        $html .= '<div class="cf-form-fields">';
                            $value = ''; if(isset($data['profile_address'][0])){ $value = esc_textarea($data['profile_address'][0]); }
                            $html .= '<input type="text" name="profile_address" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="wpneo-col6">';
                $html .= '<div class="wpneo-shadow wpneo-padding25 wpneo-clearfix">';
                    $html .= '<h4>'.__("Social Profile","wp-crowdfunding").'</h4>';
                    //Facebook
                    $html .= '<div class="cf-form-group">';
                        $html .= '<label class="cf-form-label">' . __( "Facebook:" , "wp-crowdfunding" ) . '</label>';
                        $html .= '<div class="cf-form-fields">';
                            $value = ''; if(isset($data['profile_facebook'][0])){ $value = esc_textarea($data['profile_facebook'][0]); }
                            $html .= '<input type="text" name="profile_facebook" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';

                    // Twitter
                    $html .= '<div class="cf-form-group">';
                        $html .= '<label class="cf-form-label">' . __( "Twitter:" , "wp-crowdfunding" ) . '</label>';
                        $html .= '<div class="cf-form-fields">';
                            $value = '';
                            if(isset($data['profile_twitter'][0])) {
                                $value = esc_textarea($data['profile_twitter'][0]);
                            }
                            $html .= '<input type="text" name="profile_twitter" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';

                    // VK
                    $html .= '<div class="cf-form-group">';
                        $html .= '<label class="cf-form-label">' . __( "VK:" , "wp-crowdfunding" ) . '</label>';
                        $html .= '<div class="cf-form-fields">';
                            $value = ''; if(isset($data['profile_vk'][0])){ $value = esc_textarea($data['profile_vk'][0]); }
                            $html .= '<input type="text" name="profile_vk" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';

                    // Linkedin
                    $html .= '<div class="cf-form-group">';
                        $html .= '<label class="cf-form-label">' . __( "Linkedin:" , "wp-crowdfunding" ) . '</label>';
                        $html .= '<div class="cf-form-fields">';
                            $value = ''; if(isset($data['profile_linkedin'][0])){ $value = esc_textarea($data['profile_linkedin'][0]); }
                            $html .= '<input type="text" name="profile_linkedin" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';

                    // Pinterest
                    $html .= '<div class="cf-form-group">';
                        $html .= '<label class="cf-form-label">' . __( "Pinterest:" , "wp-crowdfunding" ) . '</label>';
                        $html .= '<div class="cf-form-fields">';
                            $value = ''; if(isset($data['profile_pinterest'][0])){ $value = esc_textarea($data['profile_pinterest'][0]); }
                            $html .= '<input type="text" name="profile_pinterest" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</div>';

        ob_start();
        do_action('wpcf_dashboard_after_profile_form');
        $html .= ob_get_clean();

        $html .= wp_nonce_field( 'wpneo_crowdfunding_dashboard_form_action', 'wpneo_crowdfunding_dashboard_nonce_field', true, false );

        //Save Button
		if ($logged_user_info) {
			$html .= '<div class="wpneo-buttons-group float-right">';
			$html .= '<button id="wpneo-edit" class="wpneo-edit-btn">' . __( "Edit", "wp-crowdfunding" ) . '</button>';
			$html .= '<button id="wpneo-dashboard-btn-cancel" class="wpneo-cancel-btn wpneo-hidden" type="submit">' . __( "Cancel", "wp-crowdfunding" ) . '</button>';
			$html .= '<button id="wpneo-profile-save" class="wpneo-save-btn wpneo-hidden" type="submit">' . __( "Save", "wp-crowdfunding" ) . '</button>';
			$html .= '</div>';
			$html .= '<div class="clear-float"></div>';
		}

    $html .= '</form>';
$html .= '</div>';