<?php
/**
 * insta-feed-widget
 *
 * A widget that displays Instagram feed and user information.
 *
 * @package   insta-feed-widget 
 * @author    Daniel Dupal <daniel@dendroid.sk>
 * @license   GPL-2.0+
 * @link      http://dendroid.sk/2016/02/25/insta-feed-widget-wordpress/
 * @copyright 2016 Daniel Dupal
 *
 * @wordpress-plugin
 * Plugin Name:       Insta Feed Widget
 * Description:       A widget that displays Instagram feed and user information.
 * Version:           0.2
 * Author:            Daniel Dupal
 * Author URI:        dendroid.sk
 * Text Domain:       insta-feed-widget
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'SI_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'SI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

include_once SI_PLUGIN_DIR . '/inc/admin.php';
include_once SI_PLUGIN_DIR . '/inc/scripts.php';
include_once SI_PLUGIN_DIR . '/inc/widgets.php';
include_once SI_PLUGIN_DIR . '/inc/ajax.php';

add_action( 'plugins_loaded', 'si_init' );

/**
 * SI Init - Initialize the main Admin Class
 */
function si_init() {

    $si_admin = SI_Admin::get_instance();

}