<?php

/**
 * Shortcode to display a random quote
 */
function my_wp_plugin_random_quote_shortcode() {
    $args = array(
        'post_type'      => 'my_wp_plugin_quotes',
        'posts_per_page' => 1,
        'orderby'        => 'rand',
    );
    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $output = '<div class="my-wp-plugin-random-quote">';
            $output .= '<blockquote>' . get_the_content() . '</blockquote>';
            $output .= '<cite>' . get_the_title() . '</cite>';
            $output .= '</div>';
        }
        wp_reset_postdata();
        return $output;
    }
}
add_shortcode( 'my-wp-plugin-random-quote', 'my_wp_plugin_random_quote_shortcode' );

/**
 * Shortcode to display a list of quotes
 */
function my_wp_plugin_quotes_list_shortcode() {
    $args = array(
        'post_type'      => 'my_wp_plugin_quotes',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    );
    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        $output = '<ul class="my-wp-plugin-quotes-list">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $output .= '<li>';
            $output .= '<blockquote>' . get_the_content() . '</blockquote>';
            $output .= '<cite>' . get_the_title() . '</cite>';
            $output .= '</li>';
        }
        $output .= '</ul>';
        wp_reset_postdata();
        return $output;
    }
}
add_shortcode( 'my-wp-plugin-quotes-list', 'my_wp_plugin_quotes_list_shortcode' );

