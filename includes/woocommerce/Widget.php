<?php 
namespace WPCF;

defined( 'ABSPATH' ) || exit;
// Creating the widget 
class Latest_Backers extends \WP_Widget {
  
    function __construct() {
        parent::__construct(
            'wpb_widget',
            __('Latest Backers', 'wpb_widget_domain'), 
            array( 'description' => __( 'Latest Backers of Crowdfunding', 'wpb_widget_domain' ), ) 
        );
    }
      
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        
        echo $args['before_widget'];
        
        if ( ! empty( $title ) ){
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        global $wpdb;
		$id_array = array();
		$query = array(
			'post_type' 		=> 'product',
			'author'    		=> get_current_user_id(),
			'tax_query' 		=> array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'crowdfunding',
				),
			),
			'posts_per_page'    => -1
		);
		$id_list = get_posts( $query );
		foreach ($id_list as $value) {
			$id_array[] = $value->ID;
		}
		$order_ids = array();
		if (is_array( $id_array )) {
			if (!empty($id_array)) {
				$id_array = implode( ', ', $id_array );
				global $wpdb;
				$prefix = $wpdb->prefix;
				$query = "SELECT order_id 
					FROM {$wpdb->prefix}woocommerce_order_items oi 
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta woim 
					ON woim.order_item_id = oi.order_item_id 
					WHERE woim.meta_key='_product_id' AND woim.meta_value IN ( {$id_array} )";
				$order_ids = $wpdb->get_col( $query );
				if(is_array($order_ids)){
					if(empty($order_ids)){
						$order_ids = array( '9999999' );
					}
				}
			} else {
				$order_ids = array( '9999999' );
			}
		}

		$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
			'numberposts' => $instance['number'],
			'orderby'     => 'date',
			'order'       => 'DESC',
			'post__in'	  => $order_ids,
			'meta_key'    => '_customer_user',
			'post_type'   => wc_get_order_types( 'view-orders' ),
			'post_status' => array_keys( wc_get_order_statuses() )
		) ) );

		foreach ( $customer_orders as $customer_order ) {
            $order = wc_get_order( $customer_order );
            $order_date = (array) $order->get_date_created();
            $items = $order->get_items();
            echo '<ul class="wpcf-latest-backer-widget">';
				echo '<li>';
					foreach ($items as $val) {
						echo '<span>'.$val->get_name().'</span> - ';
					}
					echo '<span class="wpcf-latest-backer-widget-reward">'.$order->get_formatted_order_total().'</span>';
				echo '</li>';
            echo '</ul>';
        }
		
        echo $args['after_widget'];
    }
              
    // Widget Backend 
    public function form( $instance ) {
        $title = isset($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Latest Backers', 'wp-crowdfunding' );
        $number = isset($instance[ 'number' ]) ? $instance[ 'number' ] : 6;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" value="<?php echo esc_attr( $number ); ?>" />
        </p>
        <?php 
    }
          
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? $new_instance['number'] : 6;
        return $instance;
    }
     
} 
