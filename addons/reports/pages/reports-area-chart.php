<?php

/**
 * Generate Reports
 */
global $wpdb, $wp;
$date_range         = '';
$to_date            = date('Y-m-d 23:59:59');
$from_date          = date('Y-m-d 00:00:00', strtotime('-6 days'));
$chart_bottom_title = "Last 7 days";
$query_range        = 'day_wise';


if ( ! empty($_GET['date_range'])){
    $date_range = sanitize_text_field($_GET['date_range']);
    switch ($date_range){

        case 'last_7_days':
            $chart_bottom_title = "Last 7 days";
            $query_range        = 'day_wise';
            break;

        case 'this_month':
            $to_date            = date('Y-m-d 23:59:59');
            $from_date          = date('Y-m-01 00:00:00');
            $chart_bottom_title = "This Month";
            $query_range        = 'day_wise';
            break;

        case 'last_month':
            $to_date            = date('Y-m-t 23:59:59', strtotime('-1 month'));
            $from_date          = date('Y-m-01 00:00:00', strtotime('-1 month'));
            $chart_bottom_title = "Last Month";
            $query_range        = 'day_wise';
            break;

        case 'last_6_months':
            $to_date            = date('Y-m-t 23:59:59', strtotime('-1 month'));
            $from_date          = date('Y-m-01 00:00:00', strtotime('-6 month'));
            $chart_bottom_title = "Last 6 Months";
            $query_range        = 'month_wise';
            break;

        case 'this_year':
            $to_date            = date('Y-m-d 23:59:59');
            $from_date          = date('Y-01-01 00:00:00');
            $chart_bottom_title = "This Year (".date('Y').")";
            $query_range        = 'month_wise';
            break;

        case 'last_year':
            $to_date            = date('Y-12-31 23:59:59', strtotime('-1 year'));
            $from_date          = date('Y-01-01 00:00:00', strtotime('-1 year'));
            $chart_bottom_title = "Last Year (". (date('Y') -1 ).")";
            $query_range        = 'month_wise';
            break;
    }
}

if (! empty($_GET['date_range_from'])){
    $from_date      = sanitize_text_field($_GET['date_range_from']);
}
if (! empty($_GET['date_range_to'])){
    $to_date        = sanitize_text_field($_GET['date_range_to']);
}

$total_backers_amount_ever = array();
$from_time          = strtotime('-1 day', strtotime($from_date));
$to_time            = strtotime('-1 day', strtotime($to_date));
$sales_count_ever   = array();
$csv                = array();
$csv[]              = array("Date", "Pledge Amount ", "Sales");
$format             = '[';

if ($from_time < $to_time) {
   // $format .= "['Date', 'Pledge Amount (".get_woocommerce_currency().")', 'Sales'],";

    if ($query_range === 'day_wise') {

        while ($from_time < $to_time) {
            $from_time      = strtotime('+1 day', $from_time);
            $printed_date   = date('d M', $from_time);
            
            
            $sql = "SELECT ID, DATE_FORMAT(post_date, '%d %b') AS order_time  ,$wpdb->postmeta.*, GROUP_CONCAT(DISTINCT ID SEPARATOR ',') AS order_ids FROM $wpdb->posts 
LEFT JOIN $wpdb->postmeta 
ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
WHERE $wpdb->posts.post_type = 'shop_order'
AND meta_key = 'is_crowdfunding_order' AND meta_value = '1' AND post_status = 'wc-completed' AND post_date LIKE '".date('Y-m-d%', $from_time)."' group by order_time";

            $sales_count          = 0;
            $results              = $wpdb->get_results($sql);
            $total_backers_amount = array();
            
            if (  $results) {
                foreach ($results as $result) {
                    $total_backers_amount[] = $wpdb->get_var("(SELECT SUM(meta_value) from $wpdb->postmeta where post_id IN({$result->order_ids}) and meta_key = '_order_total' )");
                    $sales_count            = count(explode(',', $result->order_ids)) ;
                }
            } else{
                $total_backers_amount[]     = 0;
            }

            $csv[]                       = array($printed_date, $total_backers_amount, $sales_count);
            $format                     .= "['{$printed_date}', " . array_sum($total_backers_amount) . ", " . $sales_count. "],";
            $sales_count_ever[]          = $sales_count; //Get Total backers amount and sales count all time
            $total_backers_amount_ever[] = array_sum($total_backers_amount);
        }
    } else {
        $from_time          = strtotime('-1 month', strtotime($from_date));
        $to_time            = strtotime('-1 month', strtotime($to_date));
        
        while ($from_time < $to_time) {
            $from_time      = strtotime('+1 month', $from_time);
            $printed_date   = date('F', $from_time);

            $sql = "SELECT 
                        ID, MONTHNAME(post_date) AS order_time  ,$wpdb->postmeta.*, GROUP_CONCAT(DISTINCT ID SEPARATOR ',') AS order_ids 
                    FROM 
                        $wpdb->posts 
                    LEFT JOIN 
                        $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
                    WHERE 
                        $wpdb->posts.post_type = 'shop_order' AND meta_key = 'is_crowdfunding_order' AND meta_value = '1' AND post_status = 'wc-completed' AND post_date 
                    LIKE 
                        '".date('Y-m%', $from_time)."' group by order_time";

            $sales_count            = 0;
            $results                = $wpdb->get_results($sql);
            $total_backers_amount   = array();
            
            if (  $results ) {
                foreach ($results as $result) {
                    $total_backers_amount[] = $wpdb->get_var("(SELECT SUM(meta_value) from $wpdb->postmeta where post_id IN({$result->order_ids}) and meta_key = '_order_total' )");
                    $sales_count            = count(explode(',', $result->order_ids)) ;
                }
            } else {
                $total_backers_amount[]     = 0;
            }

            $csv[]                          = array($printed_date, $total_backers_amount, $sales_count);
            $format                         .= "['{$printed_date}', " . array_sum($total_backers_amount) . ", " . $sales_count. "],";
            $sales_count_ever[]             = $sales_count; //Get Total backers amount and sales count all time
            $total_backers_amount_ever[]    = array_sum($total_backers_amount);
        }

    }
}
$format .= ']';

