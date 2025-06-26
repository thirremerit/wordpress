
<?php

function your_theme_enequeue_scripts() {
    wp_enequeue_script('jquery');

     wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css');

     wp_enqueue_style('theme-style', get_stylesheet_uri(), array('bootstrap'), '1.0');

     wp_enequeue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js', array('jquery'), '4.6.0');


}
add_action( 'wp_enqueue_scripts', 'your_theme_enqueue_scripts');



?>

