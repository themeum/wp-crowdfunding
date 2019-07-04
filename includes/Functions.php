<?php
namespace WPCF;

defined( 'ABSPATH' ) || exit;

class Functions{

    public function generator( $arr ){
        require_once WPCF_DIR_PATH . 'settings/Generator.php';
        $generator = new \WPCF\settings\Settings_Generator();
        $generator->generator( $arr );
    }


    public function post($post_item){
        if (!empty($_POST[$post_item])) {
            return $_POST[$post_item];
        }
        return null;
    }


    public function is_free(){
        if (is_plugin_active('wp-crowdfunding-pro/wp-crowdfunding-pro.php')) {
            return false;
        } else {
            return true;
        }
    }


    public function update_text($option_name = '', $option_value = null){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        }
    }


    public function update_checkbox($option_name = '', $option_value = null, $checked_default_value = 'false'){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        } else{
            update_option($option_name, $checked_default_value);
        }
    }
    

    public function update_meta($post_id, $meta_name = '', $meta_value = null, $checked_default_value = 'false'){
        if (!empty($meta_value)) {
            update_post_meta( $post_id, $meta_name, $meta_value);
        }else{
            update_post_meta( $post_id, $meta_name, $checked_default_value);
        }
    }


    public function get_pages(){
        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'child_of' => 0,
            'parent' => -1,
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        return $pages;
    }
    

    // wpcf_function()->wc_version();
    public function wpcf_wc_version_check($version = '3.0'){ //@compatibility
        $this->version_check( $version );
    }
    public function wc_version($version = '3.0'){
        if (class_exists('WooCommerce')) {
            global $woocommerce;
            if (version_compare($woocommerce->version, $version, ">=")) {
                return true;
            }
        }
        return false;
    }
    

    public function is_woocommerce(){
        $vendor = get_option('vendor_type', 'woocommerce');
        if( $vendor == 'woocommerce' ){
            return true;
        }else{
            return false;
        }
    }
    
    
    public function screen_id(){
        $screen_ids = array(
            'toplevel_page_wpneo-crowdfunding',
            'crowdfunding_page_wpneo-crowdfunding-reports',
            'crowdfunding_page_wpneo-crowdfunding-withdraw',
        );
        return apply_filters('wpcf_screen_id', $screen_ids);
    }
    

    public function addon_config($addon_field = null){
        if ( ! $addon_field){
            return false;
        }
        $addonsConfig = maybe_unserialize(get_option('wpcf_addons_config'));
        if (isset($addonsConfig[$addon_field])){
            return $addonsConfig[$addon_field];
        }
        return false;
    }


    public function avalue_dot($key = null, $array = array()){
        $array = (array) $array;
        if ( ! $key || ! count($array) ){
            return false;
        }
        $option_key_array = explode('.', $key);
        $value = $array;
        foreach ($option_key_array as $dotKey){
            if (isset($value[$dotKey])){
                $value = $value[$dotKey];
            }else{
                return false;
            }
        }
        return $value;
    }


    public function get_wpcf_author_campaigns_url($author_id = 0, $author_nicename = ''){ //@compatibility
        $this->campaigns_url( $author_id, $author_nicename );
    }
    public function campaigns_url($author_id = 0, $author_nicename = ''){
        $author_id = $author_id ? $author_id : get_current_user_id();
        if (! $author_id){
            return false;
        }
        $url = get_author_posts_url($author_id, $author_nicename);
        return trailingslashit($url).'campaigns';
    }


    public function get_products_id_by_user($user_id = 0){
		if ( ! $user_id){
			$user_id = get_current_user_id();
		}
		global $wpdb;
		$results = $wpdb->get_col( "SELECT ID from {$wpdb->posts} WHERE post_author = {$user_id} AND post_type = 'product' " );

		return $results;
	}


    public function range_pladges_received($from_date = null, $to_date = null){
        if ( ! $from_date){
            $from_date = date('Y-m-d 00:00:00', strtotime('-6 days'));
        }
        if ( ! $to_date ){
            $to_date = date('Y-m-d 23:59:59');
        }
        $args = array(
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
        $id_list = get_posts( $args );
        $id_array = array();
        foreach ($id_list as $value) {
            $id_array[] = $value->ID;
        }

        $order_ids = array();
        if( is_array( $id_array ) ){
            if(!empty($id_array)){
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
            }else{
                $order_ids = array( '9999999' );
            }
        }

        $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
            'numberposts' => -1, // Chnage Number
            'post__in'	  => $order_ids,
            'meta_key'    => '_customer_user',
            'post_type'   => wc_get_order_types( 'view-orders' ),
            'post_status' => array_keys( wc_get_order_statuses() ),

            'date_query' => array(
                array(
                    'after'     => date('F jS, Y', strtotime($from_date)),
                    'before'    =>  array(
                        'year'  => date('Y', strtotime($to_date)),
                        'month' => date('m', strtotime($to_date)),
                        'day'   => date('d', strtotime($to_date)),
                    ),
                    'inclusive' => true,
                ),
            ),
        ) ) );

        return $customer_orders;
    }


	public function get_order_ids_by_product_ids( $product_ids , $order_status = array( 'wc-completed' ) ){
		global $wpdb;

		$results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value IN ( '" . implode( "','", $product_ids ) . "' )
    ");
		return $results;
	}


    public function get_countries($author_id = 0, $author_nicename = ''){
        return array(
            'AF' => __( 'Afghanistan', 'wp-crowdfunding' ),
            'AX' => __( '&#197;land Islands', 'wp-crowdfunding' ),
            'AL' => __( 'Albania', 'wp-crowdfunding' ),
            'DZ' => __( 'Algeria', 'wp-crowdfunding' ),
            'AS' => __( 'American Samoa', 'wp-crowdfunding' ),
            'AD' => __( 'Andorra', 'wp-crowdfunding' ),
            'AO' => __( 'Angola', 'wp-crowdfunding' ),
            'AI' => __( 'Anguilla', 'wp-crowdfunding' ),
            'AQ' => __( 'Antarctica', 'wp-crowdfunding' ),
            'AG' => __( 'Antigua and Barbuda', 'wp-crowdfunding' ),
            'AR' => __( 'Argentina', 'wp-crowdfunding' ),
            'AM' => __( 'Armenia', 'wp-crowdfunding' ),
            'AW' => __( 'Aruba', 'wp-crowdfunding' ),
            'AU' => __( 'Australia', 'wp-crowdfunding' ),
            'AT' => __( 'Austria', 'wp-crowdfunding' ),
            'AZ' => __( 'Azerbaijan', 'wp-crowdfunding' ),
            'BS' => __( 'Bahamas', 'wp-crowdfunding' ),
            'BH' => __( 'Bahrain', 'wp-crowdfunding' ),
            'BD' => __( 'Bangladesh', 'wp-crowdfunding' ),
            'BB' => __( 'Barbados', 'wp-crowdfunding' ),
            'BY' => __( 'Belarus', 'wp-crowdfunding' ),
            'BE' => __( 'Belgium', 'wp-crowdfunding' ),
            'PW' => __( 'Belau', 'wp-crowdfunding' ),
            'BZ' => __( 'Belize', 'wp-crowdfunding' ),
            'BJ' => __( 'Benin', 'wp-crowdfunding' ),
            'BM' => __( 'Bermuda', 'wp-crowdfunding' ),
            'BT' => __( 'Bhutan', 'wp-crowdfunding' ),
            'BO' => __( 'Bolivia', 'wp-crowdfunding' ),
            'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'wp-crowdfunding' ),
            'BA' => __( 'Bosnia and Herzegovina', 'wp-crowdfunding' ),
            'BW' => __( 'Botswana', 'wp-crowdfunding' ),
            'BV' => __( 'Bouvet Island', 'wp-crowdfunding' ),
            'BR' => __( 'Brazil', 'wp-crowdfunding' ),
            'IO' => __( 'British Indian Ocean Territory', 'wp-crowdfunding' ),
            'BN' => __( 'Brunei', 'wp-crowdfunding' ),
            'BG' => __( 'Bulgaria', 'wp-crowdfunding' ),
            'BF' => __( 'Burkina Faso', 'wp-crowdfunding' ),
            'BI' => __( 'Burundi', 'wp-crowdfunding' ),
            'KH' => __( 'Cambodia', 'wp-crowdfunding' ),
            'CM' => __( 'Cameroon', 'wp-crowdfunding' ),
            'CA' => __( 'Canada', 'wp-crowdfunding' ),
            'CV' => __( 'Cape Verde', 'wp-crowdfunding' ),
            'KY' => __( 'Cayman Islands', 'wp-crowdfunding' ),
            'CF' => __( 'Central African Republic', 'wp-crowdfunding' ),
            'TD' => __( 'Chad', 'wp-crowdfunding' ),
            'CL' => __( 'Chile', 'wp-crowdfunding' ),
            'CN' => __( 'China', 'wp-crowdfunding' ),
            'CX' => __( 'Christmas Island', 'wp-crowdfunding' ),
            'CC' => __( 'Cocos (Keeling) Islands', 'wp-crowdfunding' ),
            'CO' => __( 'Colombia', 'wp-crowdfunding' ),
            'KM' => __( 'Comoros', 'wp-crowdfunding' ),
            'CG' => __( 'Congo (Brazzaville)', 'wp-crowdfunding' ),
            'CD' => __( 'Congo (Kinshasa)', 'wp-crowdfunding' ),
            'CK' => __( 'Cook Islands', 'wp-crowdfunding' ),
            'CR' => __( 'Costa Rica', 'wp-crowdfunding' ),
            'HR' => __( 'Croatia', 'wp-crowdfunding' ),
            'CU' => __( 'Cuba', 'wp-crowdfunding' ),
            'CW' => __( 'Cura&ccedil;ao', 'wp-crowdfunding' ),
            'CY' => __( 'Cyprus', 'wp-crowdfunding' ),
            'CZ' => __( 'Czech Republic', 'wp-crowdfunding' ),
            'DK' => __( 'Denmark', 'wp-crowdfunding' ),
            'DJ' => __( 'Djibouti', 'wp-crowdfunding' ),
            'DM' => __( 'Dominica', 'wp-crowdfunding' ),
            'DO' => __( 'Dominican Republic', 'wp-crowdfunding' ),
            'EC' => __( 'Ecuador', 'wp-crowdfunding' ),
            'EG' => __( 'Egypt', 'wp-crowdfunding' ),
            'SV' => __( 'El Salvador', 'wp-crowdfunding' ),
            'GQ' => __( 'Equatorial Guinea', 'wp-crowdfunding' ),
            'ER' => __( 'Eritrea', 'wp-crowdfunding' ),
            'EE' => __( 'Estonia', 'wp-crowdfunding' ),
            'ET' => __( 'Ethiopia', 'wp-crowdfunding' ),
            'FK' => __( 'Falkland Islands', 'wp-crowdfunding' ),
            'FO' => __( 'Faroe Islands', 'wp-crowdfunding' ),
            'FJ' => __( 'Fiji', 'wp-crowdfunding' ),
            'FI' => __( 'Finland', 'wp-crowdfunding' ),
            'FR' => __( 'France', 'wp-crowdfunding' ),
            'GF' => __( 'French Guiana', 'wp-crowdfunding' ),
            'PF' => __( 'French Polynesia', 'wp-crowdfunding' ),
            'TF' => __( 'French Southern Territories', 'wp-crowdfunding' ),
            'GA' => __( 'Gabon', 'wp-crowdfunding' ),
            'GM' => __( 'Gambia', 'wp-crowdfunding' ),
            'GE' => __( 'Georgia', 'wp-crowdfunding' ),
            'DE' => __( 'Germany', 'wp-crowdfunding' ),
            'GH' => __( 'Ghana', 'wp-crowdfunding' ),
            'GI' => __( 'Gibraltar', 'wp-crowdfunding' ),
            'GR' => __( 'Greece', 'wp-crowdfunding' ),
            'GL' => __( 'Greenland', 'wp-crowdfunding' ),
            'GD' => __( 'Grenada', 'wp-crowdfunding' ),
            'GP' => __( 'Guadeloupe', 'wp-crowdfunding' ),
            'GU' => __( 'Guam', 'wp-crowdfunding' ),
            'GT' => __( 'Guatemala', 'wp-crowdfunding' ),
            'GG' => __( 'Guernsey', 'wp-crowdfunding' ),
            'GN' => __( 'Guinea', 'wp-crowdfunding' ),
            'GW' => __( 'Guinea-Bissau', 'wp-crowdfunding' ),
            'GY' => __( 'Guyana', 'wp-crowdfunding' ),
            'HT' => __( 'Haiti', 'wp-crowdfunding' ),
            'HM' => __( 'Heard Island and McDonald Islands', 'wp-crowdfunding' ),
            'HN' => __( 'Honduras', 'wp-crowdfunding' ),
            'HK' => __( 'Hong Kong', 'wp-crowdfunding' ),
            'HU' => __( 'Hungary', 'wp-crowdfunding' ),
            'IS' => __( 'Iceland', 'wp-crowdfunding' ),
            'IN' => __( 'India', 'wp-crowdfunding' ),
            'ID' => __( 'Indonesia', 'wp-crowdfunding' ),
            'IR' => __( 'Iran', 'wp-crowdfunding' ),
            'IQ' => __( 'Iraq', 'wp-crowdfunding' ),
            'IE' => __( 'Ireland', 'wp-crowdfunding' ),
            'IM' => __( 'Isle of Man', 'wp-crowdfunding' ),
            'IL' => __( 'Israel', 'wp-crowdfunding' ),
            'IT' => __( 'Italy', 'wp-crowdfunding' ),
            'CI' => __( 'Ivory Coast', 'wp-crowdfunding' ),
            'JM' => __( 'Jamaica', 'wp-crowdfunding' ),
            'JP' => __( 'Japan', 'wp-crowdfunding' ),
            'JE' => __( 'Jersey', 'wp-crowdfunding' ),
            'JO' => __( 'Jordan', 'wp-crowdfunding' ),
            'KZ' => __( 'Kazakhstan', 'wp-crowdfunding' ),
            'KE' => __( 'Kenya', 'wp-crowdfunding' ),
            'KI' => __( 'Kiribati', 'wp-crowdfunding' ),
            'KW' => __( 'Kuwait', 'wp-crowdfunding' ),
            'KG' => __( 'Kyrgyzstan', 'wp-crowdfunding' ),
            'LA' => __( 'Laos', 'wp-crowdfunding' ),
            'LV' => __( 'Latvia', 'wp-crowdfunding' ),
            'LB' => __( 'Lebanon', 'wp-crowdfunding' ),
            'LS' => __( 'Lesotho', 'wp-crowdfunding' ),
            'LR' => __( 'Liberia', 'wp-crowdfunding' ),
            'LY' => __( 'Libya', 'wp-crowdfunding' ),
            'LI' => __( 'Liechtenstein', 'wp-crowdfunding' ),
            'LT' => __( 'Lithuania', 'wp-crowdfunding' ),
            'LU' => __( 'Luxembourg', 'wp-crowdfunding' ),
            'MO' => __( 'Macao S.A.R., China', 'wp-crowdfunding' ),
            'MK' => __( 'North Macedonia', 'wp-crowdfunding' ),
            'MG' => __( 'Madagascar', 'wp-crowdfunding' ),
            'MW' => __( 'Malawi', 'wp-crowdfunding' ),
            'MY' => __( 'Malaysia', 'wp-crowdfunding' ),
            'MV' => __( 'Maldives', 'wp-crowdfunding' ),
            'ML' => __( 'Mali', 'wp-crowdfunding' ),
            'MT' => __( 'Malta', 'wp-crowdfunding' ),
            'MH' => __( 'Marshall Islands', 'wp-crowdfunding' ),
            'MQ' => __( 'Martinique', 'wp-crowdfunding' ),
            'MR' => __( 'Mauritania', 'wp-crowdfunding' ),
            'MU' => __( 'Mauritius', 'wp-crowdfunding' ),
            'YT' => __( 'Mayotte', 'wp-crowdfunding' ),
            'MX' => __( 'Mexico', 'wp-crowdfunding' ),
            'FM' => __( 'Micronesia', 'wp-crowdfunding' ),
            'MD' => __( 'Moldova', 'wp-crowdfunding' ),
            'MC' => __( 'Monaco', 'wp-crowdfunding' ),
            'MN' => __( 'Mongolia', 'wp-crowdfunding' ),
            'ME' => __( 'Montenegro', 'wp-crowdfunding' ),
            'MS' => __( 'Montserrat', 'wp-crowdfunding' ),
            'MA' => __( 'Morocco', 'wp-crowdfunding' ),
            'MZ' => __( 'Mozambique', 'wp-crowdfunding' ),
            'MM' => __( 'Myanmar', 'wp-crowdfunding' ),
            'NA' => __( 'Namibia', 'wp-crowdfunding' ),
            'NR' => __( 'Nauru', 'wp-crowdfunding' ),
            'NP' => __( 'Nepal', 'wp-crowdfunding' ),
            'NL' => __( 'Netherlands', 'wp-crowdfunding' ),
            'NC' => __( 'New Caledonia', 'wp-crowdfunding' ),
            'NZ' => __( 'New Zealand', 'wp-crowdfunding' ),
            'NI' => __( 'Nicaragua', 'wp-crowdfunding' ),
            'NE' => __( 'Niger', 'wp-crowdfunding' ),
            'NG' => __( 'Nigeria', 'wp-crowdfunding' ),
            'NU' => __( 'Niue', 'wp-crowdfunding' ),
            'NF' => __( 'Norfolk Island', 'wp-crowdfunding' ),
            'MP' => __( 'Northern Mariana Islands', 'wp-crowdfunding' ),
            'KP' => __( 'North Korea', 'wp-crowdfunding' ),
            'NO' => __( 'Norway', 'wp-crowdfunding' ),
            'OM' => __( 'Oman', 'wp-crowdfunding' ),
            'PK' => __( 'Pakistan', 'wp-crowdfunding' ),
            'PS' => __( 'Palestinian Territory', 'wp-crowdfunding' ),
            'PA' => __( 'Panama', 'wp-crowdfunding' ),
            'PG' => __( 'Papua New Guinea', 'wp-crowdfunding' ),
            'PY' => __( 'Paraguay', 'wp-crowdfunding' ),
            'PE' => __( 'Peru', 'wp-crowdfunding' ),
            'PH' => __( 'Philippines', 'wp-crowdfunding' ),
            'PN' => __( 'Pitcairn', 'wp-crowdfunding' ),
            'PL' => __( 'Poland', 'wp-crowdfunding' ),
            'PT' => __( 'Portugal', 'wp-crowdfunding' ),
            'PR' => __( 'Puerto Rico', 'wp-crowdfunding' ),
            'QA' => __( 'Qatar', 'wp-crowdfunding' ),
            'RE' => __( 'Reunion', 'wp-crowdfunding' ),
            'RO' => __( 'Romania', 'wp-crowdfunding' ),
            'RU' => __( 'Russia', 'wp-crowdfunding' ),
            'RW' => __( 'Rwanda', 'wp-crowdfunding' ),
            'BL' => __( 'Saint Barth&eacute;lemy', 'wp-crowdfunding' ),
            'SH' => __( 'Saint Helena', 'wp-crowdfunding' ),
            'KN' => __( 'Saint Kitts and Nevis', 'wp-crowdfunding' ),
            'LC' => __( 'Saint Lucia', 'wp-crowdfunding' ),
            'MF' => __( 'Saint Martin (French part)', 'wp-crowdfunding' ),
            'SX' => __( 'Saint Martin (Dutch part)', 'wp-crowdfunding' ),
            'PM' => __( 'Saint Pierre and Miquelon', 'wp-crowdfunding' ),
            'VC' => __( 'Saint Vincent and the Grenadines', 'wp-crowdfunding' ),
            'SM' => __( 'San Marino', 'wp-crowdfunding' ),
            'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'wp-crowdfunding' ),
            'SA' => __( 'Saudi Arabia', 'wp-crowdfunding' ),
            'SN' => __( 'Senegal', 'wp-crowdfunding' ),
            'RS' => __( 'Serbia', 'wp-crowdfunding' ),
            'SC' => __( 'Seychelles', 'wp-crowdfunding' ),
            'SL' => __( 'Sierra Leone', 'wp-crowdfunding' ),
            'SG' => __( 'Singapore', 'wp-crowdfunding' ),
            'SK' => __( 'Slovakia', 'wp-crowdfunding' ),
            'SI' => __( 'Slovenia', 'wp-crowdfunding' ),
            'SB' => __( 'Solomon Islands', 'wp-crowdfunding' ),
            'SO' => __( 'Somalia', 'wp-crowdfunding' ),
            'ZA' => __( 'South Africa', 'wp-crowdfunding' ),
            'GS' => __( 'South Georgia/Sandwich Islands', 'wp-crowdfunding' ),
            'KR' => __( 'South Korea', 'wp-crowdfunding' ),
            'SS' => __( 'South Sudan', 'wp-crowdfunding' ),
            'ES' => __( 'Spain', 'wp-crowdfunding' ),
            'LK' => __( 'Sri Lanka', 'wp-crowdfunding' ),
            'SD' => __( 'Sudan', 'wp-crowdfunding' ),
            'SR' => __( 'Suriname', 'wp-crowdfunding' ),
            'SJ' => __( 'Svalbard and Jan Mayen', 'wp-crowdfunding' ),
            'SZ' => __( 'Swaziland', 'wp-crowdfunding' ),
            'SE' => __( 'Sweden', 'wp-crowdfunding' ),
            'CH' => __( 'Switzerland', 'wp-crowdfunding' ),
            'SY' => __( 'Syria', 'wp-crowdfunding' ),
            'TW' => __( 'Taiwan', 'wp-crowdfunding' ),
            'TJ' => __( 'Tajikistan', 'wp-crowdfunding' ),
            'TZ' => __( 'Tanzania', 'wp-crowdfunding' ),
            'TH' => __( 'Thailand', 'wp-crowdfunding' ),
            'TL' => __( 'Timor-Leste', 'wp-crowdfunding' ),
            'TG' => __( 'Togo', 'wp-crowdfunding' ),
            'TK' => __( 'Tokelau', 'wp-crowdfunding' ),
            'TO' => __( 'Tonga', 'wp-crowdfunding' ),
            'TT' => __( 'Trinidad and Tobago', 'wp-crowdfunding' ),
            'TN' => __( 'Tunisia', 'wp-crowdfunding' ),
            'TR' => __( 'Turkey', 'wp-crowdfunding' ),
            'TM' => __( 'Turkmenistan', 'wp-crowdfunding' ),
            'TC' => __( 'Turks and Caicos Islands', 'wp-crowdfunding' ),
            'TV' => __( 'Tuvalu', 'wp-crowdfunding' ),
            'UG' => __( 'Uganda', 'wp-crowdfunding' ),
            'UA' => __( 'Ukraine', 'wp-crowdfunding' ),
            'AE' => __( 'United Arab Emirates', 'wp-crowdfunding' ),
            'GB' => __( 'United Kingdom (UK)', 'wp-crowdfunding' ),
            'US' => __( 'United States (US)', 'wp-crowdfunding' ),
            'UM' => __( 'United States (US) Minor Outlying Islands', 'wp-crowdfunding' ),
            'UY' => __( 'Uruguay', 'wp-crowdfunding' ),
            'UZ' => __( 'Uzbekistan', 'wp-crowdfunding' ),
            'VU' => __( 'Vanuatu', 'wp-crowdfunding' ),
            'VA' => __( 'Vatican', 'wp-crowdfunding' ),
            'VE' => __( 'Venezuela', 'wp-crowdfunding' ),
            'VN' => __( 'Vietnam', 'wp-crowdfunding' ),
            'VG' => __( 'Virgin Islands (British)', 'wp-crowdfunding' ),
            'VI' => __( 'Virgin Islands (US)', 'wp-crowdfunding' ),
            'WF' => __( 'Wallis and Futuna', 'wp-crowdfunding' ),
            'EH' => __( 'Western Sahara', 'wp-crowdfunding' ),
            'WS' => __( 'Samoa', 'wp-crowdfunding' ),
            'YE' => __( 'Yemen', 'wp-crowdfunding' ),
            'ZM' => __( 'Zambia', 'wp-crowdfunding' ),
            'ZW' => __( 'Zimbabwe', 'wp-crowdfunding' ),
        );
    }


}