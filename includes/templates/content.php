<?php
/**
 * Output content
 *
 * @package     EDD\Incentives\Templates\Content
 * @since       1.0.0
 * @author      Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright   Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Render the modal content
 *
 * @since       1.0.0
 * @global      object $post The WordPress object for a given post
 * @return      void
 */
function edd_incentives_render_content( $content ) {
    global $post;
    
    if( is_object( $post ) && edd_get_option( 'purchase_page' ) == $post->ID ) {
        $content .= '<div style="display: none;">';
        $content .= '<div id="edd-incentives-display">';

        $content .= '</div>';
        $content .= '</div>';
    }

    return $content;
}
add_filter( 'the_content', 'edd_incentives_render_content', 999 );
