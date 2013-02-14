=== WP Genesis Box ===
Tags: genesis, affiliate, marketing, commission, content box
Requires at least: 3.5
Tested up to: 3.5.1
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NRHAAC7Q9Q2X6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display the Genesis framework affiliate marketing box on your website using shortcodes or PHP.

== Description ==

Based on <a href="http://www.briangardner.com/genesis-box/">this blog post from Brian Gardner</a>, display the Genesis framework affiliate box on posts or pages, or anywhere using a PHP function.

Genesis is a framework for WordPress for developing and maintaining modern and beautiful websites. Studiopress (the company that makes Genesis) affiliates can earn commission on every referral. This content box displays marketing text and logo that can help drive referrals through your website.

If you use and enjoy this plugin, please rate it and click the "Works" button below so others know that it works with the latest version of WordPress.

== Installation ==

1. Upload the plugin through the WordPress interface.

2. Activate the plugin through the 'Plugins' menu in WordPress.

3. Go to Settings &raquo; WP Genesis Box, enable the plugin and insert your affiliate link.

4. Insert shortcode on posts or pages, or use PHP function.

== Frequently Asked Questions ==

= How do I use the plugin? =

You must have an affiliate account with <a href="http://www.studiopress.com/affiliates">Studiopress</a>, and a URL that you would use to refer visitors to Studiopress to purchase the Genesis framework.

After going to Settings &raquo; WP Genesis Box and inserting your affiliate link, use a shortcode to call the plugin from any page or post like this:

[wp-genesis-box]

You can also use the following function in your PHP code (functions.php, or a plugin):

`genesis_aff_box();`

You can also use this:

`do_shortcode('[wp-genesis-box]');`

= Examples =

You want to display the Genesis Box at the end of your blog posts, as many affiliates do. Here is <a href="http://digwp.com/2010/04/wordpress-custom-functions-php-template-part-2/">one possible snippet</a>:

`add_filter('the_content', 'include_genesis_box');
function include_genesis_box($content) {
  if (is_single()) { // it's a single post
    // append Genesis box after content
    if (function_exists('genesis_aff_box') {
      $content .= genesis_aff_box();
    }
  }
  return $content;
}`

For Genesis framework users, use the <a href="http://my.studiopress.com/docs/hook-reference/">genesis_after_post_content</a> hook:

`add_action('genesis_after_post_content', 'include_genesis_box');
function include_genesis_box() {
  if (is_single()) {
    if (function_exists('genesis_aff_box') {
      echo genesis_aff_box();
    }
  }
}`

This will echo the Genesis box after the post content on each post.

== Screenshots ==

1. Here's what the output looks like.
2. Settings page

== Changelog ==

= 0.0.1 =
created

== Upgrade Notice ==

= 0.0.1 =
created