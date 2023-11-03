jQuery(document).ready(function($) {
    $('#upload-media-button').click(function(e) {
        e.preventDefault();
        var resource_url = $('#resource-url');
        var media_frame;

        // If the media frame already exists, reopen it.
        if (media_frame) {
            media_frame.open();
            return;
        }

        // Create the media frame.
        media_frame = wp.media({
            title: 'Select an Item from the Media Library',
            button: { text: 'Use this item' },
            multiple: false
        });

        // When an item is selected, run a callback.
        media_frame.on('select', function() {
            var attachment = media_frame.state().get('selection').first().toJSON();
            resource_url.val(attachment.url);
        });

        // Open the media frame.
        media_frame.open();
    });
});
