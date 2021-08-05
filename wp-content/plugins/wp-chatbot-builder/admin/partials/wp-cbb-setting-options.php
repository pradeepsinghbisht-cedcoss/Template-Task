<?php
/**
 * Exit if accessed directly
 *
 * @package    WP_Chatbot_Builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$api = $this->get_api();
$this->api->wp_cbb_logout_bot_my_work();
$this->api->wp_cbb_disconnect_page();
$token    = $this->api->wp_cbb_connect_bot_my_work();
$internal = $this->api->wp_cbb_get_active_page_id();
?>
<div class="wp_cbb_wrapper">
	<?php
	if ( $token ) :
		$active_page          = get_option( 'botmywork_active_page_info' );
		$previously_connected = get_transient( 'wp_cbb_previously_connected_page' );
		if ( $active_page && $previously_connected ) {
			require_once WP_CBB_DIRPATH . '/admin/partials/wp-cbb-admin-display.php';
		} else {
			require_once WP_CBB_DIRPATH . '/admin/partials/templates/wp-cbb-fb-button-select-page.php';
		} else :
			require_once WP_CBB_DIRPATH . '/admin/partials/templates/wp-cbb-fb-button-not-connected.php';
	endif;
		?>
</div>
