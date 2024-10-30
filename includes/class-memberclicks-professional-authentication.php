<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://memberclicks.com
 * @since      1.0.0
 *
 * @package    MemberClicks_Professional_Authentication
 * @subpackage MemberClicks_Professional_Authentication/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MemberClicks_Professional_Authentication
 * @subpackage MemberClicks_Professional_Authentication/includes
 * @author     MemberClicks
 */
class MemberClicks_Professional_Authentication {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MemberClicks_Professional_Authentication_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MEMBERCLICKS_PROFESSIONAL_AUTHENTICATION_VERSION' ) ) {
			$this->version = MEMBERCLICKS_PROFESSIONAL_AUTHENTICATION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'memberclicks-professional-authentication';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MemberClicks_Professional_Authentication_Loader. Orchestrates the hooks of the plugin.
	 * - MemberClicks_Professional_Authentication_i18n. Defines internationalization functionality.
	 * - MemberClicks_Professional_Authentication_Admin. Defines all hooks for the admin area.
	 * - MemberClicks_Professional_Authentication_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-memberclicks-professional-authentication-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-memberclicks-professional-authentication-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-memberclicks-professional-authentication-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-memberclicks-professional-authentication-public.php';

		$this->loader = new MemberClicks_Professional_Authentication_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MemberClicks_Professional_Authentication_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MemberClicks_Professional_Authentication_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new MemberClicks_Professional_Authentication_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'memberclicks_professional_authentication_add_menu_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'memberclicks_professional_authentication_register_setting' );
		$this->loader->add_action( 'admin_post_update', $plugin_admin, 'memberclicks_professional_authentication_admin_post_update' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new MemberClicks_Professional_Authentication_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'login_form', $plugin_public, 'memberclicks_professional_authentication_login_form', 10, 1 );
		$this->loader->add_action( 'login_footer', $plugin_public, 'memberclicks_professional_authentication_login_footer', 10, 1 );
		$this->loader->add_action( 'parse_request', $plugin_public, 'memberclicks_professional_authentication_parse_request' );
		$this->loader->add_action( 'wp_logout', $plugin_public, 'memberclicks_professional_authentication_wp_logout' );

		$this->loader->add_filter( 'authenticate', $plugin_public, 'memberclicks_professional_authentication_resource_owner_password_credentials_authenticate', 10, 3 );
		$this->loader->add_filter( 'login_message', $plugin_public, 'memberclicks_professional_authentication_login_message', 10, 1 );
		$this->loader->add_filter( 'query_vars', $plugin_public, 'memberclicks_professional_authentication_query_vars');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    MemberClicks_Professional_Authentication_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * MemberClicks Professional: get client credentials access token
	 *
	 * @since  1.0.0
	 */
	public static function memberclicks_professional_authentication_get_client_credentials_access_token( $api_client_id, $api_client_secret, $api_domain ) {

		$body = array(
			'grant_type' => 'client_credentials',
			'scope' => 'read',
		);

		return MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_access_token( $api_client_id, $api_client_secret, $api_domain, $body );

	}

	/**
	 * MemberClicks Professional: get resource owner password credentials access token
	 *
	 * @since  1.0.0
	 */
	public static function memberclicks_professional_authentication_get_resource_owner_password_credentials_access_token( $username, $password, $api_client_id, $api_client_secret, $api_domain ) {

		$body = array(
			'grant_type' => 'password',
			'scope' => 'read',
			'username' => $username,
			'password' => $password,
		);

		return MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_access_token( $api_client_id, $api_client_secret, $api_domain, $body );

	}

	/**
	 * MemberClicks Professional: get authorization code access token
	 *
	 * @since  1.0.0
	 */
	public static function memberclicks_professional_authentication_get_authorization_code_access_token( $code, $api_client_id, $api_client_secret, $api_domain ) {

		$body = array(
			'grant_type' => 'authorization_code',
			'code' => $code,
			'scope' => 'read',
			'redirect_uri' => MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_authorization_code_redirect_uri()
		);

		return MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_access_token( $api_client_id, $api_client_secret, $api_domain, $body );

	}

	/**
	 * MemberClicks Professional: get authorization code redirect URI
	 *
	 * @since  1.0.0
	 */
	public static function memberclicks_professional_authentication_get_authorization_code_redirect_uri() {
		$site_url = get_site_url();
		return "$site_url?memberclicks_professional_authentication=true";
	}

