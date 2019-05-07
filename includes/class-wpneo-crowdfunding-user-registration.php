<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('Wpneo_Crowdfunding_User_Registration')) {

    class Wpneo_Crowdfunding_User_Registration{

        /**
         * @var null
         *
         * Instance of this class
         */
        protected static $_instance = null;

        /**
         * @return null|Wpneo_Crowdfunding
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Wpneo_Crowdfunding constructor.
         *
         * @hook
        */
        public function __construct() {
            add_action( 'init',                                             array($this, 'wpneo_registration_function') );
            add_action( 'wp_ajax_wpneo_crowdfunding_registration_action',   array($this, 'wpneo_registration_function') );
        }

        // register a new user
        function wpneo_registration_function() {

            if( wp_verify_nonce(wpneo_post('_wpnonce'),'wpcf-nonce-registration') ){

                //Add some option
                do_action( 'wpcf_before_registration' );

                $username = $password = $email = $website = $first_name = $last_name = $nickname = $bio = '';
                // sanitize user form input
                $username   =   sanitize_user($_POST['username']);
                $password   =   sanitize_text_field($_POST['password']);
                $email      =   sanitize_email($_POST['email']);
                $website    =   esc_url_raw($_POST['website']);
                $first_name =   sanitize_text_field($_POST['fname']);
                $last_name  =   sanitize_text_field($_POST['lname']);
                $nickname   =   sanitize_text_field($_POST['nickname']);
                $bio        =   implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['bio'])));

                $this->wpcf_registration_validation( $username , $password , $email , $website , $first_name , $last_name , $nickname , $bio );
                $this->wpcf_complete_registration( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio );
            }else{
                global $reg_errors;
                $reg_errors = new WP_Error;
                $reg_errors->add('security', __('Security Error','wp-crowdfunding'));
            }
        }

        function wpcf_complete_registration( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio ) {
            global $reg_errors;
            if ( count($reg_errors->get_error_messages()) < 1 ) {
                $userdata = array(
                    'user_login'    =>  $username,
                    'user_email'    =>  $email,
                    'user_pass'     =>  $password,
                    'user_url'      =>  $website,
                    'first_name'    =>  $first_name,
                    'last_name'     =>  $last_name,
                    'nickname'      =>  $nickname,
                    'description'   =>  $bio
                );
                $user_id = wp_insert_user( $userdata );

                //On success
                if ( ! is_wp_error( $user_id ) ) {
	                WC()->mailer(); // load email classes
                    do_action( 'wpcf_after_registration', $user_id );

                    $saved_redirect_uri = get_option('wpcf_user_reg_success_redirect_uri');
                    $redirect = $saved_redirect_uri ? $saved_redirect_uri : esc_url( home_url( '/' ) );
                    die(json_encode(array('success'=> 1, 'message' => __('Registration complete.', 'wp-crowdfunding'), 'redirect' => $redirect )));
                } else {
                    $errors = '';
                    if ( is_wp_error( $reg_errors ) ) {
                        foreach ( $reg_errors->get_error_messages() as $error ) {
                            $errors .= '<strong>'.__('ERROR','wp-crowdfunding').'</strong>:'.$error.'<br />';
                        }
                    }
                    die(json_encode(array('success'=> 0, 'message' => $errors )));
                }
            } else {
                $errors = '';
                if ( is_wp_error( $reg_errors ) ) {
                    foreach ( $reg_errors->get_error_messages() as $error ) {
                        $errors .= '<strong>'.__('ERROR','wp-crowdfunding').'</strong>:'.$error.'<br />';
                    }
                }
                die(json_encode(array('success'=> 0, 'message' => $errors )));
            }
        }

        function wpcf_registration_validation( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio )  {
            global $reg_errors;
            $reg_errors = new WP_Error;

            if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
                $reg_errors->add('field', __('Required form field is missing','wp-crowdfunding'));
            }

            if ( strlen( $username ) < 4 ) {
                $reg_errors->add('username_length', __('Username too short. At least 4 characters is required','wp-crowdfunding'));
            }

            if ( username_exists( $username ) )
                $reg_errors->add('user_name', __('Sorry, that username already exists!','wp-crowdfunding'));

            if ( !validate_username( $username ) ) {
                $reg_errors->add('username_invalid', __('Sorry, the username you entered is not valid','wp-crowdfunding'));
            }

            if ( strlen( $password ) < 6 ) {
                $reg_errors->add('password', __('Password length must be greater than 6','wp-crowdfunding'));
            }

            if ( !is_email( $email ) ) {
                $reg_errors->add('email_invalid', __('Email is not valid','wp-crowdfunding'));
            }

            if ( email_exists( $email ) ) {
                $reg_errors->add('email', __('Email Already in use','wp-crowdfunding'));
            }

            if ( !empty( $website ) ) {
                if ( !filter_var($website, FILTER_VALIDATE_URL) ) {
                    $reg_errors->add('website', __('Website is not a valid URL','wp-crowdfunding'));
                }
            }

        }

    }
}
//Call base class
Wpneo_Crowdfunding_User_Registration::instance();
