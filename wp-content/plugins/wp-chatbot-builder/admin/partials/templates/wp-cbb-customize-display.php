<?php
/**
 * Exit if accessed directly
 *
 * @package    WP_Chatbot_Builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


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

$display_notice = 'none';
if ( isset( $_POST['wp_cbb_customize_save_btn'] ) ) {
	if ( isset( $_POST['wp-cbb-nonce-verify'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['wp-cbb-nonce-verify'] ) ), 'wp-cbb-nonce' ) ) {
		unset( $_POST['wp_cbb_customize_save_btn'] );
		$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
		if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
			foreach ( $postdata as $key => $data ) {
				if ( isset( $data ) && null !== $data ) {
					update_option( $key, $data );
				} elseif ( null === $data ) {
					delete_option( $key, $data );
				}
			}
			$display_notice = 'block';
		}
	}
}
$wp_cbb_theme_color     = get_option( 'wp_cbb_plug_color_picker', '' );
$wp_cbb_text_logged_in  = get_option( 'wp_cbb_greeting_text_logged_in', '' );
$wp_cbb_text_logged_out = get_option( 'wp_cbb_greeting_text_logged_out', '' );
$wp_cbb_device_display  = get_option( 'wp_cbb_plug_device_display', 'device_all' );
$wp_cbb_display         = get_option( 'wp_cbb_plug_display', 'eve' );
$wp_cbb_dialog_delay    = get_option( 'wp_cbb_dialog_delay', '1' );

?>
<div class="notice notice-success is-dismissible" style="display: <?php echo esc_attr( $display_notice ); ?> "> 
	<p><strong><?php esc_html_e( 'Settings Saved', 'wp-chatbot-builder' ); ?></strong></p>
	<button type="button" class="notice-dismiss">
		<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice', 'wp-chatbot-builder' ); ?></span>
	</button>
</div>
<div class="wp_cbb_customize_wrapper"> 
	<div class="wp_cbb_customize_setting_wrap">
		<table class="form-table wp_cbb_customize_table">
			<tbody>
				<tr valign="top">           
					<th scope="row" class="wp_cbb_titledesc">
						<label for="wp_cbb_messenger_theme_color"><?php esc_html_e( 'Messenger Theme Color', 'wp-chatbot-builder' ); ?></label>  
					</th>
					<td class="forminp forminp-text">    
						<div class="wp_cbb_messenger_theme_wrapper" id="wp_cbb_messenger_color_wrapper">
							<p class="wp_cbb_description">
								<?php esc_html_e( 'Select Facebook Messenger theme color, Facebook highly recommends to choose a color that has a high contrast to white.', 'wp-chatbot-builder' ); ?>

								</p>
							<div class="wp_cbb_messenger_theme_color_picker">
								<input type="text" id="wp_cbb_color_picker" value="<?php echo esc_attr( $wp_cbb_theme_color ); ?>" name="wp_cbb_plug_color_picker">
							</div>						
						</div>
					</td>
				</tr>
				<tr valign="top">           
					<th scope="row" class="wp_cbb_titledesc">
						<label for="wp_cbb_greeting_text_wrap"><?php esc_html_e( 'Greeting Text', 'wp-chatbot-builder' ); ?></label>  
					</th>
					<td class="forminp forminp-text">    
						<div class="wp_cbb_greeting_text" id="wp_cbb_greeting_text_wrap">
							<p class="wp_cbb_description">
								<strong><label><?php esc_html_e( 'For logged in users:', 'wp-chatbot-builder' ); ?></label></strong>
							</p>
							<p>
								<textarea placeholder="Enter text here ( maximum 80 characters )" cols="47" rows="3" maxlength="80" name="wp_cbb_greeting_text_logged_in"><?php echo esc_html( $wp_cbb_text_logged_in ); ?></textarea>
							</p>
							<p class="wp_cbb_description">
								<strong><label><?php esc_html_e( 'For logged out users:', 'wp-chatbot-builder' ); ?></label></strong>
							</p>
							<p>
								<textarea placeholder="Enter text here ( maximum 80 characters )" cols="47" rows="3" maxlength="80" name="wp_cbb_greeting_text_logged_out"><?php echo esc_html( $wp_cbb_text_logged_out ); ?></textarea>
							</p>
							<p class="wp_cbb_description"><?php esc_html_e( 'Enter Facebook Messenger greeting text, leave blank to use default.', 'wp-chatbot-builder' ); ?></p>

						</div>
					</td>
				</tr>
				<tr valign="top">           
					<th scope="row" class="wp_cbb_titledesc">
						<label for="wp_cbb_device_behaviour">
							<?php esc_html_e( 'Device Behaviour', 'wp-chatbot-builder' ); ?>
						</label>  
					</th>
					<td class="forminp forminp-text">    
						<div class="wp_cbb_device_behaviour_wrapper" id="wp_cbb_device_behaviour">
							<select id="wp_cbb_device_display_select" name="wp_cbb_plug_device_display">
								<option <?php selected( 'device_all', $wp_cbb_device_display ); ?> value="device_all" ><?php esc_html_e( 'Show on desktop and mobile both', 'wp-chatbot-builder' ); ?></option>
								<option <?php selected( 'device_desk', $wp_cbb_device_display ); ?> value="device_desk"><?php esc_html_e( 'Show on desktop only', 'wp-chatbot-builder' ); ?></option>
								<option <?php selected( 'device_mob', $wp_cbb_device_display ); ?> value="device_mob"><?php esc_html_e( 'Show on mobile only', 'wp-chatbot-builder' ); ?></option>
							</select>
							<p class="wp_cbb_description"><?php esc_html_e( 'Select devices where you want to display Facebook Messenger. Mobile devices include both Smartphones and Tablets.', 'wp-chatbot-builder' ); ?></p>
						</div>
					</td>
				</tr>
				<tr valign="top">           
					<th scope="row" class="wp_cbb_titledesc">
						<label for="wp_cbb_device_display">
							<?php esc_html_e( 'Display', 'wp-chatbot-builder' ); ?>
						</label>  
					</th>
					<td class="forminp forminp-text">    
						<div class="wp_cbb_device_display_wrapper" id="wp_cbb_device_display">
							<select id="wp_cbb_display_select" name="wp_cbb_plug_display">
								<option <?php selected( 'eve', $wp_cbb_display ); ?> value="eve" ><?php esc_html_e( 'Show Everywhere', 'wp-chatbot-builder' ); ?></option>
								<option <?php selected( 'eve_except', $wp_cbb_display ); ?> value="eve_except"><?php esc_html_e( 'Show Everywhere except for these Pages', 'wp-chatbot-builder' ); ?></option>
								<option <?php selected( 'only_these', $wp_cbb_display ); ?> value="only_these"><?php esc_html_e( 'Only show on these Pages', 'wp-chatbot-builder' ); ?></option>
								<option <?php selected( 'only_home', $wp_cbb_display ); ?> value="only_home"><?php esc_html_e( 'Only show on Home Page', 'wp-chatbot-builder' ); ?></option>
							</select>

							<div id="wp_cbb_display_pages_eve" class="
							<?php
							if ( 'eve' !== $wp_cbb_display ) {
								esc_attr_e( 'hidden', 'wp-chatbot-builder' );}
							?>
							" >

								<p class="wp_cbb_description"><?php esc_html_e( 'The Facebook Messenger will be displayed on all pages.', 'wp-chatbot-builder' ); ?></p>

							</div>

							<div id="wp_cbb_display_pages_except" class="
							<?php
							if ( 'eve_except' !== $wp_cbb_display ) {
								esc_attr_e( 'hidden', 'wp-chatbot-builder' );}
							?>
							">

								<p class="wp_cbb_description"><?php esc_html_e( 'The Facebook Messenger will be displayed on all pages, except for these selected pages.', 'wp-chatbot-builder' ); ?></p>

								<?php

								$wp_cbb_pages = get_pages();

								$check_pages = (array) get_option( 'wp_cbb_plug_display_except_pages', '' );

								?>

								<div class="wp_cbb_display_pages_sec">

								<?php if ( is_array( $wp_cbb_pages ) && ! empty( $wp_cbb_pages ) ) : ?>

								<p class="wp_cbb_sel_pages"><?php esc_html_e( 'Select Pages: ', 'wp-chatbot-builder' ); ?></p>	

								<select id="wp_cbb_display_pages_except_select" name="wp_cbb_plug_display_except_pages[]" multiple="multiple">

									<?php

									foreach ( $wp_cbb_pages as $key => $each_page ) {

										?>

									<option <?php selected( in_array( $each_page->post_name, $check_pages ) ); ?> value='<?php echo esc_attr( $each_page->post_name ); ?>'><?php echo esc_html( $each_page->post_title ); ?></option>

										<?php

									}

									?>
								</select>

								<?php else : ?>

								<p><b><?php esc_html_e( 'Sorry No Page found.', 'wp-chatbot-builder' ); ?></b></p>	

								<?php endif; ?>

								</div>

							</div>

							<div id="wp_cbb_display_pages_only" class="
							<?php
							if ( 'only_these' !== $wp_cbb_display ) {
								esc_attr_e( 'hidden', 'wp-chatbot-builder' );}
							?>
							">

								<p class="wp_cbb_description"><?php esc_html_e( 'The Facebook Messenger will be displayed only on these selected pages.', 'wp-chatbot-builder' ); ?></p>

								<?php

								$wp_cbb_pages = get_pages();

								$check_pages = (array) get_option( 'wp_cbb_plug_display_only_pages', '' );

								?>

								<div class="wp_cbb_display_pages_sec">

								<?php if ( is_array( $wp_cbb_pages ) && ! empty( $wp_cbb_pages ) ) : ?>

								<p class="wp_cbb_sel_pages"><?php esc_html_e( 'Select Pages: ', 'wp-chatbot-builder' ); ?></p>	

								<select id="wp_cbb_display_pages_only_select" name="wp_cbb_plug_display_only_pages[]" multiple="multiple">

									<?php
									foreach ( $wp_cbb_pages as $key => $each_page ) {
										?>
									<option <?php selected( in_array( $each_page->post_name, $check_pages ) ); ?> value='<?php echo esc_attr( $each_page->post_name ); ?>'><?php echo esc_html( $each_page->post_title ); ?></option>

										<?php
									}
									?>
								</select>

								<?php else : ?>

								<p><b><?php esc_html_e( 'Sorry No Page found.', 'wp-chatbot-builder' ); ?></b></p>	
								<?php endif; ?>	

								</div>

							</div>

							<div id="wp_cbb_display_pages_home" class="
							<?php
							if ( 'only_home' !== $wp_cbb_display ) {
								esc_attr_e( 'hidden', 'wp-chatbot-builder' );}
							?>
							">

								<p class="wp_cbb_description"><?php esc_html_e( 'The Facebook Messenger will be displayed only on the Home Page.', 'wp-chatbot-builder' ); ?></p>

							</div>

						</div>
					</td>
				</tr>
				<tr valign="top">           
					<th scope="row" class="wp_cbb_titledesc">
						<label for="wp_cbb_greeting_dialog_delay"><?php esc_html_e( 'Greeting Dialog Delay', 'wp-chatbot-builder' ); ?></label>  
					</th>
					<td class="forminp forminp-text">    
						<div class="wp_cbb_greeting_dialog_delay_wrapper" id="wp_cbb_greeting_dialog_delay">
							<p class="wp_cbb_description">
								<?php esc_html_e( 'Sets the number of seconds of delay before the greeting dialog is shown after the plugin is loaded.', 'wp-chatbot-builder' ); ?>
							</p>
							<input type="number" id="wp_cbb_dialog_delay" value="<?php echo esc_attr( $wp_cbb_dialog_delay ); ?>" name="wp_cbb_dialog_delay" min="1" max="9" >	
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="clear"></div>
<p class="wp_cbb_submit_wrap">
	<input type="submit" value="<?php esc_attr_e( 'Save changes', 'wp-chatbot-builder' ); ?>" class="button-primary wp_cbb_save_btn" name="wp_cbb_customize_save_btn" id="wp_cbb_customize_save_btn" >
</p>
