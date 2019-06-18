<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if (! class_exists('WC_Product_Crowdfunding')) {

    class WC_Product_Crowdfunding extends WC_Product
    {
        public function __construct($product)
        {
            $this->product_type = 'crowdfunding';
            parent::__construct($product);
        }
    }
}