<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://memberclicks.com
 * @since      1.0.0
 *
 * @package    MemberClicks_Professional_Authentication
 * @subpackage MemberClicks_Professional_Authentication/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MemberClicks_Professional_Authentication
 * @subpackage MemberClicks_Professional_Authentication/admin
 * @author     MemberClicks
 */
#[AllowDynamicProperties]
class MemberClicks_Professional_Authentication_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/memberclicks-professional-authentication-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/memberclicks-professional-authentication-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * MemberClicks Professional: add a menu page
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_add_menu_page() {

		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'MC Professional Authentication and User Sync', 'memberclicks-professional-authentication' ),
			__( 'MC Professional', 'memberclicks-professional-authentication' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'memberclicks_professional_authentication_display_settings_page' )
		);

	}

	/**
	 * MemberClicks Professional: render the settings page
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_display_settings_page() {
		include_once 'partials/memberclicks-professional-authentication-admin-display.php';
	}

	/**
	 * MemberClicks Professional: register the settings sections and fields
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_register_setting() {

		// add an API client section
		add_settings_section(
			$this->option_name . '_api_client',
			__( 'API Client', 'memberclicks-professional-authentication' ),
			array( $this, 'memberclicks_professional_authentication_api_client_cb' ),
			$this->plugin_name
		);

		// add a login page section
		add_settings_section(
			$this->option_name . '_login_page',
			__( 'Login Page', 'memberclicks-professional-authentication' ),
			array( $this, 'memberclicks_professional_authentication_login_page_cb' ),
			$this->plugin_name
		);

		// add client ID option field
		add_settings_field(
			$this->option_name . '_api_client_id',
			__( 'Client ID', 'memberclicks-professional-authentication' ),
			array( $this, 'memberclicks_professional_authentication_api_client_id_cb' ),
			$this->plugin_name,
			$this->option_name . '_api_client',
			array( 'label_for' => $this->option_name . '_api_client_id' )
		);

		// add client secret option field
		add_settings_field(
			$this->option_name . '_api_client_secret',
			__( 'Client Secret', 'memberclicks-professional-authentication' ),
			array( $this, 'memberclicks_professional_authentication_api_client_secret_cb' ),
			$this->plugin_name,
			$this->option_name . '_api_client',
			array( 'label_for' => $this->option_name . '_api_client_secret' )
		);

		// add domain option field
		add_settings_field(
			$this->option_name . '_api_domain',
			__( 'Domain', 'memberclicks-professional-authentication' ),
			array( $this, 'memberclicks_professional_authentication_api_domain_cb' ),
			$this->plugin_name,
			$this->option_name . '_api_client',
			array( 'label_for' => $this->option_name . '_api_domain' )
		);

		// add redirect URI option field
		add_settings_field(
			$this->option_name . '_api_redirect_uri',
			__( 'Redirect URI', 'memberclicks-professional-authentication' ),
			array( $this, 'memberclicks_professional_authentication_api_redirect_uri_cb' ),
			$this->plugin_name,
			$this->option_name . '_api_client',
			array( 'label_for' => $this->option_name . '_api_domain' )
		);

		// add login button_label option field
		add_settings_field(
			$this->option_name . '_login_button_label',
			__( 'Login Button Label', 'memberclicks-professional-authentication' ),
			array( $this, 'memberclicks_professional_authentication_login_button_label_cb' ),
			$this->plugin_name,
			$this->option_name . '_login_page',
			array( 'label_for' => $this->option_name . '_login_button_label' )
		);

		// add login header message option field
		add_settings_field(
			$this->option_name . '_login_header_message',
			__( 'Login Header Message', 'memberclicks-professional-authentication' ),
			array( $this, 'memberclicks_professional_authentication_login_header_message_cb' ),
			$this->plugin_name,
			$this->option_name . '_login_page',
			array( 'label_for' => $this->option_name . '_login_header_message' )
		);

		// add login form message option field
		add_settings_field(
			$this->option_name . '_login_form_message',
			__( 'Login Form Message', 'memberclicks-professional-authentication' ),
			array( $this, 'memberclicks_professional_authentication_login_form_message_cb' ),
			$this->plugin_name,
			$this->option_name . '_login_page',
			array( 'label_for' => $this->option_name . '_login_form_message' )
		);

		// add login footer message option field
		add_settings_field(
			$this->option_name . '_login_footer_message',
			__( 'Login Footer Message', 'memberclicks-professional-authentication' ),
			array( $this, 'memberclicks_professional_authentication_login_footer_message_cb' ),
			$this->plugin_name,
			$this->option_name . '_login_page',
			array( 'label_for' => $this->option_name . '_login_footer_message' )
		);

		// Register the settings
		register_setting( $this->plugin_name, $this->option_name . '_api_client_id', 'sanitize_text_field' );
		register_setting( $this->plugin_name, $this->option_name . '_api_client_secret', 'sanitize_text_field' );
		register_setting( $this->plugin_name, $this->option_name . '_api_domain', 'sanitize_text_field' );
		register_setting( $this->plugin_name, $this->option_name . '_login_button_label', 'sanitize_text_field' );
		register_setting( $this->plugin_name, $this->option_name . '_login_header_message', array( 'sanitize_callback' => array( $this, 'memberclicks_professional_authentication_login_header_message_sanitize_cb' ) ) );
		register_setting( $this->plugin_name, $this->option_name . '_login_form_message', array( 'sanitize_callback' => array( $this, 'memberclicks_professional_authentication_login_form_message_sanitize_cb' ) ) );
		register_setting( $this->plugin_name, $this->option_name . '_login_footer_message', array( 'sanitize_callback' => array( $this, 'memberclicks_professional_authentication_login_footer_message_sanitize_cb' ) ) );

	}

	/**
	 * MemberClicks Professional: render the text for the API Client section
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_api_client_cb() {
		echo '<p>' . esc_html( __( 'Enter the MC Professional API client information.', 'memberclicks-professional-authentication' ) ) . '</p>';
	}

	/**
	 * MemberClicks Professional: render the text for the Login Page section
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_login_page_cb() {
		echo '<p>' . esc_html( __( 'Customize the standard login page.', 'memberclicks-professional-authentication' ) ) . '</p>';
	}

	/**
	 * MemberClicks Professional: render the API client ID input
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_api_client_id_cb() {

		$api_client_id = get_transient( $this->option_name . '_api_client_id' );
		delete_transient( $this->option_name . '_api_client_id' );

		if ( $api_client_id === false ) {
			$api_client_id = get_option( $this->option_name . '_api_client_id' );
		}

		echo '<input type="text" name="' . esc_attr( $this->option_name ) . '_api_client_id' . '" id="' . esc_attr( $this->option_name ) . '_api_client_id' . '" value="' . esc_attr( $api_client_id ) . '" required maxlength=100 size=35> ';

	}

	/**
	 * MemberClicks Professional: render the API client secret input
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_api_client_secret_cb() {

		$api_client_secret = get_transient( $this->option_name . '_api_client_secret' );
		delete_transient( $this->option_name . '_api_client_secret' );

		if ( $api_client_secret === false ) {
			$api_client_secret = get_option( $this->option_name . '_api_client_secret' );
		}

		echo '<input type="password" name="' . esc_attr( $this->option_name ) . '_api_client_secret' . '" id="' . esc_attr( $this->option_name ) . '_api_client_secret' . '" value="' . esc_attr( $api_client_secret ) . '" required maxlength=100 size=35> ';

	}

	/**
	 * MemberClicks Professional: render the API domain input
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_api_domain_cb() {

		$api_domain = get_transient( $this->option_name . '_api_domain' );
		delete_transient( $this->option_name . '_api_domain' );

		if ( $api_domain === false ) {
			$api_domain = get_option( $this->option_name . '_api_domain' );
		}

		echo '<input type="text" name="' . esc_attr( $this->option_name ) . '_api_domain' . '" id="' . esc_attr( $this->option_name ) . '_api_domain' . '" value="' . esc_attr( $api_domain ) . '" required placeholder="<orgId>.memberclicks.net" maxlength=200 size=35> ';

	}

	/**
	 * MemberClicks Professional: render the API redirect URI input
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_api_redirect_uri_cb() {

		$redirect_uri = get_site_url();

		echo '<input type="text" name="' . esc_attr( $this->option_name ) . '_api_redirect_uri' . '" id="' . esc_attr( $this->option_name ) . '_api_redirect_uri' . '" value="' . esc_attr( $redirect_uri ) . '" readonly maxlength=200 size=35> ';

	}

	/**
	 * MemberClicks Professional: render the login button label input
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_login_button_label_cb() {

		$login_button_label = get_transient( $this->option_name . '_login_button_label' );
		delete_transient( $this->option_name . '_login_button_label' );

		if ( $login_button_label === false ) {
			$login_button_label = get_option( $this->option_name . '_login_button_label' );
		}

		echo '<input type="text" name="' . esc_attr( $this->option_name ) . '_login_button_label' . '" id="' . esc_attr( $this->option_name ) . '_login_button_label' . '" value="' . esc_attr( $login_button_label ) . '" placeholder="Login with MC Professional" maxlength=200 size=35> ';

	}

	/**
	 * MemberClicks Professional: render the login header message input
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_login_header_message_cb() {

		$login_header_message = get_transient( $this->option_name . '_login_header_message' );
		delete_transient( $this->option_name . '_login_header_message' );

		if ( $login_header_message === false ) {
			$login_header_message = get_option( $this->option_name . '_login_header_message' );
		}

		echo '<textarea name="' . esc_attr( $this->option_name ) . '_login_header_message' . '" id="' . esc_attr( $this->option_name ) . '_login_header_message' . '" rows="4" cols="80">' . esc_textarea( $login_header_message ) . '</textarea> ';

	}

	/**
	 * MemberClicks Professional: sanitize the login header message input
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_login_header_message_sanitize_cb( $value ): string {
		return wp_kses( $value, 'post' );
	}

	/**
	 * MemberClicks Professional: render the login form message input
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_login_form_message_cb() {

		$login_form_message = get_transient( $this->option_name . '_login_form_message' );
		delete_transient( $this->option_name . '_login_form_message' );

		if ( $login_form_message === false ) {
			$login_form_message = get_option( $this->option_name . '_login_form_message' );
		}

		echo '<textarea name="' . esc_attr( $this->option_name ) . '_login_form_message' . '" id="' . esc_attr( $this->option_name ) . '_login_form_message' . '" rows="4" cols="80">' . esc_textarea( $login_form_message ) . '</textarea> ';

	}

	/**
	 * MemberClicks Professional: sanitize the login form message input
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_login_form_message_sanitize_cb( $value ): string {
		return wp_kses( $value, 'post' );
	}

	/**
	 * MemberClicks Professional: render the login footer message input
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_login_footer_message_cb() {

		$login_footer_message = get_transient( $this->option_name . '_login_footer_message' );
		delete_transient( $this->option_name . '_login_footer_message' );

		if ( $login_footer_message === false ) {
			$login_footer_message = get_option( $this->option_name . '_login_footer_message' );
		}

		echo '<textarea name="' . esc_attr( $this->option_name ) . '_login_footer_message' . '" id="' . esc_attr( $this->option_name ) . '_login_footer_message' . '" rows="4" cols="80">' . esc_textarea( $login_footer_message ) . '</textarea> ';

	}

	/**
	 * MemberClicks Professional: sanitize the login footer message input
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_login_footer_message_sanitize_cb( $value ): string {
		return wp_kses( $value, 'post' );
	}

	/**
	 * MemberClicks Professional: handle button actions on settings page
	 *
	 * @since  1.0.0
	 */
	public function memberclicks_professional_authentication_admin_post_update() {

		check_admin_referer( "$this->plugin_name-options" );

		if ( isset( $_POST['memberclicks_professional_authentication_test_api_client_credentials'] ) ) {

			$this->memberclicks_professional_authentication_test_api_client_credentials();

		} elseif ( isset( $_POST['memberclicks_professional_authentication_synchronize_roles'] ) ) {

			$this->memberclicks_professional_authentication_synchronize_roles();

		} else {

			// Redirect back to the settings page that was submitted.
			$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
			wp_redirect( $goback );
			exit;

		}
	}

	/**
	 * MemberClicks Professional: test API client credentials
	 *
	 * @since  1.0.0
	 */
	private function memberclicks_professional_authentication_test_api_client_credentials() {

		check_admin_referer( "$this->plugin_name-options" );

		$this->memberclicks_professional_authentication_save_transient_values();

		$api_client_id = ! empty( $_REQUEST[ $this->option_name . '_api_client_id' ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $this->option_name . '_api_client_id' ] ) ) : '';
		$api_client_secret = ! empty( $_REQUEST[ $this->option_name . '_api_client_secret' ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $this->option_name . '_api_client_secret' ] ) ) : '';
		$api_domain = ! empty( $_REQUEST[ $this->option_name . '_api_domain' ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $this->option_name . '_api_domain' ] ) ) : '';

		$access_token = MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_client_credentials_access_token( $api_client_id, $api_client_secret, $api_domain );

		if ( $access_token ) {

			add_settings_error( 'test_api_client_credentials', 'test_api_client_credentials',
				__( 'API Client credentials verified successfully.', 'memberclicks-professional-authentication' ), 'success' );

		} else {

			add_settings_error( 'test_api_client_credentials', 'test_api_client_credentials',
				__( 'There was an error connecting to MC Professional. Please check your API Client credentials.', 'memberclicks-professional-authentication' ) );

		}
		set_transient( 'settings_errors', get_settings_errors() );

		// Redirect back to the settings page that was submitted.
		$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
		wp_redirect( $goback );
		exit;

	}

	/**
	 * MemberClicks Professional: synchronize MemberClicks Professional groups with WordPress roles
	 *
	 * @since  1.0.0
	 */
	private function memberclicks_professional_authentication_synchronize_roles() {

		check_admin_referer( "$this->plugin_name-options" );

		$this->memberclicks_professional_authentication_save_transient_values();

		$api_client_id = ! empty( $_REQUEST[ $this->option_name . '_api_client_id' ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $this->option_name . '_api_client_id' ] ) ) : '';
		$api_client_secret = ! empty( $_REQUEST[ $this->option_name . '_api_client_secret' ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $this->option_name . '_api_client_secret' ] ) ) : '';
		$api_domain = ! empty( $_REQUEST[ $this->option_name . '_api_domain' ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $this->option_name . '_api_domain' ] ) ) : '';

		$access_token = MemberClicks_Professional_Authentication::memberclicks_professional_authentication_get_client_credentials_access_token( $api_client_id, $api_client_secret, $api_domain );

		if ( $access_token ) {

			MemberClicks_Professional_Authentication::memberclicks_professional_authentication_import_groups_into_roles( $access_token, $api_domain );

			add_settings_error( 'synchronize_roles', 'synchronize_roles',
				__( 'WordPress roles synchronized successfully.', 'memberclicks-professional-authentication' ), 'success' );

		} else {

			add_settings_error( 'synchronize_roles', 'synchronize_roles',
				__( 'There was an error connecting to MC Professional. Please check your API Client credentials.', 'memberclicks-professional-authentication' ) );

		}
		set_transient( 'settings_errors', get_settings_errors() );

		// Redirect back to the settings page that was submitted.
		$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
		wp_redirect( $goback );
		exit;

	}

	/**
	 * MemberClicks Professional: save transient values for display on page
	 *
	 * @since  1.0.0
	 */
	private function memberclicks_professional_authentication_save_transient_values() {

		check_admin_referer( "$this->plugin_name-options" );

		$api_client_id = ! empty( $_REQUEST[ $this->option_name . '_api_client_id' ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $this->option_name . '_api_client_id' ] ) ) : '';
		$api_client_secret = ! empty( $_REQUEST[ $this->option_name . '_api_client_secret' ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $this->option_name . '_api_client_secret' ] ) ) : '';
		$api_domain = ! empty( $_REQUEST[ $this->option_name . '_api_domain' ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $this->option_name . '_api_domain' ] ) ) : '';
		$login_button_label = ! empty( $_REQUEST[ $this->option_name . '_login_button_label' ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $this->option_name . '_login_button_label' ] ) ) : '';
		$login_header_message = ! empty( $_REQUEST[ $this->option_name . '_login_header_message' ] ) ? wp_kses(( wp_unslash( $_REQUEST[ $this->option_name . '_login_header_message' ] ) ), 'post') : '';
		$login_form_message = ! empty( $_REQUEST[ $this->option_name . '_login_form_message' ] ) ? wp_kses(( wp_unslash( $_REQUEST[ $this->option_name . '_login_form_message' ] ) ), 'post') : '';
		$login_footer_message = ! empty( $_REQUEST[ $this->option_name . '_login_footer_message' ] ) ? wp_kses(( wp_unslash( $_REQUEST[ $this->option_name . '_login_footer_message' ] ) ), 'post') : '';

		set_transient( $this->option_name . '_api_client_id', $api_client_id );
		set_transient( $this->option_name . '_api_client_secret', $api_client_secret );
		set_transient( $this->option_name . '_api_domain', $api_domain );
		set_transient( $this->option_name . '_login_button_label', $login_button_label );
		set_transient( $this->option_name . '_login_header_message', $login_header_message );
		set_transient( $this->option_name . '_login_form_message', $login_form_message );
		set_transient( $this->option_name . '_login_footer_message', $login_footer_message );

	}

}
