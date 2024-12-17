<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Eventify_Me
 *
 * @wordpress-plugin
 * Plugin Name: Zeya4Events
 * Description: Plugin for events.
 * Version:           1.0.0
 * Author: Dmitriy Developer
 * Text Domain: eventifyme
 * Domain Path: /languages
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
define( 'EVENTIFYME_VERSION', '1.0.0' );
define( 'EVENTIFYME_TEXTDOMAIN', 'eventifyme' );
define( 'EVENTIFYME_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'EVENTIFYME_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'EVENTIFYME_IS_LICENSED', true );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-eventify-me-activator.php
 */
function activate_eventify_me() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eventify-me-activator.php';
	Eventify_Me_Activator::activate();
    delete_option('rewrite_rules'); // flush permalinks settings on activation plugin
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-eventify-me-deactivator.php
 */
function deactivate_eventify_me() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eventify-me-deactivator.php';
	Eventify_Me_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_eventify_me' );
register_deactivation_hook( __FILE__, 'deactivate_eventify_me' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-eventify-me.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_eventify_me() {

	$plugin = new Eventify_Me();
	$plugin->run();

}
run_eventify_me();
