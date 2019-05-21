<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $woocommerce, $wpdb;
ob_start();
$campaign_id        = (int) sanitize_text_field($_GET['payment_campaign_id']);
$raised_percent 	=  WPNEOCF()->getFundRaisedPercent( $campaign_id ); // Raised Percent
$fund_raised  		= wpneo_crowdfunding_get_total_fund_raised_by_campaign( $campaign_id );
$fund_raised 		= $fund_raised ? wc_price($fund_raised) : wc_price(0); // Fund Raised
$funding_goal 		= wc_price(get_post_meta( $campaign_id, '_nf_funding_goal', true)); // Funding Goal

/**
 * Get sales by product
 */
if ( ! class_exists('WC_Admin_Report')){
    include_once($woocommerce->plugin_path().'/includes/admin/reports/class-wc-admin-report.php');
}

$wc_report      = new WC_Admin_Report();
$where_meta     = array();
$where_meta[]   = array(
    'type'          => 'order_item_meta',
    'meta_key'      => '_product_id',
    'operator'      => 'in',
    'meta_value'    => array($campaign_id)
);

// Avoid max join size error
$wpdb->query('SET SQL_BIG_SELECTS=1');

$sold_products = $wc_report->get_order_report_data(array(
    'data' => array(
        '_product_id' => array(
            'type'              => 'order_item_meta',
            'order_item_type'   => 'line_item',
            'function'          => '',
            'name'              => 'product_id'
        ),
        '_qty' => array(
            'type'              => 'order_item_meta',
            'order_item_type'   => 'line_item',
            'function'          => 'SUM',
            'name'              => 'quantity'
        ),
        '_line_subtotal' => array(
            'type'              => 'order_item_meta',
            'order_item_type'   => 'line_item',
            'function'          => 'SUM',
            'name'              => 'gross'
        ),
        '_line_total' => array(
            'type'              => 'order_item_meta',
            'order_item_type'   => 'line_item',
            'function'          => 'SUM',
            'name'              => 'gross_after_discount'
        )
    ),
    'query_type'    => 'get_results',
    'group_by'      => 'product_id',
    'where_meta'    => $where_meta,
    'order_by'      => 'quantity DESC',
    'order_types'   => wc_get_order_types('order_count'),
    'order_status'  => array('completed')
));

$total_sales = 0;
foreach ($sold_products as $product) {
    $total_sales = $product->gross;
}

$wpneo_wallet_receiver_percent = get_post_meta($campaign_id, 'wpneo_wallet_receiver_percent', true);
$commission = ( $total_sales * $wpneo_wallet_receiver_percent ) / 100;

