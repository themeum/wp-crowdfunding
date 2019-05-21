<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $woocommerce, $wpdb;
$product_ids = array();
ob_start();

function wpneo_deposite_by_order_status(){
    return array('completed');
}

$current_user_id = get_current_user_id();
$product_ids = $wpdb->get_col("select ID from {$wpdb->posts} WHERE post_author = {$current_user_id} AND post_type= 'product' ");

if ( ! class_exists('WC_Admin_Report'))
    include_once($woocommerce->plugin_path().'/includes/admin/reports/class-wc-admin-report.php');

$wc_report = new WC_Admin_Report();
//$wc_report->start_date = $start_date;
//$wc_report->end_date = $end_date;

//echo(date('Y-m-d', $end_date));

$where_meta = array();

$where_meta[] = array(
    'type' => 'order_item_meta',
    'meta_key' => '_product_id',
    'operator' => 'in',
    'meta_value' => $product_ids
);
// Get report data

// Avoid max join size error
$wpdb->query('SET SQL_BIG_SELECTS=1');

// Prevent plugins from overriding the order status filter
add_filter('woocommerce_reports_order_statuses', 'wpneo_deposite_by_order_status', 9999);

// Based on woocoommerce/includes/admin/reports/class-wc-report-sales-by-product.php
$sold_products = $wc_report->get_order_report_data(array(
    'data' => array(
        '_product_id' => array(
            'type' => 'order_item_meta',
            'order_item_type' => 'line_item',
            'function' => '',
            'name' => 'product_id'
        ),
        '_qty' => array(
            'type' => 'order_item_meta',
            'order_item_type' => 'line_item',
            'function' => 'SUM',
            'name' => 'quantity'
        ),
        '_line_subtotal' => array(
            'type' => 'order_item_meta',
            'order_item_type' => 'line_item',
            'function' => 'SUM',
            'name' => 'gross'
        ),
        '_line_total' => array(
            'type' => 'order_item_meta',
            'order_item_type' => 'line_item',
            'function' => 'SUM',
            'name' => 'gross_after_discount'
        )
    ),
    'query_type' => 'get_results',
    'group_by' => 'product_id',
    'where_meta' => $where_meta,
    'order_by' => 'ID DESC',
    'order_types' => wc_get_order_types('order_count'),
    'order_status' => wpneo_deposite_by_order_status()
));
remove_filter('woocommerce_reports_order_statuses', 'wpneo_deposite_by_order_status', 9999);

$rows = array();
// Output report rows
foreach ($sold_products as $product) {
    $row = array();

    $row['product_id'] = $product->product_id;
    $row['campaign_title'] = html_entity_decode(get_the_title($product->product_id));
    $row['total_raised_count'] = $product->quantity;
    $row['total_gross'] = $product->gross;
    $row['total_gross_after_discount'] = $product->gross_after_discount;
    $rows[] = $row;
}
?>
    <div class="wpneo-content">
        <div class="wpneo-form">

            <?php
            if ( ! empty($rows)){
                ?>
                <div class="wpneo-shadow wpneo-padding25 wpneo-clearfix">
                <div class="wpneo-responsive-table">
                    <table class="stripe-table">
                    <thead>
                    <tr>
                        <th><?php _e('Campaign Name', 'wp-crowdfunding'); ?></th>
                        <th><?php _e('Raised(Percentage)', 'wp-crowdfunding'); ?></th>
                        <th><?php _e('Receivable(Commision)', 'wp-crowdfunding'); ?></th>
                        <th><?php _e('Action', 'wp-crowdfunding'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $total_sales){ ?>
                        <tr>
                            <td><?php echo $total_sales['campaign_title']; ?></td>
                            <td>
                                <?php echo wc_price($total_sales['total_gross']);
                                $raised_percent = WPNEOCF()->getFundRaisedPercent($total_sales['product_id']);
                                echo "({$raised_percent}%)"; ?>
                            </td>
                            <td>
                                <?php
                                $wpneo_wallet_receiver_percent = get_post_meta($total_sales['product_id'], 'wpneo_wallet_receiver_percent', true);

                                //Add a meta wallet receiver if not exist
                                //@since 10.8
                                if ( ! $wpneo_wallet_receiver_percent){
                                    $wpneo_wallet_receiver_percent = (int) get_option('wallet_receiver_percent');
                                    update_post_meta($total_sales['product_id'], 'wpneo_wallet_receiver_percent',
                                        $wpneo_wallet_receiver_percent);
                                    echo $total_sales['product_id'].'-';
                                }

                                $commission = ( $total_sales['total_gross'] * $wpneo_wallet_receiver_percent ) / 100;
                                echo wc_price($commission) . " ({$wpneo_wallet_receiver_percent}%)" ;
                                ?>
                            </td>
                            <td>

                                <a class="label-primary" href="<?php echo add_query_arg(array('payment_campaign_id' => $total_sales['product_id'] )); ?>"><?php _e('View details', 'wp-crowdfunding'); ?></a>

                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>

            <?php }else{
                ?>
                <div class="wallet-alert-info">
                    <p><?php _e('There is no campaign data', 'wp-crowdfunding'); ?></p>
                </div>
                <?php
            } ?>
            </div>

        </div>
    </div>

<?php $html .= ob_get_clean();
