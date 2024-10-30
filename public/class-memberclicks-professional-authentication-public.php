<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://memberclicks.com
 * @since      1.0.0
 *
 * @package    MemberClicks_Professional_Authentication
 * @subpackage MemberClicks_Professional_Authentication/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    MemberClicks_Professional_Authentication
 * @subpackage MemberClicks_Professional_Authentication/public
 * @author     MemberClicks
 */
class MemberClicks_Professional_Authentication_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'memberclicks_professional_authentication';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MemberClicks_Professional_Authentication_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MemberClicks_Professional_Authentication_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( ! is_admin() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/memberclicks-professional-authentication-public.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MemberClicks_Professional_Authentication_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MemberClicks_Professional_Authentication_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/memberclicks-professional-authentication-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * MemberClicks Professional: resource owner password credentials authenticate with MC Pro
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_resource_owner_password_credentials_authenticate( $user, $username, $password ) {

		if ( empty( $username ) || empty( $password ) ) {
			return $user;
		}

		$user_access_token = $this->memberclicks_professional_authentication_get_resource_owner_password_credentials_user_access_token( $username, $password );
		if ( $user_access_token != null ) {
			$user = $this->memberclicks_professional_authentication_authenticate( $user_access_token );
		}

		return $user;

	}

	/**
	 * MemberClicks Professional: authorization code authenticate with MC Pro
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_authorization_code_authenticate( $code ) {

		$user = null;

		$user_access_token = $this->memberclicks_professional_authentication_get_authorization_code_user_access_token( $code );
		if ( $user_access_token != null ) {
			$user = $this->memberclicks_professional_authentication_authenticate( $user_access_token );
		}

		return $user;

	}

	/**
	 * MemberClicks Professional: authenticate with MC Pro
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_authenticate( $user_access_token ) {

		$user = null;

		$client_credentials_access_token = $this->memberclicks_professional_authentication_get_client_credentials_access_token();

		$this->memberclicks_professional_authentication_import_groups_into_roles( $client_credentials_access_token );

		$my_profile = $this->memberclicks_professional_authentication_get_my_profile( $user_access_token );
		if ( $my_profile != null ) {

			$profile_id = $my_profile['[Profile ID]'];
			$profile = $this->memberclicks_professional_authentication_get_profile( $profile_id, $client_credentials_access_token );

			$allowed_member_statuses = array( 'Active', 'Graced' );

			$member_status = $profile['[Member Status]'];
			if ( in_array( $member_status, $allowed_member_statuses ) ) {

				$username = $profile['[Username]'];
				$user_email = $profile['[Email | Primary]'];
				$display_name = $profile['[Contact Name]'];
				$first_name = $profile['[Name | First]'];
				$last_name = $profile['[Name | Last]'];

				$user_login = 'mc_pro_user_' . $profile_id;
				$user = get_user_by( 'login', $user_login );
				if ( $user ) {

					$userdata = array(
						'ID' => $user->ID,
						'user_nicename' => $username,
						'user_email' => $user_email,
						'display_name' => $display_name,
						'nickname' => $username,
						'first_name' => $first_name,
						'last_name' => $last_name,
					);

					$updated_user_id = wp_update_user( $userdata );
					$user = new WP_User( $updated_user_id );

				} else {

					$userdata = array(
						'user_login' => $user_login,
						'user_nicename' => $username,
						'user_email' => $user_email,
						'display_name' => $display_name,
						'nickname' => $username,
						'first_name' => $first_name,
						'last_name' => $last_name,
					);

					$new_user_id = wp_insert_user( $userdata );
					$user = new WP_User( $new_user_id );

				}

				$user->set_role("");
				$groups = $profile['[Group]'];
				if ( ! empty( $groups ) ) {

					foreach ( $groups as $group ) {
						$role = MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_role_from_group( $group );
						$user->add_role( $role );
					}
				}
			}
		}

		return $user;

	}

	/**
	 * MemberClicks Professional: get user access token with resource owner password credentials
	 *
	 * @since  1.0.0
	 */
	private function memberclicks_professional_authentication_get_resource_owner_password_credentials_user_access_token( $username, $password ) {

		$api_client_id = get_option( $this->option_name . '_api_client_id' );
		$api_client_secret = get_option( $this->option_name . '_api_client_secret' );
		$api_domain = get_option( $this->option_name . '_api_domain' );

		return MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_resource_owner_password_credentials_access_token( $username, $password,
			$api_client_id, $api_client_secret, $api_domain );

	}

	/**
	 * MemberClicks Professional: get user access token with authorization code
	 *
	 * @since  1.0.0
	 */
	private function memberclicks_professional_authentication_get_authorization_code_user_access_token( $code ) {

		$api_client_id = get_option( $this->option_name . '_api_client_id' );
		$api_client_secret = get_option( $this->option_name . '_api_client_secret' );
		$api_domain = get_option( $this->option_name . '_api_domain' );

		return MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_authorization_code_access_token( $code, $api_client_id, $api_client_secret, $api_domain );

	}

	/**
	 * MemberClicks Professional: get client credentials access token
	 *
	 * @since  1.0.0
	 */
	private function memberclicks_professional_authentication_get_client_credentials_access_token() {

		$api_client_id = get_option( $this->option_name . '_api_client_id' );
		$api_client_secret = get_option( $this->option_name . '_api_client_secret' );
		$api_domain = get_option( $this->option_name . '_api_domain' );

		return MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_client_credentials_access_token( $api_client_id, $api_client_secret, $api_domain );

	}

	/**
	 * MemberClicks Professional: get my profile
	 *
	 * @since  1.0.0
	 */
	private function memberclicks_professional_authentication_get_my_profile( $access_token ) {

		$my_profile = null;

		$api_domain = get_option( $this->option_name . '_api_domain' );

		if ( $access_token && $api_domain ) {

			$authorization = 'Bearer ' . $access_token;
			$headers = array(
				'Accept' => 'application/json',
				'charset' => 'utf-8',
				'Authorization' => $authorization,
				'Cache-Control' => 'no-cache',
			);

			$url = 'https://' . $api_domain . '/api/v1/profile/me';

			memberclicks_professional_log( 'My Profile Request: GET ' . $url );
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

				memberclicks_professional_log( 'My Profile Response Error' );
				memberclicks_professional_log( $response );

			} else {

				$response_status_code = $response['response']['code'];
				$response_status_message = $response['response']['message'];
				memberclicks_professional_log( 'My Profile Response: ' . $response_status_code . ' ' . $response_status_message );

				$responseBody = $response['body'];
				$responseBodyArray = json_decode( $responseBody, true );

				if ( is_array( $responseBodyArray ) ) {

					memberclicks_professional_log( $responseBodyArray );

					if ( $response_status_code == 200 ) {
						$my_profile = $responseBodyArray;
					}

				} else {
					memberclicks_professional_log( 'My Profile Response Body Invalid' );
				}
			}

		} else {

			if ( ! $api_domain ) {
				memberclicks_professional_log( 'MC Professional API Domain is not configured' );
			}

		}

		return $my_profile;

	}

	/**
	 * MemberClicks Professional: get profile by profile ID
	 *
	 * @since  1.0.0
	 */
	private function memberclicks_professional_authentication_get_profile( $profile_id, $access_token ) {

		$my_profile = null;

		$api_domain = get_option( $this->option_name . '_api_domain' );

		if ( $access_token && $api_domain ) {

			$authorization = 'Bearer ' . $access_token;
			$headers = array(
				'Accept' => 'application/json',
				'charset' => 'utf-8',
				'Authorization' => $authorization,
				'Cache-Control' => 'no-cache',
			);

			$url = 'https://' . $api_domain . '/api/v1/profile/' . $profile_id;

			memberclicks_professional_log( 'Get Profile Request: GET ' . $url );
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

				memberclicks_professional_log( 'Get Profile Response Error' );
				memberclicks_professional_log( $response );

			} else {

				$response_status_code = $response['response']['code'];
				$response_status_message = $response['response']['message'];
				memberclicks_professional_log( 'Get Profile Response: ' . $response_status_code . ' ' . $response_status_message );

				$responseBody = $response['body'];
				$responseBodyArray = json_decode( $responseBody, true );

				if ( is_array( $responseBodyArray ) ) {

					memberclicks_professional_log( $responseBodyArray );

					if ( $response_status_code == 200 ) {
						$my_profile = $responseBodyArray;
					}

				} else {
					memberclicks_professional_log( 'Get Profile Response Body Invalid' );
				}
			}

		} else {

			if ( ! $api_domain ) {
				memberclicks_professional_log( 'MC Professional API Domain is not configured' );
			}

		}

		return $my_profile;

	}

	/**
	 * MemberClicks Professional: import groups into roles
	 *
	 * @since  1.0.0
	 */
	private function memberclicks_professional_authentication_import_groups_into_roles( $access_token ) {

		$api_domain = get_option( $this->option_name . '_api_domain' );
		MemberClicks_Professional_Authentication::memberclicks_professional_authentication_import_groups_into_roles( $access_token, $api_domain );

	}

	/**
	 * MemberClicks Professional: get custom login message
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_login_message( $message ) {

		$login_header_message = get_option( $this->option_name . '_login_header_message' );

		if ( $login_header_message ) {
			return $login_header_message;
		} else {
			return $message;
		}

	}

	/**
	 * MemberClicks Professional: get custom login form
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_login_form( $message ) {

		$this->enqueue_styles();

		$login_form_message = get_option( $this->option_name . '_login_form_message' );
		if ( ! $login_form_message ) {
			$login_form_message = $message;
		}
		$login_button = '';

		$api_client_id = get_option( $this->option_name . '_api_client_id' );
		$api_domain = get_option( $this->option_name . '_api_domain' );

		if ( $api_client_id && $api_domain ) {

			$state = bin2hex( random_bytes( 5 ) );
			set_transient( $this->option_name . '_authorization_code_state_' . $state, $state, 24 * HOUR_IN_SECONDS );

			$redirect_uri = MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_authorization_code_redirect_uri();
			$login_link = "https://$api_domain/oauth/v1/authorize?response_type=code&client_id=$api_client_id&scope=read&state=$state&redirect_uri=$redirect_uri";
			$login_button_label = get_option( $this->option_name . '_login_button_label' ) ?: "Login with MC Professional";
			$lock_image = plugin_dir_url( __FILE__ ) . 'images/lock-solid.svg';

			$login_button = "<div class=\"row\" style=\"margin: 5px 0px 20px 0px;\"><a href=\"$login_link\"><button type=\"button\" class=\"memberclicks_professional_authentication_login_button\"><img src=\"$lock_image\" class=\"memberclicks_professional_authentication_login_button_icon\">$login_button_label</button></a></div>";

		}

		$login_button_allowed_tags = array(
			'a' => array(
				'href' => array()
			),
			'div' => array(
				'class' => array(),
				'style' => array(),
			),
			'button' => array(
				'class' => array(),
				'type' => array(),
			),
			'img' => array(
				'class' => array(),
				'src' => array(),
			)
		);

		echo wp_kses( $login_form_message, 'post' ) . wp_kses( $login_button, $login_button_allowed_tags );

	}

	/**
	 * MemberClicks Professional: get custom login footer message
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_login_footer( $message ) {

		$login_footer_message = get_option( $this->option_name . '_login_footer_message' );

		if ( $login_footer_message ) {
			echo wp_kses( $login_footer_message, 'post' );
		} else {
			echo wp_kses( $message, 'post' );
		}

	}

	/**
	 * MemberClicks Professional: whitelist the authentication query parameters
	 *
	 * @since  1.0.0
	 */
	function memberclicks_professional_authentication_query_vars( $query_vars ) {

		$query_vars[] = 'memberclicks_professional_authentication';
		$query_vars[] = 'code';
		$query_vars[] = 'state';

		return $query_vars;

	}

	/**
	 * MemberClicks Professional: parse the authentication request parameters
	 *
	 * @since  1.0.0
	 */
	function memberclicks_professional_authentication_parse_request( $wp ) {

		$query_vars = $wp->query_vars;

		if ( array_key_exists( 'memberclicks_professional_authentication', $query_vars ) && wp_validate_boolean( $query_vars['memberclicks_professional_authentication'] ) &&
			array_key_exists( 'code', $query_vars ) && array_key_exists( 'state', $query_vars ) ) {

			$state = $query_vars['state'];
			$transient_state = get_transient( $this->option_name . '_authorization_code_state_' . $state );
			delete_transient( $this->option_name . '_authorization_code_state_' . $state );

			if( $state === $transient_state ) {

				$code = $query_vars['code'];
				$user = $this->memberclicks_professional_authentication_authorization_code_authenticate( $code );

				if( $user ) {

					$user_id = $user->ID;
					wp_set_current_user( $user_id );
					wp_set_auth_cookie( $user_id );
					wp_safe_redirect( home_url() );

				} else {

					memberclicks_professional_log( 'Error authenticating MC Professional user with authorization code' );
					wp_safe_redirect( site_url( 'wp-login.php' ) );

				}

			} else {

				memberclicks_professional_log( 'Error authenticating MC Professional user verifying state' );
				wp_safe_redirect( site_url( 'wp-login.php' ) );

			}

			exit();
		}

	}

	/**
	 * MemberClicks Professional: log out of MemberClicks Professional and redirect to WordPress login
	 *
	 * @since  1.0.0
	 */
	function memberclicks_professional_authentication_wp_logout() {

		$wp_login_url = site_url() . '/wp-login.php?loggedout=true';
		$wp_login_url_base64 = base64_encode( $wp_login_url );

		$api_domain = get_option( $this->option_name . '_api_domain' );
		$redirect_url = "https://$api_domain/logout?return=$wp_login_url_base64";

		wp_redirect( $redirect_url );
		exit;

	}
}
