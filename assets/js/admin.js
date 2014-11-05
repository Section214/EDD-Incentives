/*global jQuery, document, edd_incentives_vars*/
jQuery(document).ready(function ($) {
    'use strict';

    jQuery('.edd-incentives-select2').select2({
        width: '250px'
    });

    jQuery('#_edd_incentive_discount').select2({
        placeholder: edd_incentives_vars.discount_placeholder,
        allowClear: true,
        width: '100%'
    });

    jQuery('#_edd_incentive_button_type').select2({
        width: '100%'
    });

    jQuery('#_edd_incentive_downloads').select2({
        width: '100%',
    });

    

    jQuery('.edd-incentives-colorbox').colorbox({
        inline: true,
        href: '#edd-incentives-preview',
        maxWidth: '650px',
        maxHeight: '75%',
        closeButton: false,
        overlayClose: edd_incentives_vars.close_on_click,
        className: 'edd-incentives-modal'
    });

    jQuery(function () {
        if (jQuery('#_edd_incentive_button_image').length > 0) {
            jQuery('form').attr('enctype', 'multipart/form-data');
        }
    });

    jQuery("select[name='_edd_incentive_meta[button_type]']").change(function () {
        if (jQuery("select[name='_edd_incentive_meta[button_type]'] option:selected").val() === 'button') {
            jQuery('.edd-incentives-button-type-text').css('display', 'none');
            jQuery('.edd-incentives-button-type-button').css('display', 'block');
            jQuery('.edd-incentives-button-type-image').css('display', 'none');
        } else if (jQuery("select[name='_edd_incentive_meta[button_type]'] option:selected").val() === 'image') {
            jQuery('.edd-incentives-button-type-text').css('display', 'none');
            jQuery('.edd-incentives-button-type-button').css('display', 'none');
            jQuery('.edd-incentives-button-type-image').css('display', 'block');
        } else {
            jQuery('.edd-incentives-button-type-text').css('display', 'block');
            jQuery('.edd-incentives-button-type-button').css('display', 'none');
            jQuery('.edd-incentives-button-type-image').css('display', 'none');
        }
    });

    jQuery("select[name='_edd_incentive_meta[condition_type]']").change(function () {
        if (jQuery("select[name='_edd_incentive_meta[condition_type]'] option:selected").val() === 'products') {
            jQuery('.edd-incentive-condition-product').css('display', 'block');
            jQuery('.edd-incentive-condition-value').css('display', 'none');
            jQuery('.edd-incentive-condition-count').css('display', 'none');
        } else if (jQuery("select[name='_edd_incentive_meta[condition_type]'] option:selected").val() === 'value') {
            jQuery('.edd-incentive-condition-product').css('display', 'none');
            jQuery('.edd-incentive-condition-value').css('display', 'block');
            jQuery('.edd-incentive-condition-count').css('display', 'none');
        } else if (jQuery("select[name='_edd_incentive_meta[condition_type]'] option:selected").val() === 'count') {
            jQuery('.edd-incentive-condition-product').css('display', 'none');
            jQuery('.edd-incentive-condition-value').css('display', 'none');
            jQuery('.edd-incentive-condition-count').css('display', 'block');
        }
    });
        
});
