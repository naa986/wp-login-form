=== WP Login Form ===
Contributors: naa986
Donate link: https://noorsplugin.com/
Tags: login, form, user, custom login, wordpress login
Requires at least: 3.0
Tested up to: 4.8
Stable tag: 1.0.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Create a WordPress user login form and add it to your post, page or sidebar

== Description ==

[WP Login Form](https://noorsplugin.com/wordpress-login-form-plugin/) allows you to create a simple login form for use anywhere on the front end of your WordPress site. You can add it to a post, page or your sidebar with a shortcode.

= WP Login Form Creation =

In order to create a WordPress login form with the default options all you need to do is use this shortcode:

`[wp_login_form]`

However, they are other parameters that you can pass in the shortcode to customize it.

**redirect**

An absolute URL to which the user will be redirected after a successful login. For example,

`[wp_login_form redirect="https://example.com/mypage/"]`

The default is to redirect back to the URL where the form was submitted.

**form_id**

Your own custom ID for the login form. For example,

`[wp_login_form form_id="myloginform"]`

The default is "loginform".

**label_username**

Your custom label for the username/email address field. For example,

`[wp_login_form label_username="Login ID or Email"]`

The default is "Username or Email Address".

**label_password**

Your custom label for the password field. For example,

`[wp_login_form label_password="Login Password"]`

The default is "Password".

**label_remember**

Your custom label for the remember field. For example,

`[wp_login_form label_remember="Remember"]`

The default is "Remember Me".

**label_log_in**

Your custom label for the form submit button. For example,

`[wp_login_form label_log_in="Submit"]`

The default is "Log In".

**id_username**

Your own custom ID for the username field. For example,

`[wp_login_form id_username="wp_user_login"]`

The default is "user_login".

**id_password**

Your own custom ID for the password field. For example,

`[wp_login_form id_password="wp_user_pass"]`

The default is "user_pass".

**id_remember**

Your own custom ID for the remember field. For example,

`[wp_login_form id_remember="login_rememberme"]`

The default is "rememberme".

**id_submit**

Your own custom ID for the form submit button. For example,

`[wp_login_form id_submit="login_form_submit"]`

The default is "wp-submit".

**remember**

Specify whether to display the "Remember Me" checkbox in the form. For example,

`[wp_login_form remember="0"]`

The default is "1" (true).

**value_username**

Your custom placeholder attribute for the username input field. For example,

`[wp_login_form value_username="Your Username"]`

The default is NULL.

**value_remember**

Specify whether the "Remember Me" checkbox in the form should be checked by default. For example,

`[wp_login_form value_remember="1"]`

The default is "0" (false).

For detailed documentation please visit the [WordPress Login Form](https://noorsplugin.com/wordpress-login-form-plugin/) plugin page


== Installation ==

1. Go to the Add New plugins screen in your WordPress Dashboard
1. Click the upload tab
1. Browse for the plugin file (wp-login-form.zip) on your computer
1. Click "Install Now" and then hit the activate button

== Frequently Asked Questions ==

= Can I use this plugin to create WordPress login form or Widget? =

Yes.

= Can I embed the login form into a post/page? =

Yes.

== Screenshots ==

1. WP Login Form Demo

== Upgrade Notice ==
none

== Changelog ==

= 1.0.1 =
* First commit
