<?php
/**
 * Fired during plugin activation
 *
 * @link       https://botmywork.com/
 * @since      1.0.2
 *
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WP_Chatbot_Builder_Api_Process' ) ) {

	/**
	 * The plugin API class.
	 *
	 * This is used to define the functions and data manipulation for custom endpoints.
	 *
	 * @since      1.0.2
	 * @package    Hydroshop_Api_Management
	 * @subpackage Hydroshop_Api_Management/includes
	 * @author     support@botmywork.com
	 */
	class WP_Chatbot_Builder_Api_Process {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.2
		 */
		public function __construct() {

		}

		/**
		 * Define the function to process data for custom endpoint.
		 *
		 * @since    1.0.2
		 * @param   Array $wcb_request  data of requesting headers and other information.
		 * @return  Array $bmw_wcb_rest_response    returns processed data and status of operations.
		 */
		public function bmw_wcb_default_process( $wcb_request ) {
			$bmw_wcb_rest_response = array();

			// Write your custom code here.

			$bmw_wcb_rest_response['status'] = 200;
			$bmw_wcb_rest_response['data'] = $wcb_request->get_headers();
			return $bmw_wcb_rest_response;
		}
	}
}
