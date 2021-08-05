<?php
/**
 * Exit if accessed directly
 *
 * @package    WP_Chatbot_Builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logout_path = add_query_arg(
	array(
		'page'   => 'wp-chatbot-builder',
		'logout' => true,
	),
	admin_url( 'admin.php' )
);

?>

<div class="wp_cbb_header_login">
	<h3><?php esc_html_e( 'Connect to your Facebook page', 'wp-chatbot-builder' ); ?></h3>
	<div class="wp_cbb_log_out">
		<a href="<?php echo esc_url( $logout_path ); ?>"><?php esc_html_e( 'Logout WP-Chatbot Builder', 'wp-chatbot-builder' ); ?></a>   
	</div>
</div>


<div class="wp_cbb_notice_wrap"></div>
<div class="wp_cbb_choose_page">
	<div class="wp_cbb_select_page_table">
		<ul class="wp_cbb_connection_row">
			<?php
			$fb_pages             = $this->api->wp_cbb_get_pages();
			$connected_fb_page_id = $this->api->wp_cbb_get_active_page_id();
			$disconnect_path      = $this->api->wp_cbb_get_disconnect_fb_page_path();
			if ( ! empty( $fb_pages ) ) :
				foreach ( $fb_pages as $fb_page ) :
					?>
					<li>
						<div class="wp_cbb_fb_page_name">
							<span class="wp_cbb_profile_first">
								<?php echo esc_html( substr( $fb_page['name'], 0, 1 ) ); ?>
							</span>
							<span class="wp_cbb_profile_rest">
								<?php echo esc_html( substr( $fb_page['name'], 0 ) ); ?>
							</span>				   		
						</div>
						<div class="wp_cbb_check_connection">
							<?php if ( isset( $connected_fb_page_id ) && '' !== $connected_fb_page_id && $connected_fb_page_id === $fb_page['id'] ) : ?>
							<div class="wp_cbb_disconnect_page">
								<a href="<?php echo esc_url( $disconnect_path ); ?>"><?php esc_html_e( 'Disconnect', 'wp-chatbot-builder' ); ?></a>   
							</div>
							<?php else : ?>
							<div class="wp_cbb_connect_page">
								<a class="wp_cbb_connect_fb" href="javascript:void(0)" data-id="<?php echo esc_attr( $fb_page['id'] ); ?>"><?php esc_html_e( 'Connect', 'wp-chatbot-builder' ); ?>
								</a>
								<div style="display: none;" class="wp-cbb-loader" id="wp_cbb_connect_page_loader<?php echo esc_attr( $fb_page['id'] ); ?>">
									<img src="<?php echo esc_url( WP_CBB_URL . 'assets/images/loading.gif' ); ?>">
								</div>
							</div> 
							<?php endif; ?>
						</div>
					</li>
					<?php
				endforeach;
				else :
					?>
				<li class="wp_cbb_no_pages_found">
					<p><?php esc_html_e( 'Oops, looks like incomplete permissions or pages are not available.', 'wp-chatbot-builder' ); ?></p>
					<a href="<?php echo esc_url( 'https://apps.botmywork.com/chatbot-builder/login' ); ?>" class="wp_cbb_fix_page_permission" target="_blank"><?php esc_html_e( 'Fix it on BotMyWork Chatbot Builder', 'wp-chatbot-builder' ); ?></a>
				</li>
				<?php endif; ?>
		</ul>  
	</div>
</div>
<?php
$this->wp_cbb_get_footer();
