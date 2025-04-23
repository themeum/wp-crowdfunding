<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wpneo-single-sidebar ASA">
	<?php
	global $post, $product;

	// var_dump( $post );
	// die();
	$currency            = '$';
	$campaign_end_method = get_post_meta( $post->ID, 'wpneo_campaign_end_method', true );
	if ( $product->get_type() == 'crowdfunding' ) {
		if ( wpcf_function()->is_campaign_valid( $post->ID ) ) {
			$recomanded_price = get_post_meta( $post->ID, 'wpneo_funding_recommended_price', true );
			$min_price        = get_post_meta( $post->ID, 'wpneo_funding_minimum_price', true );
			$max_price        = get_post_meta( $post->ID, 'wpneo_funding_maximum_price', true );
			$predefined_price = get_post_meta( $post->ID, 'wpcf_predefined_pledge_amount', true );
			if ( ! empty( $predefined_price ) ) {
				$predefined_price = apply_filters( 'wpcf_predefined_pledge_amount_array_a', explode( ',', $predefined_price ) );
			}

			if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {
				$currency = get_woocommerce_currency_symbol();
			}

			if ( ! empty( $_GET['reward_min_amount'] ) ) {
				$recomanded_price = (int) esc_html( $_GET['reward_min_amount'] );
			}
			?>

			<span class="wpneo-tooltip">
				<span class="wpneo-tooltip-min">
				<?php
				esc_html_e( 'Minimum amount is ', 'wp-crowdfunding' );
				echo $currency . $min_price;
				?>
				</span>
				<span class="wpneo-tooltip-max">
				<?php
				esc_html_e( 'Maximum amount is ', 'wp-crowdfunding' );
				echo $currency . $max_price;
				?>
				</span>
				<span class="wpneo-tooltip-empty"><?php esc_html_e( 'Put a valid number', 'wp-crowdfunding' ); ?></span>
			</span>

			<?php
			if ( is_array( $predefined_price ) && count( $predefined_price ) ) {
				echo '<ul class="wpcf_predefined_pledge_amount">';
				foreach ( $predefined_price as $price ) {
					$price    = trim( $price );
					$wooPrice = wc_price( $price );
					echo " <li><a href='javascript:;' data-predefined-price='{$price}'> {$wooPrice}</a> </li> ";
				}
				echo '</ul>';
			}
			?>

			<form enctype="multipart/form-data" method="post" class="cart AA">
				<?php do_action( 'before_wpneo_donate_field' ); ?>
				<?php echo get_woocommerce_currency_symbol(); ?>
				
				<input oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')" type="number" step="any" min="1" placeholder="<?php echo $recomanded_price; ?>" name="wpneo_donate_amount_field" class="input-text amount wpneo_donate_amount_field text" value="<?php echo ( $recomanded_price ? $recomanded_price : $min_price ); ?>" data-min-price="<?php echo $min_price; ?>" data-max-price="<?php echo $max_price; ?>" >

				<?php do_action( 'after_wpneo_donate_field' ); ?>
				<input type="hidden" value="<?php echo esc_attr( $post->ID ); ?>" name="add-to-cart">
				<button type="submit" class="<?php echo apply_filters( 'add_to_donate_button_class', 'wpneo_donate_button' ); ?>"><?php esc_html_e( 'Back Campaign', 'wp-crowdfunding' ); ?></button>
			</form>

			<?php
		} elseif ( wpcf_function()->get_date_remaining( $post->ID ) && 'target_goal_and_date' === $campaign_end_method ) {
			$recomanded_price = get_post_meta( $post->ID, 'wpneo_funding_recommended_price', true );
			$min_price        = get_post_meta( $post->ID, 'wpneo_funding_minimum_price', true );
			$max_price        = get_post_meta( $post->ID, 'wpneo_funding_maximum_price', true );
			$predefined_price = get_post_meta( $post->ID, 'wpcf_predefined_pledge_amount', true );
			if ( ! empty( $predefined_price ) ) {
				$predefined_price = apply_filters( 'wpcf_predefined_pledge_amount_array_a', explode( ',', $predefined_price ) );
			}

			if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {
				$currency = get_woocommerce_currency_symbol();
			}

			if ( ! empty( $_GET['reward_min_amount'] ) ) {
				$recomanded_price = (int) esc_html( $_GET['reward_min_amount'] );
			}
			?>

			<span class="wpneo-tooltip">
				<span class="wpneo-tooltip-min">
				<?php
				esc_html_e( 'Minimum amount is ', 'wp-crowdfunding' );
				echo $currency . $min_price;
				?>
				</span>
				<span class="wpneo-tooltip-max">
				<?php
				esc_html_e( 'Maximum amount is ', 'wp-crowdfunding' );
				echo $currency . $max_price;
				?>
				</span>
				<span class="wpneo-tooltip-empty"><?php esc_html_e( 'Put a valid number', 'wp-crowdfunding' ); ?></span>
			</span>

			<?php
			if ( is_array( $predefined_price ) && count( $predefined_price ) ) {
				echo '<ul class="wpcf_predefined_pledge_amount">';
				foreach ( $predefined_price as $price ) {
					$price    = trim( $price );
					$wooPrice = wc_price( $price );
					echo " <li><a href='javascript:;' data-predefined-price='{$price}'> {$wooPrice}</a> </li> ";
				}
				echo '</ul>';
			}
			?>

			<form enctype="multipart/form-data" method="post" class="cart AA">
				<?php do_action( 'before_wpneo_donate_field' ); ?>
				<?php echo get_woocommerce_currency_symbol(); ?>
				
				<input oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')" type="number" step="any" min="0" placeholder="<?php echo $recomanded_price; ?>" name="wpneo_donate_amount_field" class="input-text amount wpneo_donate_amount_field text" value="<?php echo ( $recomanded_price ? $recomanded_price : $min_price ); ?>" data-min-price="<?php echo $min_price; ?>" data-max-price="<?php echo $max_price; ?>" >

				<?php do_action( 'after_wpneo_donate_field' ); ?>
				<input type="hidden" value="<?php echo esc_attr( $post->ID ); ?>" name="add-to-cart">
				<button type="submit" class="<?php echo apply_filters( 'add_to_donate_button_class', 'wpneo_donate_button' ); ?>"><?php esc_html_e( 'Back Campaign', 'wp-crowdfunding' ); ?></button>
			</form>
			<?php
		} elseif ( wpcf_function()->get_date_remaining( $post->ID ) && 'target_date' === $campaign_end_method ) {
			$recomanded_price = get_post_meta( $post->ID, 'wpneo_funding_recommended_price', true );
			$min_price        = get_post_meta( $post->ID, 'wpneo_funding_minimum_price', true );
			$max_price        = get_post_meta( $post->ID, 'wpneo_funding_maximum_price', true );
			$predefined_price = get_post_meta( $post->ID, 'wpcf_predefined_pledge_amount', true );
			if ( ! empty( $predefined_price ) ) {
				$predefined_price = apply_filters( 'wpcf_predefined_pledge_amount_array_a', explode( ',', $predefined_price ) );
			}

			if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {
				$currency = get_woocommerce_currency_symbol();
			}

			if ( ! empty( $_GET['reward_min_amount'] ) ) {
				$recomanded_price = (int) esc_html( $_GET['reward_min_amount'] );
			}
			?>

			<span class="wpneo-tooltip">
				<span class="wpneo-tooltip-min">
				<?php
				esc_html_e( 'Minimum amount is ', 'wp-crowdfunding' );
				echo $currency . $min_price;
				?>
				</span>
				<span class="wpneo-tooltip-max">
				<?php
				esc_html_e( 'Maximum amount is ', 'wp-crowdfunding' );
				echo $currency . $max_price;
				?>
				</span>
				<span class="wpneo-tooltip-empty"><?php esc_html_e( 'Put a valid number', 'wp-crowdfunding' ); ?></span>
			</span>

			<?php
			if ( is_array( $predefined_price ) && count( $predefined_price ) ) {
				echo '<ul class="wpcf_predefined_pledge_amount">';
				foreach ( $predefined_price as $price ) {
					$price    = trim( $price );
					$wooPrice = wc_price( $price );
					echo " <li><a href='javascript:;' data-predefined-price='{$price}'> {$wooPrice}</a> </li> ";
				}
				echo '</ul>';
			}
			?>

			<form enctype="multipart/form-data" method="post" class="cart AA">
				<?php do_action( 'before_wpneo_donate_field' ); ?>
				<?php echo get_woocommerce_currency_symbol(); ?>
				
				<input oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')" type="number" step="any" min="0" placeholder="<?php echo $recomanded_price; ?>" name="wpneo_donate_amount_field" class="input-text amount wpneo_donate_amount_field text" value="<?php echo ( $recomanded_price ? $recomanded_price : $min_price ); ?>" data-min-price="<?php echo $min_price; ?>" data-max-price="<?php echo $max_price; ?>" >

				<?php do_action( 'after_wpneo_donate_field' ); ?>
				<input type="hidden" value="<?php echo esc_attr( $post->ID ); ?>" name="add-to-cart">
				<button type="submit" class="<?php echo apply_filters( 'add_to_donate_button_class', 'wpneo_donate_button' ); ?>"><?php esc_html_e( 'Back Campaign', 'wp-crowdfunding' ); ?></button>
			</form>
			<?php
		} elseif ( ! wpcf_function()->is_campaign_started() ) {
				wpcf_function()->campaign_start_countdown();
		} elseif ( wpcf_function()->is_reach_target_goal( $post->ID ) ) {
				esc_html_e( 'The campaign is successful.', 'wp-crowdfunding' );
		} else {
			esc_html_e( 'This campaign has been invalid or not started yet.', 'wp-crowdfunding' );
		}
	}

	?>
</div>
