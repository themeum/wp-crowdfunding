<?php
defined( 'ABSPATH' ) || exit;

$page_numb      = max( 1, get_query_var( 'paged' ) );
$posts_per_page = wpcf_function()->number_of_items_per_page();
$args           = array(
	'post_type'      => 'product',
	'post_status'    => array( 'publish', 'draft' ),
	'author'         => get_current_user_id(),
	'tax_query'      => array(
		array(
			'taxonomy' => 'product_type',
			'field'    => 'slug',
			'terms'    => 'crowdfunding',
		),
	),
	'posts_per_page' => $posts_per_page,
	'paged'          => $page_numb,
);

$html     .= '<div class="wpneo-row wp-dashboard-row">';
$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) :
	global $post;
	$i = 1;
	while ( $the_query->have_posts() ) :
		$the_query->the_post();
		ob_start();
		$permalink = wpcf_function()->is_published() ? get_permalink() : '#';
		?>
		<div class="wpneo-col6">
			<div class="wpcrowd-listing">
				<a href="<?php echo esc_url( $permalink ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"> <?php echo wp_kses_post( woocommerce_get_product_thumbnail( 'full' ) ); ?></a>
			</div>
			<div class="wpcrowd-listing-content">
				<div class="wpcrowd-admin-title">
					<h3><a href="<?php echo esc_url( $permalink ); ?> "><?php echo esc_html( get_the_title() ); ?></a></h3>
				</div>
					<div class="wpcrowd-admin-metadata">
						<div class="wpcrowd-admin-meta-info">
							<!--  Days to go -->
							<span class="wpneo-meta-wrap">
								<?php
								$days_remaining = apply_filters( 'date_expired_msg', esc_html__( '0', 'wp-crowdfunding' ) );
								if ( wpcf_function()->get_date_remaining() ) {
									$days_remaining = apply_filters( 'date_remaining_msg', __( wpcf_function()->get_date_remaining(), 'wp-crowdfunding' ) );
								}
								$end_method = get_post_meta( get_the_ID(), 'wpneo_campaign_end_method', true );
								if ( $end_method != 'never_end' ) {
									?>
									<?php if ( wpcf_function()->is_campaign_started() ) { ?>
										<span class="info-text">
										<?php
										echo esc_html( wpcf_function()->get_date_remaining() ) . ' ';
										esc_html_e( 'Days to go', 'wp-crowdfunding' );
										?>
										</span>
									<?php } else { ?>
										<span class="info-text">
										<?php
										echo esc_html( wpcf_function()->days_until_launch() ) . ' ';
										esc_html_e( 'Days Until Launch', 'wp-crowdfunding' );
										?>
										</span>
									<?php } ?>
								<?php } ?>
							</span>
							<!-- author -->
							<span class="wpneo-meta-wrap">
								<span class="wpneo-meta-name"><?php esc_html_e( 'by', 'wp-crowdfunding' ); ?> </span>
								<a href="<?php echo esc_attr( wpcf_function()->get_author_url( get_the_author_meta( 'user_login' ) ) ); ?>"><?php echo esc_html( wpcf_function()->get_author_name() ); ?></a>
							</span>

							<!-- fund-raised -->
							<?php
							$raised_percent = wpcf_function()->get_fund_raised_percent_format();
							$raised         = 0;
							$total_raised   = wpcf_function()->get_total_fund();
							if ( $total_raised ) {
								$raised = $total_raised;
							}
							?>
							<span class="wpneo-meta-wrap">
								<span class="wpneo-meta-name"><?php esc_html_e( 'Total', 'wp-crowdfunding' ); ?> </span>
								<?php echo wp_kses_post( wc_price( $raised ) ); ?>
							</span>
							<span class="wpneo-meta-wrap">
								<!-- Funding Goal -->
								<?php $funding_goal = get_post_meta( $post->ID, '_nf_funding_goal', true ); ?>
								<span class="wpneo-meta-name"><?php esc_html_e( 'Goal', 'wp-crowdfunding' ); ?></span>
								<?php echo wp_kses_post( wc_price( $funding_goal ) ); ?>
							</span>   
							
						</div><!--wpcrowd-admin-meta-info -->
					</div><!-- wpcrowd-admin-metadata -->
			</div><!-- wpneo-listing-content -->
			<?php do_action( 'wpcf_dashboard_campaign_loop_item_after_content' ); ?>
			<div style="clear: both"></div>
		</div>
		<?php
		++$i;
		$html .= ob_get_clean();
	endwhile;
	wp_reset_postdata();
else :
	$html .= '<p>' . esc_html__( 'Sorry, no Campaign Found.', 'wp-crowdfunding' ) . '</p>';
endif;
$html .= '</div>';
$html .= wpcf_function()->get_pagination( $page_numb, $the_query->max_num_pages );

