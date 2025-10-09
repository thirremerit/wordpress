<?php
get_header();

// Process registration/login BEFORE any HTML output
if (isset($_POST['register_user'])) {
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $user_id = wp_create_user($username, $password, $email);

    if (!is_wp_error($user_id)) {
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        wp_redirect(home_url());
        exit;
    } else {
        $register_error = $user_id->get_error_message();
    }
}

if (isset($_POST['login_user'])) {
    $creds = [
        'user_login' => $_POST['username'],
        'user_password' => $_POST['password'],
        'remember' => true
    ];
    $user = wp_signon($creds, false);

    if (!is_wp_error($user)) {
        wp_redirect(home_url());
        exit;
    } else {
        $login_error = $user->get_error_message();
    }
}
?>

<div class="container">
    <?php if (is_user_logged_in()) : 
        $current_user = wp_get_current_user();
    ?>
        <h2>Welcome, <?php echo esc_html($current_user->user_login); ?>!</h2>

        <!-- Add Recipe Form -->
        <div class="recipe-form">
            <h3>Add a Recipe</h3>
            <form method="post" enctype="multipart/form-data">
                <input type="text" name="recipe_name" placeholder="Recipe Name" required>
                <textarea name="recipe_ingredients" placeholder="Ingredients (comma-separated)" required></textarea>
                <textarea name="recipe_instructions" placeholder="Instructions" required></textarea>
                <label for="recipe_image">Recipe Image (optional):</label>
                <input type="file" name="recipe_image" accept="image/*">
                <button type="submit" name="add_recipe">Add Recipe</button>
            </form>
        </div>

        <!-- Search Form -->
        <div class="search-form">
            <h3>Search Recipes</h3>
            <form method="get">
                <input type="text" name="search_query" placeholder="Search by name or ingredient">
                <button type="submit">Search</button>
            </form>
        </div>

        <?php
        // Recipe submission logic
        if (isset($_POST['add_recipe'])) {
            $name = sanitize_text_field($_POST['recipe_name']);
            $ingredients = sanitize_textarea_field($_POST['recipe_ingredients']);
            $instructions = sanitize_textarea_field($_POST['recipe_instructions']);

            $post_id = wp_insert_post([
                'post_title' => $name,
                'post_content' => $instructions,
                'post_type' => 'post',
                'post_status' => 'publish'
            ]);

            if ($post_id) {
                update_post_meta($post_id, 'ingredients', $ingredients);

                if (!empty($_FILES['recipe_image']['name'])) {
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    $file = wp_handle_upload($_FILES['recipe_image'], ['test_form' => false]);
                    if ($file && !isset($file['error'])) {
                        $attachment = [
                            'post_mime_type' => $file['type'],
                            'post_title' => sanitize_file_name($_FILES['recipe_image']['name']),
                            'post_content' => '',
                            'post_status' => 'inherit'
                        ];
                        $attach_id = wp_insert_attachment($attachment, $file['file'], $post_id);
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        $attach_data = wp_generate_attachment_metadata($attach_id, $file['file']);
                        wp_update_attachment_metadata($attach_id, $attach_data);
                        set_post_thumbnail($post_id, $attach_id);
                    }
                }

                echo "<p class='success'>Recipe added successfully!</p>";
            }
        }

        // Recipe search logic
        if (isset($_GET['search_query'])) {
            $search = sanitize_text_field($_GET['search_query']);
            $args = [
                'post_type' => 'post',
                's' => $search
            ];
            $query = new WP_Query($args);

            if ($query->have_posts()) {
                echo "<div class='recipe-results'><h3>Search Results:</h3>";
                while ($query->have_posts()) {
                    $query->the_post();
                    echo "<div class='recipe-card'>";
                    if (has_post_thumbnail()) the_post_thumbnail('medium');
                    echo "<h4>" . get_the_title() . "</h4>";
                    echo "<p><strong>Ingredients:</strong> " . esc_html(get_post_meta(get_the_ID(), 'ingredients', true)) . "</p>";
                    echo "<p><strong>Instructions:</strong> " . get_the_content() . "</p>";
                    echo "</div>";
                }
                echo "</div>";
                wp_reset_postdata();
            } else {
                echo "<p>No recipes found.</p>";
            }
        }
        ?>

        <p><a href="<?php echo wp_logout_url(home_url()); ?>">Log out</a></p>

    <?php else : ?>
        <div class="auth-forms">
            <?php if (isset($_GET['login'])) : ?>
                <h2>Log In</h2>
                <?php if (!empty($login_error)) echo "<p class='error'>{$login_error}</p>"; ?>
                <form method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login_user">Log In</button>
                </form>
                <p>Don't have an account? <a href="<?php echo home_url(); ?>">Sign up here</a></p>
            <?php else : ?>
                <h2>Sign Up</h2>
                <?php if (!empty($register_error)) echo "<p class='error'>{$register_error}</p>"; ?>
                <form method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="register_user">Sign Up</button>
                </form>
                <p>Already have an account? <a href="?login=true">Log in</a></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
