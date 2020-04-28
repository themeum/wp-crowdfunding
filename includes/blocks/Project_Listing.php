<?php
namespace WPCF\blocks;

defined( 'ABSPATH' ) || exit;

class ProjectListing{
    
    public function __construct(){
        $this->register_project_listing();
    }
 
    public function register_project_listing(){
        register_block_type(
            'wp-crowdfunding/projectlisting',
            array(
                'attributes' => array(
                    'fontSize'   => array(
                        'type'      => 'number',
                        'default'   => ''
                    ),
                    'numbers'   => array(
                        'type'      => 'number',
                        'default'   => '5'
                    ),
                    'columns'   => array(
                        'type'      => 'number',
                        'default'   => 3
                    ),
                    'orderby'   => array(
                        'type'      => 'string',
                        'default'   => 'desc'
                    ),
                    'selectedCategory'   => array(
                        'type'      => 'string',
                        'default'   => 'all'
                    ),
                    
                ),
                'render_callback' => array( $this, 'project_listing_block_callback' ),
            )
        );
    }

    public function project_listing_block_callback( $att ){
        $formSize           = isset($att['formSize']) ? $att['formSize'] : '';

        // $columns 		= isset( $att['columns']) ? $att['columns'] : 3;
        // $numbers 		= isset( $att['numbers']) ? $att['numbers'] : 6;
        // $orderby 		= isset( $att['orderby']) ? $att['orderby'] : 'ASC';
        // $fontSize 		= isset( $att['fontSize']) ? $att['fontSize'] : '14';
        // $lineheight 	= isset( $att['lineheight']) ? $att['lineheight'] : '24';
        // $fontWeight 	= isset( $att['fontWeight']) ? $att['fontWeight'] : '400';
        // $blogStyle 		= isset( $att['blogStyle']) ? $att['blogStyle'] : 'style1';
        // $colorpalette 	= isset( $att['colorpalette']) ? $att['colorpalette'] : '#212127';
        // $selectedCategory = isset( $att['selectedCategory']) ? $att['selectedCategory'] : 'all';
    
        
        $html .= 'AAA';

        return $html;
    }
}