?>
<div class="wpneo-shadow wpneo-padding25 wpneo-clearfix">
<div class="wpneo-form">
    <h1><?php echo get_the_title($campaign_id); ?></h1>

    <div class="wpneo-wallet-box box-purple">
        <p class="wpneo-box-text"><?php _e('Goal', 'wp-crowdfunding'); ?></p>
        <p class="wpneo-box-amount"> <?php echo $funding_goal; ?> </p>
    </div>

    <div class="wpneo-wallet-box  box-info">
        <p class="wpneo-box-text"><?php _e('Funding', 'wp-crowdfunding'); ?></p>
        <p class="wpneo-box-amount"> <?php echo $fund_raised; ?> </p>
    </div>

    <div class="wpneo-wallet-box box-blue">
        <p class="wpneo-box-text"><?php _e('Raised(%)', 'wp-crowdfunding'); ?></p>
        <p class="wpneo-box-amount"> <?php echo $raised_percent; ?>% </p>
    </div>

    <div class="wpneo-wallet-box box-green">
        <p class="wpneo-box-text"><?php _e('Receivable', 'wp-crowdfunding'); ?></p>
        <p class="wpneo-box-amount"> <?php echo wc_price($commission); ?> </p>
    </div>

    <div class="wpneo-wallet-box box-natural">
        <p class="wpneo-box-text"><?php _e('Commission', 'wp-crowdfunding'); ?></p>
        <p class="wpneo-box-amount"> <?php echo $wpneo_wallet_receiver_percent; ?>% </p>
    </div>

    <?php

    $balance = $commission;

    $withdraw_args = array(
        'post_type' => 'wpneo_withdraw',
        'post_parent'   => $campaign_id
    );
    $withdraw_query = new WP_Query($withdraw_args);
    $total_withdraw = 0;
    //$balance        = 0;

    if ($withdraw_query->have_posts()){
        ?>

        <div class="withdraw_lists_frontend">
            <table class="stripe-table">
                <thead>
                <tr>
                    <th>#<?php _e('Title', 'wp-crowdfunding'); ?></th>
                    <th>#<?php _e('Amount', 'wp-crowdfunding'); ?></th>
                    <th>#<?php _e('Status', 'wp-crowdfunding'); ?></th>
                </tr>
                </thead>
                <?php
                $withdraw_amount_array = array();
                while ($withdraw_query->have_posts()){ $withdraw_query->the_post();
                    ?>
                    <tr>
                        <td><?php the_title(); ?></td>
                        <td>
                            <?php $withdraw_amount = get_post_meta(get_the_ID(), 'wpneo_wallet_withdrawal_amount', true);
                            echo wc_price($withdraw_amount);
                            ?>
                        </td>
                        <td>
                            <?php
                            $withdraw_amount_array[] = $withdraw_amount;

                            // Paid & Pending
                            $request 	= get_post_meta( get_the_ID(),'withdraw_request_status',true );
                            if( $request == 'paid' ){
                                $request = '<span class="label-success">'.__("Paid","wp-crowdfunding").'</span>';
                            } else {
                                $request = '<span class="label-warning">'.__("Not Paid","wp-crowdfunding").'</span>';
                            }
                            echo $request;
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="1"></td>
                    <td><strong><?php _e('Receivable', 'wp-crowdfunding'); ?></strong></td>
                    <td><span class="label-info"><?php echo wc_price($commission); ?></span></td>
                </tr>
                <tr>
                    <td colspan="1"></td>
                    <td> <strong> <?php _e('Total Withdraw', 'wp-crowdfunding'); ?> </strong> </td>
                    <td>
                        <?php $total_withdraw = array_sum($withdraw_amount_array); ?>
                        <span class="label-primary"><?php echo wc_price($total_withdraw); ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="1"></td>
                    <td> <strong> <?php _e('Balance', 'wp-crowdfunding'); ?> </strong> </td>
                    <td>
                        <?php $balance = ($commission - $total_withdraw) ; ?>
                        <span class="label-success"><?php echo wc_price($balance); ?></span>
                    </td>
                </tr>


                <tr>
                    <td colspan="1"></td>
                    <td> <strong> <?php _e('Total Commission', 'wp-crowdfunding'); ?> </strong> </td>
                    <td> <?php echo wc_price($commission); ?> </td>
                </tr>

                <tr>
                    <td colspan="1"></td>
                    <td> <strong> <?php _e('Total Withdraw', 'wp-crowdfunding'); ?> </strong> </td>
                    <td>
                        <?php $total_withdraw = array_sum($withdraw_amount_array);
                        echo wc_price($total_withdraw); ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="1"></td>
                    <td> <strong> <?php _e('Balance', 'wp-crowdfunding'); ?> </strong> </td>
                    <td>
                        <?php
                        $balance = ($commission - $total_withdraw) ;
                        echo wc_price($balance);
                        ?>
                    </td>
                </tr>

            </table>
        </div>

        <?php
        $withdraw_query->reset_postdata();
    }

    //Withdraw
    $min_withdraw_amount = (int) get_option('walleet_min_withdraw_amount');

    if ($balance >= $min_withdraw_amount){ ?>

        <div class="wpneo-wallet-withdraw-button">
            <?php
            $withdraw_button            = '';
            $wpneo_wallet_withdraw_type = get_option('wpneo_wallet_withdraw_type');
            switch ($wpneo_wallet_withdraw_type){

                case 'anytime_canbe_withdraw' :
                    $withdraw_button = '<button class="label-primary  wpneo-message">'.__('Withdraw', 'wp-crowdfunding').'</button class="label-primary">';
                    break;

                case 'after_certain_period' :
                    $wpneo_wallet_withdraw_period_percent = get_option('wpneo_wallet_withdraw_period');
                    if ($raised_percent >= $wpneo_wallet_withdraw_period_percent){
                        $withdraw_button = '<button class="label-primary  wpneo-message">'.__('Withdraw', 'wp-crowdfunding').'</button class="label-primary">';
                    }
                    break;

                case 'after_project_complete' :
                    if ( ! WPNEOCF()->campaignValid()){
                        $withdraw_button = '<button class="label-primary  wpneo-message">'.__('Withdraw', 'wp-crowdfunding').'</button class="label-primary">';
                    }
                    break;
            }

            if ( ! empty($withdraw_button)){
                ?>
                <div id="wpneo-fade" class="wpneo-message-overlay"></div>
                <?php echo $withdraw_button; ?>
                <div id="light" class="wpneo-message-content wpneo_wallet_withdraw">
                    <div class="wpneo-modal-wrapper-head">
                        <h3 id="crowdfunding_modal_title"><?php _e('Withdraw Info' , 'wp-crowdfunding'); ?></h3>
                        <span class="wpneo-message-close withdraw-button-close"><?php _e("close","wp-crowdfunding"); ?></span>
                    </div>
                    <div class="wpneo-single">
                        <div class="wpneo-name"><?php _e('Amount' , 'wp-crowdfunding'); ?></div>
                        <div class="wpneo-fields">
                        <input type="number" value="" max="<?php echo $balance; ?>" data-campaign-id="<?php echo $campaign_id; ?>" name="wpneo_wallet_withdraw_amount" placeholder="<?php _e("Withdrawal amount","wp-crowdfunding"); ?>" />
                        <small>(<?php echo _e('You can withdraw upto ').' '.wc_price($balance); ?>)</small>
                        </div>
                    </div>

                    <div class="wpneo-single">
                        <div class="wpneo-name"><?php _e('Message' , 'wp-crowdfunding'); ?></div>
                        <div class="wpneo-fields">
                        <textarea name="wpneo_wallet_withdraw_message" class="wpneo_wallet_withdraw_message"></textarea>
                        </div>
                    </div>
                    <button class="wpneo_withdraw_button"> <?php _e('Withdraw', 'wp-crowdfunding'); ?> </button>
                    
                </div>
                <?php
            }

            ?>

        </div>

        <?php

    }else {

        ?>
        <div class="wallet-alert-info">
            <p><?php _e('Your current balance is below then tha minimum withdraw amount', 'wp-crowdfunding'); ?></p>
        </div>
        <?php
    }
    ?>

</div>
</div>

<?php $html .= ob_get_clean(); ?>
