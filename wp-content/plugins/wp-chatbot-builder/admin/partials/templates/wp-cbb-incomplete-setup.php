<?php
/**
 * Exit if accessed directly
 *
 * @package    WP_Chatbot_Builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wp_cbb_incomplete_setup_banner_wrap">
	<div class="wp_cbb_dismiss_setup_banner">
		<span class="wp_cbb_setup_banner_close_button"><i class="fa fa-times" aria-hidden="true"></i></span>
	</div>
	<div class="wp_cbb_setup_banner_container">
		<div class="wp_cbb_setup_banner_container__wrapper">
			<div class="wp_cbb_banner_logo_text">
				<div class="wp_cbb_logo">
					<img src="<?php echo esc_url( WP_CBB_URL . 'assets/images/cbb_logo.png' ); ?>" alt="BotMyWork" class="wp_cbb_setup_banner_botmywork_logo">
				</div>
				<div class="wp_cbb_setup_banner_container__info">
					<div class="wp_cbb_setup_banner_botmywork_logo_wrap">
						<h1 class="wp_cbb_setup_banner_info_heading">
						<?php esc_html_e( 'WP-Chatbot Builder', 'wp-chatbot-builder' ); ?>
						</h1>	
					</div>
					<h3 class="wp_cbb_setup_banner_info_sub_heading">
						<?php esc_html_e( 'Enjoy ceaseless chatting experience with WP-Chatbot Builder.', 'wp-chatbot-builder' ); ?>
					</h3>
				</div>
			</div>

			<div class="wp_cbb_setup_banner_complete_button_wrapper">
				<?php
				$screen = get_current_screen();
				if ( isset( $screen->id ) ) :
					$active_page = get_option( 'botmywork_active_page_info' );
					if ( ( isset( $screen->id ) ) && ( 'toplevel_page_wp-chatbot-builder' === $screen->id ) && ! empty( $active_page ) ) :
						?>
						<div class="wp_cbb_setup_banner_complete_button">
							<div class="wp_cbb_setup_button__wrapper">
								<a href="<?php echo esc_url_raw( 'https://apps.botmywork.com/chatbot-builder/login?utm_source=WordPressCBB&utm_medium=Organic&utm_campaign=WordPress_Traffic' ); ?>" class="wp_cbb_setup_button__wrapper_link" target="_blank">
									<span class="wp_cbb_setup_button__wrapper_text"><?php esc_html_e( 'Explore More Options', 'wp-chatbot-builder' ); ?></span>
								</a>
							</div>
						</div>
					<?php else : ?>
						<div class="wp_cbb_setup_banner_complete_button">
							<div class="wp_cbb_setup_button__wrapper">
								<a href="<?php echo esc_url_raw( 'https://botmywork.com/blog/wordpress-chatbot-guide/?utm_source=wp_cbb&utm_medium=plugin' ); ?>" class="wp_cbb_setup_button__wrapper_link" target="_blank">
									<span class="wp_cbb_setup_button__wrapper_text"><?php esc_html_e( 'Setup Guide', 'wp-chatbot-builder' ); ?></span>
								</a>
							</div>
						</div>
						<div class="wp_cbb_setup_banner_complete_button">
							<div class="wp_cbb_setup_button__wrapper">
								<a href="<?php echo esc_url_raw( 'https://botmywork.com/knowledge-base/botmywork-chatbot-builder/wp-chatbot-builder-for-messenger/?utm_source=wp_cbb&utm_medium=plugin' ); ?>" class="wp_cbb_setup_button__wrapper_link" target="_blank">
									<span class="wp_cbb_setup_button__wrapper_text"><?php esc_html_e( 'FAQs', 'wp-chatbot-builder' ); ?></span>
								</a>
							</div>
						</div>
						<?php
					endif;
				endif;
				?>

				<div class="wp_cbb_setup_banner_complete_button">
					<div class="wp_cbb_setup_button__wrapper">
						<?php
						$screen = get_current_screen();
						if ( isset( $screen->id ) ) :
							if ( ( isset( $screen->id ) ) && ( 'toplevel_page_wp-chatbot-builder' === $screen->id ) ) :
								?>
								<a href="<?php echo esc_url_raw( 'https://botmywork.com/contact-us/?utm_source=wp_cbb&utm_medium=plugin' ); ?>" class="wp_cbb_setup_button__wrapper_link" target="_blank">
									<span class="wp_cbb_setup_button__wrapper_text"><?php esc_html_e( 'Need help?', 'wp-chatbot-builder' ); ?></span>
								</a>
							<?php elseif ( ( isset( $screen->id ) ) && ( 'plugins' === $screen->id ) && ! empty( $active_page ) ) : ?>
								<a href="<?php echo esc_url_raw( 'https://botmywork.com/chatbot-builder/?utm_source=wp_cbb&utm_medium=plugin' ); ?>" class="wp_cbb_setup_button__wrapper_link" target="_blank">
									<span class="wp_cbb_setup_button__wrapper_text"><?php esc_html_e( 'Upgrade To Pro', 'wp-chatbot-builder' ); ?></span>
									<span class="dashicons wp_cbb_setup_button__wrapper_arrow dashicons-arrow-right-alt"></span>
								</a>
							<?php else : ?>
								<a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=wp-chatbot-builder' ) ); ?>" class="wp_cbb_setup_button__wrapper_link">
									<span class="wp_cbb_setup_button__wrapper_text"><?php esc_html_e( 'Complete Setup Now', 'wp-chatbot-builder' ); ?></span>
									<span class="dashicons wp_cbb_setup_button__wrapper_arrow dashicons-arrow-right-alt"></span>
								</a>
								<?php
							endif;
						endif;
						?>
					</div>
				</div>
			</div>


		</div>
	</div>
</div>
