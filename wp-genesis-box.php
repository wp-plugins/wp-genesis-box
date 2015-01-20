<?php
/*
Plugin Name: WP Genesis Box
Plugin URI: http://www.jimmyscode.com/wordpress/wp-genesis-box/
Description: Display the Genesis framework affiliate box on your WordPress website. Make money as a Studiopress affiliate.
Version: 0.2.8
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/

if (!defined('WPGB_PLUGIN_NAME')) {
	// plugin constants
	define('WPGB_PLUGIN_NAME', 'WP Genesis Box');
	define('WPGB_VERSION', '0.2.8');
	define('WPGB_SLUG', 'wp-genesis-box');
	define('WPGB_LOCAL', 'wp_genesis_box');
	define('WPGB_OPTION', 'wp_genesis_box');
	define('WPGB_OPTIONS_NAME', 'wp_genesis_box_options');
	define('WPGB_PERMISSIONS_LEVEL', 'manage_options');
	define('WPGB_PATH', plugin_basename(dirname(__FILE__)));
	/* defaults */
	define('WPGB_DEFAULT_ENABLED', true);
	define('WPGB_DEFAULT_URL', '');
	define('WPGB_ROUNDED', false);
	define('WPGB_NOFOLLOW', false);
	define('WPGB_AVAILABLE_IMAGES','genesis_framework_logo1,genesis_framework_logo2,genesis_framework_logo3,genesis_framework_logo4,genesis_framework_logo5,genesis_framework_logo6,genesis_framework_logo7,genesis_framework_logo8,genesis_framework_logo9,genesis_framework_logo10,genesis_framework_logo11,genesis_framework_logo12,genesis_framework_logo13,genesis_framework_logo14,genesis_framework_logo15,genesis_framework_logo16,genesis_framework_logo17,genesis_framework_logo18,genesis_framework_logo19,genesis_framework_logo20,genesis_framework_logo21,genesis_framework_logo22,genesis_framework_logo23,genesis_framework_logo24,genesis_framework_logo25,genesis_framework_logo26,genesis_framework_logo27,genesis_framework_logo28,genesis_framework_logo29,genesis_framework_logo30,genesis_framework_logo31,genesis_framework_logo32,genesis_framework_logo33');
	define('WPGB_DEFAULT_IMAGE', '');
	define('WPGB_DEFAULT_AUTO_INSERT', false);
	define('WPGB_DEFAULT_SHOW', false);
	define('WPGB_DEFAULT_NEWWINDOW', false);
	define('WPGB_DEFAULT_NONLOGGEDIN', false);
	define('WPGB_DEFAULT_USE_EXTENDED_TEXT', false);
	/* default option names */
	define('WPGB_DEFAULT_ENABLED_NAME', 'enabled');
	define('WPGB_DEFAULT_URL_NAME', 'affurl');
	define('WPGB_DEFAULT_ROUNDED_NAME', 'rounded');
	define('WPGB_DEFAULT_NOFOLLOW_NAME', 'nofollow');
	define('WPGB_DEFAULT_IMAGE_NAME', 'img');
	define('WPGB_DEFAULT_AUTO_INSERT_NAME', 'autoinsert');
	define('WPGB_DEFAULT_SHOW_NAME', 'show');
	define('WPGB_DEFAULT_NEWWINDOW_NAME', 'opennewwindow');
	define('WPGB_DEFAULT_NONLOGGEDIN_NAME', 'nonloggedonly');
	define('WPGB_DEFAULT_USE_EXTENDED_TEXT_NAME', 'useextendedtext');
}
	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', wpgb_get_local()));
	}

	// localization to allow for translations
	add_action('init', 'wp_genesis_box_translation_file');
	function wp_genesis_box_translation_file() {
		$plugin_path = wpgb_get_path() . '/translations';
		load_plugin_textdomain(wpgb_get_local(), '', $plugin_path);
		register_wp_genesis_box_style();
	}
	// tell WP that we are going to use new options
	add_action('admin_init', 'wp_genesis_box_options_init');
	function wp_genesis_box_options_init() {
		register_setting(WPGB_OPTIONS_NAME, wpgb_get_option(), 'wpgb_validation');
		register_wpgb_admin_style();
		register_wpgb_admin_script();
	}
	// validation function
	function wpgb_validation($input) {
		if (!empty($input)) {
			// validate all form fields
			$input[WPGB_DEFAULT_URL_NAME] = esc_url($input[WPGB_DEFAULT_URL_NAME]);
			$input[WPGB_DEFAULT_ENABLED_NAME] = (bool)$input[WPGB_DEFAULT_ENABLED_NAME];
			$input[WPGB_DEFAULT_ROUNDED_NAME] = (bool)$input[WPGB_DEFAULT_ROUNDED_NAME];
			$input[WPGB_DEFAULT_NOFOLLOW_NAME] = (bool)$input[WPGB_DEFAULT_NOFOLLOW_NAME];
			$input[WPGB_DEFAULT_AUTO_INSERT_NAME] = (bool)$input[WPGB_DEFAULT_AUTO_INSERT_NAME];
			$input[WPGB_DEFAULT_NEWWINDOW_NAME] = (bool)$input[WPGB_DEFAULT_NEWWINDOW_NAME];
			$input[WPGB_DEFAULT_NONLOGGEDIN_NAME] = (bool)$input[WPGB_DEFAULT_NONLOGGEDIN_NAME];
			$input[WPGB_DEFAULT_USE_EXTENDED_TEXT_NAME] = (bool)$input[WPGB_DEFAULT_USE_EXTENDED_TEXT_NAME];
			$input[WPGB_DEFAULT_IMAGE_NAME] = sanitize_text_field($input[WPGB_DEFAULT_IMAGE_NAME]);
		}
		return $input;
	}
	// add Settings sub-menu
	add_action('admin_menu', 'wpgb_plugin_menu');
	function wpgb_plugin_menu() {
		add_options_page(WPGB_PLUGIN_NAME, WPGB_PLUGIN_NAME, WPGB_PERMISSIONS_LEVEL, wpgb_get_slug(), 'wp_genesis_box_page');
	}
	// plugin settings page
	// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	function wp_genesis_box_page() {
		// check perms
		if (!current_user_can(WPGB_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', wpgb_get_local()));
		}
	?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo wpgb_getimagefilename('wpgb.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo WPGB_PLUGIN_NAME; ?> by <a href="http://www.jimmyscode.com/">Jimmy Pe&ntilde;a</a></h2>
			<div><?php _e('You are running plugin version', wpgb_get_local()); ?> <strong><?php echo WPGB_VERSION; ?></strong>.</div>

			<?php /* http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-5-tabbed-navigation-for-your-settings-page--wp-24971 */ ?>
			<?php $active_tab = (isset($_GET['tab']) ? $_GET['tab'] : 'settings'); ?>
			<h2 class="nav-tab-wrapper">
			  <a href="?page=<?php echo wpgb_get_slug(); ?>&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', wpgb_get_local()); ?></a>
				<a href="?page=<?php echo wpgb_get_slug(); ?>&tab=parameters" class="nav-tab <?php echo $active_tab == 'parameters' ? 'nav-tab-active' : ''; ?>"><?php _e('Parameters', wpgb_get_local()); ?></a>
				<a href="?page=<?php echo wpgb_get_slug(); ?>&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', wpgb_get_local()); ?></a>
			</h2>
			
			<form method="post" action="options.php">
			<?php settings_fields(WPGB_OPTIONS_NAME); ?>
			<?php $options = wpgb_getpluginoptions(); ?>
			<?php update_option(wpgb_get_option(), $options); ?>
			<?php if ($active_tab == 'settings') { ?>
			<h3 id="settings"><img src="<?php echo wpgb_getimagefilename('settings.png'); ?>" title="" alt="" height="61" width="64" align="absmiddle" /> <?php _e('Plugin Settings', wpgb_get_local()); ?></h3>
				<table class="form-table" id="theme-options-wrap">
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', wpgb_get_local()); ?>" for="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', wpgb_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', wpgb_checkifset(WPGB_DEFAULT_ENABLED_NAME, WPGB_DEFAULT_ENABLED, $options)); ?> /></td>
					</tr>
					<?php wpgb_explanationrow(__('Is plugin enabled? Uncheck this to turn it off temporarily.', wpgb_get_local())); ?>
					<?php wpgb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter your affiliate URL here. This will be inserted wherever you use the shortcode.', wpgb_get_local()); ?>" for="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_URL_NAME; ?>]"><?php _e('Your Affiliate URL', wpgb_get_local()); ?></label></strong></th>
						<td><input type="url" id="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_URL_NAME; ?>]" name="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_URL_NAME; ?>]" value="<?php echo wpgb_checkifset(WPGB_DEFAULT_URL_NAME, WPGB_DEFAULT_URL, $options); ?>" /></td>
					</tr>
					<?php wpgb_explanationrow(__('Enter your affiliate URL here. This will be inserted wherever you use the shortcode.', wpgb_get_local())); ?>
					<?php wpgb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Do you want to apply rounded corners CSS to the output?', wpgb_get_local()); ?>" for="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_ROUNDED_NAME; ?>]"><?php _e('Rounded corners CSS?', wpgb_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_ROUNDED_NAME; ?>]" name="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_ROUNDED_NAME; ?>]" value="1" <?php checked('1', wpgb_checkifset(WPGB_DEFAULT_ROUNDED_NAME, WPGB_ROUNDED, $options)); ?> /></td>
					</tr>
					<?php wpgb_explanationrow(__('Do you want to apply rounded corners CSS to the output?', wpgb_get_local())); ?>
					<?php wpgb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to automatically insert the output at the end of blog posts. If you do not do this then you will need to manually insert shortcode or call the function in PHP.', wpgb_get_local()); ?>" for="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_AUTO_INSERT_NAME; ?>]"><?php _e('Auto insert Genesis box at the end of posts?', wpgb_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_AUTO_INSERT_NAME; ?>]" name="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_AUTO_INSERT_NAME; ?>]" value="1" <?php checked('1', wpgb_checkifset(WPGB_DEFAULT_AUTO_INSERT_NAME, WPGB_DEFAULT_AUTO_INSERT, $options)); ?> /></td>
					</tr>
					<?php wpgb_explanationrow(__('Check this box to automatically insert the output at the end of blog posts. If you don\'t do this then you will need to manually insert shortcode or call the function in PHP.', wpgb_get_local())); ?>
					<?php wpgb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Do you want to add rel=nofollow to all links?', wpgb_get_local()); ?>" for="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_NOFOLLOW_NAME; ?>]"><?php _e('Nofollow links?', wpgb_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_NOFOLLOW_NAME; ?>]" name="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_NOFOLLOW_NAME; ?>]" value="1" <?php checked('1', wpgb_checkifset(WPGB_DEFAULT_NOFOLLOW_NAME, WPGB_NOFOLLOW, $options)); ?> /></td>
					</tr>
					<?php wpgb_explanationrow('Do you want to add rel="nofollow" to all links?', wpgb_get_local()); ?>
					<?php wpgb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to open links in a new window.', wpgb_get_local()); ?>" for="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_NEWWINDOW_NAME; ?>]"><?php _e('Open links in new window?', wpgb_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_NEWWINDOW_NAME; ?>]" name="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_NEWWINDOW_NAME; ?>]" value="1" <?php checked('1', wpgb_checkifset(WPGB_DEFAULT_NEWWINDOW_NAME, WPGB_DEFAULT_NEWWINDOW, $options)); ?> /></td>
					</tr>
					<?php wpgb_explanationrow(__('Check this box to open links in a new window.', wpgb_get_local())); ?>
					<?php wpgb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Show output to non-logged in users only?', wpgb_get_local()); ?>" for="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_NONLOGGEDIN_NAME; ?>]"><?php _e('Show output to non-logged in users only?', wpgb_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_NONLOGGEDIN_NAME; ?>]" name="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_NONLOGGEDIN_NAME; ?>]" value="1" <?php checked('1', wpgb_checkifset(WPGB_DEFAULT_NONLOGGEDIN_NAME, WPGB_DEFAULT_NONLOGGEDIN, $options)); ?> /></td>
					</tr>
					<?php wpgb_explanationrow(__('Check this box to display Genesis box to non-logged-in users only.', wpgb_get_local())); ?>
					<?php wpgb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Show full marketing text', wpgb_get_local()); ?>" for="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_USE_EXTENDED_TEXT_NAME; ?>]"><?php _e('Show full marketing text', wpgb_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_USE_EXTENDED_TEXT_NAME; ?>]" name="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_USE_EXTENDED_TEXT_NAME; ?>]" value="1" <?php checked('1', wpgb_checkifset(WPGB_DEFAULT_USE_EXTENDED_TEXT_NAME, WPGB_DEFAULT_USE_EXTENDED_TEXT, $options)); ?> /></td>
					</tr>
					<?php wpgb_explanationrow(__('Check this box if you want to show the full marketing text. If unchecked, only the first paragraph will be shown.', wpgb_get_local())); ?>
					<?php wpgb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Select the default image.', wpgb_get_local()); ?>" for="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_IMAGE_NAME; ?>]"><?php _e('Default image', wpgb_get_local()); ?></label></strong></th>
						<td><select id="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_IMAGE_NAME; ?>]" name="<?php echo wpgb_get_option(); ?>[<?php echo WPGB_DEFAULT_IMAGE_NAME; ?>]" onChange="picture.src=this.options[this.selectedIndex].getAttribute('data-whichPicture');">
									<?php $images = explode(",", WPGB_AVAILABLE_IMAGES);
												for($i=0, $imagecount=count($images); $i < $imagecount; $i++) {
													$imageurl = wpgb_getimagefilename($images[$i] . '.png');
													if ($images[$i] === (wpgb_checkifset(WPGB_DEFAULT_IMAGE_NAME, WPGB_DEFAULT_IMAGE, $options))) { $selectedimage = $imageurl; }
													echo '<option data-whichPicture="' . $imageurl . '" value="' . $images[$i] . '"' . selected($images[$i], wpgb_checkifset(WPGB_DEFAULT_IMAGE_NAME, WPGB_DEFAULT_IMAGE, $options), false) . '>' . $images[$i] . '</option>';
												} ?>
							</select>
						</td></tr>
					<tr><td colspan="2">
						<img src="<?php if (!$selectedimage) { echo wpgb_getimagefilename(WPGB_DEFAULT_IMAGE . '.png'); } else { echo $selectedimage; } ?>" id="picture" />
					</td></tr>
					<?php wpgb_explanationrow(__('Select the default image.', wpgb_get_local())); ?>
				</table>
				<?php submit_button(); ?>
			<?php } elseif ($active_tab == 'parameters') { ?>
			<h3 id="parameters"><img src="<?php echo wpgb_getimagefilename('parameters.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Plugin Parameters and Default Values', wpgb_get_local()); ?></h3>
			These are the parameters for using the shortcode, or calling the plugin from your PHP code.

			<?php echo wpgb_parameters_table(wpgb_get_local(), wpgb_shortcode_defaults(), wpgb_required_parameters()); ?>			

			<h3 id="examples"><img src="<?php echo wpgb_getimagefilename('examples.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Shortcode and PHP Examples', wpgb_get_local()); ?></h3>
			<h4><?php _e('Shortcode Format:', wpgb_get_local()); ?></h4>
			<?php echo wpgb_get_example_shortcode('wp-genesis-box', wpgb_shortcode_defaults(), wpgb_get_local()); ?>

			<h4><?php _e('PHP Format:', wpgb_get_local()); ?></h4>
			<?php echo wpgb_get_example_php_code('wp-genesis-box', 'genesis_aff_box', wpgb_shortcode_defaults()); ?>
			<?php _e('<small>Note: \'show\' is false by default; set it to <strong>true</strong> echo the output, or <strong>false</strong> to return the output to your PHP code.</small>', wpgb_get_local()); ?>
			<?php } else { ?>
			<h3 id="support"><img src="<?php echo wpgb_getimagefilename('support.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Support', wpgb_get_local()); ?></h3>
				<div class="support">
				<?php echo wpgb_getsupportinfo(wpgb_get_slug(), wpgb_get_local()); ?>
				<small><?php _e('Disclaimer: This plugin is not affiliated with or endorsed by ShareASale, StudioPress or Copyblogger Media.', wpgb_get_local()); ?></small>
				</div>
			<?php } ?>
			</form>
		</div>
		<?php }
		
	// shortcode for posts and pages
	add_shortcode('wp-genesis-box', 'genesis_aff_box');
	// one function for shortcode and PHP
	function genesis_aff_box($atts, $content = null) {
		// get parameters
		extract(shortcode_atts(wpgb_shortcode_defaults(), $atts));
		// plugin is enabled/disabled from settings page only
		$options = wpgb_getpluginoptions();
		if (!empty($options)) {
			$enabled = (bool)$options[WPGB_DEFAULT_ENABLED_NAME];
		} else {
			$enabled = WPGB_DEFAULT_ENABLED;
		}

		$output = '';
		
		// ******************************
		// derive shortcode values from constants
		// ******************************
		if ($enabled) {
			$temp_url = constant('WPGB_DEFAULT_URL_NAME');
			$affiliate_url = $$temp_url;
			$temp_nofollow = constant('WPGB_DEFAULT_NOFOLLOW_NAME');
			$nofollow = $$temp_nofollow;
			$temp_window = constant('WPGB_DEFAULT_NEWWINDOW_NAME');
			$opennewwindow = $$temp_window;
			$temp_show = constant('WPGB_DEFAULT_SHOW_NAME');
			$show = $$temp_show;
			$temp_rounded = constant('WPGB_DEFAULT_ROUNDED_NAME');
			$rounded = $$temp_rounded;
			$temp_image = constant('WPGB_DEFAULT_IMAGE_NAME');
			$img = $$temp_image;
			$temp_nonloggedonly = constant('WPGB_DEFAULT_NONLOGGEDIN_NAME');
			$nonloggedonly = $$temp_nonloggedonly;
			$temp_showfulltext = constant('WPGB_DEFAULT_USE_EXTENDED_TEXT_NAME');
			$showfulltext = $$temp_showfulltext;
		}
		// ******************************
		// sanitize user input
		// ******************************
		if ($enabled) {
			$affiliate_url = esc_url($affiliate_url);
			$rounded = (bool)$rounded;
			$nofollow = (bool)$nofollow;
			$opennewwindow = (bool)$opennewwindow;
			$show = (bool)$show;
			$img = sanitize_text_field($img);
			$nonloggedonly = (bool)$nonloggedonly;
			$showfulltext = (bool)$showfulltext;
			
			// allow alternate parameter names for affurl
			if (!empty($atts['url'])) {
				$affiliate_url = esc_url($atts['url']);
			} elseif (!empty($atts['link'])) {
				$affiliate_url = esc_url($atts['link']);
			} elseif (!empty($atts['href'])) {
				$affiliate_url = esc_url($atts['href']);
			}
		}
		// ******************************
		// check for parameters, then settings, then defaults
		// ******************************
		if ($enabled) {
			// check for overridden parameters, if nonexistent then get from DB
			if ($affiliate_url === WPGB_DEFAULT_URL) { // no url passed to function, try settings page
				$affiliate_url = $options[WPGB_DEFAULT_URL_NAME];
				if (($affiliate_url === WPGB_DEFAULT_URL) || ($affiliate_url === false)) { // no url on settings page either
					$enabled = false;
					$output = '<!-- ' . WPGB_PLUGIN_NAME . ': ' . __('plugin is disabled. Either you did not pass a necessary setting to the plugin, or did not configure a default. Check Settings page.', wpgb_get_local()) . ' -->';
				}
			}
			
			if ($enabled) { // save some cycles if the plugin was disabled above
				$rounded = wpgb_setupvar($rounded, WPGB_ROUNDED, WPGB_DEFAULT_ROUNDED_NAME, $options);
				$nofollow = wpgb_setupvar($nofollow, WPGB_NOFOLLOW, WPGB_DEFAULT_NOFOLLOW_NAME, $options);
				$img = wpgb_setupvar($img, WPGB_DEFAULT_IMAGE, WPGB_DEFAULT_IMAGE_NAME, $options);
				$opennewwindow = wpgb_setupvar($opennewwindow, WPGB_DEFAULT_NEWWINDOW, WPGB_DEFAULT_NEWWINDOW_NAME, $options);
				$nonloggedonly = wpgb_setupvar($nonloggedonly, WPGB_DEFAULT_NONLOGGEDIN, WPGB_DEFAULT_NONLOGGEDIN_NAME, $options);
				$showfulltext = wpgb_setupvar($showfulltext, WPGB_DEFAULT_USE_EXTENDED_TEXT, WPGB_DEFAULT_USE_EXTENDED_TEXT_NAME, $options);
			}
		} // end enabled check

		// ******************************
		// do some actual work
		// ******************************
		if ($enabled) {
			if (is_user_logged_in() && $nonloggedonly) {
				// user is logged on but we don't want to show it to logged in users
				$output = '<!-- ' . WPGB_PLUGIN_NAME . ': ' . __('Set to show to non-logged-in users only, and current user is logged in.', wpgb_get_local()) . ' -->';
			} else {
				// enqueue CSS only on pages with shortcode
				wp_genesis_box_styles();

				if ($content) {
					$text = wp_kses_post(force_balance_tags($content));
				} else {
					$text = '<p>' . __('Genesis empowers you to quickly and easily build incredible websites with WordPress.', wpgb_get_local());
					$text .= __(' Whether you\'re a novice or advanced developer, Genesis provides the secure and search-engine-optimized foundation that takes WordPress to places you never thought it could go.', wpgb_get_local());
					$text .= __(' It\'s that simple', wpgb_get_local()) . ' - <a' . ($opennewwindow ? ' onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;" ' : ' ') . ($nofollow ? 'rel="nofollow" ' : ' ') . 'href="' . $affiliate_url . '">' . __('start using Genesis now!', wpgb_get_local()) . '</a></p>';
					if ($showfulltext) {
						$text .= '<p>' . __('Take advantage of the 6 default layout options, comprehensive SEO settings, rock-solid security, flexible theme options, cool custom widgets, custom design hooks, and a huge selection of child themes ("skins") that make your site look the way you want it to.', wpgb_get_local());
						$text .= __(' With automatic theme updates and world-class support included,', wpgb_get_local()) . ' <a' . ($opennewwindow ? ' onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;" ' : ' ') . ($nofollow ? 'rel="nofollow" ' : ' ') . 'href="' . $affiliate_url . '">' . __('Genesis', wpgb_get_local()) . '</a> ' . __(' is the smart choice for your WordPress website or blog.', wpgb_get_local()) . '</p>';
					}
				}

				// calculate image url
				$images = explode(",", WPGB_AVAILABLE_IMAGES);
				if (!in_array($img, $images)) {
					$img = $images[$options[WPGB_DEFAULT_IMAGE_NAME]];
					if (!$img) { $img = WPGB_DEFAULT_IMAGE; }
				}
				$imageurl = wpgb_getimagefilename($img . '.png');
				$imagedata = getimagesize($imageurl);
				if (($sitename = get_bloginfo('name')) == false) {
					$sitename = __('This website', wpgb_get_local());
				}
				$output = '<div id="genesis-box"' . ($rounded ? ' class="wpgb-rounded-corners"' : '') . '>';
				$output .= '<h3>' . $sitename . __(' runs on the Genesis framework', wpgb_get_local()) . '</h3>';
				$output .= '<a' . ($opennewwindow ? ' onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;" ' : ' ') . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="' . $affiliate_url . '">';
				$output .= '<img class="alignright" src="' . $imageurl . '" alt="' . __('Genesis Framework', wpgb_get_local()) . '" title="' . __('Get the Genesis Theme Framework for WordPress', wpgb_get_local()) . '" width="' . $imagedata[0] . '" height="' . $imagedata[1] . '" /></a>';
				$output .= do_shortcode($text) . '</div>';
			}
		} else { // plugin disabled
			$output = '<!-- ' . WPGB_PLUGIN_NAME . ': ' . __('plugin is disabled. Either you did not pass a necessary setting to the plugin, or did not configure a default. Check Settings page.', wpgb_get_local()) . ' -->';
		}
		if ($show) {
			echo $output;
		} else {
			return $output;
		}
	} // end shortcode function
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
		if (current_user_can(WPGB_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') {
				if (isset($_GET['page'])) {
					if ($_GET['page'] == wpgb_get_slug()) { // we are on this plugin's settings page
						$options = wpgb_getpluginoptions();
						if (!empty($options)) {
							$enabled = (bool)$options[WPGB_DEFAULT_ENABLED_NAME];
							$affiliate_url = $options[WPGB_DEFAULT_URL_NAME];
							if (!$enabled) {
								echo '<div id="message" class="error">' . WPGB_PLUGIN_NAME . ' ' . __('is currently disabled.', wpgb_get_local()) . '</div>';
							}
							if (($affiliate_url === WPGB_DEFAULT_URL) || ($affiliate_url === false)) {
								echo '<div id="message" class="updated">' . __('WARNING: Affiliate URL missing. Please enter it below, or pass it to the shortcode or function, otherwise the plugin won\'t do anything.', wpgb_get_local()) . '</div>';
							}
						}
					}
				}
			} // end page check
		} // end privilege check
	} // end admin msgs function
	// add admin CSS if we are on the plugin options page
	add_action('admin_head', 'insert_wpgb_admin_css');
	function insert_wpgb_admin_css() {
		global $pagenow;
		if (current_user_can(WPGB_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') {
				if (isset($_GET['page'])) {
					if ($_GET['page'] == wpgb_get_slug()) { // we are on this plugin's settings page
						wpgb_admin_styles();
					}
				}
			}
		}
	}
	// add helpful links to plugin page next to plugin name
	// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
	// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpgb_plugin_settings_link');
	add_filter('plugin_row_meta', 'wpgb_meta_links', 10, 2);
	
	function wpgb_plugin_settings_link($links) {
		return wpgb_settingslink($links, wpgb_get_slug(), wpgb_get_local());
	}
	function wpgb_meta_links($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$links = array_merge($links,
			array(
				sprintf(__('<a href="http://wordpress.org/support/plugin/%s">Support</a>', wpgb_get_local()), wpgb_get_slug()),
				sprintf(__('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', wpgb_get_local()), wpgb_get_slug()),
				sprintf(__('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a>', wpgb_get_local()), wpgb_get_slug())
			));
		}
		return $links;	
	}
	// enqueue/register the plugin CSS file
	function wp_genesis_box_styles() {
		wp_enqueue_style('wp_genesis_box_style');
	}
	function register_wp_genesis_box_style() {
		wp_register_style('wp_genesis_box_style', 
			plugins_url(wpgb_get_path() . '/css/wp-genesis-box.css'), 
			array(), 
			WPGB_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/wp-genesis-box.css')),
			'all' );
	}
	// enqueue/register the admin CSS file
	function wpgb_admin_styles() {
		wp_enqueue_style('wpgb_admin_style');
	}
	function register_wpgb_admin_style() {
		wp_register_style( 'wpgb_admin_style',
			plugins_url(wpgb_get_path() . '/css/admin.css'),
			array(),
			WPGB_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all' );
	}
	// enqueue/register the admin JS file
	add_action('admin_enqueue_scripts', 'wpgb_ed_buttons');
	function wpgb_ed_buttons($hook) {
		if (($hook == 'post-new.php') || ($hook == 'post.php')) {
			wp_enqueue_script('wpgb_add_editor_button');
		}
	}
	function register_wpgb_admin_script() {
		wp_register_script('wpgb_add_editor_button', 
			plugins_url(wpgb_get_path() . '/js/editor_button.js'), 
			array('quicktags'), 
			WPGB_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/js/editor_button.js')), 
			true);
	}
	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'wpgb_activate');
	function wpgb_activate() {
		$options = wpgb_getpluginoptions();
		update_option(wpgb_get_option(), $options);

		// delete option when plugin is uninstalled
		register_uninstall_hook(__FILE__, 'uninstall_wpgb_plugin');
	}
	function uninstall_wpgb_plugin() {
		delete_option(wpgb_get_option());
	}
		
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function wpgb_getpluginoptions() {
		return get_option(wpgb_get_option(), array(
			WPGB_DEFAULT_ENABLED_NAME => WPGB_DEFAULT_ENABLED, 
			WPGB_DEFAULT_URL_NAME => WPGB_DEFAULT_URL, 
			WPGB_DEFAULT_ROUNDED_NAME => WPGB_ROUNDED, 
			WPGB_DEFAULT_NOFOLLOW_NAME => WPGB_NOFOLLOW, 
			WPGB_DEFAULT_IMAGE_NAME => WPGB_DEFAULT_IMAGE, 
			WPGB_DEFAULT_AUTO_INSERT_NAME => WPGB_DEFAULT_AUTO_INSERT, 
			WPGB_DEFAULT_NEWWINDOW_NAME => WPGB_DEFAULT_NEWWINDOW,
			WPGB_DEFAULT_NONLOGGEDIN_NAME => WPGB_DEFAULT_NONLOGGEDIN,
			WPGB_DEFAULT_USE_EXTENDED_TEXT_NAME => WPGB_DEFAULT_USE_EXTENDED_TEXT
			));
	}
	// function to return shortcode defaults
	function wpgb_shortcode_defaults() {
		return array(
			WPGB_DEFAULT_URL_NAME => WPGB_DEFAULT_URL, 
			WPGB_DEFAULT_ROUNDED_NAME => WPGB_ROUNDED, 
			WPGB_DEFAULT_NOFOLLOW_NAME => WPGB_NOFOLLOW, 
			WPGB_DEFAULT_IMAGE_NAME => WPGB_DEFAULT_IMAGE, 
			WPGB_DEFAULT_NEWWINDOW_NAME => WPGB_DEFAULT_NEWWINDOW, 
			WPGB_DEFAULT_SHOW_NAME => WPGB_DEFAULT_SHOW,
			WPGB_DEFAULT_NONLOGGEDIN_NAME => WPGB_DEFAULT_NONLOGGEDIN,
			WPGB_DEFAULT_USE_EXTENDED_TEXT_NAME => WPGB_DEFAULT_USE_EXTENDED_TEXT
			);
	}
	// function to return parameter status (required or not)
	function wpgb_required_parameters() {
		return array(
			true, 
			false,
			false,
			false,
			false,
			false,
			false,
			true
		);
	}
	
	// encapsulate these and call them throughout the plugin instead of hardcoding the constants everywhere
	function wpgb_get_slug() { return WPGB_SLUG; }
	function wpgb_get_local() { return WPGB_LOCAL; }
	function wpgb_get_option() { return WPGB_OPTION; }
	function wpgb_get_path() { return WPGB_PATH; }
	
	function wpgb_settingslink($linklist, $slugname = '', $localname = '') {
		$settings_link = sprintf( __('<a href="options-general.php?page=%s">Settings</a>', $localname), $slugname);
		array_unshift($linklist, $settings_link);
		return $linklist;
	}
	function wpgb_setupvar($var, $defaultvalue, $defaultvarname, $optionsarr) {
		if ($var == $defaultvalue) {
			$var = $optionsarr[$defaultvarname];
			if (!$var) {
				$var = $defaultvalue;
			}
		}
		return $var;
	}
	function wpgb_getsupportinfo($slugname = '', $localname = '') {
		$output = __('Do you need help with this plugin? Check out the following resources:', $localname);
		$output .= '<ol>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/support/plugin/%s">Support Forum</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://www.jimmyscode.com/wordpress/%s">Plugin Homepage / Demo</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/developers/">Development</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/changelog/">Changelog</a><br />', $localname), $slugname) . '</li>';
		$output .= '</ol>';
		
		$output .= sprintf( __('If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/%s/">rate it on WordPress.org</a>', $localname), $slugname);
		$output .= sprintf( __(' and click the <a href="http://wordpress.org/plugins/%s/#compatibility">Works</a> button. ', $localname), $slugname);
		$output .= '<br /><br /><br />';
		$output .= __('Your donations encourage further development and support. ', $localname);
		$output .= '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Support this plugin" width="92" height="26" /></a>';
		$output .= '<br /><br />';
		return $output;
	}
	
	function wpgb_parameters_table($localname = '', $sc_defaults, $reqparms) {
	  $output = '<table class="widefat">';
		$output .= '<thead><tr>';
		$output .= '<th title="' . __('The name of the parameter', $localname) . '"><strong>' . __('Parameter Name', $localname) . '</strong></th>';
		$output .= '<th title="' . __('Is this parameter required?', $localname) . '"><strong>' . __('Is Required?', $localname) . '</strong></th>';
		$output .= '<th title="' . __('What data type this parameter accepts', $localname) . '"><strong>' . __('Data Type', $localname) . '</strong></th>';
		$output .= '<th title="' . __('What, if any, is the default if no value is specified', $localname) . '"><strong>' . __('Default Value', $localname) . '</strong></th>';
		$output .= '</tr></thead>';
		$output .= '<tbody>';
		
		$plugin_defaults_keys = array_keys($sc_defaults);
		$plugin_defaults_values = array_values($sc_defaults);
		$required = $reqparms;
		for($i = 0; $i < count($plugin_defaults_keys); $i++) {
			$output .= '<tr>';
			$output .= '<td><strong>' . $plugin_defaults_keys[$i] . '</strong></td>';
			$output .= '<td>';
			
			if ($required[$i] === true) {
				$output .= '<strong>';
				$output .= __('Yes', $localname);
				$output .= '</strong>';
			} else {
				$output .= __('No', $localname);
			}
			
			$output .= '</td>';
			$output .= '<td>' . gettype($plugin_defaults_values[$i]) . '</td>';
			$output .= '<td>';
			
			if ($plugin_defaults_values[$i] === true) {
				$output .= '<strong>';
				$output .= __('true', $localname);
				$output .= '</strong>';
			} elseif ($plugin_defaults_values[$i] === false) {
				$output .= __('false', $localname);
			} elseif ($plugin_defaults_values[$i] === '') {
				$output .= '<em>';
				$output .= __('this value is blank by default', $localname);
				$output .= '</em>';
			} elseif (is_numeric($plugin_defaults_values[$i])) {
				$output .= $plugin_defaults_values[$i];
			} else { 
				$output .= '"' . $plugin_defaults_values[$i] . '"';
			} 
			$output .= '</td>';
			$output .= '</tr>';
		}
		$output .= '</tbody>';
		$output .= '</table>';
		
		return $output;
	}
	function wpgb_get_example_shortcode($shortcodename = '', $sc_defaults, $localname = '') {
		$output = '<pre style="background:#FFF">[' . $shortcodename . ' ';
		
		$plugin_defaults_keys = array_keys($sc_defaults);
		$plugin_defaults_values = array_values($sc_defaults);
		
		for($i = 0; $i < count($plugin_defaults_keys); $i++) {
			if ($plugin_defaults_keys[$i] !== 'show') {
				if (gettype($plugin_defaults_values[$i]) === 'string') {
					$output .= '<strong>' . $plugin_defaults_keys[$i] . '</strong>=\'' . $plugin_defaults_values[$i] . '\'';
				} elseif (gettype($plugin_defaults_values[$i]) === 'boolean') {
					$output .= '<strong>' . $plugin_defaults_keys[$i] . '</strong>=' . ($plugin_defaults_values[$i] == false ? 'false' : 'true');
				} else {
					$output .= '<strong>' . $plugin_defaults_keys[$i] . '</strong>=' . $plugin_defaults_values[$i];
				}
				if ($i < count($plugin_defaults_keys) - 2) {
					$output .= ' ';
				}
			}
		}
		$output .= ']</pre>';
		
		return $output;
	}
	function wpgb_get_example_php_code($shortcodename = '', $internalfunctionname = '', $sc_defaults) {
		
		$plugin_defaults_keys = array_keys($sc_defaults);
		$plugin_defaults_values = array_values($sc_defaults);
		
		$output = '<pre style="background:#FFF">';
		$output .= 'if (shortcode_exists(\'' . $shortcodename . '\')) {<br />';
		$output .= '  $atts = array(<br />';
		for($i = 0; $i < count($plugin_defaults_keys); $i++) {
			$output .= '    \'' . $plugin_defaults_keys[$i] . '\' => ';
			if (gettype($plugin_defaults_values[$i]) === 'string') {
				$output .= '\'' . $plugin_defaults_values[$i] . '\'';
			} elseif (gettype($plugin_defaults_values[$i]) === 'boolean') {
				$output .= ($plugin_defaults_values[$i] == false ? 'false' : 'true');
			} else {
				$output .= $plugin_defaults_values[$i];
			}
			if ($i < count($plugin_defaults_keys) - 1) {
				$output .= ', <br />';
			}
		}
		$output .= '<br />  );<br />';
		$output .= '   echo ' . $internalfunctionname . '($atts);';
		$output .= '<br />}';
		$output .= '</pre>';
		return $output;	
	}
	function wpgb_checkifset($optionname, $optiondefault, $optionsarr) {
		return (isset($optionsarr[$optionname]) ? $optionsarr[$optionname] : $optiondefault);
	}
	function wpgb_getlinebreak() {
	  echo '<tr valign="top"><td colspan="2"></td></tr>';
	}
	function wpgb_explanationrow($msg = '') {
		echo '<tr valign="top"><td></td><td><em>' . $msg . '</em></td></tr>';
	}
	function wpgb_getimagefilename($fname = '') {
		return plugins_url(wpgb_get_path() . '/images/' . $fname);
	}
?>