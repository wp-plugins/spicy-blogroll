=== Spicy Blogroll ===
Contributors: michaelpedzotti
Donate link: http://www.michaelpedzotti.com/wordpress-plugins/spicy-blogroll/
Tags:  blogroll, ajax, sidebar, links, RSS, bookmarks, jquery
Requires at least: 2.6
Tested up to: 3.0.4
Stable tag: trunk

Spices up your regular Blogroll by showing an Ajax popup with post excerpts for each link in your Blogroll. Fully customizable via settings page.


== Description == 

Spicy Blogroll will bring life to an otherwise static Blogroll. It shows a number of post excerpts for each link in your Blogroll using Ajax.

When your visitor hovers over a Blogroll link the RSS feed from the site is discovered and a number of recent posts is shown dynamically in a popup box. Each post excerpt includes the post date, a clickable title and the excerpt. You choose the length of the excerpt and whether or not the click opens the post in the same or a new window/tab.

Spicy BlogRoll makes use of internal caching for feed discovery and WordPress caching for RSS feeds to make sure everything is smooth for your visitor.

Practically everything can be customized in the options panel including pop-up width and height, number of posts to show, excerpt length, RSS timeout delay, opacity, link click option (open link in new or parent window) and all the text and error messages. The overall style mimics the existing theme of your blog.

Spicy Blogroll allows you to spread more link love to your favorite bloggers and encourages click-throughs to them. Encourage those on your Blogroll to install this plugin on their blog and link back to you for your link love in return.

Plugin by Michael Pedzotti. Concept is based on the Live Blogroll plugin by Vladimir Prelovac but with a really slick options page with many more adjustable options.


== Changelog ==

= 1.0.0 =
* Plugin now adds a unique link class to blogroll hyperlinks to allow the JQuery hook to identify blogrolls in practically any theme. Previously the plugin would not operate in some themes due to differences in class choices by theme developers.

= 0.1.1 =
* Changed a global variable name ($options=>$sbr_options) to avoid clashing with one or more other plugins.

= 0.1 =
* Initial release


== Upgrade Notice ==

= 1.0.0 =
* For the plugin to function correctly you must deactivate your current plugin if you are installing this manually, upload the new files, then reactivate. Auto-updating the plugin via your blog dashboard is the best method as this is done for you.


== Installation ==

1. Upload the whole plugin folder to your /wp-content/plugins/ folder.
2. Go to the Plugins page and activate the plugin.
3. Click on the settings link under the plugin name or manually navigate to the settings page.
4. Use the Options page to customize the plugin to suit your blog.


== Screenshots ==

1. Spicy Blogroll on a blog


== License ==

This file is part of Spicy Blogroll.

Category Search is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

Category Search is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Category Search. If not, see <http://www.gnu.org/licenses/>.


== Frequently Asked Questions ==

= How does it work? =

Spicy Blogroll uses Javascript and Ajax to dynamically retrieve a number of recent posts from the sites in your Blogroll. The posts are then displayed in a pop-up hover box near the mouse position based on coordinates you set in the settings panel.

Spicy Blogroll will first attempt to retrieve the RSS feed from the link supplied in your Blogroll data. If not successful, it will then attempt several other feed locations to autodiscover the feed. If found this feed will be used to populate several entries (you can set the number) in the pop-up.

= Spicy Blogroll does not show a preview for some of the sites listed in my Blogroll. Why is that? =

The site may not have the RSS feed listed in it's HTML or it is offline for some reason. Try locating the RSS feed URL manually and edit your Blogroll/Links entry to reflect the latest RSS URL.

= Can I suggest a feature or upgrade for the plugin? =

Of course, visit <a href="http://www.michaelpedzotti.com/wordpress-plugins/spicy-blogroll/">Spicy Blogroll Home Page</a>
