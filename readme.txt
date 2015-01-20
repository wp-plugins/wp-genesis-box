=== WP Genesis Box ===
Tags: genesis, affiliate, marketing, commission, box, rounded, image
Requires at least: 4.0
Tested up to: 4.1
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display the Genesis framework affiliate marketing box on your website using shortcodes or PHP.

== Description ==

Based on <a href="http://www.briangardner.com/genesis-box/">this blog post from Brian Gardner</a>, display the Genesis framework affiliate box on posts or pages, or anywhere using a PHP function.

Genesis is a framework for WordPress for developing and maintaining modern and beautiful websites. Studiopress (the company that makes Genesis) affiliates can earn commission on every referral. This content box displays marketing text and logo that can help drive referrals through your website.

Disclaimer: This plugin is not affiliated with or endorsed by ShareASale, StudioPress or Copyblogger Media.

<h3>If you need help with this plugin</h3>

If this plugin breaks your site or just flat out does not work, please go to <a href="http://wordpress.org/plugins/wp-genesis-box/#compatibility">Compatibility</a> and click "Broken" after verifying your WordPress version and the version of the plugin you are using.

Then, create a thread in the <a href="http://wordpress.org/support/plugin/wp-genesis-box">Support</a> forum with a description of the issue. Make sure you are using the latest version of WordPress and the plugin before reporting issues, to be sure that the issue is with the current version and not with an older version where the issue may have already been fixed.

<strong>Please do not use the <a href="http://wordpress.org/support/view/plugin-reviews/wp-genesis-box">Reviews</a> section to report issues or request new features.</strong>

= Features =

- Display your affiliate link anywhere
- Works with most browsers, but degrades nicely in older browsers
- CSS only loads on pages with shortcode or function call
- Multiple images available for inclusion
- Links can be opened in new window
- Includes standard marketing language from Studiopress, or use your own
- Automatically insert the Genesis box after each post
- Hide output for logged in users

= Shortcode =

To display on any post or page, use this shortcode:

[wp-genesis-box]

Make sure you go to the plugin settings page after installing to set options.

<strong>If you use and enjoy this plugin, please rate it and click the "Works" button below so others know that it works with the latest version of WordPress.</strong>

== Installation ==

1. Upload the plugin through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings &raquo; WP Genesis Box, configure the plugin
4. Insert shortcode on posts or pages, or use PHP function.

To remove this plugin, go to the 'Plugins' menu in WordPress, find the plugin in the listing and click "Deactivate". After the page refreshes, find the plugin again in the listing and click "Delete".

== Frequently Asked Questions ==

= What are the plugin defaults? =

The plugin arguments and default values may change over time. To get the latest list of arguments and defaults, look at the settings page after installing the plugin.

= How do I use the plugin? =

You must have an affiliate account with <a href="http://www.studiopress.com/affiliates">Studiopress</a>, and a URL that you would use to refer visitors to Studiopress to purchase the Genesis framework.

After going to Settings &raquo; WP Genesis Box and inserting your affiliate link, use a shortcode to call the plugin from any page or post like this:

`[wp-genesis-box]`

You can also use the following function in your PHP code (functions.php, or a plugin):

`echo genesis_aff_box();`

You can also use this:

`do_shortcode('[wp-genesis-box]');`

<strong>You must define the URL to be displayed</strong>. If you do not set the URL in the plugin's settings page, or when you call the shortcode/function, the plugin won't do anything.</strong> 
You may also use shortcodes within the shortcode, ex:

`[wp-genesis-box][my_shortcode][/wp-genesis-box]`

And you can specify your own text to be displayed, if you do not want the default text, ex:

`[wp-genesis-box image="genesis_framework_logo10"]Click here to purchase the Genesis framework[/wp-genesis-box]`

or

`if (function_exists('genesis_aff_box') {
  genesis_aff_box(array('show' => true, 'image' => 'genesis_framework_logo10'), 'Click here to buy the Genesis Framework');
}`

= Examples =

You want to display the Genesis Box at the end of your blog posts, as many affiliates do. Here is <a href="http://digwp.com/2010/04/wordpress-custom-functions-php-template-part-2/">one possible snippet</a>:

`add_filter('the_content', 'include_genesis_box');
function include_genesis_box($content) {
  if (is_single()) { // it's a single post
    // append Genesis box after content
    if (function_exists('genesis_aff_box') {
      $content .= genesis_aff_box(); // assume affiliate URL is on plugin settings page
    }
  }
  return $content;
}`

Always wrap plugin function calls with a `function_exists` check so that your site doesn't go down if the plugin isn't active.

For Genesis framework users, use the <a href="http://my.studiopress.com/docs/hook-reference/">genesis_after_post_content</a> hook:

