<?php
/**
 * @package   Pressgram_Layout
 * @author    Daniel Milner <me@danilmilner.com>
 * @license   GPL-2.0+
 * @link      http://danielmilner.com
 * @copyright 2013 Daniel Milner
 *
 * @wordpress-plugin
 * Plugin Name: Pressgram Layout
 * Plugin URI:  http://danielmilner.com
 * Description: Adds layout settings to the official Pressgram plugin
 * Version:     1.1.0
 * Author:      Daniel Milner
 * Author URI:  http://danielmilner.com
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
register_activation_hook( __FILE__, array( 'Pressgram_Layout', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Pressgram_Layout', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Pressgram_Layout', 'get_instance' ) );
