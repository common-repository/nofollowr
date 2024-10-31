=== NoFollowr ===
Contributors: joel_birch
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=434ZRXQ2CPY5G
Tags: nofollow, external links, links, externals, rel, seo, pagerank, moderation
Requires at least: 3.4
Tested up to: 4.7.3
Stable tag: 1.2.0

Browsing a site as an admin, icons are added to external links indicating their nofollow status. Clicking the icons toggles nofollow status via Ajax.

== Description ==

### Summary

NoFollowr allows an administrator to easily see which external links are granting search-engine benefits upon another site. The administrator can toggle between allowing and disallowing this benefit with a single click. The change is instant and does not require a page reload, making the moderation of external links a breeze.

### How it works

When logged in as an administrator, green “tick” and red “stop” icons appear next to all external links in a post indicating whether `rel="nofollow"` is currently applied to them. Simply click an icon to toggle between these two states and alter the link's nofollow status. This change is applied to your database remotely, without requiring a page reload.

### Why is this plugin useful?

The whole question of [whether to use `rel="nofollow"`][1] is a hotly debated one, and applying it (or not) across the board is not a good solution. You want to give credit where it is due and reward useful websites but, at the same time, you do not want to reward suspect websites that promote unethical or unscrupulous behaviour. Read more about how [`nofollow` is extremely important][2] when engaging in activism.

If you are not the sole author of your website or blog it can be very difficult to stay on top of which links are being followed. NoFollowr allows you to easily spot and alter which links receive your love, without spending time going back and forth between your site and the admin area to edit the posts manually.</section>

 [1]: http://www.seowizz.net/2009/04/relnofollow-debate-lets-try-and-get-to.html "The rel=nofollow debate: Let’s Try and Get To Grips With It"
 [2]: http://skeptools.wordpress.com/2008/09/03/not-just-for-spam-anymore-nofollow-for-skepticism/ "Not just for spam anymore: NOFOLLOW for skepticism « Skeptical Software Tools"

== Installation ==

The easiest way to install NoFollowr is to do so from within your site's admin area:

1. Search for it from your admin plugin page and click “Install Now”
1. From the resulting installation page, wait until you see the “Activate Plugin” link, then click it.

Otherwise, the installation process is as follows:

1. Download NoFollowr
1. Expand the zip archive and copy the entire folder named &#8220;nofollowr&#8221; to the /wp-content/plugins/ folder within your WordPress installation
1. Login to the admin area of your site and go to the plugins section
1. Find NoFollowr in the list and click its “Activate” link.

NoFollowr is now fully operational and requires no further setup. If you need more help installing the plugin I recommend you follow [this step-by-step beginners guide][5].

 [5]: http://www.wpbeginner.com/beginners-guide/step-by-step-guide-to-install-a-wordpress-plugin-for-beginners/

== Screenshots ==

Here is a link to a site I do not recommend

1. Click the green tick icon next to a link that goes to a dodgy website.
2. Admire the spinning animation for half a second or so.
3. The link now has a red icon, indicating `rel="nofollow"` is applied to it (and altered in the database). Search-engines will not count this link as a recommendation by you.

== Changelog ==

= 1.2.0 =
* Refactor PHP with automated tests to avoid future regressions. Refactor JS to use strict typing with Flow and write tests.

= 1.1.3 =
* Use SVG icons for crisp rendering across devices. Refactor JavaScript and remove canvas animation in favour of pure CSS.

= 1.1.2 =
* Update 'stable tag' field. Ensure updates are notified.

= 1.1.1 =
* Use earlier array syntax for compatibility with PHP 5 versions earlier than v5.4.

= 1.1.0 =
* Update code from PHP4 compatibility to PHP5 and PHP7 compatible syntax. Tested to work up to current WordPress version 4.7.3.

= 1.0.3 =
* Bug Fix: Broken in WP 3.6. Add missing second argument for current_user_can('edit_post') to fix. Slightly alter NoFollowr's jQuery selector to omit unneeded tag selector.

= 1.0.2 =
* Bug Fix: Similar to the bug fix in v1.0.1. Found other attribute selectors that should use double quotes. May increase compatibility with various jQuery versions.

= 1.0.1.2 =
* Another attempt to update tag correctly

= 1.0.1.1 =
* Attempt to update tag correctly

= 1.0.1 =
* Bug Fix: jQuery attribute selector needed double quotes to work with latest jQuery versions included with recent WordPress updates. Fixes the never-ending spinner issue. Fix typo in docs.

= 1.0 =
* Upload to WordPress Subversion repository for listing in the official plugin directory

= 0.6.4 =
* Add version to CSS and JS elements' query string so updating will not run into cache issues

= 0.6.3 =
* Disables handler on click so only one alteration is sent at a time
* Deletes the alteration's revision to avoid cluttering up the database

= 0.6.2 =
* Now works with post_type 'any'. Previously didn't work on 'page' type posts

= 0.6.1 =
* Minor JS bug fix related to when post contains multiple links that have the same href

= 0.6 =
* Change CSS class names to be more unique, eg. ".nofollow" now ".nf-nofollow"
* Use CSS sprites for images
* Animated Ajax spinner via HTML5 canvas with fallback image for non-canvas browsers
* JS and CSS now served minified

= 0.5 =
* Added WordPress version check code to alert any incompatibility upon installation.
* Now tested to work back to WordPress v2.7

= 0.4 =
* Extend JavaScript support back to jQuery 1.3
* Now tested to work in WordPress v2.8.3

= 0.3 =
* Made adding and removing nofollow attribute much more robust. Now honours existing rel attribute as this is allowed to be a space-separated list
* Refactored JS for similar reasons

= 0.2 =
* Add changelog.txt
* Revise initial version numbers to pre v1.0

= 0.1 =
* Initial release
* Launch of nofollowr.com

== Upgrade Notice ==

= 1.0 =
Install v1.0 from the official directory so that you get informed of upgrades automatically, and can upgrade with a single click
