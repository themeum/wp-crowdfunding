<?php
defined( 'ABSPATH' ) || exit;

if (! class_exists('Crowdfunding_Core_Search')) {
    class Crowdfunding_Core_Search{
        protected static $_instance = null;
        public static function instance(){
            if(is_null(self::$_instance)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }  
        public function __construct(){
			register_block_type( 
                'crowdfunding/crowdfunding-core-search',
                array(
                    'attributes' => array(
                        'layout'   => array(
                            'type'      => 'string',
                            'default'   => 2
                        ),
                    ),
                    'render_callback' => array( $this, 'Crowdfunding_Core_Search_block_callback' ),
                )
            );
        }
    
		public function Crowdfunding_Core_Search_block_callback( $att ){
            $layout         = isset($att['layout']) ? $att['layout'] : '';
            $html = '';
            $html .= '<div class="crowdfunding-block">';
            $html .= 'AAA';
            $html .= '</div>';
			return $html;
		}
    }
}
Crowdfunding_Core_Search::instance();


