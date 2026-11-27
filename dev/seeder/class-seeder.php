<?php
/**
 * Test-data seeder for WP Crowdfunding (development only, excluded from the build).
 *
 * Works with every WooCommerce order storage:
 * - WooCommerce < 7.1 (orders as posts only),
 * - WooCommerce >= 7.1 with HPOS off, on, or on with compatibility sync.
 *
 * Orders are written only through the WooCommerce CRUD API so they land in
 * whichever storage is active. Seeded IDs are kept in an option registry, so
 * cleanup never depends on order meta queries that differ between storages.
 *
 * @package WPCF\Dev
 */

namespace WPCF\Dev;

defined( 'ABSPATH' ) || exit;

class Seeder {

	const REGISTRY = '_wpcf_seeder_registry';
	const BACKUP   = '_wpcf_seeder_backup';
	const MARK     = '_wpcf_seeded';
	const PASSWORD = 'password';

	/** Expected states, using the same wording as the campaign button template. */
	const OPEN       = 'open';
	const UPCOMING   = 'upcoming';
	const SUCCESSFUL = 'successful';
	const CLOSED     = 'closed';

	/** @var array */
	private $registry = array();

	/** @var array Seeded users by key. */
	private $users = array();

	/** @var array Seeded category term IDs by key. */
	private $cats = array();

	/** @var int[] Placeholder image attachment IDs. */
	private $images = array();

	/** @var array Campaign IDs by key. */
	private $campaign_ids = array();

	/** @var int Base timestamp for all relative dates. */
	private $now;

	/**
	 * Remove previous seed data, then seed everything again.
	 *
	 * @param bool $apply_options Also switch on the plugin options that the test data needs.
	 * @return array Summary counts.
	 */
	public function seed( $apply_options = false ) {
		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_create_order' ) ) {
			throw new \RuntimeException( 'WooCommerce must be active to seed crowdfunding data.' );
		}

