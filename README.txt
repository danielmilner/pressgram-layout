=== Layout Settings for Pressgram ===
Contributors: danielmilner, firetree
Donate link: 
Tags: pressgram, addon
Requires at least: 3.5.1
Tested up to: 3.8
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds layout settings to the official Pressgram plugin

== Description ==

__Layout Settings for Pressgram__ allows you to display your [Pressgram](http://wordpress.org/plugins/pressgram/) category
posts in a grid. Grid options are separated into both large and small screen
devices.

The options available are:

* 2 across
* 3 across
* 4 across
* 5 across
* Off

You're also able to set the posts per page to allow more images to be displayed.

== Installation ==

Please Note: This plugin requires the Pressgram plugin be installed
and activated (http://wordpress.org/plugins/pressgram/)

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' Plugin Dashboard
2. Select 'pressgram-layout.zip' from your computer
3. Upload
4. Activate the plugin on the WordPress Plugin Dashboard

= Using FTP =

1. Extract 'pressgram-layout.zip' to your computer
2. Upload the 'pressgram-layout' directory to your 'wp-content/pressgram-layout' directory
3. Activate the plugin on teh WordPress Plugin Dashboard

== Upgrade Path ==

1. Deactivate and remove the files for the version you're upgrading from
2. Install the new version per the Installation instructions.

== Frequently Asked Questions ==

= Do I need to have the Pressgram plugin installed and activated to use this plugin? =

Yes. It is required.

= Where can I find the settings for this plugin? =

Under *Settings* &gt; *Media* in the WordPress Admin Dashboard

= What is considered a small screen device and a large screen device? =

Small screen devices are iPads in Portrait mode and anything with a screen width less
than 768px.

Large screen devices are iPads in Landscape mode and anything with a screen width of 768px
or larger.

== Screenshots ==

1. The Pressgram Layout Administration options

== Changelog ==

= 1.2.0 =
* Bug fix: posts_per_page now only modifies the main loop
* New: posts_per_page can be set to use WordPress default settings. (Settings > Reading)

= 1.1.3 =
* Moved settings to the same location as the latest version of the official Pressgram plugin

= 1.1.2 =
* Bug fix: class was still named wrong

= 1.1.1 =
* Bug fix: class was named wrong

= 1.1.0 =
* posts_per_page is now an option

= 1.0.1 =
* Increased the posts_per_page to 20

= 1.0 =
* Initial release