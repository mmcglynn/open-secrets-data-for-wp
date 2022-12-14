<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.reignitionllc.com/
 * @since             1.0.0
 * @package           Open_Secrets_Data_For_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Open Secrets Data for WP
 * Plugin URI:        https://github.com/mmcglynn/open-secrets-data-for-wp
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Michael McGlynn
 * Author URI:        https://www.reignitionllc.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       open-secrets-data-for-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'OPEN_SECRETS_DATA_FOR_WP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-open-secrets-data-for-wp-activator.php
 */
function activate_open_secrets_data_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-open-secrets-data-for-wp-activator.php';
	Open_Secrets_Data_For_Wp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-open-secrets-data-for-wp-deactivator.php
 */
function deactivate_open_secrets_data_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-open-secrets-data-for-wp-deactivator.php';
	Open_Secrets_Data_For_Wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_open_secrets_data_for_wp' );
register_deactivation_hook( __FILE__, 'deactivate_open_secrets_data_for_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-open-secrets-data-for-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_open_secrets_data_for_wp() {

	$plugin = new Open_Secrets_Data_For_Wp();
	$plugin->run();

}
run_open_secrets_data_for_wp();
