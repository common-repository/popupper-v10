=== Popupper ===
Contributors: edhogan
Donate link: http://ehogan.itis5am.com:8080/popupper/
Author URI: http://ehogan.itis5am.com:8080/
Plugin URI: http://ehogan.itis5am.com:8080/popupper/
Tags: Post, posts, plugin, images, page, javascript, AJAX, picture, popup, tooltip
Requires at least: 2.5
Tested up to: 2.8.4
Stable tag: 1.6

Popupper is a plugin that enables a blogger to add popups of images and text
into their posts.

== Description ==
Popupper is a plugin for Wordpress versions 2.5 and greater that allows a
blogger to insert links that when the reader mouses over the links it shows a
popup.

The popup can be text, image, or both, and is popped up via javascript. This
plugin is useful because the wordpress editting interface does not allow you
to enter javascript.

Some people may call these tooltips, the difference between these and
conventional tooltips is that these may contain an image.

== Installation ==
1. Copy the popupper directory and its contents to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==
1. A screenshot of an image-only popup.
2. A screenshot of a text-only popup.
3. A screenshot of a image and text popup.

== Frequently Asked Questions ==

= What can be turned into a popup? =

Any text that you select in a post or a page can be turned into a popup.

= How can I tell that the text is a popup? =

The text is decorated in someway, you can choose the text to be underlined,
double-underlined, boxed, or highlighted with various colors.

= The popup button is grayed out in my WYSIWYG editor. =

The button stays gray in the WYSIWYG editor until you select text that you wish
to use for the anchor of the popup. The anchor of the popup is the text that
triggers the popup when the reader mouses over it.

= What are the current limitations on the popups? =

Currently, there is not a good way to choose the height and width of the popup,
it is chosen automatically. Future work would be to allow this to be customized.

In addition, once a popup is created there is not a good way to go back and edit the
popup, instead you need to delete the old one and create a new one. Future work is
planned to address this.

= I created a popup, but I don't see in the WYSIWYG editor. =

The Wordpress editor does not allow the javascript needed to show popups, but you
can see your popup when you preview the page.

= What versions of Wordpress work with popupper? =

Wordpress 2.5 up to Wordpress 2.7 Beta 3, as soon as there is a newer beta, I'll test that out too.

== Change Log ==

= 1.6 =
* Fix Wordpress 2.8 support; wordpress' postmeta database format changed, some long numbers changed
from signed to unsigned, and hosed the plugin.

= 1.5 =
* i18n support for French, Spanish added

= 1.4 =
* try to address some mce3 issues (button not appearing for some users)
* fix a ie7 CSS complaint

= 1.3 =
* revisions change the post_id
* blue highlight popups not appearing
* bold underline is just underline

= 1.2 =
* tested with WP 2.7-beta3
* fix mce separator bug
* replace object oriented style with standard style
* new icon from famfamfam
* direct call security hole

= 1.0.1 =
* IE7 comment bug

= 1.0 =
* First general release
* 0.9.3 - fix whitespace insertion bug
* 0.9.2 - popup dialog has preview button
* 0.9.1 - improve popup dialog box/popup markup
* 0.9.0 - connect all the dots
* 0.2.4 - get sql query connection running
* 0.2.3 - popup dialog connection to database via http post
* 0.2.2 - popup dialog connection to database via http get
* 0.2.1 - edit popup dialog; convert to php
* 0.2.0 - use key/vals for popup info
* 0.1.0 - try inserting javascript directly into page
* 0.1.0 - first revision

== License ==
Popupper Plugin for WordPress
Copyright (C) 2008 Edward P. Hogan

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
