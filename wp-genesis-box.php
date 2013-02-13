<?php
/*
Plugin Name: WP Genesis Box
Plugin URI: http://www.jimmyscode.com/wordpress/wp-genesis-box/
Description: Display the Genesis framework affiliate box on your WordPress website. Make money as a Studiopress affiliate.
Version: 0.0.1
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/
// plugin constants
define('WPGB_PLUGIN_NAME', 'WP Genesis Box');
define('WPGB_SLUG', 'wp-genesis-box');

// localization to allow for translations
add_action('init', 'wp_genesis_box_translation_file');
function wp_genesis_box_translation_file() {
  $plugin_path = plugin_basename(dirname(__FILE__)) . '/translations';
  load_plugin_textdomain('wp_genesis_box', '', $plugin_path);
}
// tell WP that we are going to use new options
add_action('admin_init', 'wp_genesis_box_options_init');
function wp_genesis_box_options_init() {
  register_setting('wp_genesis_box_options', 'wp_genesis_box');
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
    wp_die(__('You do not have sufficient permission to access this page', 'wp_genesis_box'));
  }
	?>
  <div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php echo WPGB_PLUGIN_NAME; ?></h2>
    <form method="post" action="options.php">
      <?php settings_fields('wp_genesis_box_options'); ?>
      <?php $options = get_option('wp_genesis_box'); ?>
      <table class="form-table">
        <tr valign="top"><th scope="row"><?php _e('Plugin enabled?', 'wp_genesis_box'); ?></th>
					<td><input type="checkbox" name="wp_genesis_box[enabled]" value="1" <?php checked('1', $options['enabled']); ?> /></td>
				</tr>
				<tr valign="top"><td colspan="2"><?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', 'wp_genesis_box'); ?></td></tr>
        <tr valign="top"><th scope="row"><?php _e('Your Affiliate URL', 'wp_genesis_box'); ?></th>
          <td><input type="text" name="wp_genesis_box[affurl]" value="<?php echo $options['affurl']; ?>" style="width:500px" /></td>
        </tr>
        <tr valign="top"><td colspan="2"><?php _e('Enter your affiliate URL here. This will be inserted wherever you use the shortcode.', 'wp_genesis_box'); ?></td></tr>
      </table>
      <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'wp_genesis_box'); ?>" />
      </p>
    </form>
  </div>
  <?php  
}
// shortcode for posts and pages
add_shortcode('wp-genesis-box', 'genesis_aff_box');
// one function for shortcode and PHP
function genesis_aff_box() {
  $options = get_option('wp_genesis_box');
  $affurl = $options['affurl'];
  $isenabled = (bool)$options['enabled'];

  if ($isenabled) { // plugin is enabled
	  if (strlen($affurl) !== 0) { // affiliate URL specified
			// enqueue CSS only on pages with shortcode
			wp_genesis_box_styles();
			// calculate image url
			$imageurl = plugins_url(plugin_basename(dirname(__FILE__) . '/images/genesis_framework_logo.jpg'));
			return '<div id="genesis-box"><h3>' . __('This website is powered by the Genesis Framework', 'wp-genesis-box') . '</h3><a href="' . $affurl . '"><img class="alignright" src="' . $imageurl . '" alt="Genesis Framework" title="Genesis Framework" width="180" height="150" /></a>' . __('Genesis empowers you to easily build amazing websites with WordPress. Whether you\'re a novice or advanced developer, Genesis provides the secure and search-engine-optimized foundation that takes WordPress to incredible places.', 'wp-genesis-box') . '<a href="' . $affurl . '">' . __('It\'s that simple - start using Genesis now!', 'wp-genesis-box') . '</a></div>';
		}
	}
}
// show admin messages to plugin user
add_action('admin_notices', 'wpgb_showAdminMessages');
function wpgb_showAdminMessages() {
	// http://wptheming.com/2011/08/admin-notices-in-wordpress/
  global $pagenow;
	if (current_user_can('manage_options')) { // user has privilege
		if ($pagenow == 'options-general.php') {
		  if ($_GET['page'] == WPGB_SLUG) { // on WP Genesis Box settings page
				$options = get_option('wp_genesis_box');
				$isenabled = (bool)$options['enabled'];
				$affurl = $options['affurl'];
				if (!$isenabled) {
					echo '<div class="error">' . __('WARNING: ' . WPGB_PLUGIN_NAME, 'wp-genesis-box') . ' ' . __('is currently disabled.', 'wp-genesis-box') . '</div>';
				}
				if (strlen($affurl) == 0) {
					echo '<div class="error">' . __('WARNING: Affiliate URL missing. Please enter it below, otherwise the plugin won\'t do anything.', 'wp-genesis-box') . '</div>';
				}
			}
		}
	}
}
// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
// Add settings link on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wp_genesis_box_plugin_settings_link' );
function wp_genesis_box_plugin_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=wp-genesis-box">' . __('Settings', 'wp-genesis-box') . '</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
function wp_genesis_box_styles() {
  wp_register_style( 'wp_genesis_box_style', 
    plugins_url(plugin_basename(dirname(__FILE__)) . '/css/wp-genesis-box.css'), 
    array(), 
    "0.0.1", 
    'all' );
  // enqueueing:
  wp_enqueue_style('wp_genesis_box_style');
}
?>