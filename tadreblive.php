<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.codefish.com.eg
 * @since             1.0.0
 * @package           tadreblive
 *
 * @wordpress-plugin
 * Plugin Name:       tadreb.live subscription
 * Plugin URI:        https://www.codefish.com.eg/tadreblive
 * Description:       Create Programmatically Subscriptions 
 * Version:           1.0.0
 * Author:            codefish <!-!> mediafish -> tadreb.live
 * Author URI:        https://www.codefish.com.eg
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tadreblive
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'tadreblive_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tadreblive-activator.php
 */
function activate_tadreblive() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tadreblive-activator.php';
	tadreblive_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tadreblive-deactivator.php
 */
function deactivate_tadreblive() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tadreblive-deactivator.php';
	tadreblive_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tadreblive' );
register_deactivation_hook( __FILE__, 'deactivate_tadreblive' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tadreblive.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tadreblive() {

	$plugin = new tadreblive();
	$plugin->run();

}
run_tadreblive();
