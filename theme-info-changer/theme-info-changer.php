<?php
/**
 * Theme Info Changer
 *
 * @package           ThemeInfoChanger
 * @author            WordPress Developer
 * @copyright         2025 WordPress Developer
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Theme Info Changer
 * Plugin URI:        https://wordpress.org/plugins/theme-info-changer
 * Description:       Safely change your active theme's name, author, and screenshot from the admin dashboard — without editing theme files. Improve branding, update screenshots, and customize theme metadata for client sites and multisite networks.
 * Version:           1.1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            WordPress Developer
 * Author URI:        https://example.com
 * Text Domain:       theme-info-changer
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('THEME_INFO_CHANGER_VERSION', '1.1.0');
define('THEME_INFO_CHANGER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('THEME_INFO_CHANGER_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Clean up plugin options on deactivation
 */
function theme_info_changer_deactivate() {
    // Get all WordPress options
    global $wpdb;
    $options = $wpdb->get_results(
        "SELECT option_name FROM {$wpdb->options} 
        WHERE option_name LIKE 'theme_info_changer_%'"
    );

    // Remove all plugin options
    foreach ($options as $option) {
        delete_option($option->option_name);
    }
}
register_deactivation_hook(__FILE__, 'theme_info_changer_deactivate');

// Include required files
require_once THEME_INFO_CHANGER_PLUGIN_DIR . 'includes/admin-page.php';
require_once THEME_INFO_CHANGER_PLUGIN_DIR . 'includes/filters.php';

/**
 * Initialize the plugin
 */
function theme_info_changer_init() {
    // Initialize filters
    Theme_Info_Changer_Filters::init();
}
add_action('init', 'theme_info_changer_init');

/**
 * Register the admin page
 */
function theme_info_changer_admin_menu() {
    add_theme_page(
        __('Theme Info Changer', 'theme-info-changer'),
        __('Theme Info Changer', 'theme-info-changer'),
        'manage_options',
        'theme-info-changer',
        'theme_info_changer_admin_page'
    );
}
add_action('admin_menu', 'theme_info_changer_admin_menu');

/**
 * Enqueue admin scripts and styles
 */
function theme_info_changer_admin_enqueue_scripts($hook) {
    if ('appearance_page_theme-info-changer' !== $hook) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script(
        'theme-info-changer-admin',
        THEME_INFO_CHANGER_PLUGIN_URL . 'assets/admin.js',
        array('jquery', 'customize-models'), // Add customize-models dependency
        THEME_INFO_CHANGER_VERSION,
        true
    );

    // Get current theme
    $theme = wp_get_theme();
    
    wp_localize_script('theme-info-changer-admin', 'themeInfoChanger', array(
        'title' => __('Choose Theme Screenshot', 'theme-info-changer'),
        'button' => __('Use this image', 'theme-info-changer'),
        'stylesheet' => $theme->get_stylesheet(),
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('theme_info_changer_refresh'),
    ));
}
add_action('admin_enqueue_scripts', 'theme_info_changer_admin_enqueue_scripts');