`add_action('genesis_after_post_content', 'include_genesis_box');
function include_genesis_box() {
  if (is_single()) {
    if (function_exists('genesis_aff_box') {
      echo genesis_aff_box(); // or: genesis_aff_box(array('show' => true), 'Click here to buy the Genesis Framework');
    }
  }
}`

This will echo the Genesis box after the post content on each post. Or you can simply check the "Auto insert Genesis box" checkbox on the plugin settings page and not have to use the shortcode or call the function.

= I want to use the plugin in a widget. How? =

Add this line of code to your functions.php:

`add_filter('widget_text', 'do_shortcode');`

Or install a plugin to do it for you: http://blogs.wcnickerson.ca/wordpress/plugins/widgetshortcodes/

Now, add the built-in text widget that comes with WordPress, and insert the shortcode into the text widget. See above for how to use the shortcode.

See http://digwp.com/2010/03/shortcodes-in-widgets/ for a detailed example.

<strong>Important: If using a widget in the sidebar, make sure you choose one of the smaller images so that it will fit.</strong>

= I don't want the buttons on my post editor toolbar. How do I remove them? =

Add this to your functions.php:

`remove_action('admin_print_footer_scripts', 'add_wpgb_quicktag');`

= I inserted the shortcode but don't see anything on the page. =

Clear your browser cache and also clear your cache plugin (if any). If you still don't see anything, check your webpage source for the following:

`<!-- WP Genesis Box: plugin is disabled. Check Settings page. -->`

This means you didn't pass a necessary setting to the plugin, so it disabled itself. You need to pass at least the affiliate URL, either by entering it on the settings page or passing it to the plugin in the shortcode or PHP function. You should also check that the "enabled" checkbox on the plugin settings page is checked. If that box is unchecked, the plugin will be disabled even if you pass the affiliate URL.

= I cleared my browser cache and my caching plugin but the output still looks wrong. =

Are you using a plugin that minifies CSS? If so, try excluding the plugin CSS file from minification.

= I cleared my cache and still don't see what I want. =

The CSS files include a `?ver` query parameter. This parameter is incremented with every upgrade in order to bust caches. Make sure none of your plugins or functions are stripping this query parameter. Also, if you are using a CDN, flush it or send an invalidation request for the plugin CSS files so that the edge servers request a new copy of it.

= I don't want the admin CSS. How do I remove it? =

Add this to your functions.php:

`remove_action('admin_head', 'insert_wpgb_admin_css');`

= I don't want to use the plugin CSS. =

Add this to your functions.php:

`add_action('wp_enqueue_scripts', 'remove_wpgb_style');
function remove_wpgb_style() {
  wp_deregister_style('wp_genesis_box_style');
}`

= I want to use my own text instead of the text output by the plugin. =

If you are using the shortcode, do this:

`[wp-genesis-box]Your content here[/wp-genesis-box]`

The text output by the plugin will be overriden by whatever you type inbetween the shortcode tags.

If you are using the PHP function, do this:

`genesis_aff_box(array('show' => true), 'Click <a href="my link">here</a> to buy the Genesis Framework');`

The second argument of the function is the content you want to use. You can use HTML tags and shortcodes in this string.

= I don't see the plugin toolbar button(s). =

This plugin adds one or more toolbar buttons to the HTML editor. You will not see them on the Visual editor.

The label on the toolbar button is "Genesis Box".

= I am using the shortcode but the parameters aren't working. =

On the plugin settings page, go to the "Parameters" tab. There is a list of possible parameters there along with the default values. Make sure you are spelling the parameters correctly.

The Parameters tab also contains sample shortcode and PHP code.

== Screenshots ==

1. Plugin settings page
2. Example output

== Changelog ==

= 0.2.8 =
- fixed PHP notices
- confirmed compatibility with WordPress 4.1
- fixed minor typo

= 0.2.7 =
- updated .pot file and readme

= 0.2.6 =
- fixed validation issue

= 0.2.5 =
- compressed CSS file

= 0.2.4 =
- code fix
- admin CSS and page updates

= 0.2.3 =
- minor code fix
- updated support tab

= 0.2.2 =
- option to show the output only to users who are not logged in
- option to use short or extended text in the output
- minor code optimizations
- use 'affurl', 'url', 'link' or 'href' as the URL parameter name

= 0.2.1 =
- fix 2 for wp_kses

= 0.2.0 =
- fix for wp_kses

= 0.1.9 =
- some minor code optimizations
- verified compatibility with 3.9

= 0.1.8 =
- OK, I am going to stop playing with the plugin now. Version check rolled back (again)

= 0.1.7 =
- prepare strings for internationalization
- plugin now requires WP 3.5 and PHP 5.0 and above
- minor code optimization

= 0.1.6 =
- minor plugin settings page update
- added more images to rotation
- try to use site name instead of "This Website"

