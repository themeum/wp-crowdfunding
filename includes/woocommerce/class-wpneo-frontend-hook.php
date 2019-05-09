<?php
defined( 'ABSPATH' ) || exit;

if (! class_exists('WPNEO_Frontend_Hook')) {

    class WPNEO_Frontend_Hook {

        protected static $_instance;
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct() {
            add_action('woocommerce_after_shop_loop_item',      array($this, 'after_item_title_data')); // Woocommerce Backed User
            add_filter( 'woocommerce_product_tabs',             array($this, 'product_backed_user_tab') );
            add_filter( 'woocommerce_is_sold_individually',     array($this, 'wpneo_wc_remove_crowdfunding_quantity_fields'), 10, 2 ); //Remove quantity and force item 1 cart per checkout if product is crowdfunding
            if ( 'true' == get_option('hide_cf_campaign_from_shop_page')){
                add_action('woocommerce_product_query',         array($this, 'limit_show_cf_campaign_in_shop')); //Filter product query
            }
        }

        /**
         * @return string
         *
         *
         */
        public function after_item_title_data(){
            global $woocommerce,$post,$wpdb;
            $product = wc_get_product($post->ID);

            if($product->get_type() != 'crowdfunding'){
                return '';
            }

            $funding_goal   = $this->totalGoalByCampaign($post->ID);
            $wpneo_country  = get_post_meta( $post->ID, 'wpneo_country', true);
            $total_sales    = get_post_meta( $post->ID, 'total_sales', true );
            $enddate        = get_post_meta( $post->ID, '_nf_duration_end', true );

            //Get Country name from WooCommerce
            $countries_obj  = new WC_Countries();
            $countries      = $countries_obj->__get('countries');

            $country_name = '';
            if ($wpneo_country){
                $country_name = $countries[$wpneo_country];
            }

            $raised = 0;
            $total_raised = $this->totalFundRaisedByCampaign();
            if ($total_raised){
                $raised = $total_raised;
            }

            //Get order sales value by product
            $sales_value_by_product = 0;

            $days_remaining = apply_filters('date_expired_msg', __('Date expired', 'wp-crowdfunding'));
            if ($this->dateRemaining()){
                $days_remaining = apply_filters('date_remaining_msg', __($this->dateRemaining().' days remaining', 'wp-crowdfunding'));
            }

            $html = '';
            $html .= '<div class="crowdfunding_wrapper">';

            if ($country_name) {
                $html .= '<div class="wpneo_location">';
                $html .= '<p class="wpneo_thumb_text">'. __('Location: ', 'wp-crowdfunding') . $country_name.'</p>';
                $html .= '</div>';
            }

            if ($funding_goal) {
                $html .= '<div class="funding_goal">';
                $html .= '<p class="wpneo_thumb_text">'.__('Funding Goal: ', 'wp-crowdfunding') . '<span class="price amount">'.wc_price($funding_goal).'</span>'. '</p>';
                $html .= '</div>';
            }

            if ($total_sales) {
                $html .= '<div class="total_raised">';
                $html .= '<p class="wpneo_thumb_text">'.__('Raised: ', 'wp-crowdfunding') . '<span class="price amount">' . wc_price( $raised).'</span>'. '</p>';
                $html .= '</div>';
            }

            if ($total_sales && $funding_goal) {
                $percent = $this->getFundRaisedPercent();
                $html .= '<div class="percent_funded">';
                $html .= '<p class="wpneo_thumb_text">'.__('Funded percent: ', 'wp-crowdfunding') . '<span class="price amount">' . $percent.' %</span>'. '</p>';
                $html .= '</div>';
            }

            if ($total_sales) {
                $html .= '<div class="days_remaining">';
                $html .= '<p class="wpneo_thumb_text">'.$days_remaining. '</p>';
                $html .= '</div>';
            }

            $html .= '</div>';
            echo apply_filters('woocommerce_product_cf_meta_data',$html);
        }

        public function dateRemaining($post_id = 0){

            global $post;
            if ($post_id == 0) $post_id = $post->ID;
            $enddate = get_post_meta( $post_id, '_nf_duration_end', true );

            if ((strtotime($enddate) + 86399) > time()) {
                $diff = strtotime($enddate) - time();
                $temp = $diff / 86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day
                $days = floor($temp);
                return $days >= 1 ? $days : 1; //Return min one days, though if remain only 1 min
            }
            return 0;
        }

        public function is_reach_target_goal(){
            global $post;
            $funding_goal = get_post_meta($post->ID, '_nf_funding_goal' , true);
            $raised = $this->totalFundRaisedByCampaign();
            if ( $raised >= $funding_goal ){
                return true;
            }else{
                return false;
            }
        }

        public function campaignValid(){
            global $post;

            $_nf_duration_start = get_post_meta($post->ID, '_nf_duration_start', true);

            if ($_nf_duration_start){
                if (strtotime($_nf_duration_start) > time()){
                    return false;
                }
            }

            $campaign_end_method = get_post_meta($post->ID, 'wpneo_campaign_end_method' , true);

            switch ($campaign_end_method){

                case 'target_goal':
                    if ($this->is_reach_target_goal()){
                        return false;
                    }else{
                        return true;
                    }
                    break;

                case 'target_date':
                    if ($this->dateRemaining()){
                        return true;
                    }else{
                        return false;
                    }
                    break;

                case 'target_goal_and_date':
                    if ( ! $this->is_reach_target_goal()) {
                        return true;
                    }
                    if ( $this->dateRemaining()) {
                        return true;
                    }
                    return false;
                    break;

                case 'never_end':
                    return true;
                    break;

                default :
                    return false;
            }
        }

        /**
         * @param $campaign_id
         * @return mixed
         *
         * Get Total funded amount by a campaign
         */

        public function totalFundRaisedByCampaign($campaign_id = 0){

            global $wpdb, $post;

            if ($campaign_id == 0){
                $campaign_id = $post->ID;
            }

            // WPML compatibility.
            if ( apply_filters( 'wpml_setting', false, 'setup_complete' ) ) {
                $type = apply_filters( 'wpml_element_type', get_post_type( $campaign_id ) );
                $trid = apply_filters( 'wpml_element_trid', null, $campaign_id, $type );
                $translations = apply_filters( 'wpml_get_element_translations', null, $trid, $type );
                $campaign_ids = wp_list_pluck( $translations, 'element_id' );
            } else {
                    $campaign_ids = array( $campaign_id );
            }
            $placeholders = implode( ',', array_fill( 0, count( $campaign_ids ), '%d' ) );


            $query ="SELECT 
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

            $wp_sql = $wpdb->get_row($wpdb->prepare( $query, $campaign_ids ));

            return $wp_sql->total_sales_amount;
        }

        /**
         * @param $campaign_id
         * @return mixed
         *
         * Get total campaign goal
         */
        public function totalGoalByCampaign($campaign_id){
            return $funding_goal = get_post_meta( $campaign_id, '_nf_funding_goal', true );
        }

        /**
         * @param $campaign_id
         * @return int|string
         *
         * Return total percent funded for a campaign
         */
        public function getFundRaisedPercent($campaign_id = 0) {

            global $post;
            $percent = 0;
            if ($campaign_id == 0){
                $campaign_id = $post->ID;
            }
            $total = $this->totalFundRaisedByCampaign($campaign_id);
            $goal = $this->totalGoalByCampaign($campaign_id);
            if ($total > 0 && $goal > 0  ) {
                $percent = number_format($total / $goal * 100, 2, '.', '');
            }
            return $percent;
        }

        public function getFundRaisedPercentFormat(){
            return $this->getFundRaisedPercent().'%';
        }

        public function wpneo_wc_remove_crowdfunding_quantity_fields( $return, $product ) {
            if ($product->get_type() == 'crowdfunding'){
                return true;
            }
            return $return;
        }

        /**
         * @param $tabs
         * @return string
         *
         * Return Reward Tab Data
         */
        public function product_backed_user_tab( $tabs ) {

            global $post;
            $product = wc_get_product($post->ID);
            if($product->get_type() =='crowdfunding'){
                // Adds the new tab
                $tabs['backed_user'] = array(
                    'title'     => __( 'Backed User', 'wp-crowdfunding' ),
                    'priority'  => 51,
                    'callback'  => array($this, 'product_backed_user_tab_content')
                );
            }
            return $tabs;
        }

        public function product_backed_user_tab_content( $post_id ){

            global $post, $wpdb;
            $html       = '';
            $prefix     = $wpdb->prefix;
            $product_id = $post->ID;
            $data_array = $this->ordersIDlistPerCampaign();

            $args = array(
                'post_type'     => 'shop_order',
                'post_status'   => array('wc-completed','wc-on-hold'),
                'post__in'      => $data_array
            );
            $the_query = new WP_Query( $args );

            if ( $the_query->have_posts() ) :

                $html .= '  <table class="shop_table backed_user_table">

                    <thead>
                        <tr>
                            <th>'.__('ID', 'wp-crowdfunding').'</th>
                            <th>'.__('Name', 'wp-crowdfunding').'</th>
                            <th>'.__('Email', 'wp-crowdfunding').'</th>
                            <th>'.__('Amount', 'wp-crowdfunding').'</th>
                        </tr>
                    </thead>';
                ?>


                <?php
                while ( $the_query->have_posts() ) : $the_query->the_post();

                    $html .= '<tr>';

                    $html .= '<td>'.get_the_ID().'</td>';

                    $html .= '<td>'. get_post_meta( get_the_ID() , "_billing_first_name",true ).' '.get_post_meta( get_the_ID() , "_billing_last_name",true ).'</td>';

                    $html .= '<td>'. get_post_meta( get_the_ID() , "_billing_email",true ).'</td>';
                    $post_id = get_the_ID();

                    $price = $wpdb->get_results("SELECT order_meta.meta_value FROM `{$prefix}woocommerce_order_itemmeta` AS order_meta, `{$prefix}woocommerce_order_items` AS order_item WHERE order_meta.order_item_id IN (SELECT order_item.order_item_id FROM `{$prefix}woocommerce_order_items` as order_item WHERE order_item.order_id = {$post_id}) AND order_meta.order_item_id IN (SELECT meta.order_item_id FROM `{$prefix}woocommerce_order_itemmeta` AS meta WHERE meta.meta_key='_product_id' AND meta.meta_value={$product_id} ) AND order_meta.meta_key='_line_total' GROUP BY order_meta.meta_id");

                    $price = json_decode( json_encode($price), true );

                    if(isset($price[0]['meta_value'])){
                        $html .= '<td>'. wc_price($price[0]['meta_value']).'</td>';
                    }
                    $html .= '</tr>';

                endwhile;
                wp_reset_postdata();

                $html .= '</table>';
                ?>



                <?php
            else :
                $html .= __( 'Sorry, no posts matched your criteria.','wp-crowdfunding' );
            endif;

            echo $html;
        }

        public function ordersIDlistPerCampaign(){

            global $wpdb, $post;
            $prefix = $wpdb->prefix;
            $post_id = $post->ID;

            $query ="SELECT 
                        order_id 
                    FROM 
                        {$wpdb->prefix}woocommerce_order_itemmeta woim 
			        LEFT JOIN 
                        {$wpdb->prefix}woocommerce_order_items oi ON woim.order_item_id = oi.order_item_id 
			        WHERE 
                        meta_key = '_product_id' AND meta_value = %d
			        GROUP BY 
                        order_id ORDER BY order_id DESC ;";
            $order_ids = $wpdb->get_col( $wpdb->prepare( $query, $post_id ) );

            return $order_ids;
        }

        public function totalBackers(){
            $wpneo_orders = $this->getCustomersByProductQuery();
            if ($wpneo_orders){
                return $wpneo_orders->post_count;
            }else{
                return 0;
            }
        }

        public function getCustomersByProductQuery(){
            $order_ids = $this->ordersIDlistPerCampaign();
            if( $order_ids ) {
                $args = array(
                    'post_type'         =>'shop_order',
                    'post__in'          => $order_ids,
                    'posts_per_page'    =>  999,
                    'order'             => 'ASC',
                    'post_status'       => 'wc-completed',
                );
                $wpneo_orders = new WP_Query( $args );
                return $wpneo_orders;
            }
            return false;
        }

        function getCustomersByProduct(){
            $order_ids = $this->ordersIDlistPerCampaign();
            return $order_ids;
        }

        public function wpneo_campaign_update_status(){

            global $post;
            $saved_campaign_update = get_post_meta($post->ID, 'wpneo_campaign_updates', true);
            $saved_campaign_update_a = json_decode($saved_campaign_update, true);

            $html = '';
            $html .="<div class='campaign_update_wrapper'>";

            $html .= '<h3>';
            $html .= apply_filters( 'wpneo_campaign_update_title', __( $post->post_title.'\'s Update','wp-crowdfunding' ) );
            $html .= '</h3>';

            if (is_array($saved_campaign_update_a)) {
                if ( count($saved_campaign_update_a) > 0 ) {
                    $html .= '<table class="table table-border">';
                    $html .= '<tr>';
                    foreach ($saved_campaign_update_a[0] as $k => $v) {
                        $html .= '<th>';
                        $html .= ucfirst($k);
                        $html .= '</th>';
                    }
                    $html .= '</tr>';

                    foreach ($saved_campaign_update_a as $key => $value) {
                        $html .= '<tr>';
                        foreach ($value as $k => $v) {
                            $html .= '<td>';
                            $html .= $v;
                            $html .= '</td>';
                        }
                        $html .= '</tr>';
                    }
                    $html .= '</table>';
                }
            }
            $html .= "</div>";

            echo $html;
        }

        function limit_show_cf_campaign_in_shop($wp_query){
            $tax_query = array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms' => array(
                        'crowdfunding'
                    ),
                    'operator' => 'NOT IN'
                )
            );
            $wp_query->set( 'tax_query', $tax_query );
            return $wp_query;
        }

        function limit_word_text($text, $limit) {
            if(!function_exists('mb_str_word_count')){
                function mb_str_word_count($string, $format = 0, $charlist = '[]') {
                    mb_internal_encoding( 'UTF-8');
                    mb_regex_encoding( 'UTF-8');
                    $words = mb_split('[^\x{0600}-\x{06FF}]', $string);
                    switch ($format) {
                        case 0:
                            return count($words);
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
            }
            if (mb_str_word_count($text, 0) > $limit) {
                $words  = mb_str_word_count($text, 2);
                $pos    = array_keys($words);
                $text   = mb_substr($text, 0, $pos[$limit]) . '...';
            }
            return $text;
        }


        public function is_campaign_started($post_id = 0){
	        global $post;

	        if ( ! $post_id){
	            $post_id = $post->ID;
            }

	        $_nf_duration_start = get_post_meta($post_id, '_nf_duration_start', true);

	        if ($_nf_duration_start){
		        if (strtotime($_nf_duration_start) > time()){
			        return false;
		        }
	        }

	        return true;
        }


        public function campaign_start_countdown($post_id = 0){
	        global $post;

	        if ( ! $post_id){
		        $post_id = $post->ID;
	        }
	        $_nf_duration_start = get_post_meta($post->ID, '_nf_duration_start', true);

	        ?>
            <p class="wpcf-start-campaign-countdown"><?php _e('Campaign will be started within') ?> <span id="wpcf-campaign-countdown"></span></p>
            
            <script type="text/javascript">
                // Set the date we're counting down to
                let wpcfCountDownDate = "<?php echo $_nf_duration_start; ?>".split("-");
                wpcfCountDownDate = new Date( wpcfCountDownDate[1]+"/"+wpcfCountDownDate[0]+"/"+wpcfCountDownDate[2] ).getTime();

                // Update the count down every 1 second
                let wpcfIntervalE = setInterval(function() {
                    // Get towpcfDays date and time
                    const dateDiff = wpcfCountDownDate - new Date().getTime();

                    // Time calculations
                    let wpcfDays = Math.floor(dateDiff / 86400000 );
                    let wpcfHours = Math.floor((dateDiff % 86400000 ) / 3600000 );
                    let wpcfMinutes = Math.floor((dateDiff % 3600000 ) / 60000 );
                    let wpcfSeconds = Math.floor((dateDiff % 60000 ) / 1000 );

                    // Display the result in the element with id="wpcf-campaign-countdown"
                    document.getElementById("wpcf-campaign-countdown").innerHTML = '<span>'+wpcfDays+'</span>' + "d " + "<span>" + wpcfHours + "h </span> <span> " + wpcfMinutes + "m </span> <span> " + wpcfSeconds + "s </span> ";

                    // If the count down is finished, write some text
                    if ( dateDiff < 0 ) {
                        clearInterval(wpcfIntervalE);
                        document.getElementById("wpcf-campaign-countdown").innerHTML = "";
                    }
                }, 1000);
            </script>

            <?php
        }

        public function days_until_launch($post_id = 0){
	        global $post;

	        if ( ! $post_id){
		        $post_id = $post->ID;
	        }
	        $_nf_duration_start = get_post_meta($post->ID, '_nf_duration_start', true);

	        if ((strtotime($_nf_duration_start) ) > time()) {
		        $diff = strtotime($_nf_duration_start) - time();
		        $temp = $diff / 86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day
		        $days = floor($temp);
		        return $days >= 1 ? $days : 1; //Return min one days, though if remain only 1 min
	        }

	        return 0;
        }

    }
}

//Run this class now
WPNEO_Frontend_Hook::instance();
function WPNEOCF(){
    return WPNEO_Frontend_Hook::instance();
}
$GLOBALS['WPNEOCF'] = WPNEOCF();