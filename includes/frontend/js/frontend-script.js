/**
 * This file is responsible for all the frontend JavaScript functionality
 *
 * @package My_Wordpress_Plugin
 */

jQuery(document).ready(function($) {

  // Execute code when the DOM is fully loaded
  console.log('Frontend script loaded!');

  // Add click event to button
  $('.my-wp-plugin-button').on('click', function(e) {
    e.preventDefault();
    console.log('Button clicked!');

    // Send an AJAX request to the server
    $.ajax({
      url: my_wp_plugin_ajax_object.ajax_url,
      type: 'POST',
      data: {
        action: 'my_wp_plugin_ajax_function',
        message: 'Hello, world!'
      },
      success: function(response) {
        console.log(response);
      }
    });

  });
  
  // Add form submit event
  $('#my-wp-plugin-form').on('submit', function(e) {
    e.preventDefault();
    console.log('Form submitted!');

    // Send an AJAX request to the server
    $.ajax({
      url: my_wp_plugin_ajax_object.ajax_url,
      type: 'POST',
      data: {
        action: 'my_wp_plugin_ajax_function',
        name: $('#my-wp-plugin-form-name').val(),
        email: $('#my-wp-plugin-form-email').val()
      },
      success: function(response) {
        console.log(response);
      }
    });
  });

  // Use WordPress REST API to get data from the server
  $.getJSON(my_wp_plugin_ajax_object.rest_url + 'wp/v2/posts', function(data) {
    console.log(data);
  });

});
