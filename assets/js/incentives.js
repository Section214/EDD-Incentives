/*global jQuery, document, window, edd_incentives_vars*/
jQuery(document).ready(function ($) {
    'use strict';

    jQuery(window).on('mouseout', function(e) {
        if (e.pageY <= 5) {
            jQuery.colorbox({
                inline: true,
                href: '#edd-incentives-display',
                maxWidth: '650px',
                maxHeight: '75%',
                closeButton: false,
                overlayClose: edd_incentives_vars.close_on_click,
                className: 'edd-incentives-modal'
            });
        }
    });
});
