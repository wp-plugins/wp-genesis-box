<?php
/*
Plugin Name: WP Genesis Box
Plugin URI: http://www.jimmyscode.com/wordpress/wp-genesis-box/
Description: Display the Genesis framework affiliate box on your WordPress website. Make money as a Studiopress affiliate.
Version: 0.0.5
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/
// plugin constants
define('WPGB_VERSION', '0.0.5');
define('WPGB_PLUGIN_NAME', 'WP Genesis Box');
define('WPGB_SLUG', 'wp-genesis-box');
define('WPGB_OPTION', 'wp_genesis_box');
define('WPGB_LOCAL', 'wp_genesis_box');
/* defaults */
define('WPGB_DEFAULT_ENABLED', true);
define('WPGB_DEFAULT_URL', '');
define('WPGB_ROUNDED', false);
define('WPGB_NOFOLLOW', true);
define('WPGB_AVAILABLE_IMAGES', 'genesis_framework_logo1,genesis_framework_logo2,genesis_framework_logo3,genesis_framework_logo4,genesis_framework_logo5,genesis_framework_logo6,genesis_framework_logo7,genesis_framework_logo8,genesis_framework_logo9,genesis_framework_logo10,genesis_framework_logo11,genesis_framework_logo12,genesis_framework_logo13,genesis_framework_logo14,genesis_framework_logo15,genesis_framework_logo16,genesis_framework_logo17,genesis_framework_logo18,genesis_framework_logo19');
define('WPGB_DEFAULT_IMAGE', '');
define('WPGB_DEFAULT_AUTO_INSERT', false);
define('WPGB_DEFAULT_SHOW', false);
define('WPGB_DEFAULT_NEWWINDOW', false);
/* default option names */
define('WPGB_DEFAULT_ENABLED_NAME', 'enabled');
define('WPGB_DEFAULT_URL_NAME', 'affurl');
define('WPGB_DEFAULT_ROUNDED_NAME', 'rounded');
define('WPGB_DEFAULT_NOFOLLOW_NAME', 'nofollow');
define('WPGB_DEFAULT_IMAGE_NAME', 'img');
define('WPGB_DEFAULT_AUTO_INSERT_NAME', 'autoinsert');
define('WPGB_DEFAULT_SHOW_NAME', 'show');
define('WPGB_DEFAULT_NEWWINDOW_NAME', 'opennewwindow');

// add custom quicktag
add_action('admin_print_footer_scripts', 'add_wpgb_quicktag', 100);
function add_wpgb_quicktag() {
?>
<script>
QTags.addButton('wpgb', 'Genesis Box', '[wp-genesis-box]', '', '', 'add WP Genesis Box', '' );
</script>
<?php }

// localization to allow for translations
// also, register CSS style for later inclusion
add_action('init', 'wp_genesis_box_translation_file');
function wp_genesis_box_translation_file() {
  $plugin_path = plugin_basename(dirname(__FILE__)) . '/translations';
  load_plugin_textdomain(WPGB_LOCAL, '', $plugin_path);
  register_wp_genesis_box_style();
}
// tell WP that we are going to use new options
add_action('admin_init', 'wp_genesis_box_options_init');
function wp_genesis_box_options_init() {
  register_setting('wp_genesis_box_options', WPGB_OPTION);
}
// add Settings sub-menu
add_action('admin_menu', 'wpgb_plugin_menu');
function wpgb_plugin_menu() {
  add_options_page(WPGB_PLUGIN_NAME, WPGB_PLUGIN_NAME, 'manage_options', WPGB_SLUG, 'wp_genesis_box_page');
}
// plugin settings page
// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
function wp_genesis_box_page() {
  // check perms
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permission to access this page', WPGB_LOCAL));
  }
