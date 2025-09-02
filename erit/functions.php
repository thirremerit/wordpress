<?php
function ds_enqueue_scripts() {
    wp_enqueue_style( 'main-style',get_stylesheet_uri( ));

}

add_action('wp_enqueue_scripts'.'ds_enqueue_scripts');
?>