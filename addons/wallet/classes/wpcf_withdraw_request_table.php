<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Wpneo_Withdraw_Request_Table extends WP_List_Table {
    public function __construct() {
        parent::__construct( array(
            'ajax' 		=> true
        ));
        $this->wpneo_withdraw_data_items();
        $this->display();
    }

    function get_columns() {
        $columns = array(
            'tid'    			=> __( 'ID' , 'wp-crowdfunding' ),
            'title'     		=> __( 'Title' , 'wp-crowdfunding' ),
            'user'   			=> __( 'User' , 'wp-crowdfunding' ),
            'amount'	        => __( 'Amount' , 'wp-crowdfunding' ),
            'fund_collection'   => __( 'Fund Raised (%)' , 'wp-crowdfunding' ),
            'fund_goal'   		=> __( 'Fund Goal' , 'wp-crowdfunding' ),
            'campaign_type'   	=> __( 'Campaign Type' , 'wp-crowdfunding' ),
            'action'   			=> __( 'Action' , 'wp-crowdfunding' ),
        );
        return $columns;
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'tid':
            case 'title':
            case 'user':
            case 'amount':
            case 'fund_collection':
            case 'fund_goal':
            case 'campaign_type':
            case 'action':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }

    function wpneo_withdraw_data_items() {

        $currentPage 	= $this->get_pagenum();
        if( isset($_GET['paged']) ){ $page_numb = $_GET['paged']; }
        $example_data 	= array();
        $perPage 		= 10;
        $args 			= array(
            'post_type'         => 'wpneo_withdraw',
            'post_status'       => array( 'publish' ),
            'posts_per_page'    => $perPage,
            'paged'				=> $currentPage
        );
        $the_query = new WP_Query( $args );

        if ( $the_query->have_posts() ) :
            while ( $the_query->have_posts() ) : $the_query->the_post();
                global $post;

                //$post_id 	= get_post_meta( get_the_ID(),'withdraw_post_id',true );
                // Author Name
                $fname 		= get_the_author_meta('first_name');
                $lname 		= get_the_author_meta('last_name');
                $full_name 	= '';
                if( empty($fname)){ $full_name = $lname; }
                    elseif( empty( $lname )){ $full_name = $fname;
                } else {  $full_name = "{$fname} {$lname}"; }

                $raised_percent 	=  WPNEOCF()->getFundRaisedPercent( $post->post_parent ); // Raised Percent
                $fund_raised  		= wpneo_crowdfunding_get_total_fund_raised_by_campaign( $post->post_parent );
                $fund_raised 		= $fund_raised ? wc_price($fund_raised) : wc_price(0); // Fund Raised
                $funding_goal 		= wc_price(get_post_meta( $post->post_parent, '_nf_funding_goal', true)); // Funding Goal

                $method 		= '';
                $campaign_end 	= get_post_meta( $post->post_parent, 'wpneo_campaign_end_method' , true);
                if( $campaign_end 		== 'target_goal' ){ $method = __('Target Goal','wp-crowdfunding'); }
                elseif( $campaign_end 	== 'target_date' ){ $method = __('Target Date','wp-crowdfunding'); }
                elseif( $campaign_end 	== 'target_goal_and_date' ){ $method = __('Target Goal and Date','wp-crowdfunding'); }
                elseif( $campaign_end 	== 'never_end' ){ $method = __('Campaign Never End','wp-crowdfunding'); }

                // Message
                $message = strip_tags( get_the_content() );
                if( $message != '' ){
                    $message = '<button class="label-default wpneo-message">'.__('Message', 'wp-crowdfunding').'</button><div id="light" class="wpneo-message-content">' . $message .'<span class="wpneo-message-close">'.__("close","wp-crowdfunding").'</span>';
                }

                // Paid & Pending
                $request 	= get_post_meta( get_the_ID(),'withdraw_request_status',true );
                if( $request == 'paid' ){
                    $request = '<button class="label-success wpneo-request-pending" data-post-id="'.get_the_ID().'">'.__("Decline","wp-crowdfunding").'</button>';
                } else {
                    $request = '<button class="label-warning wpneo-request-paid" data-post-id="'.get_the_ID().'">'.__("Approve","wp-crowdfunding").'</button>';
                }

                $amount = get_post_meta(get_the_ID(), 'wpneo_wallet_withdrawal_amount', true);
                $arr = array(
                    'tid'				=> $post->post_parent,
                    'title'				=> '<a href="'.get_permalink($post->post_parent).'">'.get_the_title().'</a>',
                    'user'				=> '<a href="#">'.$full_name.'</a>',
                    'amount'	        => wc_price($amount),
                    'fund_collection'	=> '<span class="label-info">'.$fund_raised. '('.$raised_percent.'%) </span>',
                    'fund_goal'			=> '<span class="label-success">'.$funding_goal.'</span>',
                    'campaign_type'		=> $method,
                    'action'			=> $request.' '.$message.'</div>',
                );
                $example_data[] = $arr;
            endwhile;

            wp_reset_postdata();
        else:
            echo "<p>".__( 'Sorry, no withdraw request found.','wp-crowdfunding' )."</p>";
        endif;

        $columns 		= $this->get_columns();
        $hidden 		= array();
        $sortable 		= $this->get_sortable_columns();
        $totalItems 	= count($example_data);
        $this->set_pagination_args( array(
            'total_pages' 	=> $the_query->max_num_pages,
            'per_page'    	=> $perPage
        ) );
        $data 			= array_slice($example_data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items 	= $example_data;
    }
}
