<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$queryArgs = array(
	'numberposts' => - 1,
	'post_type'   => array( 'shop_order' ),
	'post_status' => array( 'wc-completed' ),
	'meta_query' => array(
		'relation' => 'AND',
		array(
			'key'     => 'wpcrowdfunding_wallet_deposit',
			'value'   => '0',
			'compare' => '>',
		),
		array(
			'key'     => '_customer_user',
			'value'   => get_current_user_id(),
			'compare' => '=',
		),
	),
);


$queryDeposit = new  WP_Query($queryArgs);

ob_start();
?>


	<div class="wpneo-content">
		<div class="wpneo-form">

			<?php
			if ( $queryDeposit->have_posts()){

			?>
			<div class="wpneo-shadow wpneo-padding25 wpneo-clearfix">
				<div class="wpneo-responsive-table">
					<table class="stripe-table">
						<thead>
						<tr>
							<th><?php _e('Deposits', 'wp-crowdfunding'); ?></th>
							<th><?php _e('Amount', 'wp-crowdfunding'); ?></th>
							<th><?php _e('Status', 'wp-crowdfunding'); ?></th>
							<th><?php _e('Payment Method', 'wp-crowdfunding'); ?></th>
							<th><?php _e('Date', 'wp-crowdfunding'); ?></th>
						</tr>
						</thead>
						<tbody>
						<?php

						while ($queryDeposit->have_posts()){
							$queryDeposit->the_post();
							$order = wc_get_order(get_the_ID());
							?>

							<tr>
								<td>#<?php echo $order->get_id(); ?></td>
								<td><?php echo wc_price($order->get_subtotal()); ?></td>
								<td><?php echo $order->get_status(); ?></td>
								<td><?php echo $order->get_payment_method_title(); ?></td>
								<td><?php echo human_time_diff(strtotime($order->get_date_created())).' '.__('ago', 'wp-crowdfunding');
								?></td>
							</tr>

						<?php } ?>
						</tbody>
					</table>
				</div>

				<?php
				$queryDeposit->reset_postdata();

			}else{
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
