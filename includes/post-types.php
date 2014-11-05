<?php
/**
 * Post type functions
 *
 * @package     EDD\Incentives\Functions
 * @since       1.0.0
 * @author      Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright   Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Register our new post type
 *
 * @since       1.0.0
 * @return      void
 */
function edd_incentives_register_post_type() {
    $labels = apply_filters( 'edd_incentive_labels', array(
        'name'              => __( 'Incentives', 'edd-incentives' ),
        'singular_name'     => __( 'Incentive', 'edd-incentives' ),
        'add_new'           => __( 'New Incentive', 'edd-incentives' ),
        'add_new_item'      => __( 'Add New Incentive', 'edd-incentives' ),
        'edit_item'         => __( 'Edit Incentive', 'edd-incentives' ),
        'new_item'          => __( 'New Incentive', 'edd-incentives' ),
        'view_item'         => __( 'View Incentive', 'edd-incentives' ),
        'search_items'      => __( 'Search Incentives', 'edd-incentives' ),
        'not_found'         => __( 'No incentives found', 'edd-incentives' ),
        'not_found_in_trash'=> __( 'No incentives found in Trash', 'edd-incentives' )
    ) );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'publicly_queryable'=> true,
        'show_ui'           => true,
        'show_in_menu'      => false,
        'capability_type'   => 'product',
        'hierarchical'      => false,
        'supports'          => apply_filters( 'edd_incentive_supports', array( 'title', 'editor' ) )
    );

    register_post_type( 'incentive', apply_filters( 'edd_incentive_post_type_args', $args ) );
}
add_action( 'init', 'edd_incentives_register_post_type', 1 );


/**
 * Updated messages
 *
 * @since       1.0.0
 * @param       array $messages Post updated messages
 * @return      array $messages Updated (updated) messages
 */
function edd_incentives_updated_messages( $messages ) {
    $messages['incentive'] = array(
        1 => __( 'Incentive updated.', 'edd-incentives' ),
        4 => __( 'Incentive updated.', 'edd-incentives' ),
        6 => __( 'Incentive published.', 'edd-incentives' ),
        7 => __( 'Incentive saved.', 'edd-incentives' ),
        8 => __( 'Incentive submitted.', 'edd-incentives' )
    );

    return $messages;
}


/**
 * Setup dashboard columns
 *
 * @since       1.0.0
 * @param       array $columns The current dashboard columns
 * @return      array The updated dashboard columns
 */
function edd_incentives_dashboard_columns( $columns ) {
    $columns = array(
        'cb'        => '<input type="checkbox" />',
        'title'     => __( 'Title', 'edd-incentives' ),
        'discount'  => __( 'Discount', 'edd-incentives' ),
        'subscribe' => __( 'Subscriptions', 'edd-incentives' ),
        'author'    => __( 'Author', 'edd-incentives' ),
        'date'      => __( 'Date', 'edd-incentives' )
    );

    return apply_filters( 'edd_incentives_dashboard_columns', $columns );
}
add_filter( 'manage_edit-incentive_columns', 'edd_incentives_dashboard_columns' );


/**
 * Render dashboard columns
 *
 * @since       1.0.0
 * @param       string $column_name The column name
 * @param       int $post_id The ID of a given row item
 * @return      void
 */
function edd_incentives_render_dashboard_columns( $column_name, $post_id ) {
    if( get_post_type( $post_id ) == 'incentive' ) {
        $meta           = get_post_meta( $post_id, '_edd_incentive_meta', true );

        switch( $column_name ) {
            case 'discount':
                if( isset( $meta['discount'] ) && $meta['discount'] != '' ) {
                    $discount           = edd_get_discount( $meta['discount'] );
                    $discount_title     = $discount->post_title;
                    $discount_type      = edd_get_discount_type( $meta['discount'] );
                    $discount_amount    = edd_get_discount_amount( $meta['discount'] );
                    $discount_code      = edd_get_discount_code( $meta['discount'] );

                    if( $discount_type == 'percent' ) {
                        $discount_amount        = $discount_amount . __( '%', 'edd-incentives' );
                    } else {
                        $discount_amount        = edd_currency_filter( edd_format_amount( (double) $discount_amount ) );
                    }

                    echo '<a href="' . admin_url( 'edit.php?post_type=download&page=edd-discounts&edd-action=edit_discount&discount=' . $meta['discount'] ) . '">' . $discount_title . '</a><br />';
                    echo '<div class="edd-incentive-column-discount-item"><span>' . __( 'Code:', 'edd-incentives' ) . '</span> ' . $discount_code . '</div>';
                    echo '<div class="edd-incentive-column-discount-item"><span>' . __( 'Value:', 'edd-incentives' ) . '</span> ' . $discount_amount . '</div>';
                } else {
                    _e( 'None', 'edd-incentives' );
                }
                break;
            case 'subscribe':
                if( isset( $meta['subscribe'] ) && $meta['subscribe'] != '' ) {
                    echo '<span class="edd-incentive-enabled">' . __( 'Enabled', 'edd-incentives' ) . '</span>';
                } else {
                    echo '<span class="edd-incentive-disabled">' . __( 'Disabled', 'edd-incentives' ) . '</span>';
                }
                break;
        }
    }    
}
add_action( 'manage_posts_custom_column', 'edd_incentives_render_dashboard_columns', 10, 2 );


/**
 * Remove Quick Edit from the post actions
 *
 * @since       1.0.0
 * @param       array $actions The post actions array
 * @param       int $post_id The ID for a given row item
 * @return      array $actions The updated post actions array
 */
function edd_incentives_post_row_actions( $actions, $post_id ) {
    unset( $actions['inline hide-if-no-js'] );

    return $actions;
}
add_filter( 'post_row_actions', 'edd_incentives_post_row_actions', 10, 2 );


/**
 * Remove Edit from bulk actions
 *
 * @since       1.0.0
 * @param       array $actions The bulk actions array
 * @return      array $actions The updated bulk actions array
 */
function edd_incentives_bulk_actions( $actions ) {
    unset( $actions['edit'] );

    return $actions;
}
add_filter( 'bulk_actions-edit-incentive', 'edd_incentives_bulk_actions' );
