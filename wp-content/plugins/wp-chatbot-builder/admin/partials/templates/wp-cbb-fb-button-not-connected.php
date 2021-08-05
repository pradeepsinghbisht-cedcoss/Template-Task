<?php
/**
 * Exit if accessed directly
 *
 * @package    WP_Chatbot_Builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$connect_path = $this->api->wp_cbb_connect_link(); ?>
<div class="wp_cbb_get_started">
	<h1><?php esc_html_e( 'Welcome to WP-Chatbot Builder!', 'wp-chatbot-builder' ); ?></h1>
	<h2 class="wp_cbb_get_started"><?php esc_html_e( 'To get started, connect your Facebook page', 'wp-chatbot-builder' ); ?></h2>
	<div class="wp_cbb_get_connect_button">
		<a id="wp_cbb_get_free_button__link" class="wp_cbb_connection_button" href='<?php echo esc_url( $connect_path . '&utm_source=WordPress_CBB_Login&utm_medium=Organic_Login&utm_campaign=WordPress_Traffic' ); ?>'>
			<i class="fa fa-facebook" aria-hidden="true"></i>
			<span><?php esc_html_e( 'Connect Facebook Page', 'wp-chatbot-builder' ); ?></span>
		</a>
	</div>
	<div class="wp_cbb_bmw_terms_conditions">
		<p><?php esc_html_e( 'By connecting with Facebook you are agreeing to the ', 'wp-chatbot-builder' ); ?><a href="https://botmywork.com/terms/?utm_source=wp_cbb&utm_medium=plugin" target="_blank"><?php esc_html_e( 'WP-Chatbot Builder Terms of Service', 'wp-chatbot-builder' ); ?></a></p>
	</div>
</div>
<?php
$this->wp_cbb_get_footer();
