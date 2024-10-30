=== Plugin Name ===
Console Log Cloaking

Contributors:      Codecide
Plugin Name:       Console Log Cloaking
Plugin URI:        https://plugins.codecide.net/product/lo
Tags:              wordpress,plugin,security,console logging,development
Author URI:        https://plugins.codecide.net
Author:            Codecide
Donate link:       https://redcross.org
Requires PHP:      7.1
Requires at least: 5.0
Tested up to:      5.3.2
Stable tag:        1.0
Version:           1.0.0

== Description ==
The Console Log Cloaking plugin allows site administrators to block the display of console messages for users and visitors. 

Log messages appear in the browser console (F12) at different stages of the page loading and viewing experience. In many cases, log messages are warning or tracking messages issued by the browser to help users track the origin of display problems, such as long wait time or irregular action outcomes. Those messages can also be a side-effect of careless, console.log-happy developers tracking their own code variables. In almost all cases, it is best to avoid displaying those messages to the site's visitors, as the content often allows them insights into the inner workings of an application -- such as internal IDs, database connection strings, etc. -- and can lead to serious security problems. 

By default (upon first activation), noone but the main site administrator will see log messages in the console. Administrators can further fine-tune the behavior of the plugin to enable the display of log message to specific user roles (developers, editors, etc.), as well as determine which type of messages should be hidden. By default, all message types are cloaked.

To configure the behavior of the plugin, simply access the Console Log Cloaking menu from the sidebar.

This plugin will be updated to account for changes in future versions of WordPress, if necessary. 

If you encounter any problems with the plugin, or need extra related functionality, feel free to contact the authors through the comments or the official plugin page. 

== Installation ==
Upload the zip file to WordPress using the Plugins menu, or upload the entire plugin folder to your server using an FTP client. 

== Upgrade Notice ==
= 1.0.0 =
Initial release

== Screenshots ==
1. An example of the settings.

== Changelog ==
= 1.0.0 =
* Initial release.

== Frequently Asked Questions ==
= What happens if I deactivate the plugin? =
All logs will be displayed in the console. 

== Donations ==
None needed. 
