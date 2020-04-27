=== Plugin Name ===

Contributors: ankupromote
Plugin Name: Promote Apex Plugin
Plugin URL: https://github.com/promoteinternational/apex-wordpress-plugin
Tags: wp, promote, apex
Requires at least: 5.0
Tested up to: 5.4
Stable tag: 1.2
Version: 1.2
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin used to connect a Promote Apex course administration system to a wordpress page and display courses.

== Description ==

The Promote Apex plugin is used to display course information from a Apex installation on a wordpress site.
The plugin uses a cron script to download information about the courses and display them in a configurable way on a
wordpress site.

When correctly configured the plugin will create a new section called "Courses" that will contain all of the downloaded
course information. These pages will be updated by the script according to the schedule set on the Apex Plugin settings
page.

== Installation ==

To install the plugin you need to do the following steps:
1. Install the plugin through the Wordpress plugin screens.
2. Activate the plugin thought the 'Plugins' section in wordpress.
3. Configure the apex plugin in the menu "Apex Plugin".

== Upgrade Notice ==

= 1.2 =
* First release under the Promote Apex plugin name
* Updated so that each course that is less than 6 hours will display information about start and end times.
