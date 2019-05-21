<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// #reCAPTCHA Settings (Tab Settings)
$arr =  array(
            // #Listing Page Seperator
            array(
                'type'      => 'seperator',
                'label'     => __('reCAPTCHA Settings','wp-crowdfunding'),
                'desc'      => __('You may enable reCAPTCHA to prevent spamming','wp-crowdfunding'),
                'top_line'  => 'true',
                ),

            // #Enable Recaptcha
            array(
                'id'        => 'wpneo_enable_recaptcha',
                'type'      => 'checkbox',
                'value'     => 'true',
                'label'     => __('Enable reCAPTCHA for Crowdfunding Plugin','wp-crowdfunding'),
                'desc'      => __('Enable/Disable','wp-crowdfunding'),
                ),

            // #Enable Recaptcha in User Registration
            array(
                'id'        => 'wpneo_enable_recaptcha_in_user_registration',
                'type'      => 'checkbox',
                'value'     => 'true',
                'label'     => __('Enable reCAPTCHA on user registration page','wp-crowdfunding'),
                'desc'      => __('Enable/Disable','wp-crowdfunding'),
                ),

            // #Enable Recaptcha in User Registration
            array(
                'id'        => 'wpneo_enable_recaptcha_campaign_submit_page',
                'type'      => 'checkbox',
                'value'     => 'true',
                'label'     => __('Enable reCAPTCHA on campaign submit page','wp-crowdfunding'),
                'desc'      => __('Enable/Disable','wp-crowdfunding'),
                ),

            // #Site key / Public Key
            array(
                'id'        => 'wpneo_recaptcha_site_key',
                'type'      => 'text',
                'value'     => '',
                'label'     => __('Site key / Public Key','wp-crowdfunding'),
                'desc'      => __('Put your Google reCAPTCHA Public key here. <a href="https://www.google.com/recaptcha/admin#list" target="_blank">Visit this link</a> to generate one.','wp-crowdfunding'),
                ),

            // #Secret Key
            array(
                'id'        => 'wpneo_recaptcha_secret_key',
                'type'      => 'text',
                'value'     => '',
                'label'     => __('Secret Key','wp-crowdfunding'),
                'desc'      => __('Put your Google reCAPTCHA Secret key here. <a href="https://www.google.com/recaptcha/admin#list" target="_blank">Visit this link</a> to generate one.','wp-crowdfunding'),
                ),

            // #Save Function
            array(
                'id'        => 'wpneo_recaptcha_activation',
                'type'      => 'hidden',
                'value'     => 'true',
                ),
);
echo wpcf_settings_generator( $arr );