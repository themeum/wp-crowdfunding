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
                    
                ),
                'render_callback' => array( $this, 'project_listing_block_callback' ),
            )
        );
    }

    public function project_listing_block_callback( $att ){
        $formSize           = isset($att['formSize']) ? $att['formSize'] : '';
    
        
        $html .= 'AAA';

        return $html;
    }
}
