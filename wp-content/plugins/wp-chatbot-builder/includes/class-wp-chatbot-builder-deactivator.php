<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://botmywork.com/
 * @since      1.0.2
 *
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.2
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/includes
 * @author     support@botmywork.com
 */
class WP_Chatbot_Builder_Deactivator {

	/**
	 * When plugin deactivate
	 *
	 * @since 1.0.2
	 * @uses register_deactivation_hook
	 * @return void
	 */
	public static function deactivate() {
		$data = get_option( 'botmywork_active_page_info' );
		$test = new BotMyWorkApi();
		$test->wp_cbb_disconnect_page( $data['page_id'] );
		delete_option( 'botmywork_token' );
		delete_option( 'botmywork_active_page_info' );
	}
}
