<?php
/**
 * Helper functions
 *
 * @package     EDD\Incentives\Functions
 * @since       1.0.0
 * @author      Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright   Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Retrieve an array of valid template tags
 *
 * @since       1.0.0
 * @return      array $template_tags The valid tags array
 */
function edd_incentives_get_template_tags() {
    $template_tags = array(
        'exit_button'           => __( 'Insert an exit button. Don\'t forget to configure it!', 'edd-incentives' ),
        'discount_code'         => __( 'The selected discount code.', 'edd-incentives' ),
        'discount_url'          => __( 'The URL to apply the selected discount code.', 'edd-incentives' ),
        'discount_title'        => __( 'The title of the selected discount code.', 'edd-incentives' ),
        'discount_amount'       => __( 'The amount of the selected discount code.', 'edd-incentives' ),
        'discount_amount_text'  => __( 'The written amount of the selected discount code.', 'edd-incentives' ),
    );

    return apply_filters( 'edd_incentives_template_tags', $template_tags );
}


/**
 * Parse template tags
 *
 * @since       1.0.0
 * @param       int $id The insentive ID
 * @return      string $content The parsed content
 */
function edd_incentives_parse_template_tags( $id ) {
    $incentive  = get_post( $id );
    $content    = $incentive->post_content;
    $has_tags   = ( strpos( $content, '{' ) !== false );

    if( ! $has_tags ) {
        return $content;
    }

    $meta       = get_post_meta( $id, '_edd_incentive_meta', true );
    
    if( isset( $meta['discount'] ) && $meta['discount'] != '' ) {
        $discount           = edd_get_discount( $meta['discount'] );
        $discount_title     = $discount->post_title;
        $discount_type      = edd_get_discount_type( $meta['discount'] );
        $discount_amount    = edd_get_discount_amount( $meta['discount'] );
        $discount_code      = edd_get_discount_code( $meta['discount'] );
        $discount_url       = add_query_arg( array( 'discount' => $discount_code ) );
    }

    if( $meta['button_type'] == 'text' ) {
        $link   = $meta['link_text'];
        $class  = '';
    } else if( $meta['button_type'] == 'button' ) {
        $color  = edd_get_option( 'checkout_color', 'blue' );
        $link   = $meta['button_text'];
        $class  = ' edd-submit button ' . $color;
    } else if( $meta['button_type'] == 'image' ) {
        $link   = '<img src="' . $meta['button_image'] . '" />';
        $class  = '';
    }

    if( $discount_type == 'percent' ) {
        $discount_amount        = $discount_amount . __( '%', 'edd-incentives' );
        $discount_amount_text   = $discount_amount . ' ' . __( 'Percent', 'edd-incentives' );
    } else {
        $discount_amount        = edd_currency_filter( edd_format_amount( (double) $discount_amount ) );
        $discount_amount_text   = edd_currency_filter( edd_format_amount( (double) $discount_amount ) );
    }

    $content = str_replace( '{exit_button}', '<a onClick="jQuery.colorbox.close(); return false;" class="edd-incentives-exit-button' . $class . '">' . $link . '</a>', $content );
    $content = str_replace( '{discount_code}', $discount_code, $content );
    $content = str_replace( '{discount_title}', $discount_title, $content );
    $content = str_replace( '{discount_amount}', $discount_amount, $content );
    $content = str_replace( '{discount_amount_text}', $discount_amount_text, $content );
    $content = str_replace( '{discount_url}', $discount_url, $content );
    
    return apply_filters( 'edd_incentives_parts_template_tags', $content );
}


/**
 * Get the system currency symbol
 *
 * @since       1.0.0
 * @return      string $currency The currency symbol
 */
function edd_incentives_get_currency() {
    $currency = edd_get_currency();

    switch ( $currency ) {
        case "GBP" :
            $currency = '&pound;';
            break;
        case "BRL" :
            $currency = 'R&#36;';
            break;
        case "EUR" :
            $currency = '&euro;';
            break;
        case "USD" :
        case "AUD" :
        case "CAD" :
        case "HKD" :
        case "MXN" :
        case "NZD" :
        case "SGD" :
            $currency = '&#36;';
            break;
        case "JPY" :
            $currency = '&yen;';
            break;
        default :
            $currency = '&#36;';
            break;
    }

    return $currency;
}


/**
 * Check if a supported subscription plugin is installed
 *
 * @since       1.0.0
 * @return      mixed array $plugin if true, bool false otherwise
 */
function edd_incentives_has_subscription_support() {
    $plugin = false;

    if( class_exists( 'EDD_MailChimp' ) ) {
        $plugin = 'MailChimp';
    } else if( defined( 'EDDCP_PLUGIN_DIR' ) ) {
        $plugin = 'Campaign Monitor';
    } else if( defined( 'EDD_AWEBER_PATH'  ) ) {
        $plugin = 'aWeber';
    } else if( class_exists( 'EDD_GetResponse' ) ) {
        $plugin = 'GetResponse';
    }

    return $plugin;
}
