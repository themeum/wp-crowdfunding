<?php
defined( 'ABSPATH' ) || exit;


class WPCF_Product_Search extends WP_Widget{

    protected static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct() {
        add_action( 'widgets_init', array( $this, 'wpcf_search_widget_callback' ) );
        $widget_ops = array(
            'classname'     => 'wpcf_search_widget',
            'description'   => 'Widget for Crowdfunding Product Search',
        );
        parent::__construct( 'wpcf_search_widget', 'CF Product Search', $widget_ops );
    }

    public function wpcf_search_widget_callback(){
        register_widget( 'WPCF_Product_Search' );
    }

    // Widget
    public function widget( $args, $instance ) {
        ?>
        <section class="widget widget-blog-posts">
            <h2 class="widget-title"><?php echo $instance['title']; ?></h2>
            <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                <label>
                    <input type="search" class="search-field" placeholder="<?php echo $instance['placeholder']; ?>" value="" name="s">
                </label>
                <input type="hidden" name="post_type" value="product">
                <input type="hidden" name="product_type" value="croudfunding">
                <button type="submit" ><?php echo $instance['button_text']; ?></button>
            </form>
        </section>
        <?php
    }

    // Form
    public function form( $instance ) {
        $defaults = array(
            'title' 		=> 'Search for',
            'placeholder' 	=> 'Search',
            'button_text' 	=> 'Search',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'wp-crowdfunding'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'placeholder' ); ?>"><?php _e('Placeholder of Input Box', 'wp-crowdfunding'); ?></label>
            <input id="<?php echo $this->get_field_id( 'placeholder' ); ?>" name="<?php echo $this->get_field_name( 'placeholder' ); ?>" value="<?php echo $instance['placeholder']; ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e('Search Button Text', 'wp-crowdfunding'); ?></label>
            <input id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo $instance['button_text']; ?>" style="width:100%;" />
        </p>
        <?php
    }

    // Update
    public function update( $new_instance, $old_instance ) {
        $instance                   = $old_instance;
        $instance['title']          = strip_tags( $new_instance['title'] );
        $instance['placeholder']    = strip_tags( $new_instance['placeholder'] );
        $instance['button_text']    = strip_tags( $new_instance['button_text'] );
        return $instance;
    }

}
WPCF_Product_Search::instance();