	/**
	 * MemberClicks Professional: get access token
	 *
	 * @since  1.0.0
	 */
	public static function memberclicks_professional_authentication_get_access_token( $api_client_id, $api_client_secret, $api_domain, $body ) {

		$access_token = null;

		if ( $api_client_id && $api_client_secret && $api_domain ) {

			$authorization = 'Basic ' . base64_encode( $api_client_id . ':' . $api_client_secret );
			$headers = array(
				'Accept' => 'application/json',
				'charset' => 'utf-8',
				'Authorization' => $authorization,
				'Content-Type' => 'application/x-www-form-urlencoded',
				'Cache-Control' => 'no-cache',
			);

			$url = 'https://' . $api_domain . '/oauth/v1/token';

			memberclicks_professional_log( 'OAuth Access Token Request: POST ' . $url );
			memberclicks_professional_log( 'Headers:' );
			memberclicks_professional_log( $headers );
			memberclicks_professional_log( 'Body:' );
			memberclicks_professional_log( $body );

			$response = wp_remote_post(
				$url,
				array(
					'method' => 'POST',
					'timeout' => 30,
					'httpversion' => '1.1',
					'headers' => $headers,
					'body' => $body,
					'cookies' => array(),
				)
			);

			if ( is_wp_error( $response ) ) {

				memberclicks_professional_log( 'OAuth Access Token Response Error' );
				memberclicks_professional_log( $response );

			} else {

				$response_status_code = $response['response']['code'];
				$response_status_message = $response['response']['message'];
				memberclicks_professional_log( 'OAuth Access Token Response: ' . $response_status_code . ' ' . $response_status_message );

				$responseBody = $response['body'];
				$responseBodyArray = json_decode( $responseBody, true );

				if ( is_array( $responseBodyArray ) ) {

					memberclicks_professional_log( $responseBodyArray );

					if ( $response_status_code == 200 ) {
						$access_token = $responseBodyArray['access_token'];
					}

				} else {
					memberclicks_professional_log( 'OAuth Access Token Response Body Invalid' );
				}
			}

		} else {

			if ( ! $api_client_id ) {
				memberclicks_professional_log( 'MC Professional API Client ID is not configured' );
			}
			if ( ! $api_client_secret ) {
				memberclicks_professional_log( 'MC Professional API Client Secret is not configured' );
			}
			if ( ! $api_domain ) {
				memberclicks_professional_log( 'MC Professional API Domain is not configured' );
			}

		}

		return $access_token;

	}

	/**
	 * MemberClicks Professional: import MemberClicks Professional groups into WordPress roles
	 *
	 * @since  1.0.0
	 */
	public static function memberclicks_professional_authentication_import_groups_into_roles( $access_token, $api_domain ) {

		$groups = MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_groups( $access_token, $api_domain );
		foreach ( $groups as $group ) {

			$role = MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_role_from_group( $group );
			add_role( $role, $group );

		}
	}

	/**
	 * MemberClicks Professional: get groups
	 *
	 * @since  1.0.0
	 */
	public static function memberclicks_professional_authentication_get_groups( $access_token, $api_domain ) {

		$groups = array();

		if ( $access_token && $api_domain ) {

			$authorization = 'Bearer ' . $access_token;
			$headers = array(
				'Accept' => 'application/json',
				'charset' => 'utf-8',
				'Authorization' => $authorization,
				'Cache-Control' => 'no-cache',
			);

			$url = 'https://' . $api_domain . '/api/v1/group';

			memberclicks_professional_log( 'Group Request: GET ' . $url );
			memberclicks_professional_log( 'Headers:' );
			memberclicks_professional_log( $headers );

			$response = wp_remote_get(
				$url,
				array(
					'method' => 'GET',
					'timeout' => 30,
					'httpversion' => '1.1',
					'headers' => $headers,
					'cookies' => array(),
				)
			);

			if ( is_wp_error( $response ) ) {

				memberclicks_professional_log( 'Group Response Error' );
				memberclicks_professional_log( $response );

			} else {

				$response_status_code = $response['response']['code'];
				$response_status_message = $response['response']['message'];
				memberclicks_professional_log( 'Group Response: ' . $response_status_code . ' ' . $response_status_message );

				$responseBody = $response['body'];
				$responseBodyArray = json_decode( $responseBody, true );

				if ( is_array( $responseBodyArray ) ) {

					memberclicks_professional_log( $responseBodyArray );

					if ( $response_status_code == 200 ) {

						foreach ( $responseBodyArray['groups'] as $group ) {
							$groups[] = $group['name'];
						}

					}

				} else {
					memberclicks_professional_log( 'Group Response Body Invalid' );
				}
			}

		} else {

			if ( ! $api_domain ) {
				memberclicks_professional_log( 'MC Professional API Domain is not configured' );
			}

		}

		return $groups;

	}

	/**
	 * MemberClicks Professional: get role ID from group name
	 *
	 * @since  1.0.0
	 */
	public static function memberclicks_professional_authentication_get_role_from_group( $group ) {
		return preg_replace( '/[^a-z0-9]+/', '_', strtolower( $group ) );
	}

}
