jQuery(document).ready(function($) {
    var frame;
    
    $('#upload_screenshot_button').on('click', function(e) {
        e.preventDefault();

        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            title: themeInfoChanger.title,
            button: {
                text: themeInfoChanger.button
            },
            library: {
                type: 'image'
            },
            multiple: false
        });

        // When an image is selected in the media frame...
        frame.on('select', function() {
            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();
            
            // Set the image URL in the hidden input
            $('#theme_screenshot').val(attachment.url);
            
            // Update the preview image
            $('.theme-screenshot-preview').html(
                $('<img>', {
                    src: attachment.url,
                    style: 'max-width: 300px; height: auto;',
                    'data-timestamp': new Date().getTime()
                })
            );
            
            // Show remove button
            $('#remove_screenshot_button').show();

            // Force theme screenshot refresh
            refreshThemeScreenshot();
        });

        // Finally, open the modal on click
        frame.open();
    });

    // Handle removing the image
    $('#remove_screenshot_button').on('click', function(e) {
        e.preventDefault();
        
        // Clear the hidden input
        $('#theme_screenshot').val('');
        
        // Clear the preview
        $('.theme-screenshot-preview').empty();
        
        // Hide remove button
        $(this).hide();

        // Force theme screenshot refresh
        refreshThemeScreenshot();
    });

    // Function to refresh theme screenshot in the Themes page
    function refreshThemeScreenshot() {
        if (wp.customize && wp.customize.Themes && wp.customize.Themes.data) {
            // Force theme browser to refresh
            wp.customize.Themes.data.themes.trigger('change');
        } else {
            // For regular themes page, reload after a short delay
            setTimeout(function() {
                window.location.reload();
            }, 500);
        }
    }

    // Force initial refresh if needed
    if ($('#theme_screenshot').val()) {
        refreshThemeScreenshot();
    }
});