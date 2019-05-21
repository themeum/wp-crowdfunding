<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A custom Expedited Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class WPCF_New_Backed extends WC_Email {

	protected $email_body;

	protected $sent_to_campaign_owner;
	protected $sent_to_backer;
	protected $email_template;
	/**
	 * Set email defaults
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// set ID, this simply needs to be a unique name
		$this->id = 'wp_crowdfunding_new_backed';

		// this is the title in WooCommerce Email settings
		$this->title = 'WP CrowdFunding New Backed Notification';

		// this is the description in WooCommerce email settings
		$this->description = 'Expedited Backed Notification emails are sent when a customer places an order';

		// these are the default heading and subject lines that can be overridden using the settings
		$this->heading = 'WP Crowdfunding (A new backed has been placed)';
		$this->subject = 'WP Crowdfunding (A new backed has been placed)';

		$this->email_body = $this->get_option('body');

		$this->email_template = 'new-backed.php';
		// these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
		$this->template_html  = $this->email_template;
		$this->template_plain = $this->email_template;
		
		// Triggers for this email
		add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'trigger' ), 10, 2 );
		add_action( 'woocommerce_order_status_pending_to_completed_notification', array( $this, 'trigger' ), 10, 2 );
		add_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $this, 'trigger' ), 10, 2 );
		add_action( 'woocommerce_order_status_failed_to_processing_notification', array( $this, 'trigger' ), 10, 2 );
		add_action( 'woocommerce_order_status_failed_to_completed_notification', array( $this, 'trigger' ), 10, 2 );
		add_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $this, 'trigger' ), 10, 2 );
		add_action( 'woocommerce_order_status_cancelled_to_processing_notification', array( $this, 'trigger' ), 10, 2 );
		add_action( 'woocommerce_order_status_cancelled_to_completed_notification', array( $this, 'trigger' ), 10, 2 );
		add_action( 'woocommerce_order_status_cancelled_to_on-hold_notification', array( $this, 'trigger' ), 10, 2 );

		// Call parent constructor to load any other defaults not explicity defined here
		parent::__construct();

		// this sets the recipient to the settings defined below in init_form_fields()
		$this->recipient = $this->get_option( 'recipient' );
		$this->sent_to_campaign_owner = $this->get_option( 'sent_to_campaign_owner' );
		$this->sent_to_backer = $this->get_option( 'sent_to_backer' );

		// if none was entered, just use the WP admin email as a fallback
		if ( !$this->recipient ) {
			$this->recipient = get_option( 'admin_email' );
		}

	}


	/**
	 * Determine if the email should actually be sent and setup email merge variables
	 *
	 * @since 0.1
	 * @param int $order_id
	 */
	public function trigger( $order_id, $order = false ) {
		if ( ! $this->is_enabled() ) {
			return;
		}
		global $wpdb;

		if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
			$order = wc_get_order( $order_id );
		}

		$line_items     = $order->get_items('line_item');
		$ids            = array();
		foreach ($line_items as $item_id => $item) {
			$product_id = $wpdb->get_var("select meta_value from {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = {$item_id} AND meta_key = '_product_id'");
			$ids[]      = $product_id;
		}
		$product        = wc_get_product($product_id);

		if ($product->is_type('crowdfunding') ) {

			$author         = get_userdata($product->post->post_author);
			$dislay_name    = $author->display_name;

			$total_amount   = get_woocommerce_currency_symbol() . $order->get_total();
			$campaign_title  = $product->post->post_title;
			$order_number  = $order->get_order_number();
			$order_date  = $order->get_date_created();
			$shortcode      = array('[user_name]', '[site_title]', '[total_amount]', '[campaign_title]', '[order_number]', '[order_date]');
			$replace_str    = array($dislay_name, get_option('blogname'), $total_amount, $campaign_title, $order_number, $order_date);


			$email_str      = str_replace($shortcode, $replace_str, $this->get_content());
			$subject        = str_replace($shortcode, $replace_str, $this->get_subject());


			if ($this->sent_to_campaign_owner){
				$this->setup_locale();
				$this->send( $author->user_email, $subject, $email_str, $this->get_headers(), $this->get_attachments() );
				$this->restore_locale();
			}

			if ($this->sent_to_backer){
				$backer_email_str      = str_replace($shortcode, $replace_str, $this->get_backer_content_html());
				$backer_subject        = str_replace($shortcode, $replace_str, $this->get_option('subject_for_backer'));

				$this->setup_locale();
				$this->send( $order->get_billing_email(), $backer_subject, $backer_email_str, $this->get_headers(), $this->get_attachments() );
				$this->restore_locale();
			}
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
		$email_heading = $this->get_option('heading_for_backer');
		$email_body = $this->get_option('body_for_backer');
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

			'sent_to_campaign_owner'    => array(
				'title'   => __('Enable/Disable', 'wp-crowdfunding'),
				'type'    => 'checkbox',
				'label'   => __('Notify to Campaign Owner', 'wp-crowdfunding'),
				'default' => 'no'
			),

			'sent_to_backer'    => array(
				'title'   => __('Enable/Disable', 'wp-crowdfunding'),
				'type'    => 'checkbox',
				'label'   => __('Notify to backer', 'wp-crowdfunding'),
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
				'default'     => __('WP Crowdfunding (A new backed has been placed)', 'wp-crowdfunding')
			),

			'subject_for_backer'    => array(
				'title'       => __('Subject For Backer', 'wp-crowdfunding'),
				'type'        => 'text',
				'description' => __('This controls the backer notification email subject line.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('Thank you for your donation', 'wp-crowdfunding')
			),
			'heading'    => array(
				'title'       => __('Email Heading', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __( 'This controls the main heading contained within the email notification.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('WP Crowdfunding (A new backed has been placed)', 'wp-crowdfunding')
			),
			'heading_for_backer'    => array(
				'title'       => __('Email Backer Heading', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __( 'This controls the main heading contained within the backer email notification.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('Thank you for your donation', 'wp-crowdfunding')
			),
			'body'    => array(
				'title'       => __('Email Body', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main email body contained within the email notification. Leave blank to keep it null, <code> Params: ( [user_name], [site_title], [total_amount], [campaign_title], [order_number], [order_date] ) </code>', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => ''
			),
			'body_for_backer'    => array(
				'title'       => __('Email Body For backer', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main email body contained within the backer email notification. Leave blank to keep it null', 'wp-crowdfunding'),
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
