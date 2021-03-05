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

ob_start();
?>
<form id="cf-dashboard-form">
    <div class="cf-row">
        <div class="cf-col-lg-6">
            <div class="cf-card">
                <h4><?php _e('Profile Picture', 'wp-crowdfunding'); ?></h4>

                <div class="cf-form-fields">
                    <input type="hidden" name="action" value="wpneo_profile_form">
                    <?php
                        $img_src = get_avatar_url( $current_user_id );
                        $image_id = get_user_meta( $current_user_id, 'profile_image_id', true );
                        if ($image_id && $image_id > 0) {
                            $img_src = wp_get_attachment_image_src($image_id, 'full')[0];
                        }
                    ?>
                    
                    <img class="profile-form-img" src="<?php echo $img_src; ?>" alt="<?php _e( "Profile Image:" , "wp-crowdfunding" ); ?>">
                    <span id="wpneo-image-show"></span>
                    <input type="hidden" name="profile_image_id" class="wpneo-form-image-id" value="<?php echo $image_id; ?>">
                    <input type="hidden" name="wpneo-form-image-url" class="wpneo-form-image-url" value="">
                    <button id="cc-image-upload-file-button" class="cf-button-primary wpneo-image-upload" style="display: none;">'.__( "Upload" , "wp-crowdfunding" ).'</button>
                </div>
            </div>

            <div class="cf-card">
                <h4><?php _e("Basic Info", "wp-crowdfunding"); ?></h4>

                <label class="cf-form-label"><?php _e( "Name:" , "wp-crowdfunding" ); ?></label>
                <div class="cf-form-fields"><?php echo wpcf_function()->get_author_name($current_user_id); ?></div>

                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "First Name" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="text" name="first_name" value="<?php echo $user->first_name; ?>" disabled>
                    </div>
                </div>
                
                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "Last Name" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="text" name="last_name" value="<?php echo $user->last_name; ?>" disabled>
                    </div>
                </div>

                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "About Us" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <textarea name="profile_about" rows="3" disabled><?php echo isset($data['profile_about'][0]) ? esc_textarea($data['profile_about'][0]) : ''; ?></textarea>
                    </div>
                </div>

                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "Bio" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <textarea name="profile_portfolio" rows="3" disabled><?php echo isset($data['profile_portfolio'][0]) ? esc_textarea($data['profile_portfolio'][0]) : ''; ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="cf-col-lg-6">
            <div class="cf-card">
                <h4><?php _e("Contact Info", "wp-crowdfunding"); ?></h4>

                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "Mobile Number" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="text" name="profile_mobile1" value="<?php echo isset($data['profile_mobile1'][0]) ? esc_textarea($data['profile_mobile1'][0]) : ''; ?>" disabled>
                    </div>
                </div>

                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "Email" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="email" name="profile_email1" value="<?php echo isset($data['profile_email1'][0]) ? esc_textarea($data['profile_email1'][0]) : ''; ?>" disabled>
                    </div>
                </div>
                
                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "Fax" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="text" name="profile_fax" value="<?php echo isset($data['profile_fax'][0]) ? esc_textarea($data['profile_fax'][0]) : ''; ?>" disabled>
                    </div>
                </div>
                
                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "Website" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="url" name="profile_website" value="<?php echo isset($data['profile_website'][0]) ? esc_textarea($data['profile_website'][0]) : ''; ?>" disabled>
                    </div>
                </div>
                
                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "Address" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="text" name="profile_address" value="<?php echo isset($data['profile_address'][0]) ? esc_textarea($data['profile_address'][0]) : ''; ?>" disabled>
                    </div>
                </div>
            </div>

            <div class="cf-card">
                <h4><?php _e("Social Profile", "wp-crowdfunding"); ?></h4>

                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "Facebook" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="text" name="profile_facebook" value="<?php echo isset($data['profile_facebook'][0]) ? esc_textarea($data['profile_facebook'][0]) : ''; ?>" disabled>
                    </div>
                </div>
                
                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "Twitter" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="text" name="profile_twitter" value="<?php echo isset($data['profile_twitter'][0]) ? esc_textarea($data['profile_twitter'][0]) : ''; ?>" disabled>
                    </div>
                </div>
                
                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "Linkedin" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="text" name="profile_linkedin" value="<?php echo isset($data['profile_linkedin'][0]) ? esc_textarea($data['profile_linkedin'][0]) : ''; ?>" disabled>
                    </div>
                </div>
                
                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "Pinterest" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="text" name="profile_pinterest" value="<?php echo isset($data['profile_pinterest'][0]) ? esc_textarea($data['profile_pinterest'][0]) : ''; ?>" disabled>
                    </div>
                </div>

                <div class="cf-form-group">
                    <label class="cf-form-label"><?php _e( "VK" , "wp-crowdfunding" ); ?></label>
                    <div class="cf-form-fields">
                        <input type="text" name="profile_vk" value="<?php echo isset($data['profile_vk'][0]) ? esc_textarea($data['profile_vk'][0]) : ''; ?>" disabled>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <?php
        do_action('wpcf_dashboard_after_profile_form');
        wp_nonce_field( 'wpneo_crowdfunding_dashboard_form_action', 'wpneo_crowdfunding_dashboard_nonce_field', true, false );
    ?>

    <?php if ($logged_user_info) : ?>
        <div class="wpneo-buttons-group">
        <button id="wpneo-edit" class="cf-button-secondary wpneo-edit-btn"><?php _e( "Edit", "wp-crowdfunding" ); ?></button>
        <button id="wpneo-dashboard-btn-cancel" class="cf-button-primary wpneo-cancel-btn wpneo-hidden" type="submit"><?php _e( "Cancel", "wp-crowdfunding" ); ?></button>
        <button id="wpneo-profile-save" class="cf-button-primary wpneo-save-btn wpneo-hidden" type="submit"><?php _e( "Save", "wp-crowdfunding" ); ?></button>
        </div>
    <?php endif; ?>

</form>
<?php $html .= ob_get_clean(); ?>