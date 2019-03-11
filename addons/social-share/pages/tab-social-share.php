<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


// #Social Share Settings (Tab Settings)
$arr =  array(
            // #Listing Page Seperator
            array(
                'type'      => 'seperator',
                'label'     => __('Social Share Settings','wp-crowdfunding'),
                'desc'      => __('You may enable social share to share post.','wp-crowdfunding'),
                'top_line'  => 'true',
                ),

            // #Enable Social Share
            array(
                'id'        => 'wpneo_enable_social_share',
                'type'      => 'checkbox',
                'value'     => 'true',
                'label'     => __('Enable Social Share','wp-crowdfunding'),
                'desc'      => __('Enable Social Share for crowdfunding Plugin.','wp-crowdfunding'),
                ),

            // #Enable Twitter
            array(
                'id'        => 'wpneo_twitter_social_share',
                'type'      => 'checkbox',
                'value'     => 'true',
                'label'     => __('Enable Twitter','wp-crowdfunding'),
                'desc'      => __('Enable Twitter for crowdfunding plugin.','wp-crowdfunding'),
                ),

            // #Twitter Via
            array(
                'id'        => 'wpneo_twitter_via',
                'type'      => 'text',
                'value'     => '',
                'label'     => __('Twitter Via','wp-crowdfunding'),
                'desc'      => __('Put your twitter via key here.','wp-crowdfunding'),
                ),

            // #Enable Facebook
            array(
                'id'        => 'wpneo_facebook_social_share',
                'type'      => 'checkbox',
                'value'     => 'true',
                'label'     => __('Enable Facebook','wp-crowdfunding'),
                'desc'      => __('Enable Facebook for crowdfunding plugin.','wp-crowdfunding'),
                ),

            // #Enable GooglePlus
            array(
                'id'        => 'wpneo_googleplus_social_share',
                'type'      => 'checkbox',
                'value'     => 'true',
                'label'     => __('Enable GooglePlus','wp-crowdfunding'),
                'desc'      => __('Enable GooglePlus for crowdfunding plugin.','wp-crowdfunding'),
                ),

            // #Enable Pinterest
            array(
                'id'        => 'wpneo_pinterest_social_share',
                'type'      => 'checkbox',
                'value'     => 'true',
                'label'     => __('Enable Pinterest','wp-crowdfunding'),
                'desc'      => __('Enable Pinterest for crowdfunding plugin.','wp-crowdfunding'),
                ),

            // #Enable Linkedin
            array(
                'id'        => 'wpneo_linkedin_social_share',
                'type'      => 'checkbox',
                'value'     => 'true',
                'label'     => __('Enable Linkedin','wp-crowdfunding'),
                'desc'      => __('Enable Linkedin for crowdfunding plugin.','wp-crowdfunding'),
                ),

            // #Linkedin Via
            array(
                'id'        => 'wpneo_linkedin_via',
                'type'      => 'text',
                'value'     => '',
                'label'     => __('Linkedin Via','wp-crowdfunding'),
                'desc'      => __('Put your Linkedin via key here.','wp-crowdfunding'),
                ),
            
            // #Enable Embed Option
            array(
                'id'        => 'wpneo_embed_social_share',
                'type'      => 'checkbox',
                'value'     => 'true',
                'label'     => __('Embed Option','wp-crowdfunding'),
                'desc'      => __('Embed Option in Single Campaign.','wp-crowdfunding'),
                ),

            // #Save Function
            array(
                'id'        => 'wpneo_varify_social_share',
                'type'      => 'hidden',
                'value'     => 'true',
                ),
);
echo wpneo_crowdfunding_settings_generate_field( $arr );
