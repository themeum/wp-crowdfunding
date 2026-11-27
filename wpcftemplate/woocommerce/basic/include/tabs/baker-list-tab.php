<?php
defined( 'ABSPATH' ) || exit;

global $post;

$campaign_id = $post->ID;

$baker_list = wpcf_function()->get_customers_product();
?>
<table>
	<tr>
		<th><?php esc_html_e( 'Name', 'wp-crowdfunding' ); ?></th>
		<th><?php esc_html_e( 'Donate Amount', 'wp-crowdfunding' ); ?></th>
		<th><?php esc_html_e( 'Date', 'wp-crowdfunding' ); ?></th>
	</tr>

	<?php
	foreach ( $baker_list as $order ) {
		$order = new WC_Order( $order );

		if ( $order->get_status() == 'completed' ) {
			$ordered_items     = $order->get_items();
			$ordered_this_item = '';
			foreach ( $ordered_items as $item ) {
				if ( ! empty( $item['item_meta']['_product_id'][0] ) ) {
					if ( $campaign_id == $item['item_meta']['_product_id'][0] ) {
						$ordered_this_item = $item;
					}
				}
			}
			?>
			<tr>
				<td>
					<?php
					if ( wpcf_function()->is_backer_anonymous( $order, $campaign_id ) ) {
						esc_html_e( 'Anonymous', 'wp-crowdfunding' );
					} else {
						echo esc_html( $order->get_billing_first_name() ) . ' ' . esc_html( $order->get_billing_last_name() );
					}
					?>
				</td>
				<td>
					<?php
						echo wp_kses_post( wpcf_function()->price( $order->get_total() ) );
					?>
				</td>
				<td><?php echo esc_html( gmdate( 'F d, Y', strtotime( $order->get_date_created() ) ) ); ?></td>
			</tr>
			<?php
		} //if order completed
	}
	?>
</table>
