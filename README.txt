=== MC Professional Authentication and User Sync ===
Contributors: memberclicks
Donate link: http://memberclicks.com/
Tags: membership management, MemberClicks, MC Professional, SSO, user authentication
Requires at least: 6.6
Tested up to: 6.6
Stable tag: 1.0.1
Requires PHP: 7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Provides SSO (Single Sign-On) with MemberClicks Professional to restrict content based on member group. Sync user records for consistent access.

== Description ==
The MC Professional Authentication and User Sync plugin allows you to offer exclusive member content on your WordPress site by restricting access to some or all areas of your site. As the administrator, you have control over permissions with the flexibility to set content access based on the member types and group affiliations that are already set up within your MC Professional system.

To log in to your restricted WordPress site, your members can utilize convenient single sign-on (SSO) with their regular MC Professional credentials and will be able to access specific content and pages based on your settings. You even can customize the WordPress site login screen. Additionally, the plugin enables you to sync user records across your MC Professional system and WordPress site to ensure you have one consistent source of truth for user authentication.

The plugin reaches out to MemberClicks Professional servers using the domain and API credentials that you enter in the plugin settings page. The plugin uses standard OAuth2 protocols to authenticate members, and data is transferred over secure connections. You can find Terms of Use, Privacy Policy and other legal documents in the [Legal Center](https://memberclicks.com/legal/).

== Installation ==
To set up the MC Professional Authentication and User Sync plugin on your WordPress site, follow these steps:

1. Log in to your WordPress site.
1. Navigate to the **Dashboard**.
1. On the left side menu, hover over the *Plugins* menu item and click **Add New Plugin**.
1. Search for the *MC Professional Authentication and User Sync* plugin.
1. Once you locate the *MC Professional Authentication and User Sync* plugin, click the **Install Now** button.
1. After the installation is complete, click the **Activate** button.
1. In your **Dashboard**, from the left side menu, locate and open **MC Professional**.
1. To use the MC Professional Authentication and User Sync plugin, you must configure the **API client** in **MC Professional** account. Instructions on configuring the API client can be found [here](https://help.memberclicks.com/hc/en-us/articles/18581108667021-API-Management).
  * Select the following grant types for authentication:
      1. Authorization Code
      1. Resource Owner Password Credentials
      1. Client Credentials
  * The admin group must be selected and have the appropriate access (edit or read-only) configured in the **Group Attribute Security** settings.
  * Enter the **Redirect URI**. This has to be your WordPress site address. You can copy it from the plugin settings. The redirect URI should not end in a trailing slash.
  * Save the API Client.
1. On the MC Professional Authentication and User Sync settings page, enter your MC Professional API *Client ID*, *Client Secret*, and *Domain* in the format orgid.memberclicks.net.
You can obtain these values from the created API Client in API Management in MC Professional.
1. Modify any additional settings to customize your plugin. For example, you can customize **Login button label**.
The plugin adds an SSO button to the WordPress login screen, allowing you to customize its label with your preferred text. We recommend including your organization name in the login button label.
1. Click the **Save Changes** button.
1. You can **Test API Client Credentials**.  This will check if the client credentials (such as API key) are correct and properly configured.
1. Once the plugin settings are set you can also **Synchronize WordPress roles**.
This will update your WordPress roles with your MC Professional member groups. No existing roles will be removed.

After following these steps, your WordPress site should be successfully integrated with MC Professional for authentication and user synchronization.

== Frequently Asked Questions ==
**How do I configure the API client in MC Professional?**
This article on [API management](https://help.memberclicks.com/hc/en-us/articles/18581108667021-API-Management) will guide you through the steps to configure the API client.

The API Client requires the following grant types for authentication: Authorization Code, Resource Owner Password Credentials, Client Credentials.

The admin group must be selected and have the appropriate access (edit or read-only) configured in the Group Attribute Security settings. Learn more about Group Attribute Security [here](https://help.memberclicks.com/hc/en-us/articles/15793025617421-Group-attribute-security).

**How do members log in utilizing their MC Professional credentials?**
Installing the MC Professional Authentication and User Sync plugin adds an SSO button to the WordPress login screen.

When members click the MC Professional login button, they are redirected to your MC Professional site to enter their credentials. After logging in, they are redirected back to your WordPress site.

If members enter their MC Professional credentials, username and password, in the WordPress login form instead of using the SSO button, they will be authorized in WordPress site but not in the MC Professional website.

**How do I restrict WordPress content to MC Professional members?**
To restrict WordPress content to MC Professional members, you must first install a content management plugin.

The following content management plugins have been tested:

* [Content Control – The Ultimate Content Restriction Plugin](https://wordpress.com/plugins/content-control)
* [Restrict User Access – Ultimate Membership & Content Protection](https://wordpress.com/plugins/restrict-user-access)
* [Block Visibility – Conditional Visibility Control for Block Editor](https://wordpress.com/plugins/block-visibility)

After installing and activating the content management plugin, you can control content access in WordPress based on user roles. You can synchronize user roles in WordPress with your member groups in MC Professional.

**How do I update member profile data to ensure it is synchronized between MC Professional and WordPress?**
When a member logs in with SSO for the first time, a new user with "mc_pro_user_{user ID}" username is created in WordPress, and will populate their WordPress profile with data including First Name, Last Name, Contact Name, and Email. The member’s role within WordPress is automatically assigned based on their associated membership groups in MC Professional.

With each login, the WordPress user is synchronized with current MC Professional member profile data, making sure the information in WordPress stays up to date. Any changes made to users in WordPress are not synchronized back to the MC Professional member profile.

<strong>For this reason, member profile data should be updated exclusively in MC Professional.</strong>

== Screenshots ==
1. Settings for the MC Professional Authentication and User Sync plugin
2. MC Professional SSO login button
3. MC Professional API client settings

== Changelog ==
= 1.0.1 =
* Fix plugin upload

= 1.0.0 =
* Initial release

== Upgrade Notice ==
= 1.0.0 =
* Initial release