		$this->unseed();

		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 0 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}
		wp_suspend_cache_addition( true );
		// No emails for seeded users and orders (WP 5.7+, the plugin requires 5.9).
		add_filter( 'pre_wp_mail', '__return_false', 999 );

		mt_srand( 20261127 );
		// The plugin compares strtotime( 'd-m-Y' ) with time(), so dates are built from time() too.
		$this->now      = time();
		$this->registry = array(
			'version'     => 1,
			'seeded_at'   => time(),
			'orders'      => array(),
			'products'    => array(),
			'users'       => array(),
			'terms'       => array(),
			'comments'    => array(),
			'attachments' => array(),
			'campaigns'   => array(),
			'user_list'   => array(),
		);
		// Save as we go, so a half-finished seed can still be removed.
		$this->save_registry();

		if ( $apply_options ) {
			$this->apply_options();
		}

		$this->seed_images();
		$this->seed_users();
		$this->seed_terms();
		$this->seed_campaigns();
		$this->seed_bookmarks();
		$this->seed_reviews();

		$this->save_registry();

		remove_filter( 'pre_wp_mail', '__return_false', 999 );
		wp_suspend_cache_addition( false );

		return array(
			'campaigns' => count( $this->registry['products'] ),
			'orders'    => count( $this->registry['orders'] ),
			'users'     => count( $this->registry['users'] ),
			'reviews'   => count( $this->registry['comments'] ),
			'images'    => count( $this->registry['attachments'] ),
		);
	}

	/**
	 * Delete everything listed in the registry and restore backed-up options.
	 *
	 * @return array Deleted counts.
	 */
	public function unseed() {
		$reg     = self::get_registry();
		$deleted = array(
			'orders'    => 0,
			'campaigns' => 0,
			'users'     => 0,
		);

		if ( ! empty( $reg ) ) {
			add_filter( 'pre_wp_mail', '__return_false', 999 );

			// Orders first, through the CRUD API (works for posts and HPOS storage).
			if ( function_exists( 'wc_get_order' ) ) {
				foreach ( (array) $reg['orders'] as $order_id ) {
					$order = wc_get_order( $order_id );
					if ( $order ) {
						$order->delete( true );
						$deleted['orders']++;
					}
				}
			}

			foreach ( (array) $reg['comments'] as $comment_id ) {
				wp_delete_comment( $comment_id, true );
			}

			foreach ( (array) $reg['products'] as $product_id ) {
				if ( get_post( $product_id ) ) {
					wp_delete_post( $product_id, true );
					$deleted['campaigns']++;
				}
			}

			foreach ( (array) $reg['attachments'] as $attachment_id ) {
				wp_delete_attachment( $attachment_id, true );
			}

			foreach ( (array) $reg['terms'] as $term ) {
				wp_delete_term( $term['id'], $term['taxonomy'] );
			}

			if ( ! empty( $reg['users'] ) ) {
				require_once ABSPATH . 'wp-admin/includes/user.php';
				foreach ( (array) $reg['users'] as $user_id ) {
					if ( (int) $user_id !== get_current_user_id() && get_userdata( $user_id ) ) {
						wp_delete_user( $user_id );
						$deleted['users']++;
					}
				}
			}

			remove_filter( 'pre_wp_mail', '__return_false', 999 );
		}

		$this->restore_options();
		delete_option( self::REGISTRY );

		if ( function_exists( 'wc_delete_product_transients' ) ) {
			wc_delete_product_transients();
		}

		return $deleted;
	}

	/**
	 * @return array Empty array when nothing is seeded.
	 */
	public static function get_registry() {
		$reg = get_option( self::REGISTRY );
		return is_array( $reg ) ? $reg : array();
	}

	/* ---------------------------------------------------------------------
	 * Options
	 * ------------------------------------------------------------------- */

	/**
	 * Enable every end method and the social share addon, backing up the old values.
	 */
	private function apply_options() {
		$options = array(
			'wpneo_show_target_goal'          => 'true',
			'wpneo_show_target_date'          => 'true',
			'wpneo_show_target_goal_and_date' => 'true',
			'wpneo_show_campaign_never_end'   => 'true',
			'wpcf_show_contributor_table'     => 'true',
			'wpcf_show_contributor_anonymity' => 'true',
		);

		$addons = maybe_unserialize( get_option( 'wpcf_addons_config' ) );
		$addons = is_array( $addons ) ? $addons : array();
		if ( defined( 'WPCF_SOCIAL_SHARE_BASE_NAME' ) ) {
			$addons[ WPCF_SOCIAL_SHARE_BASE_NAME ]['is_enable'] = 1;
		}
		$options['wpcf_addons_config'] = $addons;

		$backup = array();
		foreach ( $options as $name => $value ) {
			$current         = get_option( $name, null );
			$backup[ $name ] = array(
				'exists' => null !== $current,
				'value'  => $current,
			);
			update_option( $name, $value );
		}
		update_option( self::BACKUP, $backup, false );
	}

	private function restore_options() {
		$backup = get_option( self::BACKUP );
		if ( ! is_array( $backup ) ) {
			return;
		}
		foreach ( $backup as $name => $item ) {
			if ( ! empty( $item['exists'] ) ) {
				update_option( $name, $item['value'] );
			} else {
				delete_option( $name );
			}
		}
		delete_option( self::BACKUP );
	}

	/* ---------------------------------------------------------------------
	 * Images (generated locally with GD, no downloads)
	 * ------------------------------------------------------------------- */

	private function seed_images() {
		$palette = array(
			array( 'Solar', array( 242, 153, 74 ) ),
			array( 'Water', array( 47, 128, 237 ) ),
			array( 'Garden', array( 39, 174, 96 ) ),
			array( 'Music', array( 155, 81, 224 ) ),
			array( 'Books', array( 235, 87, 87 ) ),
			array( 'Tech', array( 51, 51, 51 ) ),
		);
		foreach ( $palette as $item ) {
			$id = $this->create_image( $item[0], $item[1] );
			if ( $id ) {
				$this->images[] = $id;
			}
		}
	}

	/**
	 * @return int Attachment ID, or 0 when GD is missing or the upload fails.
	 */
	private function create_image( $label, $rgb ) {
		if ( ! function_exists( 'imagecreatetruecolor' ) || ! function_exists( 'imagepng' ) ) {
			return 0;
		}

		$img   = imagecreatetruecolor( 800, 600 );
		$bg    = imagecolorallocate( $img, $rgb[0], $rgb[1], $rgb[2] );
		$white = imagecolorallocate( $img, 255, 255, 255 );
		imagefilledrectangle( $img, 0, 0, 800, 600, $bg );
		$text = 'SEED: ' . strtoupper( $label );
		imagestring( $img, 5, (int) ( 400 - strlen( $text ) * 4.5 ), 292, $text, $white );

		ob_start();
		imagepng( $img );
		$bytes = ob_get_clean();
		if ( PHP_VERSION_ID < 80000 ) {
			imagedestroy( $img ); // No-op since PHP 8.0 and deprecated in 8.5.
		}

		$upload = wp_upload_bits( 'wpcf-seed-' . sanitize_title( $label ) . '.png', null, $bytes );
		if ( ! empty( $upload['error'] ) ) {
			return 0;
		}

		$attachment_id = wp_insert_attachment(
			array(
				'post_mime_type' => 'image/png',
				'post_title'     => 'Seed image: ' . $label,
				'post_status'    => 'inherit',
			),
			$upload['file']
		);
		if ( ! $attachment_id || is_wp_error( $attachment_id ) ) {
			return 0;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
		update_post_meta( $attachment_id, self::MARK, 1 );
		$this->registry['attachments'][] = (int) $attachment_id;

		return (int) $attachment_id;
	}

	private function image( $index ) {
		if ( empty( $this->images ) ) {
			return 0;
		}
		return $this->images[ $index % count( $this->images ) ];
	}

	/* ---------------------------------------------------------------------
	 * Users
	 * ------------------------------------------------------------------- */

	private function seed_users() {
		$creator_role = get_role( 'campaign_creator' ) ? 'campaign_creator' : 'author';
		$backer_role  = get_role( 'customer' ) ? 'customer' : 'subscriber';

		$defs = array(
			'creator_full'    => array( $creator_role, 'Ava', 'Campbell', 'Creator with full profile and several campaigns' ),
			'creator_minimal' => array( $creator_role, 'Ben', 'Okafor', 'Creator with minimal profile' ),
			'creator_empty'   => array( $creator_role, 'Chloe', 'Nakamura', 'Creator with no campaigns (also backs others)' ),
			'backer_many'     => array( $backer_role, 'Dan', 'Moreau', 'Backer with many pledges' ),
			'backer_single'   => array( $backer_role, 'Eva', 'Lindqvist', 'Backer with exactly one pledge' ),
			'backer_anon'     => array( $backer_role, 'Farid', 'Haddad', 'Backer who pledges anonymously' ),
			'backer_bookmark' => array( $backer_role, 'Grace', 'Liu', 'Backer with bookmarks only, no pledges' ),
		);

		foreach ( $defs as $key => $def ) {
			$login = 'seed_' . $key;
			$email = 'seed-' . str_replace( '_', '-', $key ) . '@example.test';

			$user_id = wp_insert_user(
				array(
					'user_login'   => $login,
					'user_pass'    => self::PASSWORD,
					'user_email'   => $email,
					'first_name'   => $def[1],
					'last_name'    => $def[2],
					'display_name' => $def[1] . ' ' . $def[2],
					'role'         => $def[0],
				)
			);
			if ( is_wp_error( $user_id ) ) {
				// Left over from a seed whose registry was lost: reuse it.
				$existing = get_user_by( 'login', $login );
				if ( ! $existing ) {
					continue;
				}
				$user_id = $existing->ID;
			}

			update_user_meta( $user_id, self::MARK, 1 );
			$this->set_address_meta( $user_id, $def[1], $def[2], $email );

			$this->users[ $key ]             = (int) $user_id;
			$this->registry['users'][]       = (int) $user_id;
			$this->registry['user_list'][]   = array(
				'id'    => (int) $user_id,
				'login' => $login,
				'role'  => $def[0],
				'note'  => $def[3],
			);
		}

		// Full creator profile (dashboard "Profile" tab).
		$profile = array(
			'profile_name'         => 'Ava Campbell',
			'profile_website'      => 'https://example.test/ava',
			'profile_portfolio'    => 'https://example.test/ava/portfolio',
			'profile_about'        => 'Maker of community energy projects.',
			'profile_bio'          => 'Ava has run five community projects since 2019 and loves building things with neighbours.',
			'profile_mobile1'      => '+1 555 0100',
			'profile_email1'       => 'seed-creator-full@example.test',
			'profile_fax'          => '+1 555 0101',
			'profile_address'      => '12 Market Street, Springfield',
			'profile_facebook'     => 'https://facebook.com/example',
			'profile_twitter'      => 'https://twitter.com/example',
			'profile_vk'           => 'https://vk.com/example',
			'profile_linkedin'     => 'https://linkedin.com/in/example',
			'profile_pinterest'    => 'https://pinterest.com/example',
			'profile_show_contact' => 'true',
			'profile_image_id'     => $this->image( 5 ),
		);
		if ( isset( $this->users['creator_full'] ) ) {
			foreach ( $profile as $meta_key => $value ) {
				update_user_meta( $this->users['creator_full'], $meta_key, $value );
			}
		}
		if ( isset( $this->users['creator_minimal'] ) ) {
			update_user_meta( $this->users['creator_minimal'], 'profile_name', 'Ben Okafor' );
		}
	}

	private function set_address_meta( $user_id, $first, $last, $email ) {
		$address = array(
			'first_name' => $first,
			'last_name'  => $last,
			'company'    => '',
			'address_1'  => mt_rand( 1, 200 ) . ' Seed Avenue',
			'address_2'  => '',
			'city'       => 'Springfield',
			'state'      => 'CA',
			'postcode'   => '90210',
			'country'    => 'US',
		);
		foreach ( $address as $field => $value ) {
			update_user_meta( $user_id, 'billing_' . $field, $value );
			update_user_meta( $user_id, 'shipping_' . $field, $value );
		}
		update_user_meta( $user_id, 'billing_email', $email );
		update_user_meta( $user_id, 'billing_phone', '+1 555 01' . mt_rand( 10, 99 ) );
	}

	private function billing_address( $user_id ) {
		$fields  = array( 'first_name', 'last_name', 'address_1', 'city', 'state', 'postcode', 'country', 'email', 'phone' );
		$address = array();
		foreach ( $fields as $field ) {
			$address[ $field ] = get_user_meta( $user_id, 'billing_' . $field, true );
		}
		return $address;
	}

	/* ---------------------------------------------------------------------
	 * Categories and tags
	 * ------------------------------------------------------------------- */

	private function seed_terms() {
		$cats = array(
			'tech'      => array( 'Seed: Technology', true ),
			'community' => array( 'Seed: Community', true ),
			'arts'      => array( 'Seed: Arts & Music', true ),
			'unmarked'  => array( 'Seed: Not crowdfunding', false ),
		);
		foreach ( $cats as $key => $def ) {
			$term_id = $this->create_term( $def[0], 'product_cat' );
			if ( $term_id ) {
				$this->cats[ $key ] = $term_id;
				if ( $def[1] ) {
					update_term_meta( $term_id, '_marked_as_crowdfunding', '1' );
				}
			}
		}
		foreach ( array( 'seed-eco', 'seed-local', 'seed-hardware' ) as $tag ) {
			$this->create_term( $tag, 'product_tag' );
		}
	}

	private function create_term( $name, $taxonomy ) {
		$result = wp_insert_term( $name, $taxonomy, array( 'slug' => 'wpcf-' . sanitize_title( $name ) ) );
		if ( is_wp_error( $result ) ) {
			// Only reuse, never register, terms that existed before seeding.
			$existing = $result->get_error_data( 'term_exists' );
			return $existing ? (int) $existing : 0;
		}
		$term_id = (int) $result['term_id'];
		update_term_meta( $term_id, self::MARK, 1 );
		$this->registry['terms'][] = array(
			'id'       => $term_id,
			'taxonomy' => $taxonomy,
		);
		return $term_id;
	}

	/* ---------------------------------------------------------------------
	 * Campaigns and pledges
	 * ------------------------------------------------------------------- */

	/**
	 * One entry per situation to test. Days are relative to today.
	 *
	 * Keys: method, label, expected, goal, start, end, pct (completed pledges as
	 * percent of goal), status, creator, cat, plus optional extras.
	 */
	private function campaign_specs() {
		return array(
			array(
				'key'      => 'date_running_zero',
				'method'   => 'target_date',
				'label'    => 'Running, no pledges yet (0%)',
				'expected' => self::OPEN,
				'goal'     => 5000,
				'start'    => -5,
				'end'      => 30,
				'pct'      => 0,
			),
			array(
				'key'           => 'date_running_partial',
				'method'        => 'target_date',
				'label'         => 'Running, ~45% funded + non-completed orders that must NOT count',
				'expected'      => self::OPEN,
				'goal'          => 5000,
				'start'         => -10,
				'end'           => 20,
				'pct'           => 45,
				'noise_orders'  => true,
				'anon_backer'   => true,
			),
			array(
				'key'           => 'date_successful',
				'method'        => 'target_date',
				'label'         => 'Goal reached before end date',
				'expected'      => self::SUCCESSFUL,
				'goal'          => 2000,
				'start'         => -12,
				'end'           => 15,
				'pct'           => 100,
				'single_backer' => true,
			),
			array(
				'key'      => 'date_expired_failed',
				'method'   => 'target_date',
				'label'    => 'Expired, goal not reached (~20%)',
				'expected' => self::CLOSED,
				'goal'     => 8000,
				'start'    => -40,
				'end'      => -3,
				'pct'      => 20,
			),
			array(
				'key'      => 'date_expired_success',
				'method'   => 'target_date',
				'label'    => 'Expired and over-funded (120%)',
				'expected' => self::SUCCESSFUL,
				'goal'     => 3000,
				'start'    => -45,
				'end'      => -10,
				'pct'      => 120,
			),
			array(
				'key'      => 'date_ends_today',
				'method'   => 'target_date',
				'label'    => 'Ends today (edge case: should still be open, 1 day left)',
				'expected' => self::OPEN,
				'goal'     => 4000,
				'start'    => -30,
				'end'      => 0,
				'pct'      => 70,
			),
			array(
				'key'      => 'date_upcoming',
				'method'   => 'target_date',
				'label'    => 'Not started yet (starts in 7 days)',
				'expected' => self::UPCOMING,
				'goal'     => 6000,
				'start'    => 7,
				'end'      => 40,
				'pct'      => 0,
			),
			array(
				'key'         => 'goal_partial',
				'method'      => 'target_goal',
				'label'       => 'Open until goal, ~60% funded (includes a guest pledge)',
				'expected'    => self::OPEN,
				'goal'        => 2500,
				'start'       => -20,
				'end'         => 60,
				'pct'         => 60,
				'guest_order' => true,
			),
			array(
				'key'      => 'goal_exact',
				'method'   => 'target_goal',
				'label'    => 'Goal reached exactly (100%), closed',
				'expected' => self::SUCCESSFUL,
				'goal'     => 1500,
				'start'    => -20,
				'end'      => 60,
				'pct'      => 100,
			),
			array(
				'key'      => 'goal_overfunded',
				'method'   => 'target_goal',
				'label'    => 'Over-funded (250%)',
				'expected' => self::SUCCESSFUL,
				'goal'     => 1000,
				'start'    => -25,
				'end'      => 60,
				'pct'      => 250,
			),
			array(
				'key'      => 'goal_date_running',
				'method'   => 'target_goal_and_date',
				'label'    => 'Running, ~30% funded',
				'expected' => self::OPEN,
				'goal'     => 5000,
				'start'    => -8,
				'end'      => 25,
				'pct'      => 30,
			),
			array(
				'key'      => 'goal_date_past',
				'method'   => 'target_goal_and_date',
				'label'    => 'End date passed, not funded (closed)',
				'expected' => self::CLOSED,
				'goal'     => 5000,
				'start'    => -40,
				'end'      => -5,
				'pct'      => 40,
			),
			array(
				'key'      => 'goal_date_funded',
				'method'   => 'target_goal_and_date',
				'label'    => 'Funded before end date',
				'expected' => self::SUCCESSFUL,
				'goal'     => 2000,
				'start'    => -15,
				'end'      => 10,
				'pct'      => 110,
			),
			array(
				'key'         => 'never_end_backers',
				'method'      => 'never_end',
				'label'       => 'Never ends, many small pledges',
				'expected'    => self::OPEN,
				'goal'        => 10000,
				'start'       => -60,
				'end'         => '',
				'small_count' => 25,
			),
			array(
				'key'      => 'never_end_empty',
				'method'   => 'never_end',
				'label'    => 'Never ends, no pledges',
				'expected' => self::OPEN,
				'goal'     => 3000,
				'start'    => -3,
				'end'      => '',
				'pct'      => 0,
			),
			array(
				'key'      => 'invalid_no_method',
				'method'   => '',
				'label'    => 'No end method saved (invalid configuration)',
				'expected' => self::CLOSED,
				'goal'     => 2000,
				'start'    => -5,
				'end'      => 20,
				'pct'      => 10,
			),
			array(
				'key'      => 'status_draft',
				'method'   => 'target_date',
				'label'    => 'Draft (only visible in dashboard/admin)',
				'expected' => self::OPEN,
				'goal'     => 3000,
				'start'    => -1,
				'end'      => 30,
				'pct'      => 0,
				'status'   => 'draft',
			),
			array(
				'key'      => 'status_pending',
				'method'   => 'target_date',
				'label'    => 'Pending review (awaiting admin approval)',
				'expected' => self::OPEN,
				'goal'     => 3000,
				'start'    => -1,
				'end'      => 30,
				'pct'      => 0,
				'status'   => 'pending',
			),
			array(
				'key'      => 'rewards_limits',
				'method'   => 'target_date',
				'label'    => 'Rewards: sold out, partly claimed, unlimited, past delivery date',
				'expected' => self::OPEN,
				'goal'     => 2000,
				'start'    => -14,
				'end'      => 30,
				'rewards'  => array(
					array( 25, 'Thank-you postcard (limit 3, SOLD OUT)', 3, '+1', 3 ),
					array( 50, 'Sticker pack (limit 10, 4 claimed)', 10, '+2', 4 ),
					array( 100, 'Signed T-shirt (unlimited)', '', '+3', 2 ),
					array( 250, 'Early-bird kit (delivery date already passed)', 5, '-2', 0 ),
				),
			),
			array(
				'key'        => 'pledge_rules',
				'method'     => 'target_date',
				'label'      => 'Min/max/recommended + predefined amounts, anonymous contributors, contributor table',
				'expected'   => self::OPEN,
				'goal'       => 5000,
				'start'      => -9,
				'end'        => 21,
				'pct'        => 25,
				'min'        => 10,
				'max'        => 500,
				'recommend'  => 50,
				'predefined' => '10,25,50,100,250',
				'anonymous'  => true,
				'table'      => true,
				'anon_backer' => true,
				'rewards'    => array(
					array( 10, 'Digital thank-you', '', '+1', 0 ),
					array( 100, 'Name on the website', 20, '+2', 0 ),
				),
			),
			array(
				'key'      => 'rich_content',
				'method'   => 'target_date',
				'label'    => 'Rich content: video, location, gallery, campaign updates',
				'expected' => self::OPEN,
				'goal'     => 7000,
				'start'    => -18,
				'end'      => 12,
				'pct'      => 35,
				'rich'     => true,
				'table'    => true,
			),
			array(
				'key'      => 'minimal',
				'method'   => 'target_date',
				'label'    => 'Bare minimum: goal and dates only, no image/rewards/updates',
				'expected' => self::OPEN,
				'goal'     => 1000,
				'start'    => -2,
				'end'      => 28,
				'pct'      => 0,
				'bare'     => true,
			),
		);
	}

	private function seed_campaigns() {
		$creators = array( 'creator_full', 'creator_full', 'creator_minimal' );
		$cat_keys = array( 'tech', 'community', 'arts' );
		$tags     = array( 'wpcf-seed-eco', 'wpcf-seed-local', 'wpcf-seed-hardware' );

		foreach ( $this->campaign_specs() as $i => $spec ) {
			$creator_key = $creators[ $i % count( $creators ) ];
			$creator_id  = isset( $this->users[ $creator_key ] ) ? $this->users[ $creator_key ] : get_current_user_id();
			$status      = isset( $spec['status'] ) ? $spec['status'] : 'publish';
			$bare        = ! empty( $spec['bare'] );

			$post_id = wp_insert_post(
				array(
					'post_type'    => 'product',
					'post_status'  => $status,
					'post_author'  => $creator_id,
					'post_title'   => sprintf( '[Seed %02d] %s', $i + 1, $spec['label'] ),
					'post_excerpt' => $bare ? '' : 'Seeded test campaign: ' . $spec['label'],
					'post_content' => $bare ? '' : $this->campaign_content( $spec ),
				),
				true
			);
			if ( is_wp_error( $post_id ) ) {
				continue;
			}

			$this->registry['products'][]          = (int) $post_id;
			$this->campaign_ids[ $spec['key'] ]    = (int) $post_id;
			$this->save_registry();

			wp_set_object_terms( $post_id, 'crowdfunding', 'product_type' );
			$cat = isset( $this->cats[ $cat_keys[ $i % 3 ] ] ) ? $this->cats[ $cat_keys[ $i % 3 ] ] : 0;
			if ( $cat ) {
				$terms = array( $cat );
				if ( 0 === $i && isset( $this->cats['unmarked'] ) ) {
					$terms[] = $this->cats['unmarked'];
				}
				wp_set_object_terms( $post_id, $terms, 'product_cat' );
			}
			if ( ! $bare ) {
				wp_set_object_terms( $post_id, array( $tags[ $i % 3 ] ), 'product_tag' );
			}

			$this->save_campaign_meta( $post_id, $spec, $creator_key, $i );

			// Let WooCommerce sync its lookup tables and stock status for the product.
			$product = wc_get_product( $post_id );
			// Only when WC resolved our class, or save() would rewrite the product type.
			if ( $product && 'crowdfunding' === $product->get_type() ) {
				$product->set_stock_status( 'instock' );
				$product->save();
			}

			$this->registry['campaigns'][] = array(
				'id'       => (int) $post_id,
				'key'      => $spec['key'],
				'method'   => $spec['method'],
				'label'    => $spec['label'],
				'expected' => $spec['expected'],
				'status'   => $status,
			);

			$this->seed_pledges( $post_id, $spec, $creator_id );
		}
	}

	private function campaign_content( $spec ) {
		return '<p>This campaign was created by the WP Crowdfunding test seeder.</p>'
			. '<p><strong>Situation:</strong> ' . esc_html( $spec['label'] ) . '</p>'
			. '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>';
	}

	private function date_str( $days ) {
		if ( '' === $days ) {
			return '';
		}
		return gmdate( 'd-m-Y', $this->now + (int) $days * DAY_IN_SECONDS );
	}

	private function save_campaign_meta( $post_id, $spec, $creator_key, $i ) {
		$bare = ! empty( $spec['bare'] );

		$meta = array(
			self::MARK                             => 1,
			'_neo_crowdfunding_product_type'       => 'yes',
			'_nf_funding_goal'                     => $spec['goal'],
			'_nf_duration_start'                   => $this->date_str( $spec['start'] ),
			'_nf_duration_end'                     => $this->date_str( $spec['end'] ),
			'wpneo_funding_minimum_price'          => isset( $spec['min'] ) ? $spec['min'] : '',
			'wpneo_funding_maximum_price'          => isset( $spec['max'] ) ? $spec['max'] : '',
			'wpneo_funding_recommended_price'      => isset( $spec['recommend'] ) ? $spec['recommend'] : '',
			'wpcf_predefined_pledge_amount'        => isset( $spec['predefined'] ) ? $spec['predefined'] : '',
			'wpneo_show_contributor_table'         => ! empty( $spec['table'] ) ? '1' : '',
			'wpneo_mark_contributors_as_anonymous' => ! empty( $spec['anonymous'] ) ? '1' : '',
			'wpneo_campaigner_paypal_id'           => $bare ? '' : 'seed-' . str_replace( '_', '-', $creator_key ) . '-paypal@example.test',
			'wpneo_country'                        => $bare ? '' : 'US',
			'_nf_location'                         => $bare ? '' : 'Springfield',
			'wpneo_funding_video'                  => '',
		);
		if ( '' !== $spec['method'] ) {
			$meta['wpneo_campaign_end_method'] = $spec['method'];
		}

		if ( ! $bare ) {
			$meta['_thumbnail_id'] = $this->image( $i );
		}

		if ( ! empty( $spec['rich'] ) ) {
			$meta['wpneo_funding_video']    = 'https://www.youtube.com/watch?v=aqz-KE-bpKQ';
			$meta['_nf_location']           = 'Portland, Oregon';
			$meta['_product_image_gallery'] = implode( ',', array_filter( array( $this->image( 1 ), $this->image( 2 ), $this->image( 3 ) ) ) );
			$updates                        = array(
				array(
					'date'    => $this->date_str( -15 ),
					'title'   => 'We are live!',
					'details' => '<p>Thanks to everyone who backed us on day one.</p>',
				),
				array(
					'date'    => $this->date_str( -7 ),
					'title'   => 'Prototype finished',
					'details' => '<p>The first prototype is ready. <strong>Photos</strong> coming soon.</p>',
				),
				array(
					'date'    => $this->date_str( -1 ),
					'title'   => 'One third of the way there',
					'details' => '<p>We passed 30% of our goal, keep sharing!</p>',
				),
			);
			$meta['wpneo_campaign_updates'] = wp_slash( wp_json_encode( $updates, JSON_UNESCAPED_UNICODE ) );
		}

		if ( ! empty( $spec['rewards'] ) ) {
			$meta['wpneo_reward'] = wp_slash( wp_json_encode( $this->build_rewards( $spec['rewards'], $i ), JSON_UNESCAPED_UNICODE ) );
		}

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
	}

	/**
	 * @param array $defs Rows of [amount, description, limit, delivery year offset, completed pledges].
	 */
	private function build_rewards( $defs, $i ) {
		$rewards = array();
		$year    = (int) gmdate( 'Y', $this->now );
		foreach ( $defs as $n => $def ) {
			$rewards[] = array(
				'wpneo_rewards_pladge_amount' => (int) $def[0],
				'wpneo_rewards_description'   => $def[1],
				'wpneo_rewards_endmonth'      => strtolower( gmdate( 'M', mktime( 0, 0, 0, ( $n * 3 % 12 ) + 1, 1 ) ) ),
				'wpneo_rewards_endyear'       => (string) ( $year + (int) $def[3] ),
				'wpneo_rewards_item_limit'    => (string) $def[2],
				'wpneo_rewards_image_field'   => 0 === $n ? '' : (string) $this->image( $i + $n ),
			);
		}
		return $rewards;
	}

	/**
	 * Build the list of pledges for a campaign and create the orders.
	 */
	private function seed_pledges( $post_id, $spec, $creator_id ) {
		$pledges = array(); // Each: [amount, backer key or null for guest, anonymous].
		// backer_single and backer_anon get their pledges only through the flags below.
		$rotation = array( 'backer_many', 'creator_empty', 'backer_many' );

		if ( ! empty( $spec['rewards'] ) && empty( $spec['pct'] ) ) {
			// Pledges that land inside each reward's band, so claim counts are exact.
			$defs = $spec['rewards'];
			foreach ( $defs as $n => $def ) {
				$next = isset( $defs[ $n + 1 ] ) ? (int) $defs[ $n + 1 ][0] - 1 : (int) $def[0] * 2;
				for ( $k = 0; $k < (int) $def[4]; $k++ ) {
					$amount    = min( $next, (int) $def[0] + $k * 5 );
					$pledges[] = array( $amount, $rotation[ $k % 3 ], false );
				}
			}
		} elseif ( ! empty( $spec['small_count'] ) ) {
			for ( $k = 0; $k < $spec['small_count']; $k++ ) {
				$pledges[] = array( mt_rand( 1, 4 ) * 5, $rotation[ $k % 3 ], false );
			}
		} elseif ( ! empty( $spec['pct'] ) ) {
			$target = round( $spec['goal'] * $spec['pct'] / 100, 2 );
			$steps  = isset( $spec['min'] )
				? array( 10, 25, 50, 100, 250 )
				: array( 25, 50, 75, 100, 150, 250, 500 );
			$sum    = 0;
			$k      = 0;
			while ( $sum < $target ) {
				$amount = $steps[ mt_rand( 0, count( $steps ) - 1 ) ];
				if ( $sum + $amount > $target ) {
					$amount = round( $target - $sum, 2 );
				}
				$pledges[] = array( $amount, $rotation[ $k % 3 ], false );
				$sum      += $amount;
				$k++;
			}
		}

		if ( ! empty( $spec['single_backer'] ) && $pledges ) {
			$pledges[0][1] = 'backer_single';
		}
		if ( ! empty( $spec['anon_backer'] ) && $pledges ) {
			$pledges[ count( $pledges ) - 1 ][1] = 'backer_anon';
			$pledges[ count( $pledges ) - 1 ][2] = true;
		}
		if ( ! empty( $spec['guest_order'] ) && $pledges ) {
			$pledges[0][1] = null;
		}

		$rewards = wpcf_function()->get_sorted_campaign_rewards( $post_id );
		foreach ( $pledges as $p ) {
			$this->create_order( $post_id, $spec, $creator_id, $p[0], $p[1], $p[2], 'completed', $rewards );
		}

		// Orders in statuses that must never count toward the raised amount.
		if ( ! empty( $spec['noise_orders'] ) ) {
			foreach ( array( 'processing', 'on-hold', 'pending', 'cancelled', 'refunded', 'failed' ) as $status ) {
				$this->create_order( $post_id, $spec, $creator_id, 500, 'backer_many', false, $status, $rewards );
			}
		}
	}

	/**
	 * Create one pledge order through the WooCommerce CRUD API (posts or HPOS).
	 */
	private function create_order( $post_id, $spec, $creator_id, $amount, $backer_key, $anonymous, $status, $rewards ) {
		$product = wc_get_product( $post_id );
		if ( ! $product ) {
			return;
		}

		$customer_id = ( $backer_key && isset( $this->users[ $backer_key ] ) ) ? $this->users[ $backer_key ] : 0;

		$order = wc_create_order( array( 'customer_id' => $customer_id ) );
		if ( is_wp_error( $order ) || ! $order ) {
			return;
		}

		$order->add_product(
			$product,
			1,
			array(
				'subtotal' => $amount,
				'total'    => $amount,
			)
		);

		if ( $customer_id ) {
			$order->set_address( $this->billing_address( $customer_id ), 'billing' );
		} else {
			$order->set_address(
				array(
					'first_name' => 'Guest',
					'last_name'  => 'Backer',
					'email'      => 'seed-guest@example.test',
					'country'    => 'US',
				),
				'billing'
			);
		}
		$order->set_payment_method( 'bacs' );
		$order->set_payment_method_title( 'Direct bank transfer (seed)' );
		$order->calculate_totals( false );

		// Spread pledges over the last 14 days, inside the campaign's run.
		$from = max( $this->now - 14 * DAY_IN_SECONDS, $this->now + (int) $spec['start'] * DAY_IN_SECONDS );
		$to   = '' === $spec['end'] ? $this->now : min( $this->now, $this->now + (int) $spec['end'] * DAY_IN_SECONDS );
		if ( $from > $to ) {
			$from = $to - DAY_IN_SECONDS;
		}
		$utc = mt_rand( (int) $from, (int) $to );
		$order->set_date_created( $utc ); // Integer timestamps are read as UTC.

		// Same meta the plugin writes on a real checkout.
		$order->update_meta_data( 'is_crowdfunding_order', '1' );
		$order->update_meta_data( '_cf_product_author_id', (int) $creator_id );
		$reward = $this->reward_for_amount( $rewards, $amount );
		if ( $reward ) {
			$order->update_meta_data( 'wpneo_selected_reward', wp_json_encode( $reward, JSON_UNESCAPED_UNICODE ) );
		}
		if ( $anonymous ) {
			$order->update_meta_data( 'mark_name_anonymous', 'true' );
		}
		$order->update_meta_data( self::MARK, '1' );
		$order->save();

		// Register before the status change, so a failure there still leaves it removable.
		$this->registry['orders'][] = (int) $order->get_id();

		$order->set_status( $status, 'Seeded test order.' );
		if ( 'completed' === $status ) {
			$order->set_date_paid( $utc );
			$order->set_date_completed( $utc );
		}
		$order->save();
	}

	/**
	 * The highest reward whose pledge amount is at or below the pledge.
	 */
	private function reward_for_amount( $rewards, $amount ) {
		$match = null;
		foreach ( (array) $rewards as $reward ) {
			if ( (float) $amount >= (float) $reward['wpneo_rewards_pladge_amount'] ) {
				$match = $reward;
			}
		}
		return $match;
	}

	/* ---------------------------------------------------------------------
	 * Bookmarks and reviews
	 * ------------------------------------------------------------------- */

	private function seed_bookmarks() {
		$sets = array(
			'backer_bookmark' => array( 'date_running_partial', 'date_expired_failed', 'never_end_backers', 'rich_content' ),
			'backer_many'     => array( 'goal_partial' ),
		);
		foreach ( $sets as $user_key => $campaign_keys ) {
			if ( ! isset( $this->users[ $user_key ] ) ) {
				continue;
			}
			$ids = array();
			foreach ( $campaign_keys as $ck ) {
				if ( isset( $this->campaign_ids[ $ck ] ) ) {
					$ids[] = (string) $this->campaign_ids[ $ck ]; // Plugin stores IDs as strings.
				}
			}
			update_user_meta( $this->users[ $user_key ], 'loved_campaign_ids', wp_json_encode( $ids ) );
		}
	}

	private function seed_reviews() {
		$reviews = array(
			array( 'date_successful', 'backer_single', 5, 'Fantastic project, delivered as promised!', 1 ),
			array( 'date_successful', 'backer_many', 4, 'Great updates throughout the campaign.', 1 ),
			array( 'rich_content', 'backer_many', 3, 'Good idea, a bit slow on shipping.', 1 ),
			array( 'rich_content', 'creator_empty', 5, 'Love the video and the updates.', 1 ),
			array( 'goal_overfunded', 'backer_anon', 1, 'Not what I expected.', 1 ),
			array( 'goal_partial', 'backer_bookmark', 4, 'Pending review: waiting for moderation.', 0 ),
		);
		$touched = array();
		foreach ( $reviews as $r ) {
			if ( ! isset( $this->campaign_ids[ $r[0] ], $this->users[ $r[1] ] ) ) {
				continue;
			}
			$user       = get_userdata( $this->users[ $r[1] ] );
			$comment_id = wp_insert_comment(
				array(
					'comment_post_ID'      => $this->campaign_ids[ $r[0] ],
					'comment_author'       => $user->display_name,
					'comment_author_email' => $user->user_email,
					'comment_content'      => $r[3],
					'comment_type'         => 'review',
					'comment_approved'     => $r[4],
					'user_id'              => $user->ID,
				)
			);
			if ( ! $comment_id ) {
				continue;
			}
			add_comment_meta( $comment_id, 'rating', $r[2], true );
			add_comment_meta( $comment_id, 'verified', 1, true );
			add_comment_meta( $comment_id, self::MARK, 1, true );
			$this->registry['comments'][] = (int) $comment_id;
			$touched[ $this->campaign_ids[ $r[0] ] ] = true;
		}
		if ( class_exists( '\WC_Comments' ) && method_exists( '\WC_Comments', 'clear_transients' ) ) {
			foreach ( array_keys( $touched ) as $product_id ) {
				\WC_Comments::clear_transients( $product_id );
			}
		}
	}

	private function save_registry() {
		update_option( self::REGISTRY, $this->registry, false );
	}
}
