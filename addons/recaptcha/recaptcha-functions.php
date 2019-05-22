<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Adding reCAPTCHA before user registration
add_action('wpneo_before_user_registration_action', 'wpcf_before_user_registration_action');
add_action('wpcf_crowdfunding_before_campaign_submit_action', 'wpcf_before_user_campaign_submit_action');

if ( ! function_exists('wpcf_before_user_registration_action')) {
	function wpcf_before_user_registration_action() {
		if (get_option('wpneo_enable_recaptcha_in_user_registration') == 'true') {
			if (function_exists('wpcf_checking_recaptcha_api')){
				wpcf_checking_recaptcha_api();
			}
		}
	}
}

if ( ! function_exists('wpcf_before_user_campaign_submit_action')) {
	function wpcf_before_user_campaign_submit_action() {
		if (get_option('wpneo_enable_recaptcha_campaign_submit_page') == 'true') {
			if (function_exists('wpcf_checking_recaptcha_api')){
				wpcf_checking_recaptcha_api();
			}
		}
	}
}

/**
 * Checking recaptcha through api
 */
if ( ! function_exists('wpcf_checking_recaptcha_api')) {
	function wpcf_checking_recaptcha_api(){
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