= 0.1.5 =
- put submit button at the top of the plugin settings page
- spruce up the plugin settings page a bit
- minor fixes to CSS
- minor bug with parameter table on plugin settings page

= 0.1.4 =
- All CSS and JS automatically busts cache
- removed screen_icon() (deprecated)
- updated for WP 3.8.1

= 0.1.3 = 
- refactored admin CSS
- added helpful links on plugin settings page and plugins page

= 0.1.2 =
fixed uninstall routine, actually deletes options now

= 0.1.1 =
- updated the plugin settings page list of parameters to indicate whether they are required or not
- updated FAQ section of readme.txt

= 0.1.0 =
some security hardening added

= 0.0.9 =
minor admin code update

= 0.0.8 =
- target="_blank" is deprecated, replaced with javascript fallback

= 0.0.7 =
- minor code refactoring

= 0.0.6 =
- added donate link on admin page
- admin page CSS added
- various admin page tweaks
- minor code refactoring
- added shortcode defaults display on settings page

= 0.0.5 =
- there are now 19 different images available
- minor code refactoring
- added marketing text from Studiopress shareasale.com settings, which can be overridden by passing $content to shortcode or function
- image picker is a dropdown box with dynamic image display (thanks to http://stackoverflow.com/questions/2921607/how-to-change-picture-using-drop-down-list)
- moved quicktag script further down the page
- minor admin page update
- updated readme.txt
- added option to open links in new window
- css file refactoring

= 0.0.4 =
- updated admin messages code
- added more images to choose from
- updated readme
- added option to auto-insert Genesis box at end of single posts

= 0.0.3 =
* code refactoring
* updated admin menu
* added quicktag to post editor toolbar

= 0.0.2 =
* added more options to plugin Settings page
* changed handling of default options

= 0.0.1 =
created

== Upgrade Notice ==

= 0.2.8 =
- fixed PHP notices, confirmed compatibility with WordPress 4.1, fixed minor typo

= 0.2.7 =
- updated .pot file and readme

= 0.2.6 =
- fixed validation issue

= 0.2.5 =
- compressed CSS file

= 0.2.4 =
- code fix; admin CSS and page updates

= 0.2.3 =
- minor code fix; updated support tab

= 0.2.2 =
- option to show the output only to users who are not logged in; option to use short or extended text in the output; code optimizations; use 'affurl', 'url', 'link' or 'href' as the URL parameter name

= 0.2.1 =
- fix 2 for wp_kses

= 0.2.0 =
- fix for wp_kses

= 0.1.9 =
- some minor code optimizations, verified compatibility with 3.9

= 0.1.8 =
- OK, I am going to stop playing with the plugin now. Version check rolled back (again)

= 0.1.7 =
- prepare strings for internationalization, plugin now requires WP 3.5 and PHP 5.0 and above, minor code optimization

= 0.1.6 =
- minor plugin settings page update, added more images to rotation, try to use site name instead of "This Website"

= 0.1.5 =
- put submit button at the top of the plugin settings page, spruce up the plugin settings page a bit, minor fixes to CSS, minor bug with parameter table on plugin settings page

= 0.1.4 =
- All CSS and JS automatically busts cache, 
- removed screen_icon() (deprecated), 
- updated for WP 3.8.1

= 0.1.3 = 
- refactored admin CSS
- added helpful links on plugin settings page and plugins page

= 0.1.2 =
fixed uninstall routine, actually deletes options now

= 0.1.1 =
- updated the plugin settings page list of parameters to indicate whether they are required or not
- updated FAQ section of readme.txt

= 0.1.0 =
some security hardening added

= 0.0.9 =
minor admin code update

= 0.0.8 =
- target="_blank" is deprecated, replaced with javascript fallback

= 0.0.7 =
- minor code refactoring

= 0.0.6 =
- added donate link on admin page
- admin page CSS added
- various admin page tweaks
- minor code refactoring
- added shortcode defaults display on settings page

= 0.0.5 =
- there are now 19 different images available
- minor code refactoring
- added marketing text from Studiopress shareasale.com settings, which can be overridden by passing $content to shortcode or function
- image picker is a dropdown box with dynamic image display (thanks to http://stackoverflow.com/questions/2921607/how-to-change-picture-using-drop-down-list)
- moved quicktag script further down the page
- minor admin page update
- updated readme.txt
- added option to open links in new window
- css file refactoring

= 0.0.4 =
- updated admin messages code
- added more images to choose from
- updated readme
- added option to auto-insert Genesis box at end of single posts

= 0.0.3 =
* code refactoring
* updated admin menu
* added quicktag to post editor toolbar

= 0.0.2 =
* added more options to plugin Settings page
* changed handling of default options

= 0.0.1 =
created