?>
  <div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php echo WPGB_PLUGIN_NAME; ?></h2>
    <form method="post" action="options.php">
      <?php settings_fields('wp_genesis_box_options'); ?>
      <?php $options = wpgb_getpluginoptions(); ?>
	<?php /* update_option(WPGB_OPTION, $options); */ ?>
      <table class="form-table">
        <tr valign="top"><th scope="row"><strong><label for="wp_genesis_box[<?php echo WPGB_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', WPGB_LOCAL); ?></label></strong></th>
		<td><input type="checkbox" id="wp_genesis_box[<?php echo WPGB_DEFAULT_ENABLED_NAME; ?>]" name="wp_genesis_box[<?php echo WPGB_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', $options[WPGB_DEFAULT_ENABLED_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', WPGB_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label for="wp_genesis_box[<?php echo WPGB_DEFAULT_URL_NAME; ?>]"><?php _e('Your Affiliate URL', WPGB_LOCAL); ?></label></strong></th>
          <td><input type="text" id="wp_genesis_box[<?php echo WPGB_DEFAULT_URL_NAME; ?>]" name="wp_genesis_box[<?php echo WPGB_DEFAULT_URL_NAME; ?>]" value="<?php echo $options[WPGB_DEFAULT_URL_NAME]; ?>" style="width:500px" /></td>
        </tr>
        <tr valign="top"><td colspan="2"><?php _e('Enter your affiliate URL here. This will be inserted wherever you use the shortcode.', WPGB_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label for="wp_genesis_box[<?php echo WPGB_DEFAULT_ROUNDED_NAME; ?>]"><?php _e('Rounded corners CSS?', WPGB_LOCAL); ?></label></strong></th>
		<td><input type="checkbox" id="wp_genesis_box[<?php echo WPGB_DEFAULT_ROUNDED_NAME; ?>]" name="wp_genesis_box[<?php echo WPGB_DEFAULT_ROUNDED_NAME; ?>]" value="1" <?php checked('1', $options[WPGB_DEFAULT_ROUNDED_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Do you want to apply rounded corners CSS to the output?', WPGB_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label for="wp_genesis_box[<?php echo WPGB_DEFAULT_AUTO_INSERT_NAME; ?>]"><?php _e('Auto insert Genesis box at the end of posts?', WPGB_LOCAL); ?></label></strong></th>
		<td><input type="checkbox" id="wp_genesis_box[<?php echo WPGB_DEFAULT_AUTO_INSERT_NAME; ?>]" name="wp_genesis_box[<?php echo WPGB_DEFAULT_AUTO_INSERT_NAME; ?>]" value="1" <?php checked('1', $options[WPGB_DEFAULT_AUTO_INSERT_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Check this box to automatically insert the output at the end of blog posts. If you don\'t do this then you will need to manually insert shortcode or call the function in PHP.', WPGB_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label for="wp_genesis_box[<?php echo WPGB_DEFAULT_NOFOLLOW_NAME; ?>]"><?php _e('Nofollow links?', WPGB_LOCAL); ?></label></strong></th>
		<td><input type="checkbox" id="wp_genesis_box[<?php echo WPGB_DEFAULT_NOFOLLOW_NAME; ?>]" name="wp_genesis_box[<?php echo WPGB_DEFAULT_NOFOLLOW_NAME; ?>]" value="1" <?php checked('1', $options[WPGB_DEFAULT_NOFOLLOW_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Do you want to add rel="nofollow" to all links?', WPGB_LOCAL); ?></td></tr>

        <tr valign="top"><th scope="row"><strong><label for="wp_genesis_box[<?php echo WPGB_DEFAULT_NEWWINDOW_NAME; ?>]"><?php _e('Open links in new window?', WPGB_LOCAL); ?></label></strong></th>
		<td><input type="checkbox" id="wp_genesis_box[<?php echo WPGB_DEFAULT_NEWWINDOW_NAME; ?>]" name="wp_genesis_box[<?php echo WPGB_DEFAULT_NEWWINDOW_NAME; ?>]" value="1" <?php checked('1', $options[WPGB_DEFAULT_NEWWINDOW_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Do you want to open links in a new window?', WPGB_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label for="wp_genesis_box[<?php echo WPGB_DEFAULT_IMAGE_NAME; ?>]"><?php _e('Default image', WPGB_LOCAL); ?></label></strong></th>
		<td><select id="wp_genesis_box[<?php echo WPGB_DEFAULT_IMAGE_NAME; ?>]" name="wp_genesis_box[<?php echo WPGB_DEFAULT_IMAGE_NAME; ?>]" onChange="picture.src=this.options[this.selectedIndex].getAttribute('data-whichPicture');">
                <?php $images = explode(",", WPGB_AVAILABLE_IMAGES);
                      for($i=0, $imagecount=count($images); $i < $imagecount; $i++) {
                        $imageurl = plugins_url(plugin_basename(dirname(__FILE__) . '/images/' . $images[$i] . '.png'));
                        if ($images[$i] === $options[WPGB_DEFAULT_IMAGE_NAME]) { $selectedimage = $imageurl; }
                        echo '<option data-whichPicture="' . $imageurl . '" value="' . $images[$i] . '" ' . selected($images[$i], $options[WPGB_DEFAULT_IMAGE_NAME]) . '>' . $images[$i] . '</option>';
                      } ?>
            </select></td></tr>
        <tr><td colspan="2"><img src="<?php if (!$selectedimage) { echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/' . WPGB_DEFAULT_IMAGE . '.png')); } else { echo $selectedimage; } ?>" id="picture" /></td></tr>
	  <tr valign="top"><td colspan="2"><?php _e('Select the default image.', WPGB_LOCAL); ?></td></tr>
      </table>
      <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes', WPGB_LOCAL); ?>" />
      </p> 
    </form>
    <h2>Support</h2>
	<div style="background:#eff;border:1px solid gray;padding:20px">
      If you like this plugin, please <a href="http://wordpress.org/extend/plugins/<?php echo WPGB_SLUG; ?>/">rate it on WordPress.org</a> and click the "Works" button so others know it will work for your WordPress version. For support please visit the <a href="http://wordpress.org/support/plugin/<?php echo WPGB_SLUG; ?>">forums</a>.
    </div>
  </div>
  <?php 
}
// shortcode for posts and pages
add_shortcode('wp-genesis-box', 'genesis_aff_box');
// one function for shortcode and PHP
function genesis_aff_box($atts, $content = null) {
  // get parameters
  extract( shortcode_atts( array(
    WPGB_DEFAULT_URL_NAME => WPGB_DEFAULT_URL, 
    WPGB_DEFAULT_ROUNDED_NAME => WPGB_ROUNDED, 
    WPGB_DEFAULT_NOFOLLOW_NAME => WPGB_NOFOLLOW, 
    WPGB_DEFAULT_IMAGE_NAME => WPGB_DEFAULT_IMAGE, 
    WPGB_DEFAULT_NEWWINDOW_NAME => WPGB_DEFAULT_NEWWINDOW, 
    WPGB_DEFAULT_SHOW_NAME => WPGB_DEFAULT_SHOW
    ), $atts ) );

  $options = wpgb_getpluginoptions();
  $enabled = $options[WPGB_DEFAULT_ENABLED_NAME];

  if ($enabled) { // plugin is enabled
    // check for overridden parameters, if nonexistent then get from DB
    if ($affurl === WPGB_DEFAULT_URL) { // no url passed to function, try settings page
      $affurl = $options[WPGB_DEFAULT_URL_NAME];
      if (($affurl === WPGB_DEFAULT_URL) || ($affurl === false)) { // no url on settings page either
        $enabled = false;
      }
    }
    if ($rounded == WPGB_ROUNDED) {
      $rounded = $options[WPGB_DEFAULT_ROUNDED_NAME];
      if ($rounded === false) {
        $rounded = WPGB_ROUNDED;
      }
    }
    if ($nofollow == WPGB_NOFOLLOW) {
	$nofollow = $options[WPGB_DEFAULT_NOFOLLOW_NAME];
	if ($nofollow === false) {
	  $nofollow = WPGB_NOFOLLOW;
	}
    }
    if ($image == WPGB_DEFAULT_IMAGE) {
      $image = $options[WPGB_DEFAULT_IMAGE_NAME];
      if ($image === false) {
        $image = WPGB_DEFAULT_IMAGE;
      }
    }
    if ($opennewwindow == WPGB_DEFAULT_NEWWINDOW) {
      $opennewwindow = $options[WPGB_DEFAULT_NEWWINDOW_NAME];
      if ($opennewwindow === false) {
        $opennewwindow = WPGB_DEFAULT_NEWWINDOW;
      }
    }
  }
  if ($enabled) {
    // enqueue CSS only on pages with shortcode
    wp_genesis_box_styles();

    if ($content) {
      $text = $content;
    } else {
      $text = '<p>Genesis empowers you to quickly and easily build incredible websites with WordPress. ';
      $text .= 'Whether you\'re a novice or advanced developer, Genesis provides the secure and search-engine-optimized ';
      $text .= 'foundation that takes WordPress to places you never thought it could go. It\'s that simple - ';
      $text .= '<a' . ($opennewwindow ? ' target="_blank" ' : ' ') . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="' . $affurl . '">start using Genesis now!</a></p>';
      $text .= '<p>Take advantage of the 6 default layout options, comprehensive SEO settings, rock-solid security, flexible ';
      $text .= 'theme options, cool custom widgets, custom design hooks, and a huge selection of child themes ("skins") that ';
      $text .= 'make your site look the way you want it to. With automatic theme updates and world-class support included, ';
      $text .= 'Genesis is the smart choice for your WordPress website or blog.</p>';
    }

    // calculate image url
    $images = explode(",", WPGB_AVAILABLE_IMAGES);
    if (!in_array($image, $images)) {
      $image = $images[$options[WPGB_DEFAULT_IMAGE_NAME]];
      if (!$image) { $image = WPGB_DEFAULT_IMAGE; }
    }
    $imageurl = plugins_url(plugin_basename(dirname(__FILE__) . '/images/' . $image . '.png'));
    $imagedata = getimagesize($imageurl);
    $output = '<div id="genesis-box"' . ($rounded ? ' class="wpgb-rounded-corners"' : '') . '>';
    $output .= '<h3>' . __('This website runs on the Genesis framework', WPGB_LOCAL) . '</h3>';
    $output .= '<a' . ($opennewwindow ? ' target="_blank" ' : ' ') . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="' . $affurl . '">';
    $output .= '<img class="alignright" src="' . $imageurl . '" alt="' . __('Genesis Framework', WPGB_LOCAL) . '" title="' . __('Genesis Framework', WPGB_LOCAL) . '" width="' . $imagedata[0] . '" height="' . $imagedata[1] . '" /></a>';
    $output .= do_shortcode($text) . '</div>';
  } else { // plugin disabled
    $output = '<!-- ' . WPGB_PLUGIN_NAME . ': ' . __('plugin is disabled. Check Settings page.', WPGB_LOCAL) . ' -->';
  }
  if ($show) {
    echo $output;
  } else {
    return $output;
  }
}
// auto insert at end of posts?
add_action('the_content', 'wpgb_insert_genesis_box');
function wpgb_insert_genesis_box($content) {
  if (is_single()) {
    $options = wpgb_getpluginoptions();
    if ($options[WPGB_DEFAULT_AUTO_INSERT_NAME]) {
      $content .= genesis_aff_box($options);
    }
  }
  return $content;
}
// show admin messages to plugin user
add_action('admin_notices', 'wpgb_showAdminMessages');
function wpgb_showAdminMessages() {
  // http://wptheming.com/2011/08/admin-notices-in-wordpress/
  global $pagenow;
  if (current_user_can('manage_options')) { // user has privilege
    if ($pagenow == 'options-general.php') {
	if ($_GET['page'] == WPGB_SLUG) { // on WP Genesis Box settings page
        $options = wpgb_getpluginoptions(); // get_option(WPGB_OPTION); // don't use encapsulated function here
	  if ($options) {
	    $enabled = $options[WPGB_DEFAULT_ENABLED_NAME];
	    $affurl = $options[WPGB_DEFAULT_URL_NAME];
	    if ($enabled === false) {
		echo '<div class="updated">' . WPGB_PLUGIN_NAME . ' ' . __('is currently disabled.', WPGB_LOCAL) . '</div>';
	    }
	    if ($affurl === WPGB_DEFAULT_URL) {
		echo '<div class="error">' . __('WARNING: Affiliate URL missing. Please enter it below, or pass it to the shortcode or function, otherwise the plugin won\'t do anything.', WPGB_LOCAL) . '</div>';
	    }
        }
	}
    }
  }
}
// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
// Add settings link on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wp_genesis_box_plugin_settings_link' );
function wp_genesis_box_plugin_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=wp-genesis-box">' . __('Settings', WPGB_LOCAL) . '</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
function wp_genesis_box_styles() {
  wp_enqueue_style('wp_genesis_box_style');
}
function register_wp_genesis_box_style() {
  wp_register_style('wp_genesis_box_style', 
    plugins_url(plugin_basename(dirname(__FILE__)) . '/css/wp-genesis-box.css'), 
    array(), 
    WPGB_VERSION, 
    'all' );
}
function wpgb_getpluginoptions() {
  return get_option(WPGB_OPTION, array(WPGB_DEFAULT_ENABLED_NAME => WPGB_DEFAULT_ENABLED, WPGB_DEFAULT_URL_NAME => WPGB_DEFAULT_URL, WPGB_DEFAULT_ROUNDED_NAME => WPGB_ROUNDED, WPGB_DEFAULT_NOFOLLOW_NAME => WPGB_NOFOLLOW, WPGB_DEFAULT_IMAGE_NAME => WPGB_DEFAULT_IMAGE, WPGB_DEFAULT_AUTO_INSERT_NAME => WPGB_DEFAULT_AUTO_INSERT, WPGB_DEFAULT_NEWWINDOW_NAME => WPGB_DEFAULT_NEWWINDOW));
}
?>