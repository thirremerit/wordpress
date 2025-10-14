<?php
function recipe_finder_enqueue_styles() {
    wp_enqueue_style('main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'recipe_finder_enqueue_styles');

function recipe_finder_handle_registration() {
    if (isset($_POST['register_user'])) {
        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];

        $errors = new WP_Error();

        if (empty($username) || empty($email) || empty($password)) {
            $errors->add('field', 'Please fill in all fields.');
        }
        if (username_exists($username)) {
            $errors->add('username', 'Username already exists.');
        }
        if (email_exists($email)) {
            $errors->add('email', 'Email already registered.');
        }

        if (empty($errors->get_error_messages())) {
            $user_id = wp_create_user($username, $password, $email);

            if (!is_wp_error($user_id)) {
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);

                wp_redirect(home_url());
                exit;
            }
        } else {
            return $errors;
        }
    }
}
add_action('init', 'recipe_finder_handle_registration');

function recipe_finder_handle_login() {
    if (isset($_POST['login_user'])) {
        $creds = [
            'user_login' => sanitize_text_field($_POST['username']),
            'user_password' => $_POST['password'],
            'remember' => true,
        ];

        $user = wp_signon($creds, false);

        if (!is_wp_error($user)) {
            wp_redirect(home_url());
            exit;
        } else {
            return $user;
        }
    }
}
add_action('init', 'recipe_finder_handle_login');

function recipe_finder_theme_setup() {
    add_theme_support('custom-logo');
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'recipe_finder_theme_setup');

ob_start();
