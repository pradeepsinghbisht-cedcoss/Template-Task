<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://botmywork.com
 * @since      1.0.2
 *
 * @package    Botmywork_Onboarding
 * @subpackage Botmywork_Onboarding/admin/onboarding
 */

global $pagenow, $wcb_bmw_wcb_obj;
if ( empty( $pagenow ) || 'plugins.php' != $pagenow ) {
	return false;
}

$wcb_onboarding_form_deactivate = apply_filters( 'bmw_wcb_deactivation_form_fields', array() );
?>
<?php if ( ! empty( $wcb_onboarding_form_deactivate ) ) : ?>
	<div class="mdc-dialog mdc-dialog--scrollable">
		<div class="bmw-wcb-on-boarding-wrapper-background mdc-dialog__container">
			<div class="bmw-wcb-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="bmw-wcb-on-boarding-close-btn">
						<a href="#">
							<span class="wcb-close-form material-icons bmw-wcb-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span>
						</a>
					</div>

					<h3 class="bmw-wcb-on-boarding-heading mdc-dialog__title"></h3>
					<p class="bmw-wcb-on-boarding-desc"><?php esc_html_e( 'May we have a little info about why you are deactivating?', 'wp-chatbot-builder' ); ?></p>
					<form action="#" method="post" class="bmw-wcb-on-boarding-form">
						<?php 
						$wcb_onboarding_deactive_html = $wcb_bmw_wcb_obj->bmw_wcb_plug_generate_html( $wcb_onboarding_form_deactivate );
						echo esc_html( $wcb_onboarding_deactive_html );
						?>
						<div class="bmw-wcb-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="bmw-wcb-on-boarding-form-submit bmw-wcb-on-boarding-form-verify ">
								<input type="submit" class="bmw-wcb-on-boarding-submit bmw-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="bmw-wcb-on-boarding-form-no_thanks">
								<a href="#" class="bmw-deactivation-no_thanks mdc-button"><?php esc_html_e( 'Skip and Deactivate Now', 'wp-chatbot-builder' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
