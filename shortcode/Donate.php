<?php

namespace WPCF\shortcode;

defined( 'ABSPATH' ) || exit;

class Donate {

	function __construct() {
		add_shortcode( 'wpcf_donate', array( $this, 'donate_callback' ) );
	}

	function donate_callback( $atts, $shortcode ) {
		$atts = shortcode_atts(
			array(
				'campaign_id'        => null,
				'amount'             => '',
				'show_input_box'     => 'true',
				'min_amount'         => '',
				'max_amount'         => '',
				'donate_button_text' => __( 'Back Campaign', 'wp-crowdfunding' ),
			),
			$atts,
			$shortcode
		);

		// Sanitize inputs
		$atts['campaign_id']        = intval( $atts['campaign_id'] );
		$atts['amount']             = sanitize_text_field( $atts['amount'] );
		$atts['show_input_box']     = filter_var( $atts['show_input_box'], FILTER_VALIDATE_BOOLEAN );
		$atts['min_amount']         = sanitize_text_field( $atts['min_amount'] );
		$atts['max_amount']         = sanitize_text_field( $atts['max_amount'] );
		$atts['donate_button_text'] = sanitize_text_field( $atts['donate_button_text'] );

		if ( ! $atts['campaign_id'] ) {
			return '<p class="wpcf-donate-form-response">' . esc_html__( 'Campaign ID required', 'wp-crowdfunding' ) . '</p>';
		}

		$campaign = wc_get_product( $atts['campaign_id'] );
		if ( ! $campaign || $campaign->get_type() !== 'crowdfunding' ) {
			return '<p class="wpcf-donate-form-response">' . esc_html__( 'Invalid Campaign ID', 'wp-crowdfunding' ) . '</p>';
		}

		ob_start();
		?>
		<div class="wpcf-donate-form-wrap">
			<form enctype="multipart/form-data" method="post" class="cart">
				<?php
				if ( $atts['show_input_box'] == 'true' ) {
					echo esc_html( get_woocommerce_currency_symbol() );
					?>
					<input type="number" step="any" min="1" 
						placeholder="<?php echo esc_attr( $atts['amount'] ); ?>"
						name="wpneo_donate_amount_field" class="input-text amount wpneo_donate_amount_field text"
						value="<?php echo esc_attr( $atts['amount'] ); ?>" data-min-price="<?php echo esc_attr( $atts['min_amount'] ); ?>"
						data-max-price="<?php echo esc_attr( $atts['max_amount'] ); ?>">
					<?php
				} else {
					echo '<input type="hidden" name="wpneo_donate_amount_field" value="' . esc_attr( $atts['amount'] ) . '" />';
				}
				?>
				<input type="hidden" value="<?php echo esc_attr( $atts['campaign_id'] ); ?>" name="add-to-cart">
				<button type="submit" class="<?php echo esc_attr( apply_filters( 'add_to_donate_button_class', 'wpneo_donate_button' ) ); ?>">
					<?php
					echo esc_html( $atts['donate_button_text'] );
					if ( $atts['show_input_box'] != 'true' ) {
						echo ' (' . esc_html( wc_price( $atts['amount'] ) ) . ') ';
					}
					?>
				</button>
			</form>
		</div>
		<?php
		return ob_get_clean();
	}
}