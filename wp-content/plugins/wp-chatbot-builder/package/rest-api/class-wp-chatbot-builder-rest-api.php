<?php
/**
 * The file that defines the core plugin api class
 *
 * A class definition that includes api's endpoints and functions used across the plugin
 *
 * @link       https://botmywork.com/
 * @since      1.0.2
 *
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/package/rest-api/version1
 */

/**
 * The core plugin  api class.
 *
 * This is used to define internationalization, api-specific hooks, and
 * endpoints for plugin.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.2
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/package/rest-api/version1
 * @author     support@botmywork.com
 */
class WP_Chatbot_Builder_Rest_Api {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.2
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.2
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin api.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the merthods, and set the hooks for the api and
	 *
	 * @since    1.0.2
	 * @param   string $plugin_name    Name of the plugin.
	 * @param   string $version        Version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	/**
	 * Define endpoints for the plugin.
	 *
	 * Uses the WP_Chatbot_Builder_Rest_Api class in order to create the endpoint
	 * with WordPress.
	 *
	 * @since    1.0.2
	 * @access   private
	 */
	public function bmw_wcb_add_endpoint() {
		register_rest_route(
			'wcb-route/v1',
			'/wcb-dummy-data/',
			array(
				// 'methods'  => 'POST',
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'bmw_wcb_default_callback' ),
				'permission_callback' => array( $this, 'bmw_wcb_default_permission_check' ),
			)
		);
	}


	/**
	 * Begins validation process of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $result   return rest response to server from where the endpoint hits.
	 * @since    1.0.2
	 */
	public function bmw_wcb_default_permission_check( $request ) {

		// Add rest api validation for each request.
		$result = true;
		return $result;
	}


	/**
	 * Begins execution of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $bmw_wcb_response   return rest response to server from where the endpoint hits.
	 * @since    1.0.2
	 */
	public function bmw_wcb_default_callback( $request ) {

		require_once WP_CBB_DIRPATH . 'package/rest-api/version1/class-wp-chatbot-builder-api-process.php';
		$bmw_wcb_api_obj     = new WP_Chatbot_Builder_Api_Process();
		$bmw_wcb_resultsdata = $bmw_wcb_api_obj->bmw_wcb_default_process( $request );
		if ( is_array( $bmw_wcb_resultsdata ) && isset( $bmw_wcb_resultsdata['status'] ) && 200 == $bmw_wcb_resultsdata['status'] ) {
			unset( $bmw_wcb_resultsdata['status'] );
			$bmw_wcb_response = new WP_REST_Response( $bmw_wcb_resultsdata, 200 );
		} else {
			$bmw_wcb_response = new WP_Error( $bmw_wcb_resultsdata );
		}
		return $bmw_wcb_response;
	}
}
