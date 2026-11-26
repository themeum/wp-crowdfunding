<?php

namespace WPCF;

use WP_Query;

defined( 'ABSPATH' ) || exit;

class Functions {

	public function generator( $arr ) {
		require_once WPCF_DIR_PATH . 'settings/Generator.php';
		$generator = new \WPCF\settings\Settings_Generator();
		$generator->generator( $arr );
	}

	public function post( $post_item ) {
		// phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Raw shared reader: nonce verification and sanitizing are the caller's responsibility.
		if ( ! empty( $_POST[ $post_item ] ) ) {
			return wp_unslash( $_POST[ $post_item ] );
		}
		return null;
		// phpcs:enable WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	}

	public function is_published( $post_id = 0 ) {
		global $post;
		if ( $post_id == 0 ) {
			$post_id = $post->ID;
		}
		$status = get_post_status( $post_id );
		return $status == 'publish' ? true : false;
	}

	public function is_free() {
		if ( is_plugin_active( 'wp-crowdfunding-pro/wp-crowdfunding-pro.php' ) ) {
			return false;
		} else {
			return true;
		}
	}

	public function update_text( $option_name = '', $option_value = null ) {
		if ( ! empty( $option_value ) ) {
			update_option( $option_name, $option_value );
		}
	}

	public function update_checkbox( $option_name = '', $option_value = null, $checked_default_value = 'false' ) {
		if ( ! empty( $option_value ) ) {
			update_option( $option_name, $option_value );
		} else {
			update_option( $option_name, $checked_default_value );
		}
	}

	public function update_meta( $post_id, $meta_name = '', $meta_value = '', $checked_default_value = '' ) {
		if ( ! empty( $meta_value ) ) {
			update_post_meta( $post_id, $meta_name, $meta_value );
		} else {
			update_post_meta( $post_id, $meta_name, $checked_default_value );
		}
	}

	public function get_pages() {
		$args  = array(
			'sort_order'   => 'asc',
			'sort_column'  => 'post_title',
			'hierarchical' => 1,
			'child_of'     => 0,
			'parent'       => -1,
			'offset'       => 0,
			'post_type'    => 'page',
			'post_status'  => 'publish',
		);
		$pages = get_pages( $args );
		return $pages;
	}

	public function wc_version( $version = '3.0' ) {
		if ( class_exists( 'WooCommerce' ) ) {
			if ( version_compare( WC()->version, $version, '>=' ) ) {
				return true;
			}
		}
		return false;
	}

	public function is_woocommerce() {
		$vendor = get_option( 'vendor_type', 'woocommerce' );
		if ( $vendor == 'woocommerce' ) {
			return true;
		} else {
			return false;
		}
	}

	public function get_screen_id() {
		$screen_ids = array(
			'toplevel_page_wpneo-crowdfunding',
			'crowdfunding_page_wpneo-crowdfunding-reports',
			'crowdfunding_page_wpneo-crowdfunding-withdraw',
		);
		return apply_filters( 'wpcf_screen_id', $screen_ids );
	}

	public function get_addon_config( $addon_field = null ) {
		if ( ! $addon_field ) {
			return false;
		}
		$addonsConfig = maybe_unserialize( get_option( 'wpcf_addons_config' ) );
		if ( isset( $addonsConfig[ $addon_field ] ) ) {
			return $addonsConfig[ $addon_field ];
		}
		return false;
	}

	public function avalue_dot( $key = null, $array = array() ) {
		$array = (array) $array;
		if ( ! $key || ! count( $array ) ) {
			return false;
		}
		$option_key_array = explode( '.', $key );
		$value            = $array;
		foreach ( $option_key_array as $dotKey ) {
			if ( isset( $value[ $dotKey ] ) ) {
				$value = $value[ $dotKey ];
			} else {
				return false;
			}
		}
		return $value;
	}

	public function campaign_url( $author_id = 0, $author_nicename = '' ) {
		$author_id = $author_id ? $author_id : get_current_user_id();
		if ( ! $author_id ) {
			return false;
		}
		$url = get_author_posts_url( $author_id, $author_nicename );
		return trailingslashit( $url ) . 'campaigns';
	}


	public function get_products_id_by_user( $user_id = 0 ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		global $wpdb;
		$results = $wpdb->get_col( $wpdb->prepare( "SELECT ID from {$wpdb->posts} WHERE post_author = %d AND post_type = 'product'", absint( $user_id ) ) );

		return $results;
	}

	public function get_pladge_received( $from_date = null, $to_date = null ) {
		if ( ! $from_date ) {
			$from_date = gmdate( 'Y-m-d 00:00:00', strtotime( '-6 days' ) );
		}
		if ( ! $to_date ) {
			$to_date = gmdate( 'Y-m-d 23:59:59' );
		}
		$args     = array(
			'post_type'      => 'product',
			'author'         => get_current_user_id(),
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'crowdfunding',
				),
			),
			'posts_per_page' => -1,
		);
		$id_list  = get_posts( $args );
		$id_array = array();
		foreach ( $id_list as $value ) {
			$id_array[] = $value->ID;
		}

		$orders = $this->get_orders_by_ids(
			$this->get_order_ids_for_products( $id_array ),
			array(
				'type'   => wc_get_order_types( 'view-orders' ),
				'status' => array_keys( wc_get_order_statuses() ),
			)
		);

		// Orders created between the two dates (inclusive, by day).
		$from_day = gmdate( 'Y-m-d', strtotime( $from_date ) );
		$to_day   = gmdate( 'Y-m-d', strtotime( $to_date ) );

