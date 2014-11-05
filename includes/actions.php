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


/**
 * Duplicate incentive
 *
 * @since       1.0.0
 * @return      void
 */
function edd_duplicate_incentive() {
    if( ! isset( $_GET['post'] ) ) {
        return;
    }

    $incentive  = get_post( $_GET['post'] );
    $meta       = get_post_meta( $_GET['post'], '_edd_incentive_meta', true );
    $success    = 'failed';

    $post_data  = array(
        'post_author'   => $incentive->post_author,
        'post_date'     => date( 'Y-m-d H:i:s' ),
        'post_content'  => $incentive->post_content,
        'post_title'    => $incentive->post_title,
        'post_type'     => 'incentive',
    );

    $post_id    = wp_insert_post( $post_data );

    if( $post_id ) {
        update_post_meta( $post_id, '_edd_incentive_meta', $meta );
        $success = 'succeeded';
    }

    wp_redirect( add_query_arg( array( 'edd-incentive-notice' => 'duplicate-' . $success, 'edd-action' => null, 'post' => $post_id ) ) );
    exit;
}
add_action( 'edd_duplicate_incentive', 'edd_duplicate_incentive' );
