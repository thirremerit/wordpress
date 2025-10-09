<?php
// Enqueue theme styles
function rf_enqueue_styles() {
    wp_enqueue_style('style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'rf_enqueue_styles');

// Enable featured images for recipes
add_theme_support('post-thumbnails');

// Change site title in browser tab
function rf_custom_title($title) {
    return 'Recipe Finder';
}
add_filter('pre_get_document_title', 'rf_custom_title');

// No closing PHP tag to prevent whitespace issues
