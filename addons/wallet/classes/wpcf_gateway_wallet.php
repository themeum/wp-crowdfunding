<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WP Crowdfunding Wallet Payment Gateway
 *
 * Provides an way to pay from Wallet Balance
 *
 * @class 		WPCF_Gateway_Wallet
 * @extends		WC_Payment_Gateway
 * @version		1.0.0
 * @author 		Themeum
 */

class WPCF_Gateway_Wallet extends WC_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		global $wpcf_wallet;
		$currentBalance = $wpcf_wallet->current_balance();

		// Setup general properties
		$this->setup_properties();

		// Load the settings
		$this->init_form_fields();
		$this->init_settings();

		// Get settings
		$this->title              = $this->get_option( 'title' );
		$this->description        = $this->get_option( 'description' ).' <p class="wpcf-wallet-balance">'.__('Current Balance', 'wp-crowdfunding').' : '.wc_price($currentBalance).'</p>';

		$this->instructions       = $this->get_option( 'instructions' );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
		add_filter( 'woocommerce_payment_complete_order_status', array( $this, 'change_payment_complete_order_status' ), 10, 3 );

		// Customer Emails
		add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
	}

	/**
	 * Setup general properties for the gateway.
	 */
	protected function setup_properties() {
		$this->id                 = 'wpcf_wallet';
		$this->icon               = apply_filters( 'woocommerce_wpcf_wallet_icon', WPCF_DIR_URL.'addons/wallet/assets/wallet.png' );
		$this->method_title       = __( 'WP Crowdfunding Wallet', 'wp-crowdfunding' );
		$this->method_description = __( 'Pay using your WP Crowdfunding wallet balance', 'wp-crowdfunding' );
		$this->has_fields         = false;
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$shipping_methods = array();

		foreach ( WC()->shipping()->load_shipping_methods() as $method ) {
			$shipping_methods[ $method->id ] = $method->get_method_title();
		}

		$this->form_fields = array(
			'enabled' => array(
				'title'       => __( 'Enable/Disable', 'wp-crowdfunding' ),
				'label'       => __( 'Enable WP Crowdfunding Wallet', 'wp-crowdfunding' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no',
			),
			'title' => array(
				'title'       => __( 'Title', 'wp-crowdfunding' ),
				'type'        => 'text',
				'description' => __( 'Payment method description that the customer will see on your checkout.', 'wp-crowdfunding' ),
				'default'     => __( 'WP Crowdfunding Wallet', 'wp-crowdfunding' ),
				'desc_tip'    => true,
			),
			'description' => array(
				'title'       => __( 'Description', 'wp-crowdfunding' ),
				'type'        => 'textarea',
				'description' => __( 'Payment method description that the customer will see on your website.', 'wp-crowdfunding' ),
				'default'     => __( 'Pay using your WP Crowdfunding wallet balance.', 'wp-crowdfunding' ),
				'desc_tip'    => true,
			),
			'instructions' => array(
				'title'       => __( 'Instructions', 'wp-crowdfunding' ),
				'type'        => 'textarea',
				'description' => __( 'Instructions that will be added to the thank you page.', 'wp-crowdfunding' ),
				'default'     => __( 'Pay using your WP Crowdfunding wallet balance.', 'wp-crowdfunding' ),
				'desc_tip'    => true,
			),


		);
	}

	/**
	 * Check If The Gateway Is Available For Use.
	 *
	 * @return bool
	 */
	public function is_available() {
		global $wpcf_wallet;
		if ( ! is_user_logged_in() || ! WC()->cart){
			return false;
		}
		$cart_contents = WC()->cart->get_cart_contents();

		//Allow this payment gateway only for crowdfunding campaign checkout
		foreach ($cart_contents as $cart){
			$product = wc_get_product($cart['product_id']);
			if ($product->get_type() !== 'crowdfunding'){
				return false;
			}
		}

		return parent::is_available();
	}


	/**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id
	 * @return array
	 */
	public function process_payment( $order_id ) {
		global $wpcf_wallet;
		$order = wc_get_order( $order_id );

		$order_total = $order->get_total();

		if ($order_total > $wpcf_wallet->current_balance()){
			wc_add_notice( __('You have no enough balance to do this action, please deposit first'), 'error' );
			return;
		}else{
			update_post_meta($order_id, 'wpcrowdfunding_wallet_debited', $order_total);
			//$order->payment_complete();
			$order->update_status( apply_filters( 'woocommerce_wpcf_wallet_process_payment_order_status', 'completed'
			), __( 'Donated Successful via WP Crowdfunding Wallet Balance', 'woocommerce' ) );

		}

		// Reduce stock levels
		wc_reduce_stock_levels( $order_id );

		// Remove cart
		WC()->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> $this->get_return_url( $order ),
		);
	}

	/**
	 * Output for the order received page.
	 */
	public function thankyou_page() {
		if ( $this->instructions ) {
			echo wpautop( wptexturize( $this->instructions ) );
		}
	}

	/**
	 * Change payment complete order status to completed for COD orders.
	 *
	 */
	public function change_payment_complete_order_status( $status, $order_id = 0, $order = false ) {
		if ( $order && 'wpcf_wallet' === $order->get_payment_method() ) {
			$status = 'completed';
		}
		return $status;
	}

	/**
	 * Add content to the WC emails.
	 *
	 * @access public
	 * @param WC_Order $order
	 * @param bool $sent_to_admin
	 * @param bool $plain_text
	 */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		if ( $this->instructions && ! $sent_to_admin && $this->id === $order->get_payment_method() ) {
			echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
		}
	}
}

if ( ! function_exists('add_wpcf_wallet_payment')){
	function add_wpcf_wallet_payment( $methods ) {
		$methods[] = 'WPCF_Gateway_Wallet';
		return $methods;
	}
}

add_filter( 'woocommerce_payment_gateways', 'add_wpcf_wallet_payment' );