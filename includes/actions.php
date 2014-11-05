<?php
/**
 * Admin actions
 *
 * @package     EDD\Incentives\Actions
 * @since       1.0.0
 * @author      Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright   Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Remove button image
 *
 * @since       1.0.0
 * @return      void
 */
function edd_remove_button_image() {
    if( isset( $_GET['post'] ) ) {
        $meta = get_post_meta( $_GET['post'], '_edd_incentive_meta', true );

        unset( $meta['button_image'] );
        update_post_meta( $_GET['post'], '_edd_incentive_meta', $meta );
    }

    wp_safe_redirect( add_query_arg( array( 'edd-action' => null ) ) );
    exit;
}
add_action( 'edd_remove_button_image', 'edd_remove_button_image' );
