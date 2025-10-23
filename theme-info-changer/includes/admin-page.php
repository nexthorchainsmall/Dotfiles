<?php
/**
 * Admin page functionality for Theme Info Changer
 *
 * @package ThemeInfoChanger
 */

if (!defined('WPINC')) {
    die;
}

/**
 * Render the admin page
 */
function theme_info_changer_admin_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings if data has been posted
    if (isset($_POST['theme_info_changer_save'])) {
        if (
            !isset($_POST['theme_info_changer_nonce']) ||
            !wp_verify_nonce($_POST['theme_info_changer_nonce'], 'theme_info_changer_save')
        ) {
            wp_die(__('Invalid nonce specified', 'theme-info-changer'));
        }

        // Get current theme
        $current_theme = wp_get_theme();
        $stylesheet = $current_theme->get_stylesheet();

        // Get and sanitize form data
        $theme_name = isset($_POST['theme_name']) ? sanitize_text_field($_POST['theme_name']) : '';
        $theme_author = isset($_POST['theme_author']) ? sanitize_text_field($_POST['theme_author']) : '';
        $theme_screenshot = isset($_POST['theme_screenshot']) ? esc_url_raw($_POST['theme_screenshot']) : '';

        // Store settings with theme-specific keys
        update_option('theme_info_changer_' . $stylesheet . '_name', $theme_name);
        update_option('theme_info_changer_' . $stylesheet . '_author', $theme_author);
        update_option('theme_info_changer_' . $stylesheet . '_screenshot', $theme_screenshot);

        add_settings_error(
            'theme_info_changer_messages',
            'theme_info_changer_message',
            __('Settings Saved', 'theme-info-changer'),
            'updated'
        );
    }

    // Get current theme
    $current_theme = wp_get_theme();
    $stylesheet = $current_theme->get_stylesheet();

    // Get current theme-specific values
    $theme_name = get_option('theme_info_changer_' . $stylesheet . '_name', '');
    $theme_author = get_option('theme_info_changer_' . $stylesheet . '_author', '');
    $theme_screenshot = get_option('theme_info_changer_' . $stylesheet . '_screenshot', '');

    // Get current theme info
    $theme = wp_get_theme();
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <?php settings_errors('theme_info_changer_messages'); ?>

        <form method="post" action="">
            <?php wp_nonce_field('theme_info_changer_save', 'theme_info_changer_nonce'); ?>
            
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="theme_name"><?php _e('Theme Name', 'theme-info-changer'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="theme_name" name="theme_name" 
                               value="<?php echo esc_attr($theme_name); ?>" 
                               class="regular-text"
                               placeholder="<?php echo esc_attr($theme->get('Name')); ?>">
                        <p class="description">
                            <?php _e('Current theme name:', 'theme-info-changer'); ?> 
                            <strong><?php echo esc_html($theme->get('Name')); ?></strong>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="theme_author"><?php _e('Theme Author', 'theme-info-changer'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="theme_author" name="theme_author" 
                               value="<?php echo esc_attr($theme_author); ?>" 
                               class="regular-text"
                               placeholder="<?php echo esc_attr($theme->get('Author')); ?>">
                        <p class="description">
                            <?php _e('Current theme author:', 'theme-info-changer'); ?> 
                            <strong><?php echo esc_html($theme->get('Author')); ?></strong>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="theme_screenshot"><?php _e('Theme Screenshot', 'theme-info-changer'); ?></label>
                    </th>
                    <td>
                        <div class="theme-screenshot-preview">
                            <?php if ($theme_screenshot): ?>
                                <img src="<?php echo esc_url($theme_screenshot); ?>" style="max-width: 300px; height: auto;">
                            <?php endif; ?>
                        </div>
                        <input type="hidden" id="theme_screenshot" name="theme_screenshot" 
                               value="<?php echo esc_attr($theme_screenshot); ?>">
                        <button type="button" class="button" id="upload_screenshot_button">
                            <?php _e('Choose Image', 'theme-info-changer'); ?>
                        </button>
                        <?php if ($theme_screenshot): ?>
                            <button type="button" class="button" id="remove_screenshot_button">
                                <?php _e('Remove Image', 'theme-info-changer'); ?>
                            </button>
                        <?php endif; ?>
                        <p class="description">
                            <?php _e('Recommended size: 1200x900 pixels', 'theme-info-changer'); ?>
                        </p>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="theme_info_changer_save" 
                       class="button button-primary" 
                       value="<?php esc_attr_e('Save Changes', 'theme-info-changer'); ?>">
            </p>
        </form>
    </div>
    <?php
}