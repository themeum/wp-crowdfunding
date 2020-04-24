<?php
namespace WPCF\blocks;

defined( 'ABSPATH' ) || exit;

class Search{
    
    public function __construct(){
        $this->register_search();
    }


    public function register_search(){
        register_block_type(
            'wp-crowdfunding/search',
            array(
                'attributes' => array(
                    'layout'   => array(
                        'type'      => 'string',
                        'default'   => 2
                    ),
                ),
                'render_callback' => array( $this, 'search_block_callback' ),
            )
        );
    }

    public function search_block_callback( $att ){
        $layout = isset($att['layout']) ? $att['layout'] : '';
        $html = '';
        $html .= '<div class="wpcf-title-inner">';
        $html .= 'AAA='.$layout;
        $html .= '</div>';
        return $html;
    }

}