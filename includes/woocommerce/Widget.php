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
		$customer_orders = wpcf_function()->get_orders_by_ids(
			wpcf_function()->get_order_ids_for_products( $id_array ),
			array(
				'limit'   => max( 1, absint( isset( $instance['number'] ) ? $instance['number'] : 6 ) ),
				'orderby' => 'date',
				'order'   => 'DESC',
				'type'    => wc_get_order_types( 'view-orders' ),
				'status'  => array_keys( wc_get_order_statuses() ),
			)
		);

		foreach ( $customer_orders as $order ) {
			$items = $order->get_items();
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
