jQuery(document).ready(function($) {
    const MDCText = mdc.textField.MDCTextField;
    const textField = [].map.call(document.querySelectorAll('.mdc-text-field'), function(el) {
        return new MDCText(el);
    });
    const MDCRipple = mdc.ripple.MDCRipple;
    const buttonRipple = [].map.call(document.querySelectorAll('.mdc-button'), function(el) {
        return new MDCRipple(el);
    });
    const MDCSwitch = mdc.switchControl.MDCSwitch;
    const switchControl = [].map.call(document.querySelectorAll('.mdc-switch'), function(el) {
        return new MDCSwitch(el);
    });

    var dialog = "";
    if( $('.mdc-dialog').length > 0 ){
        dialog = mdc.dialog.MDCDialog.attachTo(document.querySelector('.mdc-dialog'));
    }

    /*if device is mobile*/
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        jQuery('body').addClass('mobile-device');
    }

    var deactivate_url = '';

    // Add Select2.
    jQuery('.on-boarding-select2').select2({
        placeholder: 'Select All Suitable Options...',
    });

    // On click of deactivate.
    if ('plugins.php' == bmw_wcb_onboarding.wcb_current_screen) {

        // Add Deactivation id to all deactivation links.
        bmw_wcb_embed_id_to_deactivation_urls();
        bmw_wcb_add_deactivate_slugs_callback(bmw_wcb_onboarding.wcb_current_supported_slug);

        jQuery(document).on('change', '.wcb-on-boarding-radio-field', function(e) {

            e.preventDefault();
            if ('other' == jQuery(this).attr('id')) {
                jQuery('#deactivation-reason-text').removeClass('bmw-wcb-keep-hidden');
            } else {
                jQuery('#deactivation-reason-text').addClass('bmw-wcb-keep-hidden');
            }
        });
    } else {
        // Show Popup after 1 second of entering into the bmw pagescreen.
        if (jQuery('#bmw-wcb-show-counter').length > 0 && jQuery('#bmw-wcb-show-counter').val() == 'not-sent') {
            setTimeout(bmw_wcb_show_onboard_popup(), 1000);
        }
    }

    /* Close Button Click */
    jQuery(document).on('click', '.bmw-wcb-on-boarding-close-btn a', function(e) {
        e.preventDefault();
        bmw_wcb_hide_onboard_popup();
    });

    /* Skip and deactivate. */
    jQuery(document).on('click', '.bmw-deactivation-no_thanks', function(e) {

        window.location.replace(deactivate_url);
        bmw_wcb_hide_onboard_popup();
    });

    /* Skip For a day. */
    jQuery(document).on('click', '.bmw-wcb-on-boarding-no_thanks', function(e) {

        jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: bmw_wcb_onboarding.ajaxurl,
            data: {
                nonce: bmw_wcb_onboarding.wcb_auth_nonce,
                action: 'wcb_skip_onboarding_popup',
            },
            success: function(msg) {
                bmw_wcb_hide_onboard_popup();
            }
        });

    });

    /* Submitting Form */
    jQuery(document).on('submit', 'form.bmw-wcb-on-boarding-form', function(e) {

        e.preventDefault();
        var form_data = JSON.stringify(jQuery('form.bmw-wcb-on-boarding-form').serializeArray());

        jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: bmw_wcb_onboarding.ajaxurl,
            data: {
                nonce: bmw_wcb_onboarding.wcb_auth_nonce,
                action: 'bmw_wcb_send_onboarding_data',
                form_data: form_data,
            },
            success: function(msg) {
                if ('plugins.php' == bmw_wcb_onboarding.wcb_current_screen) {
                    window.location.replace(deactivate_url);
                }
                console.log(msg);
                bmw_wcb_hide_onboard_popup();
            }
        });
    });

    /* Open Popup */
    function bmw_wcb_show_onboard_popup() {
        dialog.open();
        if (!jQuery('body').hasClass('mobile-device')) {
            jQuery('body').addClass('bmw-on-boarding-wrapper-control');
        }
    }

    /* Close Popup */
    function bmw_wcb_hide_onboard_popup() {
        // jQuery( '.bmw-on-boarding-wrapper-background' ).removeClass( 'onboard-popup-show' );
		// jQuery( '.bmw-onboarding-section' ).hide();
        // const dialog = mdc.dialog.MDCDialog.attachTo(document.querySelector('.mdc-dialog'));
        dialog.close();
        if (!jQuery('body').hasClass('mobile-device')) {
            jQuery('body').removeClass('bmw-on-boarding-wrapper-control');
        }
    }



    /* Apply deactivate in all the bmw plugins. */
    function bmw_wcb_add_deactivate_slugs_callback(all_slugs) {

        for (var i = all_slugs.length - 1; i >= 0; i--) {

            jQuery(document).on('click', '#deactivate-' + all_slugs[i], function(e) {

                e.preventDefault();
                deactivate_url = jQuery(this).attr('href');
                plugin_name = jQuery(this).attr('aria-label');
                plugin_name = plugin_name.replace('Deactivate ', '');
                jQuery('#plugin-name').val(plugin_name);
                jQuery('.bmw-wcb-on-boarding-heading').text(plugin_name + ' Feedback');
                var placeholder = jQuery('#bmw-wcb-deactivation-reason-text').attr('placeholder');
                jQuery('#bmw-wcb-deactivation-reason-text').attr('placeholder', placeholder.replace('{plugin-name}', plugin_name));
                bmw_wcb_show_onboard_popup();
            });
        }
    }

    /* Add deactivate id in all the plugins links. */
    function bmw_wcb_embed_id_to_deactivation_urls() {
        jQuery('a').each(function() {
            if ('Deactivate' == jQuery(this).text() && 0 < jQuery(this).attr('href').search('action=deactivate')) {
                if ('undefined' == typeof jQuery(this).attr('id')) {
                    var slug = jQuery(this).closest('tr').attr('data-slug');
                    jQuery(this).attr('id', 'deactivate-' + slug);
                }
            }
        });
    }

    // End of scripts.
});