<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A custom Expedited Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class WPCF_New_User extends WC_Email {

	protected $email_body;

	protected $sent_to_admin;
	protected $sent_to_user;
	/**
	 * Set email defaults
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// set ID, this simply needs to be a unique name
		$this->id = 'wp_crowdfunding_new_user';

		// this is the title in WooCommerce Email settings
		$this->title = 'WP CrowdFunding New User';

		// this is the description in WooCommerce email settings
		$this->description = 'Expedited Order Notification emails are sent when a customer places an order with 3-day or next day shipping';

		// these are the default heading and subject lines that can be overridden using the settings
		$this->heading = 'WP Crowdfunding New User Registration [user_name]';
		$this->subject = 'WP Crowdfunding New User Registration';

		$this->email_body = $this->get_option('body');

		$this->email_template = 'new-user-registration.php';
		// these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
		$this->template_html  = $this->email_template;
		$this->template_plain = $this->email_template;

		// Trigger on new paid orders
		add_action( 'wpneo_crowdfunding_after_user_registration', array( $this, 'trigger' ) );

		// Call parent constructor to load any other defaults not explicity defined here
		parent::__construct();

		// this sets the recipient to the settings defined below in init_form_fields()
		$this->recipient = $this->get_option( 'recipient' );
		$this->sent_to_admin = $this->get_option( 'is_email_to_admin' );
		$this->sent_to_user = $this->get_option( 'is_email_to_user' );

		// if none was entered, just use the WP admin email as a fallback
		if ( ! $this->recipient ){
			$this->recipient = get_option( 'admin_email' );
		}

	}


	/**
	 * Determine if the email should actually be sent and setup email merge variables
	 *
	 * @since 0.1
	 * @param int $order_id
	 */
	public function trigger($user_id =  null) {
		$user = get_userdata($user_id);
		// replace variables in the subject/headings
		$this->find[] = '[user_name]';
		$this->replace[] = $user->user_nicename;

		if ( ! $this->is_enabled() )
			return;

		$author         = get_userdata( $user_id );
		$dislay_name    = $author->display_name;

		$shortcode      = array( '[user_name]' );
		$replace_str    = array( $dislay_name );

		if ($this->sent_to_admin){
			//Send the email now
			$this->send( $this->recipient, $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		}

		/**
		 * Send greetings to newly created user
		 */
		if ($this->sent_to_user){
			$user_email_str      = str_replace($shortcode, $replace_str, $this->get_content_html_for_user());
			$user_subject        = str_replace($shortcode, $replace_str, $this->get_option('subject_user'));

			$this->setup_locale();
			$this->send( $author->user_email, $user_subject, $user_email_str, $this->get_headers(), $this->get_attachments() );
			$this->restore_locale();
		}


	}

	/**
	 * get_content_html function.
	 *
	 * @since 0.1
	 * @return string
	 */
	public function get_content_html() {
		ob_start();
		$email_heading = $this->get_heading();
		$email_body = $this->email_body;
		wc_get_template( $this->template_html, array(
			'email_heading' => $email_heading,
			'email_body' 	=> $email_body,
			'plain_text'    => false
		) );
		return ob_get_clean();
	}


	public function get_content_html_for_user() {
		ob_start();
		$email_heading = $this->get_option('heading_user');
		$email_body = $this->get_option('body_user');
		wc_get_template( $this->template_html, array(
			'email_heading' => $email_heading,
			'email_body' 	=> $email_body,
			'plain_text'    => false
		) );
		return ob_get_clean();
	}

	/**
	 * get_content_plain function.
	 *
	 * @since 0.1
	 * @return string
	 */
	public function get_content_plain() {
		ob_start();
		$email_heading = $this->get_heading();
		$email_body = $this->email_body;
		wc_get_template( $this->template_plain, array(
			'email_heading' => $email_heading,
			'email_body' 	=> $email_body,
			'plain_text'    => false
		) );
		return ob_get_clean();
	}


	/**
	 * Initialize Settings Form Fields
	 *
	 * @since 2.0
	 */
	public function init_form_fields() {

		$this->form_fields = array(
			'enabled'    => array(
				'title'   => __('Enable/Disable', 'wp-crowdfunding'),
				'type'    => 'checkbox',
				'label'   => __('Enable this email notification', 'wp-crowdfunding'),
				'default' => 'yes'
			),
			'is_email_to_admin'    => array(
				'title'   => __('Enable/Disable', 'wp-crowdfunding'),
				'type'    => 'checkbox',
				'label'   => __('Send Email to Admin', 'wp-crowdfunding'),
				'default' => 'no'
			),
			'is_email_to_user'    => array(
				'title'   => __('Enable/Disable', 'wp-crowdfunding'),
				'type'    => 'checkbox',
				'label'   => __('Send Email Greetings To User', 'wp-crowdfunding'),
				'default' => 'no'
			),
			'recipient'  => array(
				'title'       => __('Recipient(s)', 'wp-crowdfunding'),
				'type'        => 'text',
				'description' => sprintf( __('Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', 'wp-crowdfunding'), esc_attr( get_option( 'admin_email' ) ) ),
				'placeholder' => '',
				'default'     => ''
			),
			'subject'    => array(
				'title'       => __('Subject', 'wp-crowdfunding'),
				'type'        => 'text',
				'description' => __('This controls the email subject line.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('WP Crowdfunding New User Registration', 'wp-crowdfunding')
			),
			'heading'    => array(
				'title'       => __('Email Heading', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __( 'This controls the main heading contained within the email notification.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('WP Crowdfunding New User Registration [user_name]', 'wp-crowdfunding')
			),
			'body'    => array(
				'title'       => __('Email Body', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main email body contained within the email notification. Leave blank to keep it null', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => ''
			),
			'subject_user'    => array(
				'title'       => __('Subject for User', 'wp-crowdfunding'),
				'type'        => 'text',
				'description' => __('This controls the email subject line.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('Thank you for registration', 'wp-crowdfunding')
			),
			'heading_user'    => array(
				'title'       => __('Email Heading for User', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main heading contained within the email notification.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('Thank you for registration [user_name]', 'wp-crowdfunding')
			),
			'body_user'    => array(
				'title'       => __('Email Body for User', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main email body contained within the email notification. Leave blank to keep it null', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => ''
			),

			'email_type' => array(
				'title'         => __( 'Email type', 'woocommerce' ),
				'type'          => 'select',
				'description'   => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'       => 'html',
				'class'         => 'email_type wc-enhanced-select',
				'options'       => $this->get_email_type_options(),
				'desc_tip'      => true,
			),

		);
	}


} // end \WC_Expedited_Order_Email class
