<?php
/**
 * Scripts
 *
 * @package     EDD\Incentives\Scripts
 * @since       1.0.0
 * @author      Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright   Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @global      object $current_screen The WordPress object for this screen
 * @return      void
 */
function edd_incentives_admin_scripts( $hook ) {
    global $current_screen;
    
    if( $current_screen->post_type != 'incentive' && $current_screen->base != 'post' ) {
        return;
    }

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix     = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    wp_enqueue_style( 'edd-incentives-select2', EDD_INCENTIVES_URL . 'assets/css/select2' . $suffix . '.css' );
    wp_enqueue_script( 'edd-incentives-select2', EDD_INCENTIVES_URL . 'assets/js/select2' . $suffix . '.js', array( 'jquery' ) );

    wp_enqueue_style( 'edd-incentives', EDD_INCENTIVES_URL . 'assets/css/admin' . $suffix . '.css', array(), EDD_INCENTIVES_VER );
    wp_enqueue_script( 'edd-incentives', EDD_INCENTIVES_URL . 'assets/js/admin' . $suffix . '.js', array( 'jquery' ), EDD_INCENTIVES_VER );
    wp_localize_script( 'edd-incentives', 'edd_incentives_vars', array(
        'discount_placeholder'      => __( 'Select a discount', 'edd-incentives' ),
        'close_on_click'            => edd_get_option( 'edd_incentives_close_on_click', false ),
    ) );

    // The colorbox included in EDD is outdated... let's remove it!
    wp_dequeue_style( 'colorbox' );
    wp_dequeue_script( 'colorbox' );

    wp_enqueue_style( 'edd-incentives-colorbox', EDD_INCENTIVES_URL . 'assets/css/colorbox' . $suffix . '.css' );
    wp_enqueue_script( 'edd-incentives-colorbox', EDD_INCENTIVES_URL . 'assets/js/jquery.colorbox' . $suffix . '.js', array( 'jquery' ) );
}
add_action( 'admin_enqueue_scripts', 'edd_incentives_admin_scripts', 101 );


/**
 * Load scripts
 *
 * @since       1.0.0
 * @global      object $post The WordPress object for a given post
 * @return      void
 */
function edd_incentives_scripts() {
    global $post;

    if( ! is_object( $post ) || edd_get_option( 'purchase_page' ) !== $post->ID ) {
        return;
    }

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix     = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    wp_enqueue_script( 'ouibounce', EDD_INCENTIVES_URL . 'assets/js/ouibounce' . $suffix . '.js', array( 'jquery' ) );

    wp_enqueue_style( 'edd-incentives', EDD_INCENTIVES_URL . 'assets/css/style' . $suffix . '.css', array(), EDD_INCENTIVES_VER );
    wp_enqueue_script( 'edd-incentives', EDD_INCENTIVES_URL . 'assets/js/incentives' . $suffix . '.js', array( 'jquery' ), EDD_INCENTIVES_VER );
    wp_localize_script( 'edd-incentives', 'edd_incentives_vars', array(
        'close_on_click'            => edd_get_option( 'edd_incentives_close_on_click', false ),
    ) );

    // The colorbox included in EDD is outdated... let's remove it!
    wp_dequeue_style( 'colorbox' );
    wp_dequeue_script( 'colorbox' );

    wp_enqueue_style( 'edd-incentives-colorbox', EDD_INCENTIVES_URL . 'assets/css/colorbox' . $suffix . '.css' );
    wp_enqueue_script( 'edd-incentives-colorbox', EDD_INCENTIVES_URL . 'assets/js/jquery.colorbox' . $suffix . '.js', array( 'jquery' ) );    
}
add_action( 'wp_enqueue_scripts', 'edd_incentives_scripts', 101 );
