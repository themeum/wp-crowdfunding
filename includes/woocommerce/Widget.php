<?php
namespace WPCF;

defined( 'ABSPATH' ) || exit;
// Creating the widget
class Latest_Backers extends \WP_Widget {

	function __construct() {
		parent::__construct(
			'wpb_widget',
			__( 'Latest Backers', 'wp-crowdfunding' ),
			array( 'description' => __( 'Latest Backers of Crowdfunding', 'wp-crowdfunding' ) )
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] ) . esc_html( $title ) . wp_kses_post( $args['after_title'] );
		}

		global $wpdb;
		$id_array = array();
		$query    = array(
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
		$id_list  = get_posts( $query );
		foreach ( $id_list as $value ) {
			$id_array[] = $value->ID;
		}
		$order_ids = array();
		if ( is_array( $id_array ) ) {
			if ( ! empty( $id_array ) ) {
				$id_array = array_map( 'absint', $id_array );
				global $wpdb;
				$order_ids = $wpdb->get_col(
					$wpdb->prepare(
						"SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items oi
						LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta woim ON woim.order_item_id = oi.order_item_id
						WHERE woim.meta_key = '_product_id' AND woim.meta_value IN ( " . implode( ',', array_fill( 0, count( $id_array ), '%d' ) ) . ' )',
						$id_array
					)
				);
				if ( is_array( $order_ids ) ) {
					if ( empty( $order_ids ) ) {
						$order_ids = array( '9999999' );
					}
				}
			} else {
				$order_ids = array( '9999999' );
			}
		}

		$customer_orders = get_posts(
			apply_filters(
				'woocommerce_my_account_my_orders_query',
				array(
					'numberposts' => $instance['number'],
					'orderby'     => 'date',
					'order'       => 'DESC',
					'post__in'    => $order_ids,
					'meta_key'    => '_customer_user',
					'post_type'   => wc_get_order_types( 'view-orders' ),
					'post_status' => array_keys( wc_get_order_statuses() ),
				)
			)
		);

		foreach ( $customer_orders as $customer_order ) {
			$order      = wc_get_order( $customer_order );
			$order_date = (array) $order->get_date_created();
			$items      = $order->get_items();
			echo '<ul class="wpcf-latest-backer-widget">';
				echo '<li>';
			foreach ( $items as $val ) {
				echo '<span>' . esc_html( $val->get_name() ) . '</span> - ';
			}
					echo '<span class="wpcf-latest-backer-widget-reward">' . wp_kses( $order->get_formatted_order_total(), wpcf_function()->allowed_html() ) . '</span>';
				echo '</li>';
			echo '</ul>';
		}

		echo wp_kses_post( $args['after_widget'] );
	}

	// Widget Backend
	public function form( $instance ) {
		$title  = isset( $instance['title'] ) ? $instance['title'] : __( 'Latest Backers', 'wp-crowdfunding' );
		$number = isset( $instance['number'] ) ? $instance['number'] : 6;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wp-crowdfunding' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number:', 'wp-crowdfunding' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" value="<?php echo esc_attr( $number ); ?>" />
		</p>
		<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance           = array();
		$instance['title']  = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? $new_instance['number'] : 6;
		return $instance;
	}
}
