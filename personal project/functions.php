<?php

function rf_enqueue_styles() {
    wp_enqueue_style('rf-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'rf_enqueue_styles');


function rf_register_recipes() {
    $labels = [
        'name' => 'Recipes',
        'singular_name' => 'Recipe',
        'add_new_item' => 'Add New Recipe'
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => ['title','editor','thumbnail']
    ];

    register_post_type('recipes', $args);
}
add_action('init', 'rf_register_recipes');


function rf_handle_add_recipe_form() {
    if(isset($_POST['submit_recipe'])) {
        $title = sanitize_text_field($_POST['recipe_name']);
        $ingredients = sanitize_text_field($_POST['ingredients']);
        $instructions = sanitize_textarea_field($_POST['instructions']);

        $post_id = wp_insert_post([
            'post_title' => $title,
            'post_content' => $instructions,
            'post_type' => 'recipes',
            'post_status' => 'publish'
        ]);


        if(!empty($_FILES['recipe_image']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $file = wp_handle_upload($_FILES['recipe_image'], ['test_form' => false]);
            if($file && !isset($file['error'])) {
                $filename = $file['file'];
                $attachment = [
                    'post_mime_type' => $file['type'],
                    'post_title' => sanitize_file_name(basename($filename)),
                    'post_status' => 'inherit'
                ];
                $attach_id = wp_insert_attachment($attachment, $filename, $post_id);
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
                wp_update_attachment_metadata($attach_id, $attach_data);
                set_post_thumbnail($post_id, $attach_id);
            }
        }

        update_post_meta($post_id, 'ingredients', $ingredients);
        echo '<p>Recipe added successfully!</p>';
    }
}
