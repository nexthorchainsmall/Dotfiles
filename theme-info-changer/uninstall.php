<?php
/**
 * Uninstall handler for Theme Info Changer
 *
 * This file is executed when the plugin is deleted from the Plugins screen.
 * It removes all options created by the plugin (per-theme settings and backups).
 *
 * @package ThemeInfoChanger
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;

// Delete all options with our prefix
$like = $wpdb->esc_like('theme_info_changer_') . '%';
$options = $wpdb->get_col( $wpdb->prepare( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s", $like ) );

foreach ( $options as $option ) {
    delete_option( $option );
}

// Also remove backups
$like_backup = $wpdb->esc_like('theme_info_changer_backup_') . '%';
$backup_options = $wpdb->get_col( $wpdb->prepare( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s", $like_backup ) );
foreach ( $backup_options as $option ) {
    delete_option( $option );
}
