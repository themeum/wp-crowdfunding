<?php
defined( 'ABSPATH' ) || exit;

if (! function_exists('wpneo_post')){
    function wpneo_post($post_item){
        if (!empty($_POST[$post_item])) {
            return $_POST[$post_item];
        }
        return null;
    }
}

if (! function_exists('wpcf_update_text')){
    function wpcf_update_text($option_name = '', $option_value = null){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        }
    }
}

if (! function_exists('wpcf_update_checkbox')){
    function wpcf_update_checkbox($option_name = '', $option_value = null, $checked_default_value = 'false'){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        } else{
            update_option($option_name, $checked_default_value);
        }
    }
}

if (! function_exists('wpcf_update_meta')){
    function wpcf_update_meta($post_id, $meta_name = '', $meta_value = null){
        update_post_meta( $post_id, $meta_name, $meta_value);
    }
}

if (! function_exists('wpcf_update_meta_checkbox')){
    function wpcf_update_meta_checkbox($post_id, $meta_name = '', $meta_value = null, $checked_default_value = 'false'){
        if (!empty($meta_value)) {
            update_post_meta( $post_id, $meta_name, $meta_value);
        }else{
            update_post_meta( $post_id, $meta_name, $checked_default_value);
        }
    }
}

if (! function_exists('wpcf_get_published_pages')) {
    function wpcf_get_published_pages(){
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
}

/**
 * @param string $version
 * @return bool
 */
function wpcf_wc_version_check( $version = '3.0' ) {
    if ( class_exists( 'WooCommerce' ) ) {
        global $woocommerce;
        if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
            return true;
        }
    }
    return false;
}


function wpcf_vendor(){
    return get_option( 'vendor_type', 'woocommerce' );
}

function wpcf_is_woocommerce(){
    if( wpcf_vendor() == 'woocommerce' ){
        return true;
    }else{
        return false;
    }
}


/**
 * @return mixed|void
 *
 * @return Crowdfunding Admin Page ID
 */

if ( ! function_exists('wpcf_screen_id')){
    function wpcf_screen_id(){
        $screen_ids = array(
            'toplevel_page_wpneo-crowdfunding',
            'crowdfunding_page_wpneo-crowdfunding-reports',
            'crowdfunding_page_wpneo-crowdfunding-withdraw',
        );

        return apply_filters('wpcf_screen_id', $screen_ids);
    }
}

/**
 * @param $from_date
 * @param $to_date
 * @return array
 */
