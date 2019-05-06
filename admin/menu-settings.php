<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once( ABSPATH.'wp-includes/pluggable.php' );

// Settings Option Generator
function wpneo_crowdfunding_settings_generate_field( $arr ){

    $output = '';
    $output .= '<table class="form-table">';
    $output .= '<tbody>';

    foreach ($arr as $value) {
        if(isset( $value['type'] )){
            switch ( $value['type'] ) {

                case 'dropdown':
                    $output .= '<tr>';
                    $output .= '<th><label for="'.$value['id'].'">'.$value["label"].'</label></th>';
                    $output .= '<td>';
                    $multiple = '';
                    if(isset($value['multiple'])){ $multiple = 'multiple'; }
                    $output .= '<select id="'.$value['id'].'" name="'.$value['id'].'" '.$multiple.'>';
                    $campaign_status = get_option( $value['id'] );
                    if(!empty($value['option'])){
                        foreach ( $value['option'] as $key => $val ){
                            $output .= '<option value="'.$key.'" '.( $key == $campaign_status ? "selected":"" ).'>'.$val.'</option>';
                        }
                    }
                    $output .= '</select>';
                    if( isset($value['desc']) ){ $output .= '<p>'.$value['desc'].'</p>'; }
                    $output .= '</td>';
                    $output .= '</tr>';
                    break;

                case 'multiple':
                    $output .= '<tr>';
                    $output .= '<th><label for="'.$value['id'].'">'.$value["label"].'</label></th>';
                    $output .= '<td>';
                    $multiple = '';
                    if(isset($value['multiple'])){ $multiple = 'multiple'; }
                    $output .= '<select style="height:190px;" id="'.$value['id'].'" name="'.$value['id'].'[]" '.$multiple.'>';
                    $campaign_status = get_option( $value['id'] );
                    if(!empty($value['option'])){
                        foreach ( $value['option'] as $val ){
                            if( !empty($campaign_status) && is_array($campaign_status) ){
                                if( in_array( $val , $campaign_status ) ){
                                    $output .= '<option value="'.$val.'" selected>'.$val.'</option>';
                                }else{
                                    $output .= '<option value="'.$val.'">'.$val.'</option>';
                                }
                            }else{
                                $output .= '<option value="'.$val.'">'.$val.'</option>';
                            }
                        }
                    }
                    $output .= ' </select>';
                    if( isset($value['desc']) ){ $output .= '<p>'.$value['desc'].'</p>'; }
                    $output .= '</td>';
                    $output .= '</tr>';
                    break;

                case 'text':
                    $output .= '<tr>';
                    $output .= '<th><label for="'.$value['id'].'">'.$value['label'].'</label></th>';
                    $output .= '<td>';
                    $var = get_option( $value['id'] );
                    $default_value = ( isset($value["value"])) ? $value["value"] : '';
                    $output .= '<input type="text" id="'.$value['id'].'" value="'.( $var ? $var : $default_value ).'" name="'.$value['id'].'">';
                    if( isset($value['desc']) ){ $output .= '<p>'.$value['desc'].'</p>'; }
                    $output .= '</td>';
                    $output .= '</tr>';
                    break;

                case 'password':
                    $output .= '<tr>';
                    $output .= '<th><label for="'.$value['id'].'">'.$value['label'].'</label></th>';
                    $output .= '<td>';
                    $var = get_option( $value['id'] );
                    $output .= '<input type="password" id="'.$value['id'].'" value="'.( $var ? $var : $value["value"] ).'" name="'.$value['id'].'">';
                    if( isset($value['desc']) ){ $output .= '<p>'.$value['desc'].'</p>'; }
                    $output .= '</td>';
                    $output .= '</tr>';
                    break;

                case 'textarea':
                    $output .= '<tr>';
                    $output .= '<th><label for="'.$value['id'].'">'.$value['label'].'</label></th>';
                    $output .= '<td>';
                    $var = get_option( $value['id'] );
                    $output .= '<textarea name="'.$value['id'].'" id="'.$value['id'].'">'.( $var ? $var : $value["value"] ).'</textarea>';
                    if( isset($value['desc']) ){ $output .= '<p>'.$value['desc'].'</p>'; }
                    $output .= '</td>';
                    $output .= '</tr>';
                    break;

                case 'number':
                    $output .= '<tr>';
                    $output .= '<th scope="row"><label for="'.$value["id"].'">'.$value["label"].'</label></th>';
                    $output .= '<td>';
                    $data = '';
                    $var = get_option( $value["id"] );
                    if( isset($value["min"]) != "" ){ $data .= 'min="'.$value["min"].'"'; }
                    if( isset($value["max"]) != "" ){ $data .= ' max="'.$value["max"].'"'; }
                    $output .= '<input type="number" value="'.( $var ? $var : $value["value"]).'" '.$data.' name="'.$value["id"].'" />';
                    if( isset($value['desc']) ){ $output .= '<p>'.$value['desc'].'</p>'; }
                    $output .= '</td>';
                    $output .= '</tr>';
                    break;

                case 'radio':
                    $output .= '<tr>';
                    $output .= '<th scope="row"><label for="'.$value["id"].'">'.$value["label"].'</label></th>';
                    $output .= '<td>';
                    $data = '';
                    $var = get_option( $value["id"] );
                    if( ! $var ){ $var =  ! empty($value["value"]) ? $value["value"] : ''  ; }
                    if(!empty($value['option'])){
                        foreach( $value['option'] as $key => $val ){
                            $cehcked = ($key == $var) ? ' checked="checked" ' : '';
                            $output .= '<label> <input type="radio" name="'.$value['id'].'" value="'.$key.'" '.$cehcked.' > '.$val.' </label> <br>';
                        }
                    }

                    if( isset($value['desc']) ){ $output .= '<p>'.$value['desc'].'</p>'; }

                    $output .= '</td>';
                    $output .= '</tr>';
                    break;

                case 'checkbox':
                    $output .= '<tr>';
                    $output .= '<th><label for="'.$value['id'].'">'.$value['label'].'</label></th>';
                    $output .= '<td>';
                    $var = get_option( $value['id'] );
                    $output .= '<label><input type="checkbox" name="'.$value['id'].'" id="'.$value['id'].'" value="true" '.($var=="true"?"checked='checked'":"").'/>';
                    if(isset($value['desc'])){ $output .= $value['desc']; }
                    $output .= '</label>';
                    $output .= '</td>';
                    $output .= '</tr>';
                    break;

                case 'seperator':
                    $output .= '<tr>';
                    $output .= '<th colspan="2">';
                    if( isset($value['label']) ){ $output .= '<h2>'.$value["label"].'</h2>'; }
                    if( isset($value['desc']) ){ $output .= '<p>'.$value['desc'].'</p>'; }
                    if( isset($value['top_line']) != '' ){ $output .= '<hr>'; }
                    $output .= '</th>';
                    $output .= '</tr>';
                    break;

                case 'color':
                    $output .= '<tr>';
                    $output .= '<th><label for="'.$value['id'].'">'.$value['label'].'</label></th>';
                    $output .= '<td>';
                    $var = get_option( $value['id'] );
                    if(!$var){ $var = $value['value']; }
                    $output .= '<input type="text" name="'.$value['id'].'" value="'.$var.'" id="'.$value['id'].'" class="wpneo-color-field" >';
                    if(isset($value['desc'])){ $output .= '<p>'.$value['desc'].'</p>'; }
                    $output .= '</td>';
                    $output .= '</tr>';
                    break;

                case 'hidden':
                    $output .= '<tr>';
                    $output .= '<th colspan="2">';
                    $output .= '<input type="hidden" value="'.$value["value"].'" name="'.$value['id'].'">';
                    $output .= '</th>';
                    $output .= '</tr>';
                    break;

                default:
                    # code...
                    break;
            }
        }
    }
    $output .= '</tbody>';
    $output .= '</table>';

    return $output;
}


