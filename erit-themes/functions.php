<?php

function erit_enqueue_scripts() {
    wp_enqueue_style('main-style',get_stylesheet_uri());

}
//add_action( 'wp_enqueue_scipts'.'ds_enqueue_scripts)' );


function erit_setup(){
    add_theme_support('menus');
    register_nav_menu( 'primary','Primary Navigation');
}
add_action( 'init','erit_setup');

?>                                                       