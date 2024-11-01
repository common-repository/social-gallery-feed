<?php

/**
 * Simple Instagram Widgets Class
 *
 *
 * @package insta-feed-widget
 */

require_once( SI_PLUGIN_DIR . '/widgets/si-feed-widget.php' );

class SI_Widgets {

    private static $instance;

    function __construct() {

        add_action( 'widgets_init', array( $this, 'si_register_widgets' ) );

    }

    /**
     * Register Widgets
     */
    function si_register_widgets() {
        register_widget( 'SI_Feed_Widget' );
    }

    /**
     * Get Class Instance
     *
     * @return obj
     */
    public static function get_instance() {

        if ( self::$instance === null ) {
            self::$instance = new SI_Widgets();
        }
        return self::$instance;

    }
}