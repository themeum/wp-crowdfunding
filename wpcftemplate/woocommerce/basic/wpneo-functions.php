<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wpcf_campaign_listing_before_loop', 'campaign_listing_by_author_before_loop' );
function campaign_listing_by_author_before_loop() {
	if ( ! empty( $_GET['author'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only display parameter; no state change.
		echo '<h3>' . esc_html__( 'Campaigns by: ', 'wp-crowdfunding' ) . ' ' . esc_html( wpcf_function()->author_name_by_login( sanitize_text_field( wp_unslash( $_GET['author'] ) ) ) ) . '</h3>'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only display parameter; no state change.
	}
}

function wpcf_campaign_order_number_data( $min_data, $max_data, $post_id ) {
	global $wpdb;

	$min_data = is_numeric( $min_data ) ? (float) $min_data : 0;
	$max_data = is_numeric( $max_data ) ? (float) $max_data : 0;
	$post_id  = absint( $post_id );

	if ( $post_id <= 0 ) {
		return 0;
	}

	// Count from the active order storage: HPOS (wc_orders) or legacy (wp_posts).
	// Table and column names are fixed strings, never user input.
	$orders       = wpcf_function()->get_orders_table_info();
	$orders_table = $orders['table'];
	$id_col       = $orders['id'];
	$type_col     = $orders['type'];
	$status_col   = $orders['status'];

	// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Only fixed table/column names are interpolated; values use placeholders.
	return (int) $wpdb->get_var(
		$wpdb->prepare(
		"SELECT COUNT(p.{$id_col})
		FROM {$orders_table} AS p,
			{$wpdb->prefix}woocommerce_order_items AS i,
			{$wpdb->prefix}woocommerce_order_itemmeta AS im
		WHERE p.{$type_col} = 'shop_order'
			AND p.{$status_col} = 'wc-completed'
			AND i.order_id = p.{$id_col}
			AND i.order_item_id = im.order_item_id
			AND im.meta_key = '_product_id'
			AND im.order_item_id IN (
				SELECT DISTINCT order_item_id
				FROM {$wpdb->prefix}woocommerce_order_itemmeta
				WHERE meta_key = '_line_total'
					AND meta_value BETWEEN %f AND %f
			)
			AND im.meta_value = %d",
		$min_data,
		$max_data,
		$post_id
	)
	);
	// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
}

// Bio Data View.
add_action( 'wp_ajax_nopriv_wpcf_bio_action', 'wpcf_bio_campaign_action' );
add_action( 'wp_ajax_wpcf_bio_action', 'wpcf_bio_campaign_action' );

/**
 * Whether a user has chosen to show their contact details on their public creator profile.
 *
 * @since 2.2.3
 *
 * @param int $user_id User ID.
 * @return bool
 */
function wpcf_user_shows_public_contact( $user_id ) {
	return '1' === get_user_meta( absint( $user_id ), 'profile_show_contact', true );
}

/**
 * Return the public profile of a campaign creator for the bio popup.
 *
 * Only users with at least one published crowdfunding campaign have a public
 * profile, and contact details are only included when the creator opted in.
 */
function wpcf_bio_campaign_action() {
	$invalid = array(
		'success' => 0,
		'message' => __( 'Invalid request!', 'wp-crowdfunding' ),
	);

	if ( ! check_ajax_referer( 'cf_ajax_nonce', 'nonce', false ) ) {
		wp_send_json( $invalid );
	}

	$author      = isset( $_POST['author'] ) ? absint( wp_unslash( $_POST['author'] ) ) : 0;
	$campaign_id = isset( $_POST['campaign_id'] ) ? absint( wp_unslash( $_POST['campaign_id'] ) ) : 0;
	$creator     = $author ? get_userdata( $author ) : false;

	// Only campaign creators have a public profile. Do not reveal whether other user IDs exist.
	if ( ! $creator || 0 === (int) wpcf_function()->author_campaigns( $author )->post_count ) {
		wp_send_json(
			array(
				'success' => 0,
				'message' => __( 'Profile not found.', 'wp-crowdfunding' ),
			)
		);
	}

	// The campaign is only used for its location, and must be a published campaign by this creator.
	if ( $campaign_id ) {
		$campaign = get_post( $campaign_id );
		$product  = $campaign ? wc_get_product( $campaign_id ) : false;
		if ( ! $campaign || 'publish' !== $campaign->post_status || (int) $campaign->post_author !== $author || ! $product || ! $product->is_type( 'crowdfunding' ) ) {
			$campaign_id = 0;
		}
	}

	$user_info    = get_user_meta( $author );
	$show_contact = wpcf_user_shows_public_contact( $author );
	$meta         = function ( $key ) use ( $user_info ) {
		return ! empty( $user_info[ $key ][0] ) ? $user_info[ $key ][0] : '';
	};

	$html     = '<div class="wpneo-profile">';
	$image_id = absint( $meta( 'profile_image_id' ) );
	if ( $image_id ) {
		$img_src = wp_get_attachment_image_url( $image_id, 'full' );
		if ( $img_src ) {
			$html .= '<img width="105" height="105" class="profile-avatar" src="' . esc_url( $img_src ) . '" alt="">';
		}
	}
	$html .= '</div>';

	$html .= '<div class="wpneo-profile">';
	$html .= '<div class="wpneo-profile-name"><a href="' . esc_url( wpcf_function()->campaign_url( $author ) ) . '">' . esc_html( wpcf_function()->get_author_name( $author ) ) . '</a></div>';

	$location = $campaign_id ? wpcf_function()->campaign_location( $campaign_id ) : '';
	if ( $location ) {
		$html .= '<div class="wpneo-profile-location">';
		$html .= '<i class="wpneo-icon wpneo-icon-location"></i> <span>' . esc_html( $location ) . '</span>';
		$html .= '</div>';
	}
	$html .= '<div class="wpneo-profile-campaigns">' . esc_html( wpcf_function()->author_campaigns( $author )->post_count ) . esc_html__( ' Campaigns', 'wp-crowdfunding' ) . ' | ' . esc_html( wpcf_function()->loved_count() ) . esc_html__( ' Loved campaigns', 'wp-crowdfunding' ) . '</div>';
	$html .= '</div>';

	if ( $meta( 'profile_about' ) ) {
		$html .= '<div class="wpneo-profile-about">';
		$html .= '<h3>' . esc_html__( 'Profile Information', 'wp-crowdfunding' ) . '</h3>';
		$html .= '<p>' . esc_html( $meta( 'profile_about' ) ) . '</p>';
		$html .= '</div>';
	}

	if ( $meta( 'profile_portfolio' ) ) {
		$html .= '<div class="wpneo-profile-about">';
		$html .= '<h3>' . esc_html__( 'Portfolio', 'wp-crowdfunding' ) . '</h3>';
		$html .= '<p>' . esc_html( $meta( 'profile_portfolio' ) ) . '</p>';
		$html .= '</div>';
	}

	if ( $show_contact ) {
		$email   = sanitize_email( $meta( 'profile_email1' ) );
		$contact = '';
		if ( $email ) {
			$contact .= '<p>' . esc_html__( 'Email: ', 'wp-crowdfunding' ) . esc_html( antispambot( $email ) ) . '</p>';
		}
		if ( $meta( 'profile_mobile1' ) ) {
			$contact .= '<p>' . esc_html__( 'Phone: ', 'wp-crowdfunding' ) . esc_html( $meta( 'profile_mobile1' ) ) . '</p>';
		}
		if ( $meta( 'profile_fax' ) ) {
			$contact .= '<p>' . esc_html__( 'Fax: ', 'wp-crowdfunding' ) . esc_html( $meta( 'profile_fax' ) ) . '</p>';
		}
		if ( $meta( 'profile_website' ) ) {
			$website_url = esc_url( wpcf_function()->url( $meta( 'profile_website' ) ) );
			$contact    .= '<p>' . esc_html__( 'Website: ', 'wp-crowdfunding' ) . ' <a href="' . $website_url . '" rel="nofollow noopener"> ' . esc_html( $website_url ) . ' </a></p>';
		}
		if ( $email ) {
			$contact .= '<a class="wpneo-profile-button" href="' . esc_url( 'mailto:' . antispambot( $email ) ) . '" target="_top">' . esc_html__( 'Contact Me', 'wp-crowdfunding' ) . '</a>';
		}
		if ( $contact ) {
			$html .= '<div class="wpneo-profile-about">';
			$html .= '<h3>' . esc_html__( 'Contact Info', 'wp-crowdfunding' ) . '</h3>';
			$html .= $contact;
			$html .= '</div>';
		}
	}

	$social = array(
		'profile_facebook'  => 'wpneo-icon-facebook',
		'profile_twitter'   => 'wpneo-icon-twitter',
		'profile_vk'        => 'wpneo-icon-gplus',
		'profile_linkedin'  => 'wpneo-icon-linkedin',
		'profile_pinterest' => 'wpneo-icon-pinterest',
	);
	$links  = '';
	foreach ( $social as $key => $icon ) {
		if ( $meta( $key ) ) {
			$links .= '<a class="wpcf-social-link" href="' . esc_url( $meta( $key ) ) . '" rel="nofollow noopener"><i class="wpneo-icon ' . esc_attr( $icon ) . '"></i></a>';
		}
	}
	if ( $links ) {
		$html .= '<div class="wpneo-profile-about">';
		$html .= '<h3>' . esc_html__( 'Social Link', 'wp-crowdfunding' ) . '</h3>';
		$html .= $links;
		$html .= '</div>';
	}

	wp_send_json(
		array(
			'success' => 1,
			'message' => $html,
			'title'   => __( 'About the campaign creator', 'wp-crowdfunding' ),
		)
	);
}
