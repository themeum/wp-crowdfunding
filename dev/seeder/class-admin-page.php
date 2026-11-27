<?php
/**
 * Tools → Crowdfunding Seeder admin page (development only).
 *
 * @package WPCF\Dev
 */

namespace WPCF\Dev;

defined( 'ABSPATH' ) || exit;

class Admin_Page {

	const SLUG   = 'wpcf-dev-seeder';
	const ACTION = 'wpcf_dev_seeder';
	const NOTICE = 'wpcf_dev_seeder_notice_';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_post_' . self::ACTION, array( $this, 'handle' ) );
	}

	public function menu() {
		add_management_page(
			'Crowdfunding Seeder',
			'Crowdfunding Seeder',
			'manage_options',
			self::SLUG,
			array( $this, 'render' )
		);
	}

	public function handle() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Not allowed.' );
		}
		check_admin_referer( self::ACTION );

		$task   = isset( $_POST['task'] ) ? sanitize_key( wp_unslash( $_POST['task'] ) ) : '';
		$seeder = new Seeder();
		$notice = array(
			'type' => 'success',
			'text' => '',
		);

		try {
			if ( 'seed' === $task ) {
				$counts         = $seeder->seed( ! empty( $_POST['apply_options'] ) );
				$notice['text'] = sprintf(
					'Seeded %d campaigns, %d orders, %d users, %d reviews and %d images.',
					$counts['campaigns'],
					$counts['orders'],
					$counts['users'],
					$counts['reviews'],
					$counts['images']
				);
			} elseif ( 'remove' === $task ) {
				$counts         = $seeder->unseed();
				$notice['text'] = sprintf(
					'Removed %d campaigns, %d orders and %d users. Options restored.',
					$counts['campaigns'],
					$counts['orders'],
					$counts['users']
				);
			}
		} catch ( \Throwable $e ) {
			$notice = array(
				'type' => 'error',
				'text' => 'Seeder failed: ' . $e->getMessage() . ' (' . basename( $e->getFile() ) . ':' . $e->getLine() . '). Partly seeded data can still be removed.',
			);
		}

		set_transient( self::NOTICE . get_current_user_id(), $notice, 60 );
		wp_safe_redirect( admin_url( 'tools.php?page=' . self::SLUG ) );
		exit;
	}

	public function render() {
		$notice = get_transient( self::NOTICE . get_current_user_id() );
		delete_transient( self::NOTICE . get_current_user_id() );
		$reg = Seeder::get_registry();
		?>
		<div class="wrap">
			<h1>Crowdfunding Seeder <small style="font-size:12px;color:#d63638;">development only, not part of the plugin build</small></h1>

			<?php if ( $notice ) : ?>
				<div class="notice notice-<?php echo esc_attr( $notice['type'] ); ?> is-dismissible"><p><?php echo esc_html( $notice['text'] ); ?></p></div>
			<?php endif; ?>

			<?php $this->render_environment(); ?>

			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:16px 0;">
					<?php wp_nonce_field( self::ACTION ); ?>
					<input type="hidden" name="action" value="<?php echo esc_attr( self::ACTION ); ?>">
					<p>
						<label><input type="checkbox" name="apply_options" value="1" checked>
							Also switch on test options (all campaign end methods, contributor table/anonymity, social share addon). The old values are restored on removal.</label>
					</p>
					<p>
						<button class="button button-primary" name="task" value="seed"><?php echo $reg ? 'Reset &amp; reseed' : 'Seed test data'; ?></button>
						<?php if ( $reg ) : ?>
							<button class="button" name="task" value="remove" onclick="return confirm('Delete all seeded campaigns, orders, users, reviews and images?');">Remove seeded data</button>
						<?php endif; ?>
					</p>
					<p class="description">Seeding first removes any earlier seed data, so it can be run again safely. Only seeded objects (tracked by ID) are ever deleted. No emails are sent.</p>
				</form>
			<?php endif; ?>

			<?php
			if ( $reg ) {
				$this->render_campaigns( $reg );
				$this->render_users( $reg );
			}
			?>
		</div>
		<?php
	}

	private function render_environment() {
		$wc_version = defined( 'WC_VERSION' ) ? WC_VERSION : '';
		$has_util   = class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' );
		$hpos       = $has_util && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
		$sync       = 'yes' === get_option( 'woocommerce_custom_orders_table_data_sync_enabled' );

		if ( ! $wc_version ) {
			echo '<div class="notice notice-error"><p>WooCommerce is not active. Activate it to seed data.</p></div>';
			return;
		}

		$storage = $hpos ? ( $sync ? 'HPOS + compatibility sync' : 'HPOS (custom order tables)' ) : 'Legacy (orders stored as posts)';

		echo '<table class="widefat striped" style="max-width:720px"><tbody>';
		printf( '<tr><th>WooCommerce</th><td>%s</td></tr>', esc_html( $wc_version ) );
		printf( '<tr><th>Order storage</th><td>%s</td></tr>', esc_html( $storage ) );
		printf( '<tr><th>Plugin</th><td>WP Crowdfunding %s</td></tr>', esc_html( WPCF_VERSION ) );
		echo '</tbody></table>';

		// get_total_fund() picks its SQL from Functions::hpos_enabled(); guard against it disagreeing with WooCommerce.
		if ( $hpos && ! $sync && method_exists( '\WPCF\Functions', 'hpos_enabled' ) && ! \WPCF\Functions::hpos_enabled() ) {
			echo '<div class="notice notice-warning inline"><p><strong>Storage mismatch:</strong> HPOS is on, but <code>Functions::hpos_enabled()</code> (used by <code>get_total_fund()</code>) says it is off. Raised totals are read from <code>wp_posts</code> and may show 0. See the "Match" column below.</p></div>';
		}
	}

	private function render_campaigns( $reg ) {
		$fn = wpcf_function();
		echo '<h2>Seeded campaigns</h2>';
		echo '<p class="description">"Actual" uses the same checks as the campaign pledge button: valid = open, not started = upcoming, goal reached = successful, otherwise closed/expired.</p>';
		echo '<table class="widefat striped"><thead><tr>';
		foreach ( array( '#', 'Campaign', 'End method', 'Post status', 'Start / End', 'Raised / Goal', '%', 'Days left', 'Expected', 'Actual', 'Match' ) as $h ) {
			echo '<th>' . esc_html( $h ) . '</th>';
		}
		echo '</tr></thead><tbody>';

		$pass = 0;
		$fail = 0;
		foreach ( (array) $reg['campaigns'] as $n => $c ) {
			$id = (int) $c['id'];
			if ( ! get_post( $id ) ) {
				continue;
			}
			$raised  = (float) $fn->get_total_fund( $id );
			$goal    = (float) get_post_meta( $id, '_nf_funding_goal', true );
			$pct     = $goal > 0 ? round( $raised / $goal * 100, 1 ) : 0;
			$actual  = $this->actual_state( $id );
			$ok      = $actual === $c['expected'];
			$ok ? $pass++ : $fail++;
			$start   = get_post_meta( $id, '_nf_duration_start', true );
			$end     = get_post_meta( $id, '_nf_duration_end', true );

			echo '<tr>';
			printf( '<td>%d</td>', (int) $n + 1 );
			printf(
				'<td><strong>%s</strong><br><a href="%s">View</a> | <a href="%s">Edit</a></td>',
				esc_html( $c['label'] ),
				esc_url( get_permalink( $id ) ),
				esc_url( get_edit_post_link( $id ) )
			);
			printf( '<td><code>%s</code></td>', esc_html( $c['method'] ? $c['method'] : '(empty)' ) );
			printf( '<td>%s</td>', esc_html( get_post_status( $id ) ) );
			printf( '<td>%s<br>%s</td>', esc_html( $start ? $start : '-' ), esc_html( $end ? $end : '-' ) );
			printf( '<td>%s / %s</td>', wp_kses_post( wc_price( $raised ) ), wp_kses_post( wc_price( $goal ) ) );
			printf( '<td>%s</td>', esc_html( $pct ) );
			printf( '<td>%s</td>', $end ? esc_html( $fn->get_date_remaining( $id ) ) : '-' );
			printf( '<td>%s</td>', esc_html( $c['expected'] ) );
			printf( '<td>%s</td>', esc_html( $actual ) );
			printf( '<td style="color:%s;font-weight:600">%s</td>', $ok ? '#00a32a' : '#d63638', $ok ? 'PASS' : 'FAIL' );
			echo '</tr>';
		}
		echo '</tbody></table>';
		printf( '<p><strong>%d passed, %d failed.</strong> Orders seeded: %d. ', (int) $pass, (int) $fail, count( (array) $reg['orders'] ) );
		printf( '<a href="%s">Open WooCommerce orders</a></p>', esc_url( $this->orders_admin_url() ) );
	}

	/**
	 * Same decision as wpcftemplate/woocommerce/basic/include/fund-campaign-btn.php.
	 */
	private function actual_state( $id ) {
		$fn = wpcf_function();
		if ( $fn->is_campaign_valid( $id ) ) {
			return Seeder::OPEN;
		}
		$start = get_post_meta( $id, '_nf_duration_start', true );
		if ( $start && strtotime( $start ) > time() ) {
			return Seeder::UPCOMING;
		}
		if ( $fn->is_reach_target_goal( $id ) ) {
			return Seeder::SUCCESSFUL;
		}
		return Seeder::CLOSED;
	}

	private function orders_admin_url() {
		$has_util = class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' );
		if ( $has_util && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
			return admin_url( 'admin.php?page=wc-orders' );
		}
		return admin_url( 'edit.php?post_type=shop_order' );
	}

	private function render_users( $reg ) {
		echo '<h2>Seeded users</h2>';
		printf( '<p>Every seeded user has the password <code>%s</code>.</p>', esc_html( Seeder::PASSWORD ) );
		echo '<table class="widefat striped" style="max-width:900px"><thead><tr><th>Login</th><th>Role</th><th>Purpose</th></tr></thead><tbody>';
		foreach ( (array) $reg['user_list'] as $u ) {
			if ( ! get_userdata( $u['id'] ) ) {
				continue;
			}
			printf(
				'<tr><td><a href="%s">%s</a></td><td>%s</td><td>%s</td></tr>',
				esc_url( get_edit_user_link( $u['id'] ) ),
				esc_html( $u['login'] ),
				esc_html( $u['role'] ),
				esc_html( $u['note'] )
			);
		}
		echo '</tbody></table>';
	}
}
