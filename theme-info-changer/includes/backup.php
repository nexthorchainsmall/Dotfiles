<?php
// Add this to the main plugin file (theme-info-changer.php)

register_activation_hook(__FILE__, 'theme_info_changer_activate');
register_deactivation_hook(__FILE__, 'theme_info_changer_deactivate');

/**
 * Plugin activation hook
 */
function theme_info_changer_activate() {
    // Create backup of any existing theme info
    $themes = wp_get_themes();
    foreach ($themes as $stylesheet => $theme) {
        $existing_data = array(
            'name' => get_option('theme_info_changer_' . $stylesheet . '_name'),
            'author' => get_option('theme_info_changer_' . $stylesheet . '_author'),
            'screenshot' => get_option('theme_info_changer_' . $stylesheet . '_screenshot')
        );
        if (!empty(array_filter($existing_data))) {
            update_option('theme_info_changer_backup_' . $stylesheet, $existing_data);
        }
    }
}

/**
 * Plugin deactivation hook
 */
function theme_info_changer_deactivate() {
    // Optionally restore from backup
    $themes = wp_get_themes();
    foreach ($themes as $stylesheet => $theme) {
        $backup = get_option('theme_info_changer_backup_' . $stylesheet);
        if ($backup) {
            if (!empty($backup['name'])) {
                update_option('theme_info_changer_' . $stylesheet . '_name', $backup['name']);
            }
            if (!empty($backup['author'])) {
                update_option('theme_info_changer_' . $stylesheet . '_author', $backup['author']);
            }
            if (!empty($backup['screenshot'])) {
                update_option('theme_info_changer_' . $stylesheet . '_screenshot', $backup['screenshot']);
            }
        }
    }
}