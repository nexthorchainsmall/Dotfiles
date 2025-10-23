=== Theme Info Changer ===
Contributors: wordpressdeveloper
Tags: theme, customization, admin, appearance, branding, screenshot, media-library, multisite
Requires at least: 6.0
Tested up to: 6.7
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Safely change your active theme's name, author, and screenshot from the admin dashboard — without editing theme files. Use Theme Info Changer to rebrand themes, update theme screenshots via the Media Library, and maintain consistent theme metadata across multisite networks. SEO optimized for plugin listing and discoverability.

== Description ==

Theme Info Changer allows WordPress administrators to customize how their active theme appears in the WordPress admin interface without modifying any theme files. This is particularly useful when you want to:

* Rebrand a third-party theme with your own branding
* Update outdated theme screenshots
* Customize theme information for client sites
* Create a consistent brand experience across multiple sites

**Key Features:**

* Change the theme name that appears in WordPress
* Update the theme author information
* Upload custom theme screenshots through the Media Library
* All changes are stored safely in the WordPress database
* No theme files are modified
* Simple and intuitive admin interface
* Works with any WordPress theme

== Installation ==

1. Upload the `theme-info-changer` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Appearance → Theme Info Changer' to customize your theme information

== Frequently Asked Questions ==

= Will this plugin modify my theme files? =

No, this plugin does not modify any theme files. It uses WordPress filters to virtually override the display of theme information while keeping your original theme files intact.

= What happens if I deactivate the plugin? =

Your theme will revert to displaying its original information. No permanent changes are made to your theme files.

= Can I use this plugin with any WordPress theme? =

Yes, this plugin works with any properly coded WordPress theme.

= Will the changes appear on the frontend of my site? =

No, the changes only affect how the theme appears in the WordPress admin interface.

== Screenshots ==

1. The Theme Info Changer settings page
2. Before and after comparison in the Themes page

== Changelog ==

= 1.0.0 =
* Initial release

= 1.1.0 =
* Improved: Plugin header and readme enhanced for SEO and clearer install details
* Improved: Persist theme-specific overrides across theme switches
* Fixed: Better handling of custom screenshots and metadata to avoid PHP warnings
* Added: Backup of per-theme settings on activation
* Misc: JS improvements for media uploader and cache-busting for screenshots

== Upgrade Notice ==

= 1.0.0 =
Initial release of Theme Info Changer