<?php

/**
 * Generate Reports
 */
global $wpdb;
$to_date            = date('Y-m-d 23:59:59');
$from_date          = date('Y-m-d 00:00:00', strtotime('-6 days'));
$chart_bottom_title = "Last 7 days";
$query_range        = 'day_wise';

$date_range = '';
if ( ! empty($_GET['date_range'])){
    $date_range     = sanitize_text_field($_GET['date_range']);

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
    $from_date          = sanitize_text_field($_GET['date_range_from']);
}
if (! empty($_GET['date_range_to'])){
    $to_date            = sanitize_text_field($_GET['date_range_to']);
}

$from_time              = strtotime('-1 day', strtotime($from_date));
$to_time                = strtotime($to_date);
$reports                = new WPCF_Reports_Query();
$reports->start_date    = $from_time;
$reports->end_date      = $to_time;
?>

<div class="wrap campaign-warp">
    <!-- Top menu -->
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&tab=top_campaigns&date_range=last_7_days' ); ?>" class="page-title-action"><?php _e('Last 7 days', 'wp-crowdfunding') ?></a>
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&tab=top_campaigns&date_range=this_month' ); ?>" class="page-title-action"><?php _e('This Month', 'wp-crowdfunding') ?></a>
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&tab=top_campaigns&date_range=last_month' ); ?>" class="page-title-action"><?php _e('Last Month', 'wp-crowdfunding') ?></a>
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&tab=top_campaigns&date_range=last_6_months' ); ?>" class="page-title-action"><?php _e('Last 6 Months', 'wp-crowdfunding') ?></a>
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&tab=top_campaigns&date_range=this_year' ); ?>" class="page-title-action"><?php _e('This Year', 'wp-crowdfunding') ?></a>
    <a href="<?php echo admin_url( 'admin.php?page=wpcf-reports&tab=top_campaigns&date_range=last_year' ); ?>" class="page-title-action"><?php _e('Last Year', 'wp-crowdfunding') ?></a>

    <form method="get" action="">
        <input type="hidden" name="page" value="wpcf-reports" />
        <input type="text" id="datepicker" name="date_range_from" class="datepickers_1" value="<?php echo date('Y-m-d', strtotime($from_date)); ?>" placeholder="From" />
        <input type="text" name="date_range_to" class="datepickers_1" value="<?php echo date('Y-m-d', strtotime($to_date)); ?>" placeholder="To" />
        <input type="hidden" name="tab" value="top_campaigns" />
        <input type="submit" value="Search" class="button" id="search-submit">
    </form>



    <?php
    $args = array(
        'post_type'     => 'product',
        'posts_per_page'=> 20,
        'date_query'    => array(
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
        'meta_key'      => 'total_sales',
        'orderby'       => 'meta_value_num',
        'order'         => 'DESC',
    );

    $loop       = new WP_Query( $args );
    $csv        = array();
    $csv[]      = array( 'Campaigns Name', 'Total Sales', 'Total Order' );
    if ( $loop->have_posts() ) {
        ?>

        <table class="wp-list-table widefat fixed striped">
            <tr>
                <th><?php _e( "Campaigns Name","wp-crowdfunding" ); ?></th>
                <th><?php _e( "Total Sales","wp-crowdfunding" ); ?></th>
                <th><?php _e( "Total Order","wp-crowdfunding" ); ?></th>
            </tr>
            <?php
            while ( $loop->have_posts() ) : $loop->the_post();
                //query
                $get_sales_query_by_item = array(
                    'data' => array(
                        '_line_total' => array(
                            'type'              => 'order_item_meta',
                            'order_item_type'   => 'line_item',
                            'function'          => 'SUM',
                            'name'              => 'order_item_amount'
                        )
                    ),
                    'where_meta'    => array(
                        'relation'  => 'OR',
                        array(
                            'type'       => 'order_item_meta',
                            'meta_key'   => array( '_product_id', '_variation_id' ),
                            'meta_value' => array( get_the_ID()),
                            'operator'   => 'IN'
                        )
                    ),
                    'query_type'   => 'get_var',
                    'order_status' => array( 'completed' ),
                    'filter_range' => true
                );


                $get_sales_query_by_item_count = array(
                    'data' => array(
                        '_line_total' => array(
                            'type'            => 'order_item_meta',
                            'order_item_type' => 'line_item',
                            'function'        => 'COUNT',
                            'name'            => 'order_item_amount'
                        )
                    ),
                    'where_meta' => array(
                        'relation' => 'OR',
                        array(
                            'type'       => 'order_item_meta',
                            'meta_key'   => array( '_product_id', '_variation_id' ),
                            'meta_value' => array( get_the_ID()),
                            'operator'   => 'IN'
                        )
                    ),
                    'query_type'   => 'get_var',
                    'order_status' => array('completed'),
                    'filter_range' => true
                );

                $total_sales_by_product     = $reports->wpcf_get_order_report_data($get_sales_query_by_item);
                $total_sales_by_count       = $reports->wpcf_get_order_report_data($get_sales_query_by_item_count);
                $csv[]                      = array(get_the_title(), $total_sales_by_product, get_post_meta(get_the_ID(), 'total_sales', true));
                ?>
                <tr>
                    <td><a href="post.php?post=<?php echo get_the_ID(); ?>&action=edit"> <?php echo get_the_title() ?> </a></td>
                    <td><?php echo wc_price($total_sales_by_product) ?></td>
                    <td><?php echo get_post_meta(get_the_ID(), 'total_sales', true) ?></td>
                </tr>
                <?php
            endwhile;
            ?>
        </table>
        <?php
    } else {
        echo __( 'No products found','wp-crowdfunding' );
    }
    wp_reset_postdata();
    ?>

    <a class="button-default" href="<?php echo add_query_arg(array('export_csv'=> str_replace('"', '--', serialize($csv)), 'file_name' => $chart_bottom_title),$_SERVER['REQUEST_URI']); ?>">
        <?php _e( "Export Last 7 days CSV Report","wp-crowdfunding" ); ?>
    </a>

    <a class="button-default" href="<?php echo add_query_arg('export_csv', str_replace('"', '--', serialize($csv)),$_SERVER['REQUEST_URI']); ?>">
        <?php _e( "Export Currently Showing CSV","wp-crowdfunding" ); ?>
    </a>
</div>