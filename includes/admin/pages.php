<?php
/**
 * Admin pages
 *
 * @package     EDD\Incentives\Admin\Pages
 * @since       1.0.0
 * @author      Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright   Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Add our new submenu page under the Downloads menu
 *
 * @since       1.0.0
 * @global      string $edd_incentives_page The Incentives page
 * @return      void
 */
function edd_incentives_add_options_page() {
    global $edd_incentives_page, $edd_incentives_tutorial_page;

    $edd_incentives_page            = add_submenu_page( 'edit.php?post_type=download', __( 'Incentives', 'edd-incentives' ), __( 'Incentives', 'edd-incentives' ), 'manage_shop_settings', 'edit.php?post_type=incentive' );
    $edd_incentives_tutorial_page   = add_submenu_page( 'edit.php?post_type=download', __( 'Incentives Tutorial', 'edd-incentives' ), __( 'Incentives Tutorial', 'edd-incentives' ), 'manage_shop_settings', 'incentives-tutorial', 'edd_incentives_render_tutorial_page' );
}
add_action( 'admin_menu', 'edd_incentives_add_options_page', 10 );


/**
 * Hide tutorial link
 *
 * @since       1.0.0
 * @return      void
 */
function edd_incentives_hide_tutorial_link() {
    remove_submenu_page( 'edit.php?post_type=download', 'incentives-tutorial' );
}
add_action( 'admin_head', 'edd_incentives_hide_tutorial_link' );


/**
 * Fix CPT active entry
 *
 * @since       1.0.0
 * @param       string $parent_file The parent item for this entry
 * @global      string $submenu_file This menu item
 * @global      object $current_screen The screen we are viewing
 * @return      string $parent_file The fixed parent item for this entry
 */
function edd_incentives_fix_parent_file( $parent_file ) {
    global $submenu_file, $current_screen;

    if( $current_screen->post_type == 'incentive' || $current_screen->base == 'download_page_incentives-tutorial' ) {
        $submenu_file   = 'edit.php?post_type=incentive';
        $parent_file    = 'edit.php?post_type=download';
    }

    return $parent_file;
}
add_filter( 'parent_file', 'edd_incentives_fix_parent_file' );


/**
 * Render Incentives tutorial page
 *
 * @since       1.0.0
 * @return      void
 */
function edd_incentives_render_tutorial_page() {
    ?>
        <div class="wrap">
            <h2><?php _e( 'Incentives Tutorial', 'edd-incentives' ); ?> <a class="add-new-h2" href="<?php echo sprintf( admin_url( 'post.php?post=%s&action=edit' ), $_GET['post'] ); ?>"><?php _e( 'Return to Incentive Editor', 'edd-incentives' ); ?></a></h2>
        </div>
    <?php
}
