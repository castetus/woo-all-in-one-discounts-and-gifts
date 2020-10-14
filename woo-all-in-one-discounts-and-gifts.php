<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce All-in-One Discounts & Gifts
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_woodiscount() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woodiscounts-activator.php';
	Woo_Discounts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_woodiscount() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woodiscounts-deactivator.php';
	Woo_Discounts_Deactivator::deactivate();
}

function uninstall_woodiscounts(){
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woodiscounts-uninstaller.php';
	Woo_Discounts_Uninstaller::uninstall();
}

register_activation_hook( __FILE__, 'activate_woodiscount' );
register_deactivation_hook( __FILE__, 'deactivate_woodiscount' );
register_uninstall_hook(__FILE__, 'uninstall_woodiscounts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woodiscount.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woodiscount() {

	$plugin = new Woo_Discount();
	$plugin->run();

}
run_woodiscount();
