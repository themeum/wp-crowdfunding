<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A custom Expedited Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class WPCF_Campaign_Update extends WC_Email {

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
		$this->id = 'wpn_crowdfunding_campaign_update_email';

		// this is the title in WooCommerce Email settings
		$this->title = 'WP CrowdFunding Campaign Update';

		// this is the description in WooCommerce email settings
		$this->description = __('Get email notification when a campaign is updated', 'wp-crowdfunding');

		// these are the default heading and subject lines that can be overridden using the settings
		$this->heading = __('WP Crowdfunding Campaign Update', 'wp-crowdfunding');
		$this->subject = __('WP Crowdfunding Campaign Update', 'wp-crowdfunding');

		$this->email_body = $this->get_option('body');

		$this->email_template = 'campaign-updated.php';
		// these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
		$this->template_html  = $this->email_template;
		$this->template_plain = $this->email_template;

		// Triggers for update campaign
		add_action( 'wpneo_crowdfunding_campaign_update_email', array( $this, 'trigger' )); // Published Campaign Action

		// Call parent constructor to load any other defaults not explicity defined here
		parent::__construct();

		// this sets the recipient to the settings defined below in init_form_fields()
		$this->recipient = $this->get_option( 'recipient' );
		$this->sent_to_admin = $this->get_option( 'is_email_to_admin' );
		$this->sent_to_user = $this->get_option( 'is_email_to_user' );

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
	public function trigger( $post_id ) {
		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		//Check if this is a product
		$product = wc_get_product( $post_id );
		if ( ! $product){
			return;
		}

		//Don't send if product is not crowdfunding campaign
		if (!$product->is_type('crowdfunding')){
			return;
		}
		$order_ids = WPNEOCF()->getCustomersByProduct( $post_id );

		if( $order_ids ) {

			global $wpdb;
			$order_ids = implode(',', $order_ids);

			$query ="SELECT meta_value as billing_email
				FROM
					{$wpdb->prefix}postmeta
				WHERE
					meta_key = '_billing_email' AND post_id IN ({$order_ids})
				GROUP BY
					billing_email;";
			$backer_emails = $wpdb->get_col( $query );
			
			$author         = get_userdata( $product->post->post_author );
			$dislay_name    = $author->display_name;

			$campaign_title  = $product->post->post_title;
			$campaign_link  = $product->post->guid;
			$campaign_short_link  =  site_url( '?p='.$post_id );
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
				$backer_subject        = str_replace($shortcode, $replace_str, $this->get_option('subject_for_all_backers'));

				$this->setup_locale();
				$this->send( $backer_emails, $backer_subject, $backer_email_str, $this->get_headers(), $this->get_attachments() );
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
		$email_heading = $this->get_option('heading_for_all_backers');
		$email_body = $this->get_option('body_for_all_backers');
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
				'title'   => __('Enable/Disable'),
				'type'    => 'checkbox',
				'label'   => __('Send Email Notification to Campaign Owner', 'wp-crowdfunding'),
				'default' => 'no'
			),

			'recipient'  => array(
				'title'       => __('Recipient(s)', 'wp-crowdfunding'),
				'type'        => 'text',
				'description' => sprintf( __('Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.'), esc_attr( get_option( 'admin_email' ) ) ),
				'placeholder' => '',
				'default'     => ''
			),
			'subject'    => array(
				'title'       => __('Subject', 'wp-crowdfunding'),
				'type'        => 'text',
				'description' => __('This controls the email subject line.', 'wp-crowdfunding'), 
				'placeholder' => '',
				'default'     => __('WP Crowdfunding Campaign [campaign_title] got update', 'wp-crowdfunding')
			),

			'subject_for_all_backers'    => array(
				'title'       => __('Subject for All Bakcers', 'wp-crowdfunding'),
				'type'        => 'text',
				'description' => __('This controls the campaign backers notification email subject line', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('WP Crowdfunding Campaign [campaign_title] got update', 'wp-crowdfunding')
			),
			'heading'    => array(
				'title'       => __('Email Heading', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main heading contained within the email notification.', 'wp-crowdfunding' ),
				'placeholder' => '',
				'default'     => __('Campaign [campaign_title] got update', 'wp-crowdfunding')
			),
			'heading_for_all_backers'    => array(
				'title'       => __('Email Heading for All Bakcers', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main heading contained within the campaign backers email notification.', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => __('Campaign [campaign_title] got update', 'wp-crowdfunding')
			),

			'body'    => array(
				'title'       => __('Email Body', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main email body contained within the email notification. Leave blank to keep it null, <code> Params: ( [user_name], [campaign_title], [campaign_link], [campaign_short_link] ) </code>', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => ''
			),

			'body_for_all_backers'    => array(
				'title'       => __('Email Body for All Backers', 'wp-crowdfunding'),
				'type'        => 'textarea',
				'description' => __('This controls the main email body contained within the campaign backers email notification. Leave blank to keep it null', 'wp-crowdfunding'),
				'placeholder' => '',
				'default'     => ''
			),


			'email_type' => array(
				'title'         => __( 'Email type', 'woocommerce' ),
				'type'          => 'select',
				'description'   => __('Choose which format of email to send.', 'woocommerce'),
				'default'       => 'html',
				'class'         => 'email_type wc-enhanced-select',
				'options'       => $this->get_email_type_options(),
				'desc_tip'      => true,
			),
		);
	}


} // end \WC_Expedited_Order_Email class
