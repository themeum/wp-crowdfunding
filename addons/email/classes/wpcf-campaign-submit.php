<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A custom Expedited Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class WPCF_Campaign_Submit extends WC_Email {

	protected $email_body;

	protected $sent_to_admin;
	protected $sent_to_user;
	protected $email_template;
	/**
	 * Set email defaults
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// set ID, this simply needs to be a unique name
		$this->id = 'wp_crowdfunding_submit_campaign';

		// this is the title in WooCommerce Email settings
		$this->title = 'WP CrowdFunding Submit Campaign';

		// this is the description in WooCommerce email settings
		$this->description = __('Get email notification when a campaign submitted by campaign owner', 'wp-crowdfunding');

		// these are the default heading and subject lines that can be overridden using the settings
		$this->heading = __('WP Crowdfunding Campaign Successfully submitted', 'wp-crowdfunding');
		$this->subject = __('WP Crowdfunding Campaign Successfully submitted', 'wp-crowdfunding');

		$this->email_body = $this->get_option('body');

		$this->email_template = 'submit-campaign.php';
		// these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
		$this->template_html  = $this->email_template;
		$this->template_plain = $this->email_template;

		// Triggers for accept campaign
		add_action( 'wpneo_crowdfunding_after_campaign_email', array( $this, 'trigger' )); // Published Campaign Action

		// Call parent constructor to load any other defaults not explicity defined here
		parent::__construct();

		// this sets the recipient to the settings defined below in init_form_fields()
		$this->recipient = $this->get_option( 'recipient' );
		$this->sent_to_admin = $this->get_option( 'sent_to_admin' );
		$this->sent_to_user = $this->get_option( 'sent_to_user' );

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
	public function trigger( $post_id) {
		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		$post_type      = get_post_type($post_id);
		if ( "product" != $post_type ){ return; }
		if ( wp_is_post_revision( $post_id ) ){ return; }
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){ return; }

		$email          = array();
		$product        = wc_get_product( $post_id );
		$author         = get_userdata( $product->post->post_author );
		$dislay_name    = $author->display_name;

		$campaign_title  = $product->post->post_title;
		$shortcode      = array( '[user_name]', '[campaign_title]' );
		$replace_str    = array( $dislay_name, $campaign_title );
		$str            = $this->get_content();
		$email_str      = str_replace( $shortcode, $replace_str, $str );
		$subject        = str_replace( $shortcode, $replace_str, $this->get_subject() );

		if ($this->sent_to_admin){
			$this->setup_locale();
			$this->send( $this->recipient, $subject, $email_str, $this->get_headers(), $this->get_attachments() );
			$this->restore_locale();
		}

		if ($this->sent_to_user){
			$backer_email_str      = str_replace($shortcode, $replace_str, $this->get_backer_content_html());
			$backer_subject        = str_replace($shortcode, $replace_str, $this->get_option('subject_for_campaign_owner'));

			$this->setup_locale();
			$this->send( $author->user_email, $backer_subject, $backer_email_str, $this->get_headers(), $this->get_attachments() );
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


	public function get_backer_content_html() {
		ob_start();
		$email_heading = $this->get_option('heading_for_campaign_owner');
		$email_body = $this->get_option('body_for_campaign_owner');
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
			'sent_to_admin'    => array(
				'title'   => __('Enable/Disable', 'wp-crowdfunding'),
				'type'    => 'checkbox',
				'label'   => __('Send Email to Admin', 'wp-crowdfunding'),
				'default' => 'no'
			),

			'sent_to_user'    => array(
				'title'   => __('Enable/Disable', 'wp-crowdfunding'),
				'type'    => 'checkbox',
				'label'   => __('Send Email Notification to Campaign Owner', 'wp-crowdfunding'),
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
				'default'     => __('A campaign has been submitted to your platform', 'wp-crowdfunding')
			),

			'subject_for_campaign_owner'    => array(
				'title'       => __('Subject For Campaign Owner', 'wp-crowdfunding'),
				'type'        => 'text',
				'description' => __('This controls the campaign owner notification email subject line.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('Congratulation, your campaign has been submitted', 'wp-crowdfunding')
			),
			'heading'    => array(
				'title'       => __('Email Heading', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main heading contained within the email notification.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('A campaign has been submitted to your platform', 'wp-crowdfunding')
			),
			'heading_for_campaign_owner'    => array(
				'title'       => __('Email Heading for Campaign Owner', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __( 'This controls the main heading contained within the campaign owner email notification.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('Congratulation, your campaign has been submitted', 'wp-crowdfunding')
			),
			'body'    => array(
				'title'       => __('Email Body', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main email body contained within the email notification. Leave blank to keep it null, <code> Params: ( [user_name], [campaign_title] ) </code>', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => ''
			),

			'body_for_campaign_owner'    => array(
				'title'       => __('Email Body For Campaign owner', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main email body contained within the campaign owner email notification. Leave blank to keep it null, <code> Params: ( [user_name], [campaign_title] ) </code>', 'wp-crowdfunding'),
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