if ( ! function_exists('wpcf_get_date_range_pladges_received')){
    function wpcf_get_date_range_pladges_received($from_date = null, $to_date = null){

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
}


/**
 * @param $product_ids
 * @param array $order_status
 *
 * @return array
 */

if ( ! function_exists('get_orders_ids_by_product_ids')){
	function get_orders_ids_by_product_ids( $product_ids , $order_status = array( 'wc-completed' ) ){
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
}

/**
 * @param int $user_id
 *
 * @return array
 */

if ( ! function_exists('get_products_ids_by_user')){
	function get_products_ids_by_user($user_id = 0){
		if ( ! $user_id){
			$user_id = get_current_user_id();
		}
		global $wpdb;
		$results = $wpdb->get_col( "SELECT ID from {$wpdb->posts} WHERE post_author = {$user_id} AND post_type = 'product' " );

		return $results;
	}
}

if ( ! function_exists('wp_crowdfunding_license_info')){
	function wp_crowdfunding_license_info(){
		$blank_license_info = array(
			'activated'     => false,
			'license_key'   => '',
			'license_to'    => '',
			'expires_at'    => '',
			'msg'  => 'A valid license is required to unlock available features',
		);

		$saved_license_info = maybe_unserialize(get_option(WPCF_BASENAME.'_license_info'));

		if ($saved_license_info && is_array($saved_license_info)){
			return (object) array_merge($blank_license_info, $saved_license_info);
		}
		return (object) $blank_license_info;
	}
}

$GLOBALS['wp_crowdfunding_license_info'] = wp_crowdfunding_license_info();

/**
 * @param int $author_id
 * @param string $author_nicename
 *
 * @return bool|string
 */
function get_wpcf_author_campaigns_url($author_id = 0, $author_nicename = ''){
	$author_id = $author_id ? $author_id : get_current_user_id();
	if (! $author_id){
		return false;
	}
	$url = get_author_posts_url($author_id, $author_nicename);
	return trailingslashit($url).'campaigns';
}


function wpcf_countries($author_id = 0, $author_nicename = ''){
    return array(
        'AF' => __( 'Afghanistan', 'woocommerce' ),
        'AX' => __( '&#197;land Islands', 'woocommerce' ),
        'AL' => __( 'Albania', 'woocommerce' ),
        'DZ' => __( 'Algeria', 'woocommerce' ),
        'AS' => __( 'American Samoa', 'woocommerce' ),
        'AD' => __( 'Andorra', 'woocommerce' ),
        'AO' => __( 'Angola', 'woocommerce' ),
        'AI' => __( 'Anguilla', 'woocommerce' ),
        'AQ' => __( 'Antarctica', 'woocommerce' ),
        'AG' => __( 'Antigua and Barbuda', 'woocommerce' ),
        'AR' => __( 'Argentina', 'woocommerce' ),
        'AM' => __( 'Armenia', 'woocommerce' ),
        'AW' => __( 'Aruba', 'woocommerce' ),
        'AU' => __( 'Australia', 'woocommerce' ),
        'AT' => __( 'Austria', 'woocommerce' ),
        'AZ' => __( 'Azerbaijan', 'woocommerce' ),
        'BS' => __( 'Bahamas', 'woocommerce' ),
        'BH' => __( 'Bahrain', 'woocommerce' ),
        'BD' => __( 'Bangladesh', 'woocommerce' ),
        'BB' => __( 'Barbados', 'woocommerce' ),
        'BY' => __( 'Belarus', 'woocommerce' ),
        'BE' => __( 'Belgium', 'woocommerce' ),
        'PW' => __( 'Belau', 'woocommerce' ),
        'BZ' => __( 'Belize', 'woocommerce' ),
        'BJ' => __( 'Benin', 'woocommerce' ),
        'BM' => __( 'Bermuda', 'woocommerce' ),
        'BT' => __( 'Bhutan', 'woocommerce' ),
        'BO' => __( 'Bolivia', 'woocommerce' ),
        'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'woocommerce' ),
        'BA' => __( 'Bosnia and Herzegovina', 'woocommerce' ),
        'BW' => __( 'Botswana', 'woocommerce' ),
        'BV' => __( 'Bouvet Island', 'woocommerce' ),
        'BR' => __( 'Brazil', 'woocommerce' ),
        'IO' => __( 'British Indian Ocean Territory', 'woocommerce' ),
        'BN' => __( 'Brunei', 'woocommerce' ),
        'BG' => __( 'Bulgaria', 'woocommerce' ),
        'BF' => __( 'Burkina Faso', 'woocommerce' ),
        'BI' => __( 'Burundi', 'woocommerce' ),
        'KH' => __( 'Cambodia', 'woocommerce' ),
        'CM' => __( 'Cameroon', 'woocommerce' ),
        'CA' => __( 'Canada', 'woocommerce' ),
        'CV' => __( 'Cape Verde', 'woocommerce' ),
        'KY' => __( 'Cayman Islands', 'woocommerce' ),
        'CF' => __( 'Central African Republic', 'woocommerce' ),
        'TD' => __( 'Chad', 'woocommerce' ),
        'CL' => __( 'Chile', 'woocommerce' ),
        'CN' => __( 'China', 'woocommerce' ),
        'CX' => __( 'Christmas Island', 'woocommerce' ),
        'CC' => __( 'Cocos (Keeling) Islands', 'woocommerce' ),
        'CO' => __( 'Colombia', 'woocommerce' ),
        'KM' => __( 'Comoros', 'woocommerce' ),
        'CG' => __( 'Congo (Brazzaville)', 'woocommerce' ),
        'CD' => __( 'Congo (Kinshasa)', 'woocommerce' ),
        'CK' => __( 'Cook Islands', 'woocommerce' ),
        'CR' => __( 'Costa Rica', 'woocommerce' ),
        'HR' => __( 'Croatia', 'woocommerce' ),
        'CU' => __( 'Cuba', 'woocommerce' ),
        'CW' => __( 'Cura&ccedil;ao', 'woocommerce' ),
        'CY' => __( 'Cyprus', 'woocommerce' ),
        'CZ' => __( 'Czech Republic', 'woocommerce' ),
        'DK' => __( 'Denmark', 'woocommerce' ),
        'DJ' => __( 'Djibouti', 'woocommerce' ),
        'DM' => __( 'Dominica', 'woocommerce' ),
        'DO' => __( 'Dominican Republic', 'woocommerce' ),
        'EC' => __( 'Ecuador', 'woocommerce' ),
        'EG' => __( 'Egypt', 'woocommerce' ),
        'SV' => __( 'El Salvador', 'woocommerce' ),
        'GQ' => __( 'Equatorial Guinea', 'woocommerce' ),
        'ER' => __( 'Eritrea', 'woocommerce' ),
        'EE' => __( 'Estonia', 'woocommerce' ),
        'ET' => __( 'Ethiopia', 'woocommerce' ),
        'FK' => __( 'Falkland Islands', 'woocommerce' ),
        'FO' => __( 'Faroe Islands', 'woocommerce' ),
        'FJ' => __( 'Fiji', 'woocommerce' ),
        'FI' => __( 'Finland', 'woocommerce' ),
        'FR' => __( 'France', 'woocommerce' ),
        'GF' => __( 'French Guiana', 'woocommerce' ),
        'PF' => __( 'French Polynesia', 'woocommerce' ),
        'TF' => __( 'French Southern Territories', 'woocommerce' ),
        'GA' => __( 'Gabon', 'woocommerce' ),
        'GM' => __( 'Gambia', 'woocommerce' ),
        'GE' => __( 'Georgia', 'woocommerce' ),
        'DE' => __( 'Germany', 'woocommerce' ),
        'GH' => __( 'Ghana', 'woocommerce' ),
        'GI' => __( 'Gibraltar', 'woocommerce' ),
        'GR' => __( 'Greece', 'woocommerce' ),
        'GL' => __( 'Greenland', 'woocommerce' ),
        'GD' => __( 'Grenada', 'woocommerce' ),
        'GP' => __( 'Guadeloupe', 'woocommerce' ),
        'GU' => __( 'Guam', 'woocommerce' ),
        'GT' => __( 'Guatemala', 'woocommerce' ),
        'GG' => __( 'Guernsey', 'woocommerce' ),
        'GN' => __( 'Guinea', 'woocommerce' ),
        'GW' => __( 'Guinea-Bissau', 'woocommerce' ),
        'GY' => __( 'Guyana', 'woocommerce' ),
        'HT' => __( 'Haiti', 'woocommerce' ),
        'HM' => __( 'Heard Island and McDonald Islands', 'woocommerce' ),
        'HN' => __( 'Honduras', 'woocommerce' ),
        'HK' => __( 'Hong Kong', 'woocommerce' ),
        'HU' => __( 'Hungary', 'woocommerce' ),
        'IS' => __( 'Iceland', 'woocommerce' ),
        'IN' => __( 'India', 'woocommerce' ),
        'ID' => __( 'Indonesia', 'woocommerce' ),
        'IR' => __( 'Iran', 'woocommerce' ),
        'IQ' => __( 'Iraq', 'woocommerce' ),
        'IE' => __( 'Ireland', 'woocommerce' ),
        'IM' => __( 'Isle of Man', 'woocommerce' ),
        'IL' => __( 'Israel', 'woocommerce' ),
        'IT' => __( 'Italy', 'woocommerce' ),
        'CI' => __( 'Ivory Coast', 'woocommerce' ),
        'JM' => __( 'Jamaica', 'woocommerce' ),
        'JP' => __( 'Japan', 'woocommerce' ),
        'JE' => __( 'Jersey', 'woocommerce' ),
        'JO' => __( 'Jordan', 'woocommerce' ),
        'KZ' => __( 'Kazakhstan', 'woocommerce' ),
        'KE' => __( 'Kenya', 'woocommerce' ),
        'KI' => __( 'Kiribati', 'woocommerce' ),
        'KW' => __( 'Kuwait', 'woocommerce' ),
        'KG' => __( 'Kyrgyzstan', 'woocommerce' ),
        'LA' => __( 'Laos', 'woocommerce' ),
        'LV' => __( 'Latvia', 'woocommerce' ),
        'LB' => __( 'Lebanon', 'woocommerce' ),
        'LS' => __( 'Lesotho', 'woocommerce' ),
        'LR' => __( 'Liberia', 'woocommerce' ),
        'LY' => __( 'Libya', 'woocommerce' ),
        'LI' => __( 'Liechtenstein', 'woocommerce' ),
        'LT' => __( 'Lithuania', 'woocommerce' ),
        'LU' => __( 'Luxembourg', 'woocommerce' ),
        'MO' => __( 'Macao S.A.R., China', 'woocommerce' ),
        'MK' => __( 'North Macedonia', 'woocommerce' ),
        'MG' => __( 'Madagascar', 'woocommerce' ),
        'MW' => __( 'Malawi', 'woocommerce' ),
        'MY' => __( 'Malaysia', 'woocommerce' ),
        'MV' => __( 'Maldives', 'woocommerce' ),
        'ML' => __( 'Mali', 'woocommerce' ),
        'MT' => __( 'Malta', 'woocommerce' ),
        'MH' => __( 'Marshall Islands', 'woocommerce' ),
        'MQ' => __( 'Martinique', 'woocommerce' ),
        'MR' => __( 'Mauritania', 'woocommerce' ),
        'MU' => __( 'Mauritius', 'woocommerce' ),
        'YT' => __( 'Mayotte', 'woocommerce' ),
        'MX' => __( 'Mexico', 'woocommerce' ),
        'FM' => __( 'Micronesia', 'woocommerce' ),
        'MD' => __( 'Moldova', 'woocommerce' ),
        'MC' => __( 'Monaco', 'woocommerce' ),
        'MN' => __( 'Mongolia', 'woocommerce' ),
        'ME' => __( 'Montenegro', 'woocommerce' ),
        'MS' => __( 'Montserrat', 'woocommerce' ),
        'MA' => __( 'Morocco', 'woocommerce' ),
        'MZ' => __( 'Mozambique', 'woocommerce' ),
        'MM' => __( 'Myanmar', 'woocommerce' ),
        'NA' => __( 'Namibia', 'woocommerce' ),
        'NR' => __( 'Nauru', 'woocommerce' ),
        'NP' => __( 'Nepal', 'woocommerce' ),
        'NL' => __( 'Netherlands', 'woocommerce' ),
        'NC' => __( 'New Caledonia', 'woocommerce' ),
        'NZ' => __( 'New Zealand', 'woocommerce' ),
        'NI' => __( 'Nicaragua', 'woocommerce' ),
        'NE' => __( 'Niger', 'woocommerce' ),
        'NG' => __( 'Nigeria', 'woocommerce' ),
        'NU' => __( 'Niue', 'woocommerce' ),
        'NF' => __( 'Norfolk Island', 'woocommerce' ),
        'MP' => __( 'Northern Mariana Islands', 'woocommerce' ),
        'KP' => __( 'North Korea', 'woocommerce' ),
        'NO' => __( 'Norway', 'woocommerce' ),
        'OM' => __( 'Oman', 'woocommerce' ),
        'PK' => __( 'Pakistan', 'woocommerce' ),
        'PS' => __( 'Palestinian Territory', 'woocommerce' ),
        'PA' => __( 'Panama', 'woocommerce' ),
        'PG' => __( 'Papua New Guinea', 'woocommerce' ),
        'PY' => __( 'Paraguay', 'woocommerce' ),
        'PE' => __( 'Peru', 'woocommerce' ),
        'PH' => __( 'Philippines', 'woocommerce' ),
        'PN' => __( 'Pitcairn', 'woocommerce' ),
        'PL' => __( 'Poland', 'woocommerce' ),
        'PT' => __( 'Portugal', 'woocommerce' ),
        'PR' => __( 'Puerto Rico', 'woocommerce' ),
        'QA' => __( 'Qatar', 'woocommerce' ),
        'RE' => __( 'Reunion', 'woocommerce' ),
        'RO' => __( 'Romania', 'woocommerce' ),
        'RU' => __( 'Russia', 'woocommerce' ),
        'RW' => __( 'Rwanda', 'woocommerce' ),
        'BL' => __( 'Saint Barth&eacute;lemy', 'woocommerce' ),
        'SH' => __( 'Saint Helena', 'woocommerce' ),
        'KN' => __( 'Saint Kitts and Nevis', 'woocommerce' ),
        'LC' => __( 'Saint Lucia', 'woocommerce' ),
        'MF' => __( 'Saint Martin (French part)', 'woocommerce' ),
        'SX' => __( 'Saint Martin (Dutch part)', 'woocommerce' ),
        'PM' => __( 'Saint Pierre and Miquelon', 'woocommerce' ),
        'VC' => __( 'Saint Vincent and the Grenadines', 'woocommerce' ),
        'SM' => __( 'San Marino', 'woocommerce' ),
        'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'woocommerce' ),
        'SA' => __( 'Saudi Arabia', 'woocommerce' ),
        'SN' => __( 'Senegal', 'woocommerce' ),
        'RS' => __( 'Serbia', 'woocommerce' ),
        'SC' => __( 'Seychelles', 'woocommerce' ),
        'SL' => __( 'Sierra Leone', 'woocommerce' ),
        'SG' => __( 'Singapore', 'woocommerce' ),
        'SK' => __( 'Slovakia', 'woocommerce' ),
        'SI' => __( 'Slovenia', 'woocommerce' ),
        'SB' => __( 'Solomon Islands', 'woocommerce' ),
        'SO' => __( 'Somalia', 'woocommerce' ),
        'ZA' => __( 'South Africa', 'woocommerce' ),
        'GS' => __( 'South Georgia/Sandwich Islands', 'woocommerce' ),
        'KR' => __( 'South Korea', 'woocommerce' ),
        'SS' => __( 'South Sudan', 'woocommerce' ),
        'ES' => __( 'Spain', 'woocommerce' ),
        'LK' => __( 'Sri Lanka', 'woocommerce' ),
        'SD' => __( 'Sudan', 'woocommerce' ),
        'SR' => __( 'Suriname', 'woocommerce' ),
        'SJ' => __( 'Svalbard and Jan Mayen', 'woocommerce' ),
        'SZ' => __( 'Swaziland', 'woocommerce' ),
        'SE' => __( 'Sweden', 'woocommerce' ),
        'CH' => __( 'Switzerland', 'woocommerce' ),
        'SY' => __( 'Syria', 'woocommerce' ),
        'TW' => __( 'Taiwan', 'woocommerce' ),
        'TJ' => __( 'Tajikistan', 'woocommerce' ),
        'TZ' => __( 'Tanzania', 'woocommerce' ),
        'TH' => __( 'Thailand', 'woocommerce' ),
        'TL' => __( 'Timor-Leste', 'woocommerce' ),
        'TG' => __( 'Togo', 'woocommerce' ),
        'TK' => __( 'Tokelau', 'woocommerce' ),
        'TO' => __( 'Tonga', 'woocommerce' ),
        'TT' => __( 'Trinidad and Tobago', 'woocommerce' ),
        'TN' => __( 'Tunisia', 'woocommerce' ),
        'TR' => __( 'Turkey', 'woocommerce' ),
        'TM' => __( 'Turkmenistan', 'woocommerce' ),
        'TC' => __( 'Turks and Caicos Islands', 'woocommerce' ),
        'TV' => __( 'Tuvalu', 'woocommerce' ),
        'UG' => __( 'Uganda', 'woocommerce' ),
        'UA' => __( 'Ukraine', 'woocommerce' ),
        'AE' => __( 'United Arab Emirates', 'woocommerce' ),
        'GB' => __( 'United Kingdom (UK)', 'woocommerce' ),
        'US' => __( 'United States (US)', 'woocommerce' ),
        'UM' => __( 'United States (US) Minor Outlying Islands', 'woocommerce' ),
        'UY' => __( 'Uruguay', 'woocommerce' ),
        'UZ' => __( 'Uzbekistan', 'woocommerce' ),
        'VU' => __( 'Vanuatu', 'woocommerce' ),
        'VA' => __( 'Vatican', 'woocommerce' ),
        'VE' => __( 'Venezuela', 'woocommerce' ),
        'VN' => __( 'Vietnam', 'woocommerce' ),
        'VG' => __( 'Virgin Islands (British)', 'woocommerce' ),
        'VI' => __( 'Virgin Islands (US)', 'woocommerce' ),
        'WF' => __( 'Wallis and Futuna', 'woocommerce' ),
        'EH' => __( 'Western Sahara', 'woocommerce' ),
        'WS' => __( 'Samoa', 'woocommerce' ),
        'YE' => __( 'Yemen', 'woocommerce' ),
        'ZM' => __( 'Zambia', 'woocommerce' ),
        'ZW' => __( 'Zimbabwe', 'woocommerce' ),
    );
}


/**
 * @param null $addon_field
 *
 * @return bool
 *
 * Get Addon config
 *
 * @since v.1.0.0
 */
function get_wpcf_addon_config($addon_field = null){
    if ( ! $addon_field){
        return false;
    }
    $addonsConfig = maybe_unserialize(get_option('wpcf_addons_config'));
    if (isset($addonsConfig[$addon_field])){
        return $addonsConfig[$addon_field];
    }
    return false;
}

/**
 * @param null $key
 * @param array $array
 *
 * @return array|bool|mixed
 *
 * get array value by dot notation
 *
 * @since v.1.0.0
 *
 */

function wpcf_avalue_dot($key = null, $array = array()){
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