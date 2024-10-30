<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://memberclicks.com
 * @since      1.0.0
 *
 * @package    MemberClicks_Professional_Authentication
 * @subpackage MemberClicks_Professional_Authentication/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php settings_errors(); ?>
    <p><?php esc_html_e( 'Provides SSO (Single Sign-On) with MemberClicks Professional to restrict content based on member group. Sync user records for consistent access.', 'memberclicks-professional-authentication' ); ?></p>
    <form action="options.php" method="post">
		<?php
		settings_fields( $this->plugin_name );
		do_settings_sections( $this->plugin_name );
		submit_button( 'Test API Client Credentials', 'secondary', 'memberclicks_professional_authentication_test_api_client_credentials', false, array( 'formaction' => 'admin-post.php' ) );
		echo ' ';
		submit_button( 'Synchronize WordPress Roles', 'secondary', 'memberclicks_professional_authentication_synchronize_roles', false, array( 'formaction' => 'admin-post.php' ) );
		echo ' ';
		submit_button( null, 'primary', 'submit', false );
		?>
    </form>
</div>
