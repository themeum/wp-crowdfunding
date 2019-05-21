<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A custom Expedited Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class WPCF_Campaign_Accept extends WC_Email {

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


		// Triggers for accept campaign
		add_action( 'pre_post_update', array( $this, 'trigger' ), 999, 2 ); // Published Campaign Action


		// set ID, this simply needs to be a unique name
		$this->id = 'wp_crowdfunding_campaign_accept';

		// this is the title in WooCommerce Email settings
		$this->title = 'WP CrowdFunding Campaign Accept';

		// this is the description in WooCommerce email settings
		$this->description = __('Get email notification when a campaign accept by the platform owner', 'wp-crowdfunding');

		// these are the default heading and subject lines that can be overridden using the settings
		$this->heading = __('Congratulation, your campaign has been accepted', 'wp-crowdfunding');
		$this->subject = __('Congratulation, your campaign has been accepted', 'wp-crowdfunding');

		$this->email_body = $this->get_option('body');

		$this->email_template = 'campaign-accepted.php';
		// these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
		$this->template_html  = $this->email_template;
		$this->template_plain = $this->email_template;


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
	public function trigger( $ID, $post  ) {
		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		//Check if this is a product
		$product = wc_get_product( $ID );
		if ( ! $product){
			return;
		}

		//Don't send if product is not crowdfunding campaign
		if (!$product->is_type('crowdfunding')){
			return;
		}

		//Send email only if product is draft and changing to publish
		if (empty($post['post_status']) || $post['post_status'] !== 'publish' ||  $product->get_status() !== get_option('wpneo_default_campaign_status') ) {
			return;
		}
		
		$author         = get_userdata( $product->post->post_author );
		$dislay_name    = $author->display_name;

		$campaign_title  = $product->post->post_title;
		$campaign_link  = $product->post->guid;
		$campaign_short_link  =  site_url( '?p='.$ID );
		$shortcode      = array( '[user_name]', '[campaign_title]', '[campaign_link]', '[campaign_short_link]' );
		$replace_str    = array( $dislay_name, $campaign_title, $campaign_link, $campaign_short_link );

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

			'is_email_to_admin'    => array(
				'title'   => __('Enable/Disable', 'wp-crowdfunding'),
				'type'    => 'checkbox',
				'label'   => __('Send Email to Admin', 'wp-crowdfunding'),
				'default' => 'no'
			),

			'is_email_to_user'    => array(
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
				'default'     => __('WP Crowdfunding You have accepted a campaign', 'wp-crowdfunding')
			),

			'subject_for_campaign_owner'    => array(
				'title'       => __('Subject For Campaign Owner', 'wp-crowdfunding'),
				'type'        => 'text',
				'description' => __('This controls the campaign owner notification email subject line.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('Congratulation, your campaign has been approved', 'wp-crowdfunding')
			),
			'heading'    => array(
				'title'       => __('Email Heading', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main heading contained within the email notification.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('WP Crowdfunding You have accepted a campaign', 'wp-crowdfunding')
			),
			'heading_for_campaign_owner'    => array(
				'title'       => __('Email Heading for Campaign Owner', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main heading contained within the campaign owner email notification.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('Congratulation, your campaign has been approved', 'wp-crowdfunding')
			),

			'body'    => array(
				'title'       => __('Email Body', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main email body contained within the email notification. Leave blank to keep it null, <code> Params: ( [user_name], [campaign_title], [campaign_link], [campaign_short_link] ) </code>', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => ''
			),

			'body_for_campaign_owner'    => array(
				'title'       => __('Email Body For Campaign owner', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main email body contained within the campaign owner email notification. Leave blank to keep it null', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => ''
			),


			'email_type' => array(
				'title'         => __( 'Email type', 'woocommerce' ),
				'type'          => 'select',
				'description'   => __('Choose which format of email to send.', 'woocommerce' ),
				'default'       => 'html',
				'class'         => 'email_type wc-enhanced-select',
				'options'       => $this->get_email_type_options(),
				'desc_tip'      => true,
			),
		);
	}


} // end \WC_Expedited_Order_Email class
