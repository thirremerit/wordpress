<?php

function erit_enqueue_scripts() {
    wp_enqueue_style('main-style',get_stylesheet_uri());

}
add_action( 'wp_enqueue_scipts'.'ds_enqueue_scripts)' )

?>