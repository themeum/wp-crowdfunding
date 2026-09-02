<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Product_Crowdfunding' ) ) {
	class WC_Product_Crowdfunding extends WC_Product {

		/**
		 * Get internal type.
		 *
		 * @return string
		 */
		public function get_type() {
			return 'crowdfunding';
		}
	}
}
