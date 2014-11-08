<?php
/**
 * Plugin Name:     Easy Digital Downloads - Incentives
 * Plugin URI:      https://easydigitaldownloads.com/extensions/incentives
 * Description:     Keep users from abandoning their cart by providing them with incentives
 * Version:         1.0.0
 * Author:          Daniel J Griffiths
 * Author URI:      http://section214.com
 * Text Domain:     edd-incentives
 *
 * @package         EDD\Incentives
 * @author          Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright       Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


if( ! class_exists( 'EDD_Incentives' ) ) {


    /**
     * Main EDD_Incentives class
     *
     * @since       1.0.0
     */
    class EDD_Incentives {


        /**
         * @var         EDD_Incentives $instance The one true EDD_Incentives
         * @since       1.0.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      self::$instance The one true EDD_Incentives
         */
        public static function instance() {
            if( ! self::$instance ) {
                self::$instance = new EDD_Incentives();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'EDD_INCENTIVES_VER', '1.0.0' );

            // Plugin path
            define( 'EDD_INCENTIVES_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'EDD_INCENTIVES_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            require_once EDD_INCENTIVES_DIR . 'includes/scripts.php';
            require_once EDD_INCENTIVES_DIR . 'includes/functions.php';
            require_once EDD_INCENTIVES_DIR . 'includes/actions.php';
            require_once EDD_INCENTIVES_DIR . 'includes/post-types.php';
            require_once EDD_INCENTIVES_DIR . 'includes/templates/content.php';

            if( is_admin() ) {
                require_once EDD_INCENTIVES_DIR . 'includes/admin/pages.php';
                require_once EDD_INCENTIVES_DIR . 'includes/admin/notices.php';
                require_once EDD_INCENTIVES_DIR . 'includes/admin/meta-boxes.php';
            }
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function hooks() {
            // Handle licensing
            if( class_exists( 'EDD_License' ) ) {
                $license = new EDD_License( __FILE__, 'Incentives', EDD_INCENTIVES_VER, 'Daniel J Griffiths' );
            }

            // Register settings
            add_filter( 'edd_settings_extensions', array( $this, 'settings' ) );
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
            $lang_dir = apply_filters( 'edd_incentives_lang_dir', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), '' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'edd-incentives', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/edd-incentives/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/edd-incentives/ folder
                load_textdomain( 'edd-incentives', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/edd-incentives/languages/ folder
                load_textdomain( 'edd-incentives', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'edd-incentives', false, $lang_dir );
            }
        }


        /**
         * Register settings
         *
         * @access      public
         * @since       1.0.0
         * @param       array $settings The existing plugin settings
         * @return      array The modified plugin settings
         */
        public function settings( $settings ) {
            $new_settings = array(
                array(
                    'id'    => 'edd_incentives_settings',
                    'name'  => '<strong>' . __( 'Incentives Settings', 'edd-incentives' ) . '</strong>',
                    'desc'  => '',
                    'type'  => 'header'
                ),
                array(
                    'id'    => 'edd_incentives_close_on_click',
                    'name'  => '<strong>' . __( 'Close on Click', 'edd-incentives' ) . '</strong>',
                    'desc'  => __( 'Enable closing the popup on background click.', 'edd-incentives' ),
                    'type'  => 'checkbox'
                )
            );

            return array_merge( $settings, $new_settings );
        }
    }
}


/**
 * The main function responsible for returning the one true EDD_Incentives
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      EDD_Incentives The one true EDD_Incentives
 */
function edd_incentives() {
    if( ! class_exists( 'Easy_Digital_Downloads' ) ) {
        if( ! class_exists( 'EDD_Extension_Activation' ) ) {
            require_once 'includes/class.extension-activation.php';
        }

        $activation = new EDD_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
        $activation = $activation->run;
    }
    
    return EDD_Incentives::instance();
}
add_action( 'plugins_loaded', 'edd_incentives' );