/**
 * Get Total Campaigns
 */
$query_args = array(
    'post_type' => 'product',
    'tax_query' => array(
        array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => 'crowdfunding',
        ),
    ),
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
);
$get_crowdfunding_campaigns = new WP_Query($query_args);
$get_crowdfunding_campaigns->post_count;
?>

<div class="wrap campaign-warp">
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&date_range=last_7_days' ); ?>" class="page-title-action"><?php _e('Last 7 days', 'wp-crowdfunding'); ?></a>
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&date_range=this_month' ); ?>" class="page-title-action"><?php _e('This Month', 'wp-crowdfunding'); ?></a>
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&date_range=last_month' ); ?>" class="page-title-action"><?php _e('Last Month', 'wp-crowdfunding'); ?></a>
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&date_range=last_6_months' ); ?>" class="page-title-action"><?php _e('Last 6 Months', 'wp-crowdfunding'); ?></a>
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&date_range=this_year' ); ?>" class="page-title-action"><?php _e('This Year', 'wp-crowdfunding'); ?></a>
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&date_range=last_year' ); ?>" class="page-title-action"><?php _e('Last Year', 'wp-crowdfunding'); ?></a>

    <form method="get" action="">
        <input type="hidden" name="page" value="wpcf-reports" />
        <input type="text" id="datepicker" name="date_range_from" class="datepickers_1" value="<?php echo date('Y-m-d', strtotime($from_date)); ?>" placeholder="From" />
        <input type="text" name="date_range_to" class="datepickers_1" value="<?php echo date('Y-m-d', strtotime($to_date)); ?>" placeholder="To" />
        <input type="submit" value="Search" class="button" id="search-submit">
    </form>

    <div id="chart_div" style="width: 1200px; height: 500px;"></div>
    
    <a class="button-default" href="<?php echo add_query_arg(array('export_csv'=> str_replace('"', '--', serialize($csv)), 'file_name' => $chart_bottom_title),$_SERVER['REQUEST_URI']); ?>"><?php _e( "Export CSV","wp-crowdfunding" ); ?></a>

    <p class="alert alert-success"><?php _e('Total Campaigns : ', 'wp-crowdfunding'); ?>  <?php echo $get_crowdfunding_campaigns->post_count; ?> </p>
    <p class="alert alert-info"><?php _e('Total Backed : ', 'wp-crowdfunding'); ?>  <?php echo array_sum($sales_count_ever); ?> </p>
    <p class="alert alert-warning"><?php _e('Total Backed Amount : ', 'wp-crowdfunding'); ?>  <?php echo wc_price(array_sum($total_backers_amount_ever)); ?> </p>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'Pledge Amount');
        data.addColumn('number', 'Total Sales');
        data.addRows(<?php echo $format; ?>);
        var options = {
            legend: {position: 'top', maxLines:1},
            title: '<?php _e('Total Sales & Total Pledged Amount Report', 'wp-crowdfunding'); ?>',
            hAxis: { title: '<?php echo $chart_bottom_title; ?>', },
        };
        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>