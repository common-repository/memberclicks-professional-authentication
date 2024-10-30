<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://memberclicks.com
 * @since             1.0.0
 * @package           MemberClicks_Professional_Authentication
 *
 * @wordpress-plugin
 * Plugin Name:       MC Professional Authentication and User Sync
 * Description:       Provides SSO (Single Sign-On) with MemberClicks Professional to restrict content based on member group. Sync user records for consistent access.
 * Version:           1.0.1
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Author:            MemberClicks
 * Author URI:        http://memberclicks.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       memberclicks-professional-authentication
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
define( 'MEMBERCLICKS_PROFESSIONAL_AUTHENTICATION_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-memberclicks-professional-authentication-activator.php
 */
function memberclicks_professional_authentication_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-memberclicks-professional-authentication-activator.php';
	MemberClicks_Professional_Authentication_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-memberclicks-professional-authentication-deactivator.php
 */
function memberclicks_professional_authentication_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-memberclicks-professional-authentication-deactivator.php';
	MemberClicks_Professional_Authentication_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'memberclicks_professional_authentication_activate' );
register_deactivation_hook( __FILE__, 'memberclicks_professional_authentication_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-memberclicks-professional-authentication.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function memberclicks_professional_authentication_run() {

	$plugin = new MemberClicks_Professional_Authentication();
	$plugin->run();

}
memberclicks_professional_authentication_run();

// Utility Functions

/**
 * MemberClicks Professional: log message or data
 *
 * @since  1.0.0
 */
function memberclicks_professional_log( $data ) {

	if ( true === WP_DEBUG ) {
		if ( is_array( $data ) || is_object( $data ) ) {
			error_log( print_r( $data, true ) );
		} else {
			error_log( $data );
		}
	}

}
