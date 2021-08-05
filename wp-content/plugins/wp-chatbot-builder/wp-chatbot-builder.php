<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://botmywork.com/
 * @since             1.0.2
 * @package           WP_Chatbot_Builder
 *
 * @wordpress-plugin
 * Plugin Name:       WP-Chatbot Builder
 * Plugin URI:        https://wordpress.org/plugins/wp-chatbot-builder
 * Description:       <code><strong>WP-Chatbot Builder</strong></code> enables you to add Facebook Messenger to Automate Chat and Generate Leads on your website.
 * Version:           1.0.2
 * Author:            BotMyWork
 * Author URI:        https://botmywork.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-chatbot-builder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.2 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

// define WP_CBB_VERSION.
if ( ! defined( 'WP_CBB_VERSION' ) ) {
	define( 'WP_CBB_VERSION', '1.0.2' );
}
// define WP_CBB_DIRPATH.
if ( ! defined( 'WP_CBB_DIRPATH' ) ) {
	define( 'WP_CBB_DIRPATH', plugin_dir_path( __FILE__ ) );
}
// define WP_CBB_URL.
if ( ! defined( 'WP_CBB_URL' ) ) {
	define( 'WP_CBB_URL', plugin_dir_url( __FILE__ ) );
}
// define WP_CBB_PLUGIN_FILE.
if ( ! defined( 'WP_CBB_PLUGIN_FILE' ) ) {
	define( 'WP_CBB_PLUGIN_FILE', __FILE__ );
}
// define WP_CBB_PLUGIN_BASENAME.
if ( ! defined( 'WP_CBB_PLUGIN_BASENAME' ) ) {
	define( 'WP_CBB_PLUGIN_BASENAME', plugin_basename( WP_CBB_PLUGIN_FILE ) );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-chatbot-builder-activator.php
 */
function activate_wp_cbb() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-chatbot-builder-activator.php';
	WP_Chatbot_Builder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-chatbot-builder-deactivator.php
 */
function deactivate_wp_cbb() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-chatbot-builder-deactivator.php';
	WP_Chatbot_Builder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_cbb' );
register_deactivation_hook( __FILE__, 'deactivate_wp_cbb' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-chatbot-builder.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.2
 */
function run_wp_chatbot_builder() {
	$plugin = new WP_Chatbot_Builder();
	$plugin->run();
	$GLOBALS['wcb_bmw_wcb_obj'] = $plugin;

}
run_wp_chatbot_builder();

/**
 * Function for checking if Woocommerce is active.
 *
 * @since 1.0.2
 * @name wp_cbb_woocommerce_active
 * @author support@botmywork.com
 * @link https://botmywork.com/
 */
function wp_cbb_woocommerce_active() {
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		return true;
	} else {
		return false;
	}
}
