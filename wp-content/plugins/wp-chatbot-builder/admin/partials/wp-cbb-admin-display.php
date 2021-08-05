<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://botmywork.com/
 * @since      1.0.2
 *
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wp_cbb_setting_tab = array(
	'setup_setting'     => array(
		'title'     => esc_html__( 'Setup', 'wp-chatbot-builder' ),
		'file_path' => WP_CBB_DIRPATH . 'admin/partials/templates/wp-cbb-setup-display.php',
	),
	'customize_setting' => array(
		'title'     => esc_html__( 'Customize', 'wp-chatbot-builder' ),
		'file_path' => WP_CBB_DIRPATH . 'admin/partials/templates/wp-cbb-customize-display.php',
	),
	'leads_settings'    => array(
		'title'     => esc_html__( 'Leads', 'wp-chatbot-builder' ),
		'file_path' => WP_CBB_DIRPATH . 'admin/partials/templates/class-wp-cbb-lead-display.php',
	),
);
$disconnect_path    = $this->api->wp_cbb_get_disconnect_fb_page_path();
$activate_page_name = $this->api->wp_cbb_get_active_page_name();
?>

<div class="wp_wrap_main" id="wp_wpchatbot_setting_wrapper">
	<div style="display: none;" class="wp-chatbot-loader" id="wp-chatbot-form-loader">
		<img src="<?php echo esc_url( WP_CBB_URL . 'assets/images/loading.gif' ); ?>">
	</div>
	<form enctype="multipart/form-data" action="" id="wp_mainform" method="post">
		<?php wp_nonce_field( 'wp-cbb-nonce', 'wp-cbb-nonce-verify' ); ?>
		<div class="wp_cbb_main_template">
			<div class="wp_cbb_body_template">
				<div class="wp_cbb_navigator_template">
					<div class="wp_cbb-navigations">
					<h3 class="wp_cbb_setting_title"><?php esc_html_e( 'WP-Chatbot Builder Settings', 'wp-chatbot-builder' ); ?></h3>
						<?php
						if ( isset( $wp_cbb_setting_tab ) && ! empty( $wp_cbb_setting_tab ) && is_array( $wp_cbb_setting_tab ) ) {
							foreach ( $wp_cbb_setting_tab as $key => $wp_tab ) {
								if ( isset( $_GET['tab'] ) && sanitize_key( wp_unslash( $_GET['tab'] ) ) == $key ) {
									?>
									<div class="wp_cbbs_tabs">
										<a class="wp_cbb_nav_tab nav-tab nav-tab-active" href="?page=wp-chatbot-builder&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $wp_tab['title'] ); ?></a>
									</div>
									<?php
								} else {
									if ( ! isset( $_GET['tab'] ) && 'setup_setting' == $key ) {
										?>
										<div class="wp_cbb_tabs">
											<a class="wp_cbb_nav_tab nav-tab nav-tab-active" href="?page=wp-chatbot-builder&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $wp_tab['title'] ); ?></a>
										</div>
										<?php
									} else {
										?>
										<div class="wp_cbb_tabs">
											<a class="wp_cbb_nav_tab nav-tab" href="?page=wp-chatbot-builder&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $wp_tab['title'] ); ?></a>
										</div>
										<?php
									}
								}
							}
						}
						?>
						<div class="wp_cbb_setting_collapse">
							<h4 class="wp_cbb_setting_collapse_btn">Connected With <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
							<div class="wp_cbb_setting_collapse_text" style="display: none;">
								<!-- translators: %s: Conmected Page Name -->
								<p class="wp_cbb_connected_page_btn"><?php esc_html_e( sprintf( 'Bot is connected to: %s', $activate_page_name ), 'wp-chatbot-builder' ); ?></p>	
								<a href="<?php echo esc_url( $disconnect_path ); ?>"><?php esc_html_e( 'Disconnect', 'wp-chatbot-builder' ); ?></a>   
							</div>
						</div>
					</div>
					<?php if ( ! isset( $_GET['tab'] ) || isset( $_GET['tab'] ) && sanitize_key( wp_unslash( $_GET['tab'] ) ) == 'setup_setting' ) : ?>
						<div class="wp_cbb_personalization_options">
							<h3 class="wp_cbb_personalize_title"><?php esc_html_e( 'Personalization Options', 'wp-chatbot-builder' ); ?></h3>
							<div class="wp_cbb_personalize_body"><?php esc_html_e( 'You can personalize the answers using the person\'s name and last input. You can use the following template strings.', 'wp-chatbot-builder' ); ?></div>
							<div class="wp_cbb_personalize_strings">
								<span class="wp_cbb_string_box"><?php esc_html_e( '{{first_name}}', 'wp-chatbot-builder' ); ?></span>
								<span class="wp_cbb_string_box"><?php esc_html_e( '{{last_name}}', 'wp-chatbot-builder' ); ?></span>
								<span class="wp_cbb_string_box"><?php esc_html_e( '{{name}}', 'wp-chatbot-builder' ); ?></span>
								<span class="wp_cbb_string_box"><?php esc_html_e( '{{input}}', 'wp-chatbot-builder' ); ?></span>
							</div>
						</div>
					<?php endif; ?>
						<div class="wp_cbb_personalization_options">
							<h3 class="wp_cbb_personalize_title"><?php esc_html_e( 'Found this Plugin Useful?', 'wp-chatbot-builder' ); ?></h3>
							<div class="wp_cbb_personalize_body"><?php esc_html_e( 'We would appreciate a nice little comment and Rate it 5 stars at wordpress.org.', 'wp-chatbot-builder' ); ?></div>
							<div class="wp_cbb_personalize_strings wp_cbb_button">
								<a href="<?php echo esc_url_raw( 'https://wordpress.org/support/plugin/wp-chatbot-builder/reviews/#new-post' ); ?>" class="button" target="_blank"><?php esc_html_e( 'Rate our Plugin', 'wp-chatbot-builder' ); ?></a>
								<a href="<?php echo esc_url_raw( 'https://wordpress.org/support/plugin/wp-chatbot-builder/' ); ?>" class="button" target="_blank"><?php esc_html_e( 'Report an Issue', 'wp-chatbot-builder' ); ?></a>
							</div>
						</div>
				</div>
				<?php
				if ( isset( $wp_cbb_setting_tab ) && ! empty( $wp_cbb_setting_tab ) && is_array( $wp_cbb_setting_tab ) ) {
					foreach ( $wp_cbb_setting_tab as $key => $wp_file ) {
						if ( isset( $_GET['tab'] ) && sanitize_key( wp_unslash( $_GET['tab'] ) ) == $key ) {
							$include_tab = isset( $wp_file['file_path'] ) ? $wp_file['file_path'] : '';
							?>
							<div class="wp_cbb_content_template">
								<?php include_once $include_tab; ?>
							</div>
							<?php
						} elseif ( ! isset( $_GET['tab'] ) && 'setup_setting' == $key ) {
							$include_tab = isset( $wp_file['file_path'] ) ? $wp_file['file_path'] : '';
							?>
							<div class="wp_cbb_content_template">
								<?php include_once $include_tab; ?>
							</div>
							<?php
							break;
						}
					}
				}
				?>
			</div>
		</div>
	</form>
</div>
<?php
$this->wp_cbb_get_footer();

