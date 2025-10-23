<?php
/**
 * Theme info filter functionality
 *
 * @package ThemeInfoChanger
 */

if (!defined('WPINC')) {
    die;
}

/**
 * Class Theme_Info_Changer_Filters
 */
class Theme_Info_Changer_Filters {

    /**
     * Initialize filters
     */
    public static function init() {
        add_filter('wp_prepare_themes_for_js', array(__CLASS__, 'filter_themes_for_js'));
        add_filter('wp_get_attachment_image_src', array(__CLASS__, 'filter_theme_screenshot'), 10, 4);
        add_filter('get_post_metadata', array(__CLASS__, 'filter_theme_screenshot_metadata'), 10, 4);
    }

    /**
     * Filter theme screenshot image source
     *
     * @param array|false  $image         Array of image data, or boolean false if no image is available.
     * @param int         $attachment_id  Image attachment ID.
     * @param string|array $size          Requested size of image.
     * @param bool        $icon          Whether the image should be treated as an icon.
     *
     * @return array|false
     */
    public static function filter_theme_screenshot($image, $attachment_id, $size, $icon) {
        // Get current theme
        $current_theme = wp_get_theme();
        $stylesheet = $current_theme->get_stylesheet();
        
        // Get custom screenshot URL
        $custom_screenshot = get_option('theme_info_changer_' . $stylesheet . '_screenshot');
        
        if (!empty($custom_screenshot) && $size === 'theme-preview') {
            return array(
                $custom_screenshot,
                1200, // Width
                900,  // Height
                false
            );
        }
        
        return $image;
    }

    /**
     * Filter theme screenshot metadata
     *
     * @param mixed  $value     The value to return, either a single metadata value or an array of values.
     * @param int    $post_id   Post ID.
     * @param string $meta_key  Meta key.
     * @param bool   $single    Whether to return only the first value of the specified $meta_key.
     *
     * @return mixed
     */
    public static function filter_theme_screenshot_metadata($value, $post_id, $meta_key, $single) {
        $current_theme = wp_get_theme();
        $stylesheet = $current_theme->get_stylesheet();
        $custom_screenshot = get_option('theme_info_changer_' . $stylesheet . '_screenshot');
        
        if (!empty($custom_screenshot) && $meta_key === '_wp_attachment_metadata') {
            return array(
                'width' => 1200,
                'height' => 900,
                'file' => basename($custom_screenshot),
                'sizes' => array(
                    'theme-preview' => array(
                        'file' => basename($custom_screenshot),
                        'width' => 1200,
                        'height' => 900,
                        'mime-type' => 'image/png'
                    )
                )
            );
        }
        
        return $value;
    }

/**
 * Filter themes data for JS to modify info only for active theme
 *
 * @param array $prepared_themes Array of theme data.
 *
 * @return array
 */
public static function filter_themes_for_js($prepared_themes) {
    // Get current active theme
    $active_theme = wp_get_theme();
    $stylesheet = $active_theme->get_stylesheet();
    
    // Get the theme-specific options using the theme stylesheet as part of the option name
    $custom_name = get_option('theme_info_changer_' . $stylesheet . '_name');
    $custom_author = get_option('theme_info_changer_' . $stylesheet . '_author');
    $custom_screenshot = get_option('theme_info_changer_' . $stylesheet . '_screenshot');

    if (isset($prepared_themes[$stylesheet])) {
        // Only modify if we have custom values set for this specific theme
        if (!empty($custom_name)) {
            $prepared_themes[$stylesheet]['name'] = $custom_name;
        }

        if (!empty($custom_author)) {
            $prepared_themes[$stylesheet]['author'] = $custom_author;
            $prepared_themes[$stylesheet]['authorAndUri'] = $custom_author;
        }

        if (!empty($custom_screenshot)) {
            $prepared_themes[$stylesheet]['screenshot'] = array($custom_screenshot);
        }
    }        return $prepared_themes;
    }
}