/**
 * Display a custom menu page
 */
function neo_crowdfunding_menu_page(){
    // Settings Tab With slug and Display name
    $tabs = apply_filters('wpneo_crowdfunding_settings_panel_tabs', array(
            'general' 	=>
                array(
                    'tab_name' => __('General Settings','wp-crowdfunding'),
                    'load_form_file' => WPCF_DIR_PATH.'admin/includes-tab/tab-general.php'
                ),
            'woocommerce' 	=>
                array(
                    'tab_name' => __('WooCommerce Settings','wp-crowdfunding'),
                    'load_form_file' => WPCF_DIR_PATH.'admin/includes-tab/tab-woocommerce.php'
                ),
            'style'   =>
                array(
                    'tab_name' => __('Style','wp-crowdfunding'),
                    'load_form_file' => WPCF_DIR_PATH.'admin/includes-tab/tab-style.php'
                ),
        )
    );

    $current_page = 'general';
    if( ! empty($_GET['tab']) ){
        $current_page = sanitize_text_field($_GET['tab']);
    }

    // Print the Tab Title
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current_page ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=wpneo-crowdfunding&tab=$tab'>{$name['tab_name']}</a>";
    }
    echo '</h2>';
    ?>

    <form id="neo-crowdfunding" role="form" method="post" action="">
        <?php
        //Load tab file
        $request_file = $tabs[$current_page]['load_form_file'];
        $default_file = WPCF_DIR_PATH.'admin/includes-tab/tab-general.php';

        if (array_key_exists(trim(esc_attr($current_page)), $tabs)){
            if (file_exists($default_file)){
                include_once $request_file;
            }else{
                include_once $default_file;
            }
        }else {
            include_once $default_file;
        }

        wp_nonce_field('wpneo_settings_page_action', 'wpneo_settings_page_nonce_field');
        submit_button(null,'primary','wpneo_admin_settings_submit_btn');
        ?>

        <a href="javascript:;" class="button wpneo-crowdfunding-reset-btn"> <i class="dashicons dashicons-image-rotate"></i> <?php _e('Reset Settings', 'wp-crowdfunding'); ?></a>
    </form>
    <?php
}

