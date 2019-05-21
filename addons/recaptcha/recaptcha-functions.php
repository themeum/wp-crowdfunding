<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Adding reCAPTCHA before user registration
add_action('wpneo_before_user_registration_action', 'wpneo_before_user_registration_action');
add_action('wpneo_crowdfunding_before_campaign_submit_action', 'wpneo_before_user_campaign_submit_action');

if ( ! function_exists('wpneo_before_user_registration_action')) {
	function wpneo_before_user_registration_action() {
		if (get_option('wpneo_enable_recaptcha_in_user_registration') == 'true') {
			if (function_exists('wpneo_checking_recaptcha_api')){
				wpneo_checking_recaptcha_api();
			}
		}
	}
}

if ( ! function_exists('wpneo_before_user_campaign_submit_action')) {
	function wpneo_before_user_campaign_submit_action() {
		if (get_option('wpneo_enable_recaptcha_campaign_submit_page') == 'true') {
			if (function_exists('wpneo_checking_recaptcha_api')){
				wpneo_checking_recaptcha_api();
			}
		}
	}
}

/**
 * Checking recaptcha through api
 */
if ( ! function_exists('wpneo_checking_recaptcha_api')) {
	function wpneo_checking_recaptcha_api(){
		if (get_option('wpneo_enable_recaptcha') == 'true') {
			$recaptcha = (object)array('success' => false);
			if ( isset($_POST['g-recaptcha-response'])) {
				$secret_key = get_option('wpneo_recaptcha_secret_key');

				$recaptcha_response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
					'method' => 'POST',
					'body' => array( 'secret' => $secret_key, 'response' => sanitize_text_field($_POST['g-recaptcha-response']) ),
				));
				$recaptcha = json_decode($recaptcha_response['body']);
			}
			if (!$recaptcha->success) {
				die(json_encode(array('success'=> 0, 'message' => __('Error with reCAPTCHA, please check again', 'wp-crowdfunding'))));
			}
		}
	}
}