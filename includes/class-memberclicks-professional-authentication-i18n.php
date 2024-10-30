<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://memberclicks.com
 * @since      1.0.0
 *
 * @package    MemberClicks_Professional_Authentication
 * @subpackage MemberClicks_Professional_Authentication/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    MemberClicks_Professional_Authentication
 * @subpackage MemberClicks_Professional_Authentication/includes
 * @author     MemberClicks
 */
class MemberClicks_Professional_Authentication_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'memberclicks-professional-authentication',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