/**
 * Add settings option
 */
if (wpneo_post('wpneo_admin_settings_submit_btn') && wp_verify_nonce( sanitize_text_field(wpneo_post('wpneo_settings_page_nonce_field')), 'wpneo_settings_page_action' ) ){

    $wpneo_crowdfunding_admin_tab = sanitize_text_field(wpneo_post('wpneo_crowdfunding_admin_tab'));
    if ( ! empty($wpneo_crowdfunding_admin_tab)) {

        /**
         * General Settings
         */

        if (sanitize_text_field(wpneo_post('wpneo_crowdfunding_admin_tab')) == 'tab_general'){

            $vendor_type = sanitize_text_field(wpneo_post('vendor_type'));
            wpneo_crowdfunding_update_option_text('vendor_type', $vendor_type);

            $wpneo_default_campaign_status = sanitize_text_field(wpneo_post('wpneo_default_campaign_status'));
            wpneo_crowdfunding_update_option_text('wpneo_default_campaign_status', $wpneo_default_campaign_status);
            
	        $wpneo_campaign_edit_status = sanitize_text_field(wpneo_post('wpneo_campaign_edit_status'));
	        wpneo_crowdfunding_update_option_text('wpneo_campaign_edit_status', $wpneo_campaign_edit_status);

	        $wpneo_show_min_price = sanitize_text_field(wpneo_post('wpneo_show_min_price'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_show_min_price', $wpneo_show_min_price);

            $wpneo_show_max_price = sanitize_text_field(wpneo_post('wpneo_show_max_price'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_show_max_price', $wpneo_show_max_price);

            $wpneo_show_recommended_price = sanitize_text_field(wpneo_post('wpneo_show_recommended_price'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_show_recommended_price', $wpneo_show_recommended_price);

            $wpneo_show_target_goal = sanitize_text_field(wpneo_post('wpneo_show_target_goal'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_show_target_goal', $wpneo_show_target_goal);

            $wpneo_show_target_date = sanitize_text_field(wpneo_post('wpneo_show_target_date'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_show_target_date', $wpneo_show_target_date);

            $wpneo_show_target_goal_and_date = sanitize_text_field(wpneo_post('wpneo_show_target_goal_and_date'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_show_target_goal_and_date', $wpneo_show_target_goal_and_date);

            $wpneo_show_campaign_never_end = sanitize_text_field(wpneo_post('wpneo_show_campaign_never_end'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_show_campaign_never_end', $wpneo_show_campaign_never_end);

            $wpneo_enable_paypal_per_campaign_email = sanitize_text_field(wpneo_post('wpneo_enable_paypal_per_campaign_email'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_enable_paypal_per_campaign_email', $wpneo_enable_paypal_per_campaign_email);

            $wpneo_user_role_selector = wpneo_post('wpneo_user_role_selector');
            update_option( 'wpneo_user_role_selector', $wpneo_user_role_selector );
            function wpneo_crowdfunding_add_theme_caps() {
                $role_list = maybe_unserialize(get_option( 'wpneo_user_role_selector' ));

                // Init Setup Action
                //$roles  = maybe_unserialize(get_option( 'wp_user_roles' ));
                $roles  = get_editable_roles();
                foreach( $roles as $key=>$role ){
                    if( isset( $role['capabilities']['campaign_form_submit'] ) ){
                        $role = get_role( $key );
                        $role->remove_cap( 'campaign_form_submit' );
                    }
                }

                if( is_array( $role_list ) ){
                    if( !empty( $role_list ) ){
                        foreach( $role_list as $val ){
                            $role = get_role( $val );
                            $role->add_cap( 'campaign_form_submit' );
                            $role->add_cap( 'upload_files' );
                        }
                    }
                }
            }
            add_action( 'admin_init', 'wpneo_crowdfunding_add_theme_caps');

            $wpneo_form_page_id = intval(wpneo_post('wpneo_form_page_id'));

            if (!empty($wpneo_form_page_id)) {
                global $wpdb;
                $page_id = $wpneo_form_page_id;
                update_option( 'wpneo_form_page_id', $page_id );

                //Update That Page with new crowdFunding [wpneo_crowdfunding_form]
                $previous_content = str_replace('[wpneo_crowdfunding_form]', '', get_post_field('post_content', $page_id));
                $new_content = $previous_content . '[wpneo_crowdfunding_form]';
                //Update Post
                $wpdb->update($wpdb->posts, array('post_content' => $new_content), array('ID'=> $page_id));
            }

            $wpneo_crowdfunding_dashboard_page_id = intval(wpneo_post('wpneo_crowdfunding_dashboard_page_id'));
            if (!empty($wpneo_crowdfunding_dashboard_page_id)) {
                $page_id = $wpneo_crowdfunding_dashboard_page_id;
                update_option('wpneo_crowdfunding_dashboard_page_id', $page_id);

                //Update That Page with new crowdFunding [wpneo_crowdfunding_dashboard]
                $previous_content = str_replace('[wpneo_crowdfunding_dashboard]', '', get_post_field('post_content', $page_id));
                $new_content = $previous_content . '[wpneo_crowdfunding_dashboard]';
                //Update Post
                $wpdb->update($wpdb->posts, array('post_content' => $new_content), array('ID'=> $page_id));
            }

	        $wpcf_user_reg_success_redirect_uri = sanitize_text_field(wpneo_post('wpcf_user_reg_success_redirect_uri'));
	        update_option('wpcf_user_reg_success_redirect_uri', $wpcf_user_reg_success_redirect_uri);

        }

        /**
         * Listing Page Settings
         */
        if (sanitize_text_field(wpneo_post('wpneo_crowdfunding_admin_tab')) == 'tab_listing_page'){
            $number_of_collumn_in_row = intval(wpneo_post('number_of_collumn_in_row'));
            wpneo_crowdfunding_update_option_text('number_of_collumn_in_row', $number_of_collumn_in_row);

            $number_of_words_show_in_listing_description = intval(wpneo_post('number_of_words_show_in_listing_description'));
            wpneo_crowdfunding_update_option_text('number_of_words_show_in_listing_description', $number_of_words_show_in_listing_description);

            $wpneo_show_rating = sanitize_text_field(wpneo_post('wpneo_show_rating'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_show_rating', $wpneo_show_rating);
        }

        /**
         * Single Page Settings
         */
        if (sanitize_text_field(wpneo_post('wpneo_crowdfunding_admin_tab')) == 'tab_single_page'){
            $wpneo_single_page_reward_design = intval(wpneo_post('wpneo_single_page_reward_design'));
            wpneo_crowdfunding_update_option_text('wpneo_single_page_reward_design', $wpneo_single_page_reward_design);

            $reward_fixed_price = sanitize_text_field(wpneo_post('wpneo_reward_fixed_price'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_reward_fixed_price', $reward_fixed_price);
        }


        /**
         * WooCommerce Settings
         */
        if (sanitize_text_field(wpneo_post('wpneo_crowdfunding_admin_tab')) == 'tab_woocommerce') {
            $hide_cf_campaign_from_shop_page = sanitize_text_field(wpneo_post('hide_cf_campaign_from_shop_page'));
            wpneo_crowdfunding_update_option_checkbox('hide_cf_campaign_from_shop_page', $hide_cf_campaign_from_shop_page);

            $wpneo_single_page_id = sanitize_text_field(wpneo_post('wpneo_single_page_id'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_single_page_id', $wpneo_single_page_id);

            $hide_cf_address_from_checkout = sanitize_text_field(wpneo_post('hide_cf_address_from_checkout'));
            wpneo_crowdfunding_update_option_checkbox('hide_cf_address_from_checkout', $hide_cf_address_from_checkout);

            $wpneo_listing_page_id = intval(sanitize_text_field(wpneo_post('wpneo_listing_page_id')));
            wpneo_crowdfunding_update_option_text('wpneo_listing_page_id', $wpneo_listing_page_id);

            $wpneo_form_page_id = intval(sanitize_text_field(wpneo_post('wpneo_form_page_id')));
            wpneo_crowdfunding_update_option_text('wpneo_form_page_id', $wpneo_form_page_id);

            $wpneo_registration_page_id = intval(sanitize_text_field(wpneo_post('wpneo_registration_page_id')));
            wpneo_crowdfunding_update_option_text('wpneo_registration_page_id', $wpneo_registration_page_id);

	        $seperate_crowdfunding_categories = sanitize_text_field(wpneo_post('seperate_crowdfunding_categories'));
	        wpneo_crowdfunding_update_option_checkbox('seperate_crowdfunding_categories', $seperate_crowdfunding_categories);

	        $wpneo_cf_selected_theme = sanitize_text_field(wpneo_post('wpneo_cf_selected_theme'));
            wpneo_crowdfunding_update_option_text('wpneo_cf_selected_theme', $wpneo_cf_selected_theme);

            $wpneo_requirement_title = sanitize_text_field(wpneo_post('wpneo_requirement_title'));
            wpneo_crowdfunding_update_option_text('wpneo_requirement_title', $wpneo_requirement_title);

            $wpneo_requirement_text = sanitize_text_field(wpneo_post('wpneo_requirement_text'));
            wpneo_crowdfunding_update_option_text('wpneo_requirement_text', $wpneo_requirement_text);

            $wpneo_requirement_agree_title = sanitize_text_field(wpneo_post('wpneo_requirement_agree_title'));
            wpneo_crowdfunding_update_option_text('wpneo_requirement_agree_title', $wpneo_requirement_agree_title);

            $wpneo_crowdfunding_add_to_cart_redirect = sanitize_text_field(wpneo_post('wpneo_crowdfunding_add_to_cart_redirect'));
            wpneo_crowdfunding_update_option_text('wpneo_crowdfunding_add_to_cart_redirect', $wpneo_crowdfunding_add_to_cart_redirect);

            $number_of_collumn_in_row = intval(wpneo_post('number_of_collumn_in_row'));
            wpneo_crowdfunding_update_option_text('number_of_collumn_in_row', $number_of_collumn_in_row);

            $number_of_words_show_in_listing_description = intval(wpneo_post('number_of_words_show_in_listing_description'));
            wpneo_crowdfunding_update_option_text('number_of_words_show_in_listing_description', $number_of_words_show_in_listing_description);

            $wpneo_show_rating = sanitize_text_field(wpneo_post('wpneo_show_rating'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_show_rating', $wpneo_show_rating);

            //Load single campaign to WooCommerce or not
            $wpneo_single_page_template = sanitize_text_field(wpneo_post('wpneo_single_page_template'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_single_page_template', $wpneo_single_page_template);

            $wpneo_single_page_reward_design = intval(wpneo_post('wpneo_single_page_reward_design'));
            wpneo_crowdfunding_update_option_text('wpneo_single_page_reward_design', $wpneo_single_page_reward_design);

            $reward_fixed_price = sanitize_text_field(wpneo_post('wpneo_reward_fixed_price'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_reward_fixed_price', $reward_fixed_price);

	        $wpcf_enable_tax = sanitize_text_field(wpneo_post('wpcf_enable_tax'));
	        wpneo_crowdfunding_update_option_checkbox('wpcf_enable_tax', $wpcf_enable_tax);
        }

        /**
         * Style Settings
         */
        if ( sanitize_text_field(wpneo_post('wpneo_crowdfunding_admin_tab')) == 'tab_style' ){

            $wpneo_enable_color_styling = sanitize_text_field(wpneo_post('wpneo_enable_color_styling'));
            wpneo_crowdfunding_update_option_checkbox('wpneo_enable_color_styling', $wpneo_enable_color_styling);

            $wpneo_color_scheme = sanitize_text_field(wpneo_post('wpneo_color_scheme'));
            wpneo_crowdfunding_update_option_text('wpneo_color_scheme', $wpneo_color_scheme);

            $wpneo_button_bg_color = sanitize_text_field(wpneo_post('wpneo_button_bg_color'));
            wpneo_crowdfunding_update_option_text('wpneo_button_bg_color', $wpneo_button_bg_color);

            $wpneo_button_bg_hover_color = sanitize_text_field(wpneo_post('wpneo_button_bg_hover_color'));
            wpneo_crowdfunding_update_option_text('wpneo_button_bg_hover_color', $wpneo_button_bg_hover_color);

            $wpneo_button_text_color = sanitize_text_field(wpneo_post('wpneo_button_text_color'));
            wpneo_crowdfunding_update_option_text('wpneo_button_text_color', $wpneo_button_text_color);

            $wpneo_button_text_hover_color = sanitize_text_field(wpneo_post('wpneo_button_text_hover_color'));
            wpneo_crowdfunding_update_option_text('wpneo_button_text_hover_color', $wpneo_button_text_hover_color);

            $wpneo_custom_css = wpneo_post( 'wpneo_custom_css' );
            wpneo_crowdfunding_update_option_text( 'wpneo_custom_css', $wpneo_custom_css );
        }
    }
}


function neo_crowdfunding_go_premium(){
    $html = '';
    $html .= '<div class="wpneo-premium">';
    $html .= '<h2><span class="wpneo-highlight">WP Crowdfunding</span> <br>Take your crowdfunding <br>site to next level!</h2>';
    $html .= '<iframe width="560" height="315" src="https://www.youtube.com/embed/jHJBV2MbgBw" frameborder="0" allowfullscreen></iframe>';
    $html .= '<p>Try before you buy, here is the try <a href="http://try.themeum.com/plugins/wp-crowdfunding/" target="_blank">demo</a>. WP Crowdfunding premium feature list.</p>';
    $html .= '<ul>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>Unlimited Rewards</span> - You can add unlimited rewards in campaigns.</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>PayPal Adaptive Payment</span> - PayPal Split Payment (chained and parallel) for crowdfunding campaigns.</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>Native Wallet</span> - Crowdfunding Enterprise comes with the native wallet system.</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>Authorize.net (AIM) Support</span> - Crowdfunding Enterprise comes with the Authorize.net addons.</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>Stripe Connect</span> -  Stripe Split Payment for crowdfunding campaigns.</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>Google reCAPTCHA</span> - Helps you prevent spamming in user campaign submission, registration and login.</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>Email Notifications</span> - Send emails on campaign submission, campaign approval, new backing and user registration.</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>Analytical Reports</span> - Generate crowdfunding sales report and top campaign lists.</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>Social Share</span> - Share the campaigns on popular social media.</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>Native Wallet System</span> - An alternate way of PayPal And Stripe Connect to spilt raised amount between campaign creator and website admin .</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>1 Year Support</span> - Dedicated support for any issue.</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>1 Year Update</span> - You can get immidiate fixes and regular updates.</li>';
    $html .= '<li class="dashicons-before dashicons-yes"><span>1 Free Theme</span> - You will get a free WordPress theme, check the <a href="http://demo.themeum.com/wordpress/crowdfunding-theme/" target="_blank">demo theme</a>.</li>';
    $html .= '</ul>';


    $html .= '<a target="_blank" href="https://www.themeum.com/product/wp-crowdfunding-plugin/" class="wpneo-buynow">Buy WP Crowdfunding Premium Now >></a>';
    $html .= '</div>';

    echo $html;
}

/**
 * Neo Crowdfunding Custom Styling Option
 */
add_action( 'wp_head','wpneo_custom_css' );
function wpneo_custom_css(){

    if( 'true' == get_option('wpneo_enable_color_styling') ){
        $color_scheme       = get_option( 'wpneo_color_scheme' );
        $button_bg          = get_option( 'wpneo_button_bg_color' );
        $button_bg_hover    = get_option( 'wpneo_button_bg_hover_color' );
        $button_text_color  = get_option( 'wpneo_button_text_color' );
        $text_hover_color   = get_option( 'wpneo_button_text_hover_color' );
        $custom_css         = get_option( 'wpneo_custom_css' );

        $style = '';

        if( $button_bg ){
            $style .= '.wpneo_donate_button, 
                        #wpneo-tab-reviews .submit,
                        .wpneo-edit-btn,
                        .wpneo-image-upload.float-right,
                        .wpneo-image-upload-btn,
                        .wpneo-save-btn,
                        #wpneo_active_edit_form,
                        .removeCampaignRewards,
                        #addreward,
                        .btn-style1,
                        #addcampaignupdate,
                        .wpneo-profile-button,
                        .dashboard-btn-link,
                        .wpneo_login_form_div #wp-submit,
                        .wpneo-submit-campaign,
                        input[type="button"].wpneo-image-upload,
                        input[type="button"]#search-submit,
                        #addreward,input[type="submit"].wpneo-submit-campaign,
                        .dashboard-btn-link,.label-primary,
                        .btn-style1,#wpneo-tab-reviews .submit,.dashboard-head-date input[type="submit"],
                        .wp-crowd-btn-primary, .wpneo_withdraw_button,.wpneo-dashboard-head-left ul li.active,
                        .wpneo-pagination ul li a:hover, .wpneo-pagination ul li span.current{ background-color:'.$button_bg.'; color:'.$button_text_color.'; }';

            $style .= '.wpneo_donate_button:hover, 
                        #wpneo-tab-reviews .submit:hover,
                        .wpneo-edit-btn:hover,
                        .wpneo-image-upload.float-right:hover,
                        .wpneo-image-upload-btn:hover,
                        .wpneo-save-btn:hover,
                        .removeCampaignRewards:hover,
                        #addreward:hover,
                        .removecampaignupdate:hover,
                        .btn-style1:hover,
                        #addcampaignupdate:hover,
                        #wpneo_active_edit_form:hover,
                        .removecampaignupdate:hover,
                        .wpneo-profile-button:hover,
                        .dashboard-btn-link:hover,
                        .wpneo_login_form_div #wp-submit:hover,
                        .wpneo-submit-campaign:hover,
                        .wpneo_donate_button:hover,.dashboard-head-date input[type="submit"]:hover,
                        .wp-crowd-btn-primary:hover,
                        .wpneo_withdraw_button:hover{ background-color:'.$button_bg_hover.'; color:'.$text_hover_color.'; }';
        }

        if( $color_scheme ){
            $style .=  '#neo-progressbar > div,
                        ul.wpneo-crowdfunding-update li:hover span.round-circle,
                        .wpneo-links li a:hover, .wpneo-links li.active a,#neo-progressbar > div {
                            background-color: '.$color_scheme.';
                        }
                        .wpneo-dashboard-summary ul li.active {
                            background: '.$color_scheme.';
                        }
                        .wpneo-tabs-menu li.wpneo-current {
                            border-bottom: 3px solid '.$color_scheme.';
                        }
                        .wpneo-pagination ul li a:hover,
                        .wpneo-pagination ul li span.current {
                            border: 2px solid '.$color_scheme.';
                        }
                        .wpneo-dashboard-summary ul li.active:after {
                            border-color: '.$color_scheme.' rgba(0, 128, 0, 0) rgba(255, 255, 0, 0) rgba(0, 0, 0, 0);
                        }
                        .wpneo-fields input[type="email"]:focus,
                        .wpneo-fields input[type="text"]:focus,
                        .wpneo-fields select:focus,
                        .wpneo-fields textarea {
                            border-color: '.$color_scheme.';
                        }
                        .wpneo-link-style1,
                        ul.wpneo-crowdfunding-update li .wpneo-crowdfunding-update-title,
                        .wpneo-fields-action span a:hover,.wpneo-name > p,
                        .wpneo-listings-dashboard .wpneo-listing-content h4 a,
                        .wpneo-listings-dashboard .wpneo-listing-content .wpneo-author a,
                        .wpcf-order-view,#wpneo_crowdfunding_modal_message td a,
                        .dashboard-price-number,.wpcrowd-listing-content .wpcrowd-admin-title h3 a,
                        .campaign-listing-page .stripe-table a,.stripe-table  a.label-default:hover,
                        a.wpneo-fund-modal-btn.wpneo-link-style1,.wpneo-tabs-menu li.wpneo-current a,
                        .wpneo-links div a:hover, .wpneo-links div.active a{
                            color: '.$color_scheme.';
                        }
                        .wpneo-links div a:hover .wpcrowd-arrow-down, .wpneo-links div.active a .wpcrowd-arrow-down {
                            border: solid '.$color_scheme.';
                            border-width: 0 2px 2px 0;
                        }
                        .wpneo-listings-dashboard .wpneo-listing-content h4 a:hover,
                        .wpneo-listings-dashboard .wpneo-listing-content .wpneo-author a:hover,
                        #wpneo_crowdfunding_modal_message td a:hover{
                            color: rgba('.$color_scheme.','.$color_scheme.','.$color_scheme.',0.95);
                        }';

            list($r, $g, $b) = sscanf( $color_scheme, "#%02x%02x%02x" );
            $style .=  '.tab-rewards-wrapper .overlay { background: rgba('.$r.','.$g.','.$b.',.95); }';
        }

        if( $custom_css ){ $style .= $custom_css; }

        $output = '<style type="text/css"> '.$style.' </style>';
        echo $output;
    }
}

/**
 * Neo Crowdfunding option page menu
 */
function neo_crowdfunding_register_menu_page(){
	add_menu_page( 'Crowdfunding','Crowdfunding','manage_options','wpneo-crowdfunding','','dashicons-admin-multisite', null );
	add_submenu_page(
		'wpneo-crowdfunding',
		__('Settings', 'wp-crowdfunding'),
		__('Settings', 'wp-crowdfunding'),
		'manage_options',
		'wpneo-crowdfunding',
		'neo_crowdfunding_menu_page'
	);

    $adaptive = WPCF_DIR_PATH.'addons/paypal-adaptive/classes/class-wpneo-adaptive-payment-initiate.php';
    if (WPCF_TYPE == 'free'){
        add_submenu_page( 'wpneo-crowdfunding', __( 'Go Premium', 'wp-crowdfunding' ), __( 'Go Premium <span class="dashicons dashicons-star-filled"></span>', 'wp-crowdfunding' ), 'manage_options', 'wp-crowdfunding', 'neo_crowdfunding_go_premium' );
    }
}
add_action( 'admin_menu', 'neo_crowdfunding_register_menu_page' );