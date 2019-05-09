<?php
defined( 'ABSPATH' ) || exit;

$page_numb = max( 1, get_query_var('paged') );
$html .= '<div class="wpneo-content">';
$html .= '<div class="wpneo-form campaign-listing-page">';

$campaign_ids = get_user_meta( get_current_user_id(), 'loved_campaign_ids',true);
$campaign_ids = json_decode($campaign_ids, true);
if( empty($campaign_ids) ){
    $campaign_ids = array(9999999);
}
$posts_per_page = get_option( 'posts_per_page',10 );
$args = array(
    'post_type'         => 'product',
    'post__in'          => $campaign_ids,
    'posts_per_page'    => $posts_per_page,
    'paged'             => $page_numb
);
$the_query = new WP_Query( $args );
$html .='<div class="wpneo-shadow wpneo-padding25 wpneo-clearfix">';

if ( $the_query->have_posts() ) :
    global $post;
    $html .='<div class="wpneo-responsive-table">';
    $html .= '<table class="stripe-table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>' . __( "Title","wp-crowdfunding" ) . '</th>';
    $html .= '<th>' . __( "Created Time","wp-crowdfunding" ) . '</th>';
    $html .= '<th>' . __( "Action","wp-crowdfunding" ) . '</a></th>';
    $html .= '</tr>';
    $html .= '</thead>';

    $html .= '<tbody>';
    while ( $the_query->have_posts() ) : $the_query->the_post();
        $html .= '<tr>';
        $html .= '<td>' . get_the_title() . '</td>';
        $html .= '<td>'.__('Created at', 'wp-crowdfunding').' : '.get_the_date().'</td>';
        $html .= '<td><a href="' . get_permalink() . '">' . __("View", "wp-crowdfunding") . '</a></td>';
        $html .= '</tr>';
    endwhile;
    $html .= '</tbody>';

    $html .= '</table>';
    $html .= '</div>';//wpneo-responsive-table
    wp_reset_postdata();
else :
    $html .= "<p>".__( 'Sorry, No bookmark found.','wp-crowdfunding' )."</p>";
endif;
$html .= '</div>';//wpneo-padding25

$html .= wpneo_crowdfunding_pagination( $page_numb , $the_query->max_num_pages );

$html .= '</div>';
$html .= '</div>';