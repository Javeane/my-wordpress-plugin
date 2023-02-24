jQuery(document).ready(function($) {

    // Handle avatar upload form submission
    $('form#my-wp-plugin-avatar-upload-form').submit(function(event) {
        event.preventDefault();
        var form = $(this);
        var fileInput = $('input#my-wp-plugin-avatar-file');
        var file = fileInput[0].files[0];
        var formData = new FormData();
        formData.append('file', file);
        formData.append('nonce', form.data('nonce'));

        // Show loading spinner
        form.find('.my-wp-plugin-avatar-upload-spinner').show();

        // Send AJAX request
        $.ajax({
            url: my_wp_plugin_ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Update user avatar
                    var img = form.siblings('img.my-wp-plugin-avatar');
                    img.attr('src', response.file_url);

                    // Hide upload form
                    form.slideUp();

                    // Show success message
                    form.siblings('.my-wp-plugin-avatar-success-message').slideDown();
                } else {
                    // Show error message
                    form.siblings('.my-wp-plugin-avatar-error-message').slideDown();
                }
            },
            error: function() {
                // Show error message
                form.siblings('.my-wp-plugin-avatar-error-message').slideDown();
            },
            complete: function() {
                // Hide loading spinner
                form.find('.my-wp-plugin-avatar-upload-spinner').hide();
            }
        });
    });

});
