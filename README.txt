=== category external feed ===
Contributors: danielecr
Donate link: http://www.smartango.com/articles/wordpress-category-external-feed-plugin
Tags: seo, feed, link
Stable tag: 1.6
Requires at least: 3.1
Tested up to: 4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Display feed for category in page, use simplepie and import feed
from external site and display it in category page.

This add new fields to category: the url of feed and the title
that will appear in the widget.

It uses simplepie class that came with wordpress distribution.


== Installation ==

Copy this folder in directory wp-content/plugins/ of your wordpress

Make sure the script can write the cache directory, have a look
via ftp, in doubt set it to 777

== Upgrade ==

I am experiencing some problem, deactivate and reactivate the module
should fix those. Be carefull, just make a backup of table data
(prefix_category_ext_feed), deactivate plugin, then reactivate.
Thus if data about feed is not available anymore, just replace
the table.


== Changelog ==

= 1.5 =
* add delta for installing db table
* added function for db table name

= 1.3 =
* fix typo
* remove simplepie code and use wp one
* check in wp repo

= 1.2 =
* include simplepie code from upstream (never uploaded to wordpress site)
* define widget for displaying the feed per category