		return array_values(
			array_filter(
				$orders,
				function ( $order ) use ( $from_day, $to_day ) {
					$created = $order->get_date_created();
					if ( ! $created ) {
						return false;
					}
					$day = $created->date( 'Y-m-d' );
					return $day >= $from_day && $day <= $to_day;
				}
			)
		);
	}

	/**
	 * Whether WooCommerce stores orders in its own tables (HPOS).
	 *
	 * @since 2.2.3
	 *
	 * @return bool
	 */
	public function is_hpos_enabled() {
		return class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' )
			&& \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
	}

	/**
	 * Orders table and column names for the active order storage.
	 *
	 * The values are fixed strings, safe to interpolate into SQL.
	 *
	 * @since 2.2.3
	 *
	 * @return array{table: string, id: string, type: string, status: string}
	 */
	public function get_orders_table_info() {
		global $wpdb;
		if ( $this->is_hpos_enabled() ) {
			return array(
				'table'  => $wpdb->prefix . 'wc_orders',
				'id'     => 'id',
				'type'   => 'type',
				'status' => 'status',
			);
		}
		return array(
			'table'  => $wpdb->posts,
			'id'     => 'ID',
			'type'   => 'post_type',
			'status' => 'post_status',
		);
	}

	/**
	 * IDs of orders (any status) that contain any of the given products.
	 *
	 * Order items live in the same tables with and without HPOS.
	 *
	 * @since 2.2.3
	 *
	 * @param int[] $product_ids Product IDs.
	 * @return int[]
	 */
	public function get_order_ids_for_products( $product_ids ) {
		global $wpdb;
		$product_ids = array_values( array_filter( array_map( 'absint', (array) $product_ids ) ) );
		if ( empty( $product_ids ) ) {
			return array();
		}
		$order_ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT oi.order_id FROM {$wpdb->prefix}woocommerce_order_items oi
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta woim ON woim.order_item_id = oi.order_item_id
				WHERE woim.meta_key = '_product_id' AND woim.meta_value IN ( " . implode( ',', array_fill( 0, count( $product_ids ), '%d' ) ) . ' )',
				$product_ids
			)
		);
		return array_map( 'absint', (array) $order_ids );
	}

	/**
	 * Load orders by ID from the active order storage (HPOS or posts).
	 *
	 * An empty ID list returns no orders (never "all orders").
	 *
	 * @since 2.2.3
	 *
	 * @param int[] $order_ids Order IDs.
	 * @param array $args      Extra wc_get_orders() arguments, e.g. status, type, customer_id, limit, page, paginate.
	 * @return \WC_Order[]|object Orders, or wc_get_orders()'s paginated result when 'paginate' is set.
	 */
	public function get_orders_by_ids( $order_ids, $args = array() ) {
		$order_ids = array_values( array_unique( array_filter( array_map( 'absint', (array) $order_ids ) ) ) );
		if ( empty( $order_ids ) ) {
			return empty( $args['paginate'] ) ? array() : (object) array(
				'orders'        => array(),
				'total'         => 0,
				'max_num_pages' => 0,
			);
		}

		$query = array_merge(
			array(
				'limit'   => -1,
				'orderby' => 'date',
				'order'   => 'DESC',
			),
			(array) $args
		);
		// HPOS maps post__in to order IDs; legacy storage passes it to WP_Query.
		$query['post__in'] = $order_ids;

		return wc_get_orders( $query );
	}

	/**
	 * The reward a backer selected, as saved on the order.
	 *
	 * Saved as JSON by the plugin; very old orders may hold an array.
	 *
	 * @since 2.2.3
	 *
	 * @param \WC_Abstract_Order|int $order Order or order ID.
	 * @return array Reward data, or an empty array.
	 */
	public function get_order_reward( $order ) {
		$order = $order instanceof \WC_Abstract_Order ? $order : wc_get_order( $order );
		if ( ! $order ) {
			return array();
		}
		$reward = $order->get_meta( 'wpneo_selected_reward', true );
		if ( is_string( $reward ) && '' !== $reward ) {
			$decoded = json_decode( $reward, true );
			if ( ! is_array( $decoded ) ) {
				$decoded = json_decode( wp_unslash( $reward ), true );
			}
			$reward = $decoded;
		}
		return is_array( $reward ) ? $reward : array();
	}

	public function get_order_ids_by_product_ids( $product_ids, $order_status = array( 'wc-completed' ) ) {
		global $wpdb;
		$product_ids  = array_filter( array_map( 'absint', (array) $product_ids ) );
		$order_status = array_filter( array_map( 'sanitize_key', (array) $order_status ) );
		if ( empty( $product_ids ) || empty( $order_status ) ) {
			return array();
		}
		$orders = $this->get_orders_table_info();
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Only fixed table/column names from get_orders_table_info() are interpolated.
		$results = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT order_items.order_id
				FROM {$wpdb->prefix}woocommerce_order_items as order_items
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
				LEFT JOIN {$orders['table']} AS posts ON order_items.order_id = posts.{$orders['id']}
				WHERE posts.{$orders['type']} = 'shop_order'
				AND posts.{$orders['status']} IN ( " . implode( ',', array_fill( 0, count( $order_status ), '%s' ) ) . " )
				AND order_items.order_item_type = 'line_item'
				AND order_item_meta.meta_key = '_product_id'
				AND order_item_meta.meta_value IN ( " . implode( ',', array_fill( 0, count( $product_ids ), '%d' ) ) . ' )',
				array_merge( array_values( $order_status ), array_values( $product_ids ) )
			)
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		return $results;
	}

	function get_author_url( $user_login ) {
		return esc_url( add_query_arg( array( 'author' => $user_login ) ) );
	}
	public function get_author_name( $user_id = '' ) {
		if ( ! $user_id ) {
			global $post;
			$user_id = $post->post_author;
		}

		$author_name = '';
		$author      = get_userdata( $user_id );
		if ( isset( $author->user_login ) ) {
			$author_name = isset( $author->display_name ) ? $author->display_name : $author->user_login;
			if ( ! empty( $author->first_name ) ) {
				$author_name = $author->first_name . ' ' . $author->last_name;
			}
		}
		return $author_name;
	}

	public function author_name_by_login( $author_login ) {
		$author      = get_user_by( 'login', $author_login );
		$author_name = $author->first_name . ' ' . $author->last_name;
		if ( empty( $author->first_name ) ) {
			$author_name = $author->user_login;
		}
		return $author_name;
	}

	public function campaign_location( $campaign_id = 0 ) {
		if ( ! $campaign_id ) {
			global $post;
			$campaign_id = isset( $post->ID ) ? $post->ID : 0;
		}
		if ( ! $campaign_id ) {
			return '';
		}
		$country      = get_post_meta( $campaign_id, 'wpneo_country', true );
		$location     = get_post_meta( $campaign_id, '_nf_location', true );
		$country_name = '';
		if ( class_exists( 'WC_Countries' ) ) {
			$countries_obj = new \WC_Countries();
			$countries     = $countries_obj->__get( 'countries' );
			if ( $country ) {
				$country_name = $countries[ $country ];
				$location     = $location . ', ' . $country_name;
			}
		}
		return $location;
	}

	public function template( $template = '404' ) {
		$template_class = new \WPCF\woocommerce\Templating();
		$locate_file    = $template_class->_theme_in_themes_path . $template . '.php';
		if ( file_exists( $locate_file ) ) {
			include $locate_file;
		} else {
			include $template_class->_theme_in_plugin_path . $template . '.php';
		}
	}

	public static function hpos_enabled() {
		$hpos = false;

		if ( version_compare( WC()->version, '8.2.0', '>=' ) ) {
			$hpos = 'yes' === get_option( 'woocommerce_custom_orders_table_enabled' );
		}

		return $hpos;
	}

	public function fund_raised( $campaign_id = 0 ) {
		global $wpdb, $post;
		$db_prefix = $wpdb->prefix;
		if ( $campaign_id == 0 ) {
			$campaign_id = $post->ID;
		}
		// WPML compatibility.
		if ( apply_filters( 'wpml_setting', false, 'setup_complete' ) ) {
			$type         = apply_filters( 'wpml_element_type', get_post_type( $campaign_id ) );
			$trid         = apply_filters( 'wpml_element_trid', null, $campaign_id, $type );
			$translations = apply_filters( 'wpml_get_element_translations', null, $trid, $type );
			$campaign_ids = wp_list_pluck( $translations, 'element_id' );
		} else {
			$campaign_ids = array( $campaign_id );
		}
		$placeholders = implode( ',', array_fill( 0, count( $campaign_ids ), '%d' ) );

		if ( ! $this->hpos_enabled() ) {
			$query = "SELECT SUM(ltoim.meta_value) as total_sales_amount
                FROM {$wpdb->prefix}woocommerce_order_itemmeta woim
                LEFT JOIN {$wpdb->prefix}woocommerce_order_items oi ON woim.order_item_id = oi.order_item_id
                LEFT JOIN {$wpdb->prefix}posts wpposts ON order_id = wpposts.ID
                LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta ltoim ON ltoim.order_item_id = oi.order_item_id AND ltoim.meta_key = '_line_total'
                WHERE woim.meta_key = '_product_id' AND woim.meta_value IN ($placeholders) AND wpposts.post_status = 'wc-completed';";
		} else {
			$query = "SELECT 
                        SUM(ltoim.meta_value) as total_sales_amount
                    FROM
                        {$wpdb->prefix}woocommerce_order_itemmeta woim
                    LEFT JOIN
                        {$wpdb->prefix}woocommerce_order_items oi ON woim.order_item_id = oi.order_item_id
                    LEFT JOIN
                        {$wpdb->prefix}wc_orders wco ON oi.order_id = wco.id
                    LEFT JOIN
                        {$wpdb->prefix}woocommerce_order_itemmeta ltoim ON ltoim.order_item_id = oi.order_item_id AND ltoim.meta_key = '_line_total'
                    WHERE
                    woim.meta_key = '_product_id'
                    AND woim.meta_value IN ($placeholders)
                    AND wco.status = 'wc-completed';
                ";
		}
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- $query is built above from $wpdb table names and a %d placeholder list only.
		$wp_sql = $wpdb->get_row( $wpdb->prepare( $query, $campaign_ids ) );

		return $wp_sql->total_sales_amount;
	}

	public function campaign_loved( $echo = true ) {
		global $post;
		$campaign_id = $post->ID;

		$html = '';
		if ( is_user_logged_in() ) {
			// Get Current user id
			$user_id = get_current_user_id();
			// empty array
			$loved_campaign_ids = array();
			$prev_campaign_ids  = get_user_meta( $user_id, 'loved_campaign_ids', true );

			if ( $prev_campaign_ids ) {
				$loved_campaign_ids = json_decode( $prev_campaign_ids, true );
			}

			// If found previous liked
			if ( in_array( $campaign_id, $loved_campaign_ids ) ) {
				$html .= '<a href="#" id="remove_from_love_campaign" data-campaign-id="' . esc_attr( $campaign_id ) . '"><i class="wpneo-icon wpneo-icon-love-full"></i></a>';
			} else {
				$html .= '<a href="#" id="love_this_campaign" data-campaign-id="' . esc_attr( $campaign_id ) . '"><i class="wpneo-icon wpneo-icon-love-empty"></i></a>';
			}
		} else {
			$html .= '<a href="#" id="love_this_campaign" data-campaign-id="' . esc_attr( $campaign_id ) . '"><i class="wpneo-icon wpneo-icon-love-empty"></i></a>';
		}

		if ( $echo ) {
			echo wp_kses_post( $html );
		} else {
			return $html;
		}
	}

	public function login_form() {
		$html  = '';
		$error = false;
		$login = ( isset( $_GET['login'] ) ) ? sanitize_key( wp_unslash( $_GET['login'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only display parameter; no state change.
		if ( strpos( $login, 'failed' ) !== false ) {
			$error = true;
			$html .= '<p class="error"><strong>ERROR:</strong> Invalid username and/or password.</p><br><br>';
		} elseif ( strpos( $login, 'empty' ) !== false ) {
			$error = true;
			$html .= '<p class="error"><strong>ERROR:</strong> Username or Password is empty.</p><br><br>';
		}
		$html .= '<div class="wpneo_login_form_div" ' . ( $error == false ? 'style="display: none;"' : '' ) . '>';
		$html .= wp_login_form(
			array(
				'echo'   => false,
				'hidden' => true,
			)
		);
		$html .= '</div>';
		return $html;
	}

	/**
	 * Number of Items Per Page in CF Dashboard
	 *
	 * @return int $cf_dash_per_page_item
	 */
	public function number_of_items_per_page() {
		$cf_dash_per_page_item = ! empty( get_option( 'wpcf_dashboard_paginate_number' ) ) ? absint( get_option( 'wpcf_dashboard_paginate_number' ) ) : 10;
		return $cf_dash_per_page_item;
	}

	public function wpcf_redurl() {
		$cf_red_url = ! empty( get_option( 'wpcf_redurl' ) ) ? sanitize_text_field( get_option( 'wpcf_redurl' ) ) : esc_url( home_url( '/' ) );
		return $cf_red_url;
	}

	public function loved_count( $user_id = 0 ) {
		if ( $user_id == 0 ) {
			if ( is_user_logged_in() ) {
				$user_id            = get_current_user_id();
				$loved_campaign_ids = array();
				$prev_campaign_ids  = get_user_meta( $user_id, 'loved_campaign_ids', true );
				if ( $prev_campaign_ids ) {
					$loved_campaign_ids = json_decode( $prev_campaign_ids, true );
					return count( $loved_campaign_ids );
				}
			}
		}
		return 0;
	}

	public function author_campaigns( $author_id = 0 ) {
		if ( ! $author_id ) {
			$author_id = get_current_user_id();
		}
		$args      = array(
			'post_status'    => 'publish',
			'post_type'      => 'product',
			'author'         => $author_id,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'crowdfunding',
				),
			),
			'posts_per_page' => -1,
		);
		$the_query = new \WP_Query( $args );

		return $the_query;
	}

	public function url( $url ) {
		if ( ! preg_match( '~^(?:f|ht)tps?://~i', $url ) ) {
			$url = 'http://' . $url;
		}
		return $url;
	}

	public function get_embeded_video( $url ) {
		if ( ! empty( $url ) ) {
			$embeded = wp_oembed_get( $url );

			if ( $embeded == false ) {
				$format = '';
				$url    = strtolower( $url );
				if ( strpos( $url, '.mp4' ) ) {
					$format = 'mp4';
				} elseif ( strpos( $url, '.ogg' ) ) {
					$format = 'ogg';
				} elseif ( strpos( $url, '.webm' ) ) {
					$format = 'WebM';
				}
				$embeded = '<video controls><source src="' . $url . '" type="video/' . $format . '">' . __( 'Your browser does not support the video tag.', 'wp-crowdfunding' ) . '</video>';
			}
			return '<div class="wpneo-video-wrapper">' . $embeded . '</div>';
		} else {
			return false;
		}
	}

	// Pagination
	function get_pagination( $page_numb, $max_page ) {
		$html  = '';
		$big   = 999999999; // need an unlikely integer
		$html .= '<div class="wpneo-pagination">';
		$html .= paginate_links(
			array(
				'base'              => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'            => '?paged=%#%',
				'current'           => $page_numb,
				'total'             => $max_page,
				'type'              => 'list',
				'after_page_number' => '',
			)
		);
		$html .= '</div>';
		return $html;
	}

	public function campaign_single_love_this() {
		global $post;
		if ( is_product() ) {
			if ( function_exists( 'get_product' ) ) {
				$product = wc_get_product( $post->ID );
				if ( $product->is_type( 'crowdfunding' ) ) {
					wpcf_function()->template( 'include/love_campaign' );
				}
			}
		}
	}

	public function total_goal( $campaign_id ) {
		return $funding_goal = get_post_meta( $campaign_id, '_nf_funding_goal', true );
	}

	public function price( $price, $args = array() ) {
		return wc_price( $price, $args = array() );
	}

	public function get_total( $context = 'view' ) {
		return $this->get_prop( 'total', $context );
	}


	public function get_date_remaining( $post_id = 0 ) {
		global $post;
		if ( $post_id == 0 ) {
			$post_id = $post->ID;
		}
		$enddate = get_post_meta( $post_id, '_nf_duration_end', true );
		if ( ( strtotime( $enddate ) + 86399 ) > time() ) {
			$diff = strtotime( $enddate ) - time();
			$temp = $diff / 86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day
			$days = floor( $temp );
			return $days >= 1 ? $days : 1; // Return min one days, though if remain only 1 min
		}
		return 0;
	}

	public function is_reach_target_goal( $campaign_id = '', $donate_total = null ) {
		$funding_goal = get_post_meta( $campaign_id, '_nf_funding_goal', true );

		$raised = $donate_total ? $this->get_total_fund( $campaign_id ) + $donate_total : $this->get_total_fund( $campaign_id );

		if ( $raised >= $funding_goal ) {
			return true;
		} else {
			return false;
		}
	}

	public function is_campaign_valid( $campaign_id = '', $donate_total = null ) {
		global $post;
		if ( ! $campaign_id ) {
			$campaign_id = $post->ID;
		}
		$_nf_duration_start = get_post_meta( $campaign_id, '_nf_duration_start', true );

		if ( $_nf_duration_start ) {
			if ( strtotime( $_nf_duration_start ) > time() ) {
				return false;
			}
		}

		$campaign_end_method = get_post_meta( $campaign_id, 'wpneo_campaign_end_method', true );

		switch ( $campaign_end_method ) {

			case 'target_goal':
				if ( $this->is_reach_target_goal( $campaign_id, $donate_total ) ) {
					return false;
				} else {
					return true;
				}
				break;

			case 'target_date':
				if ( $this->is_reach_target_goal( $campaign_id, $donate_total ) ) {
					return false;
				} elseif ( ! $this->is_reach_target_goal( $campaign_id, $donate_total ) && $this->get_date_remaining( $campaign_id ) ) {
					return true;
				} elseif ( $this->get_date_remaining( $campaign_id ) ) {
					return true;
				} else {
					return false;
				}
				break;

			case 'target_goal_and_date':
				if ( $this->is_reach_target_goal( $campaign_id, $donate_total ) ) {
					return false;
				} elseif ( $this->get_date_remaining( $campaign_id ) ) {
					return true;
				} elseif ( ! $this->is_reach_target_goal( $campaign_id, $donate_total ) ) {
					return true;
				} else {
					return false;
				}
				break;

			case 'never_end':
				return true;
				break;

			default:
				return false;
		}
	}

	/**
	 * @param $campaign_id
	 * @return mixed
	 *
	 * Get Total funded amount by a campaign
	 */
	public function get_total_fund( $campaign_id = 0 ) {
		global $wpdb, $post;
		if ( $campaign_id == 0 ) {
			$campaign_id = $post->ID;
		}
		$od = get_option( 'woocommerce_custom_orders_table_enabled' );
		// WPML compatibility.
		if ( apply_filters( 'wpml_setting', false, 'setup_complete' ) ) {
			$type         = apply_filters( 'wpml_element_type', get_post_type( $campaign_id ) );
			$trid         = apply_filters( 'wpml_element_trid', null, $campaign_id, $type );
			$translations = apply_filters( 'wpml_get_element_translations', null, $trid, $type );
			$campaign_ids = wp_list_pluck( $translations, 'element_id' );
		} else {
			$campaign_ids = array( $campaign_id );
		}
		$placeholders = implode( ',', array_fill( 0, count( $campaign_ids ), '%d' ) );

		if ( ! $this->hpos_enabled() ) {
			$query = "SELECT 
                SUM(ltoim.meta_value) as total_sales_amount 
            FROM 
                {$wpdb->prefix}woocommerce_order_itemmeta woim 
            LEFT JOIN 
                {$wpdb->prefix}woocommerce_order_items oi ON woim.order_item_id = oi.order_item_id 
            LEFT JOIN 
                {$wpdb->prefix}posts wpposts ON order_id = wpposts.ID 
            LEFT JOIN 
                {$wpdb->prefix}woocommerce_order_itemmeta ltoim ON ltoim.order_item_id = oi.order_item_id AND ltoim.meta_key = '_line_total' 
            WHERE 
                woim.meta_key = '_product_id' AND woim.meta_value IN ($placeholders) AND wpposts.post_status = 'wc-completed';";
		} else {
			$query = "SELECT 
                SUM(ltoim.meta_value) as total_sales_amount
                FROM
                    {$wpdb->prefix}woocommerce_order_itemmeta woim
                LEFT JOIN
                    {$wpdb->prefix}woocommerce_order_items oi ON woim.order_item_id = oi.order_item_id
                LEFT JOIN
                    {$wpdb->prefix}wc_orders wco ON oi.order_id = wco.id
                LEFT JOIN
                    {$wpdb->prefix}woocommerce_order_itemmeta ltoim ON ltoim.order_item_id = oi.order_item_id AND ltoim.meta_key = '_line_total'
                WHERE
                woim.meta_key = '_product_id'
                AND woim.meta_value IN ($placeholders)
                AND wco.status = 'wc-completed';
            ";
		}
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- $query is built above from $wpdb table names and a %d placeholder list only.
		$wp_sql = $wpdb->get_row( $wpdb->prepare( $query, $campaign_ids ) );

		return $wp_sql->total_sales_amount;
	}

	/**
	 * @param $campaign_id
	 * @return mixed
	 *
	 * Get total campaign goal
	 */
	public function get_total_goal( $campaign_id ) {
		return $funding_goal = get_post_meta( $campaign_id, '_nf_funding_goal', true );
	}

	/**
	 * @param $campaign_id
	 * @return int|string
	 *
	 * Return total percent funded for a campaign
	 */
	public function get_raised_percent( $campaign_id = 0 ) {
		global $post;
		$percent = 0;
		if ( $campaign_id == 0 ) {
			$campaign_id = $post->ID;
		}
		$total = $this->get_total_fund( $campaign_id );
		$goal  = $this->get_total_goal( $campaign_id );
		if ( $total > 0 && $goal > 0 ) {
			$percent = ( $goal < $total ) ? '100' : number_format( $total / $goal * 100, 2, '.', '' );
		}
		return $percent;
	}

	public function get_fund_raised_percent_format() {
		return $this->get_raised_percent() . '%';
	}

	public function get_campaign_orders_id_list( $post_id = null ) {

		global $wpdb, $post;
		$prefix  = $wpdb->prefix;
		$post_id = ( $post_id ) ? $post_id : $post->ID;

		$order_ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT order_id
				FROM {$wpdb->prefix}woocommerce_order_itemmeta woim
				LEFT JOIN {$wpdb->prefix}woocommerce_order_items oi ON woim.order_item_id = oi.order_item_id
				WHERE meta_key = '_product_id' AND meta_value = %d
				GROUP BY order_id ORDER BY order_id DESC",
				$post_id
			)
		);

		return $order_ids;
	}

	public function totalBackers() {
		return $this->get_total_backers();
	}
	public function get_total_backers() {
		$order_ids = $this->get_orders_by_ids(
			$this->get_campaign_orders_id_list(),
			array(
				'type'   => 'shop_order',
				'status' => 'wc-completed',
				'return' => 'ids',
			)
		);
		return count( $order_ids );
	}

	/**
	 * Completed orders of the current campaign as a WP_Query.
	 *
	 * Only works with legacy (posts) order storage. Kept for backward
	 * compatibility; use get_orders_by_ids() instead.
	 *
	 * @return \WP_Query|false
	 */
	public function get_customers_by_product_query() {
		$order_ids = $this->get_campaign_orders_id_list();
		if ( $order_ids ) {
			$args   = array(
				'post_type'      => 'shop_order',
				'post__in'       => $order_ids,
				'posts_per_page' => 999,
				'order'          => 'ASC',
				'post_status'    => 'wc-completed',
			);
			$orders = new \WP_Query( $args );
			return $orders;
		}
		return false;
	}

	public function getCustomersByProduct( $post_id = null ) {
		return $this->get_customers_product( $post_id );
	}
	public function get_customers_product( $post_id = null ) {
		$order_ids = $this->get_campaign_orders_id_list( $post_id );
		return $order_ids;
	}

	public function get_campaign_update_status() {

		global $post;
		$saved_campaign_update   = get_post_meta( $post->ID, 'wpneo_campaign_updates', true );
		$saved_campaign_update_a = json_decode( $saved_campaign_update, true );

		$html  = '';
		$html .= "<div class='campaign_update_wrapper'>";

		$html .= '<h3>';
		$html .= apply_filters( 'wpcf_campaign_update_title', sprintf( /* translators: %s: campaign title */ __( '%s\'s Update', 'wp-crowdfunding' ), $post->post_title ) );
		$html .= '</h3>';

		if ( is_array( $saved_campaign_update_a ) ) {
			if ( count( $saved_campaign_update_a ) > 0 ) {
				$html .= '<table class="table table-border">';
				$html .= '<tr>';
				foreach ( $saved_campaign_update_a[0] as $k => $v ) {
					$html .= '<th>';
					$html .= ucfirst( $k );
					$html .= '</th>';
				}
				$html .= '</tr>';

				foreach ( $saved_campaign_update_a as $key => $value ) {
					$html .= '<tr>';
					foreach ( $value as $k => $v ) {
						$html .= '<td>';
						$html .= $v;
						$html .= '</td>';
					}
					$html .= '</tr>';
				}
				$html .= '</table>';
			}
		}
		$html .= '</div>';

		echo wp_kses_post( $html );
	}

	function limit_word_text( $text, $limit ) {
		if ( $this->mb_str_word_count( $text, 0 ) > $limit ) {
			$words = $this->mb_str_word_count( $text, 2 );
			$pos   = array_keys( $words );
			$text  = mb_substr( $text, 0, $pos[ $limit ] );
		}
		return $text;
	}

	function mb_str_word_count( $string, $format = 0, $charlist = '[]' ) {
		mb_internal_encoding( 'UTF-8' );
		mb_regex_encoding( 'UTF-8' );
		$words = mb_split( '[^\x{0600}-\x{06FF}]', $string );
		switch ( $format ) {
			case 0:
				return count( $words );
				break;
			case 1:
			case 2:
				return $words;
				break;
			default:
				return $words;
				break;
		}
	}

	public function is_campaign_started( $post_id = 0 ) {
		global $post;

		if ( ! $post_id ) {
			$post_id = $post->ID;
		}

		$_nf_duration_start = get_post_meta( $post_id, '_nf_duration_start', true );

		if ( $_nf_duration_start ) {
			if ( strtotime( $_nf_duration_start ) > time() ) {
				return false;
			}
		}

		return true;
	}


	public function campaign_start_countdown( $post_id = 0 ) {
		global $post;

		if ( ! $post_id ) {
			$post_id = $post->ID;
		}
		$_nf_duration_start = get_post_meta( $post->ID, '_nf_duration_start', true );

		?>
		<p class="wpcf-start-campaign-countdown"><?php esc_html_e( 'Campaign will be started within', 'wp-crowdfunding' ); ?> <span id="wpcf-campaign-countdown"></span></p>

		<script type="text/javascript">
			// Set the date we're counting down to
			let wpcfCountDownDate = "<?php echo esc_js( $_nf_duration_start ); ?>".split("-");
			wpcfCountDownDate = new Date(wpcfCountDownDate[1] + "/" + wpcfCountDownDate[0] + "/" + wpcfCountDownDate[2]).getTime();

			// Update the count down every 1 second
			let wpcfIntervalE = setInterval(function() {
				// Get towpcfDays date and time
				const dateDiff = wpcfCountDownDate - new Date().getTime();

				// Time calculations
				let wpcfDays = Math.floor(dateDiff / 86400000);
				let wpcfHours = Math.floor((dateDiff % 86400000) / 3600000);
				let wpcfMinutes = Math.floor((dateDiff % 3600000) / 60000);
				let wpcfSeconds = Math.floor((dateDiff % 60000) / 1000);

				// Display the result in the element with id="wpcf-campaign-countdown"
				document.getElementById("wpcf-campaign-countdown").innerHTML = '<span>' + wpcfDays + '</span>' + "d " + "<span>" + wpcfHours + "h </span> <span> " + wpcfMinutes + "m </span> <span> " + wpcfSeconds + "s </span> ";

				// If the count down is finished, write some text
				if (dateDiff < 0) {
					clearInterval(wpcfIntervalE);
					document.getElementById("wpcf-campaign-countdown").innerHTML = "";
				}
			}, 1000);
		</script>

		<?php
	}

	public function days_until_launch( $post_id = 0 ) {
		global $post;

		if ( ! $post_id ) {
			$post_id = $post->ID;
		}
		$_nf_duration_start = get_post_meta( $post->ID, '_nf_duration_start', true );

		if ( ( strtotime( $_nf_duration_start ) ) > time() ) {
			$diff = strtotime( $_nf_duration_start ) - time();
			$temp = $diff / 86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day
			$days = floor( $temp );
			return $days >= 1 ? $days : 1; // Return min one days, though if remain only 1 min
		}

		return 0;
	}


	// Compatibilities
	function dateRemaining( $post_id = 0 ) {
		return $this->get_date_remaining( $post_id = 0 );
	}
	function campaignValid() {
		return $this->is_campaign_valid();
	}
	function totalFundRaisedByCampaign( $campaign_id = 0 ) {
		return $this->get_total_fund( $campaign_id = 0 );
	}
	function totalGoalByCampaign( $campaign_id ) {
		return $this->get_total_goal( $campaign_id );
	}
	function getFundRaisedPercent() {
		return $this->get_raised_percent( $campaign_id = 0 );
	}
	function getFundRaisedPercentFormat() {
		return $this->get_fund_raised_percent_format();
	}

	/**
	 * Allowed HTML for markup the plugin builds itself and that contains form
	 * fields or embeds (dashboard forms, settings fields, donate form, video).
	 *
	 * @since 2.2.3
	 *
	 * @return array
	 */
	public function allowed_html() {
		static $allowed = null;
		if ( null !== $allowed ) {
			return $allowed;
		}
		$common  = array(
			'id'          => true,
			'class'       => true,
			'style'       => true,
			'name'        => true,
			'title'       => true,
			'disabled'    => true,
			'readonly'    => true,
			'required'    => true,
			'placeholder' => true,
			'aria-label'  => true,
			'data-*'      => true,
		);
		$allowed = array_merge(
			wp_kses_allowed_html( 'post' ),
			array(
				'form'     => $common + array( 'action' => true, 'method' => true, 'enctype' => true, 'autocomplete' => true, 'novalidate' => true ),
				'input'    => $common + array( 'type' => true, 'value' => true, 'checked' => true, 'min' => true, 'max' => true, 'step' => true, 'size' => true, 'maxlength' => true, 'autocomplete' => true, 'multiple' => true, 'accept' => true, 'pattern' => true ),
				'select'   => $common + array( 'multiple' => true, 'size' => true ),
				'option'   => array( 'value' => true, 'selected' => true, 'disabled' => true, 'class' => true, 'data-*' => true ),
				'optgroup' => array( 'label' => true, 'disabled' => true ),
				'textarea' => $common + array( 'rows' => true, 'cols' => true, 'maxlength' => true ),
				'button'   => $common + array( 'type' => true, 'value' => true ),
				'label'    => array( 'for' => true, 'class' => true, 'id' => true, 'style' => true ),
				'fieldset' => array( 'class' => true, 'id' => true ),
				'legend'   => array( 'class' => true ),
				'canvas'   => array( 'id' => true, 'class' => true, 'width' => true, 'height' => true ),
				'iframe'   => array( 'src' => true, 'width' => true, 'height' => true, 'frameborder' => true, 'allow' => true, 'allowfullscreen' => true, 'title' => true, 'loading' => true, 'referrerpolicy' => true, 'class' => true, 'id' => true, 'style' => true ),
				'video'    => array( 'src' => true, 'width' => true, 'height' => true, 'controls' => true, 'poster' => true, 'preload' => true, 'class' => true, 'id' => true, 'style' => true, 'autoplay' => true, 'loop' => true, 'muted' => true, 'playsinline' => true ),
				'source'   => array( 'src' => true, 'type' => true ),
				'bdi'      => array(),
			)
		);
		// WooCommerce images are responsive; keep their srcset and loading hints.
		$allowed['img'] = array_merge(
			isset( $allowed['img'] ) ? $allowed['img'] : array(),
			array( 'srcset' => true, 'sizes' => true, 'loading' => true, 'decoding' => true, 'fetchpriority' => true )
		);
		// Marker so the kses filters below only relax CSS for this allow-list.
		$allowed[ self::KSES_MARKER ] = array();

		add_filter( 'pre_kses', array( $this, 'track_kses_context' ), 10, 2 );
		add_filter( 'safe_style_css', array( $this, 'allow_display_style' ) );

		return $allowed;
	}

	/**
	 * Marker key identifying the plugin's own allow-list inside kses filters.
	 */
	const KSES_MARKER = 'wpcf-allowed-html';

	/**
	 * Whether the wp_kses() call currently running uses allowed_html().
	 *
	 * @var bool
	 */
	private $kses_is_plugin_markup = false;

	/**
	 * Record, at the start of every wp_kses() call, whether it uses the plugin allow-list.
	 *
	 * @since 2.2.3
	 *
	 * @param string       $content      Content being filtered.
	 * @param array|string $allowed_html Allowed HTML for this call.
	 * @return string Unchanged content.
	 */
	public function track_kses_context( $content, $allowed_html ) {
		$this->kses_is_plugin_markup = is_array( $allowed_html ) && isset( $allowed_html[ self::KSES_MARKER ] );
		return $content;
	}

	/**
	 * Allow the CSS display property, used by the plugin's show/hide toggles,
	 * only while filtering the plugin's own markup.
	 *
	 * @since 2.2.3
	 *
	 * @param string[] $properties Allowed CSS properties.
	 * @return string[]
	 */
	public function allow_display_style( $properties ) {
		if ( $this->kses_is_plugin_markup ) {
			$properties[] = 'display';
		}
		return $properties;
	}

	/**
	 * Whether visitors may register through the plugin's registration form.
	 *
	 * Defaults to allowed so existing installs keep working until an admin turns it off.
	 *
	 * @since 2.2.3
	 *
	 * @return bool
	 */
	public function is_registration_allowed() {
		return 'false' !== get_option( 'wpcf_allow_registration', 'true' );
	}

	/**
	 * Whether the "Backed User" tab is shown on campaign product pages.
	 *
	 * Set in WP Crowdfunding > Settings > General. Defaults to shown so
	 * existing installs keep working until an admin turns it off.
	 *
	 * @since 2.2.3
	 *
	 * @return bool
	 */
	public function is_backed_user_tab_enabled() {
		return 'false' !== get_option( 'wpcf_show_backed_user_tab', 'true' );
	}

	/**
	 * Whether a column of the "Backed User" tab is shown.
	 *
	 * Set in WP Crowdfunding > Settings > General. Defaults to shown so
	 * existing installs keep working until an admin turns it off.
	 *
	 * @since 2.2.3
	 *
	 * @param string $column 'email' or 'amount'.
	 * @return bool
	 */
	public function is_backed_user_column_enabled( $column ) {
		$options = array(
			'email'  => 'wpcf_backed_user_show_email',
			'amount' => 'wpcf_backed_user_show_amount',
		);
		if ( ! isset( $options[ $column ] ) ) {
			return false;
		}
		return 'false' !== get_option( $options[ $column ], 'true' );
	}

	/**
	 * Rewards of a campaign, sorted by pledge amount ascending.
	 *
	 * This is the same order the reward forms render in, so the 1-based
	 * `wpneo_rewards_index` they post maps to `$rewards[ $index - 1 ]`.
	 *
	 * @since 2.2.3
	 *
	 * @param int $campaign_id Campaign (product) ID.
	 * @return array
	 */
	public function get_sorted_campaign_rewards( $campaign_id ) {
		$raw = get_post_meta( absint( $campaign_id ), 'wpneo_reward', true );
		if ( empty( $raw ) || ! is_string( $raw ) ) {
			return array();
		}

		$rewards = json_decode( $raw, true );
		if ( ! is_array( $rewards ) ) {
			$rewards = json_decode( stripslashes( $raw ), true );
		}
		if ( ! is_array( $rewards ) ) {
			return array();
		}

		$amount = array();
		foreach ( $rewards as $key => $row ) {
			if ( ! is_array( $row ) ) {
				unset( $rewards[ $key ] );
				continue;
			}
			$rewards[ $key ]['wpneo_rewards_pladge_amount'] = isset( $row['wpneo_rewards_pladge_amount'] ) ? intval( $row['wpneo_rewards_pladge_amount'] ) : 0;
			$amount[ $key ] = $rewards[ $key ]['wpneo_rewards_pladge_amount'];
		}
		array_multisort( $amount, SORT_ASC, $rewards );

		return array_values( $rewards );
	}

	/**
	 * Get one reward of a campaign from the server-side data.
	 *
	 * @since 2.2.3
	 *
	 * @param int $campaign_id  Campaign (product) ID.
	 * @param int $reward_index 1-based index as posted by the reward forms.
	 * @return array|null Reward data, or null when it does not exist.
	 */
	public function get_campaign_reward( $campaign_id, $reward_index ) {
		$reward_index = absint( $reward_index );
		if ( $reward_index < 1 ) {
			return null;
		}
		$rewards = $this->get_sorted_campaign_rewards( $campaign_id );
		return isset( $rewards[ $reward_index - 1 ] ) ? $rewards[ $reward_index - 1 ] : null;
	}

	/**
	 * Whether a reward still has backer slots left.
	 *
	 * Mirrors the "Reward no longer available" check in the reward templates.
	 *
	 * @since 2.2.3
	 *
	 * @param int $campaign_id  Campaign (product) ID.
	 * @param int $reward_index 1-based reward index.
	 * @return bool
	 */
	public function is_reward_available( $campaign_id, $reward_index ) {
		$rewards = $this->get_sorted_campaign_rewards( $campaign_id );
		$pos     = absint( $reward_index ) - 1;
		if ( $pos < 0 || ! isset( $rewards[ $pos ] ) ) {
			return false;
		}

		$reward = $rewards[ $pos ];
		$limit  = isset( $reward['wpneo_rewards_item_limit'] ) ? intval( $reward['wpneo_rewards_item_limit'] ) : 0;
		if ( $limit <= 0 || ! function_exists( 'wpcf_campaign_order_number_data' ) ) {
			return true;
		}

		$min_data = intval( $reward['wpneo_rewards_pladge_amount'] );
		$max_data = isset( $rewards[ $pos + 1 ] ) && ! empty( $rewards[ $pos + 1 ]['wpneo_rewards_pladge_amount'] )
			? intval( $rewards[ $pos + 1 ]['wpneo_rewards_pladge_amount'] ) - 1
			: 9000000000;
		$orders   = (int) wpcf_campaign_order_number_data( $min_data, $max_data, $campaign_id );

		return $orders < $limit;
	}

	/**
	 * A price formatted as plain text, for messages shown outside HTML.
	 *
	 * @since 2.2.3
	 *
	 * @param float|string $amount Amount.
	 * @return string
	 */
	private function plain_price( $amount ) {
		return trim( html_entity_decode( wp_strip_all_tags( wc_price( $amount ) ), ENT_QUOTES, get_bloginfo( 'charset' ) ) );
	}

	/**
	 * Validate a pledge amount on the server.
	 *
	 * The minimum, maximum and reward tier amounts are also enforced in the
	 * browser, but that can be bypassed, so every add-to-cart path must
	 * validate here before the amount is stored or used as a cart price.
	 *
	 * @since 2.2.3
	 *
	 * @param int   $campaign_id  Campaign (product) ID.
	 * @param mixed $raw_amount   Amount as submitted (unslashed or not).
	 * @param int   $reward_index Optional 1-based reward index (0 = no reward).
	 * @return float|\WP_Error Validated amount, or an error.
	 */
	public function validate_pledge_amount( $campaign_id, $raw_amount, $reward_index = 0 ) {
		$campaign_id = absint( $campaign_id );
		$product     = $campaign_id ? wc_get_product( $campaign_id ) : false;

		if ( ! $product || ! $product->is_type( 'crowdfunding' ) || 'publish' !== get_post_status( $campaign_id ) ) {
			return new \WP_Error( 'wpcf_invalid_campaign', __( 'This campaign is not available.', 'wp-crowdfunding' ) );
		}

		if ( ! $this->is_campaign_valid( $campaign_id ) ) {
			return new \WP_Error( 'wpcf_campaign_closed', __( 'This campaign is no longer accepting pledges.', 'wp-crowdfunding' ) );
		}

		if ( is_array( $raw_amount ) || is_object( $raw_amount ) ) {
			return new \WP_Error( 'wpcf_invalid_amount', __( 'Please enter a valid pledge amount.', 'wp-crowdfunding' ) );
		}

		$amount = wc_format_decimal( trim( wp_unslash( (string) $raw_amount ) ) );
		if ( '' === $amount || ! is_numeric( $amount ) ) {
			return new \WP_Error( 'wpcf_invalid_amount', __( 'Please enter a valid pledge amount.', 'wp-crowdfunding' ) );
		}

		$amount = (float) $amount;
		if ( ! is_finite( $amount ) || $amount <= 0 ) {
			return new \WP_Error( 'wpcf_invalid_amount', __( 'Please enter a valid pledge amount.', 'wp-crowdfunding' ) );
		}

		$min_price = get_post_meta( $campaign_id, 'wpneo_funding_minimum_price', true );
		if ( is_numeric( $min_price ) && (float) $min_price > 0 && $amount < (float) $min_price ) {
			return new \WP_Error(
				'wpcf_amount_below_min',
				/* translators: %s: minimum pledge amount */
				sprintf( __( 'The minimum pledge amount is %s.', 'wp-crowdfunding' ), $this->plain_price( $min_price ) )
			);
		}

		$max_price = get_post_meta( $campaign_id, 'wpneo_funding_maximum_price', true );
		if ( is_numeric( $max_price ) && (float) $max_price > 0 && $amount > (float) $max_price ) {
			return new \WP_Error(
				'wpcf_amount_above_max',
				/* translators: %s: maximum pledge amount */
				sprintf( __( 'The maximum pledge amount is %s.', 'wp-crowdfunding' ), $this->plain_price( $max_price ) )
			);
		}

		$reward_index = absint( $reward_index );
		if ( $reward_index > 0 ) {
			$reward = $this->get_campaign_reward( $campaign_id, $reward_index );
			if ( null === $reward ) {
				return new \WP_Error( 'wpcf_invalid_reward', __( 'The selected reward is not available.', 'wp-crowdfunding' ) );
			}
			if ( ! $this->is_reward_available( $campaign_id, $reward_index ) ) {
				return new \WP_Error( 'wpcf_reward_sold_out', __( 'The selected reward is no longer available.', 'wp-crowdfunding' ) );
			}
			$reward_amount = (float) $reward['wpneo_rewards_pladge_amount'];
			if ( $amount < $reward_amount ) {
				return new \WP_Error(
					'wpcf_amount_below_reward',
					/* translators: %s: reward pledge amount */
					sprintf( __( 'The selected reward requires a pledge of at least %s.', 'wp-crowdfunding' ), $this->plain_price( $reward_amount ) )
				);
			}
		}

		return $amount;
	}
}