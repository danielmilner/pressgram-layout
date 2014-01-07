<?php
/**
 * @package   Layout Settings for Pressgram
 * @author    Daniel Milner <daniel@firetreedesign.com>
 * @license   GPL-2.0+
 * @link      http://firetreedesign.com
 * @copyright 2013 FireTree Design
 *
 * @wordpress-plugin
 * Plugin Name: Layout Settings for Pressgram
 * Plugin URI:  http://firetreedesign.com/wordpress/layout-settings-for-pressgram/
 * Description: Adds layout settings to the official Pressgram plugin
 * Version:     1.2.0
 * Author:      FireTree Design
 * Author URI:  http://firetreedesign.com
 * Text Domain: pressgram-layout
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-pressgram-layout.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Layout_Settings_for_Pressgram', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Layout_Settings_for_Pressgram', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Layout_Settings_for_Pressgram', 'get_instance' ) );
