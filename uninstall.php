// http://codex.wordpress.org/Function_Reference/register_uninstall_hook
// if uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit();
}
delete_option('wp_genesis_box');