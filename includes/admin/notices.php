<?php
/**
 * Admin notices
 *
 * @package     EDD\Incentives\Admin\Notices
 * @since       1.0.0
 * @author      Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright   Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Process admin notices
 *
 * @since       1.0.0
 * @return      void
 */
function edd_incentives_admin_notices() {
    if( isset( $_GET['edd-incentive-notice'] ) ) {
        $message = false;

        switch( $_GET['edd-incentive-notice'] ) {
            case 'duplicate-failed':
                $message    = __( 'Incentive duplication failed.', 'edd-incentives' );
                $type       = 'error';
                break;
            case 'duplicate-succeeded':
                $message    = sprintf( __( 'Incentive duplicated successfully. <a href="%s">Edit</a>', 'edd-incentives' ), admin_url( 'post.php?action=edit&post=' . $_GET['post'] ) );
                $type       = 'updated';
                break;
        }

        if( $message ) {
            echo '<div class="' . $type . '"><p>' . $message . '</p></div>';
        }
    }
}
add_action( 'admin_notices', 'edd_incentives_admin_notices' );
