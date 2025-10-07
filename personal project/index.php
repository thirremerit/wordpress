<?php get_header(); ?>

<h2>Add a Recipe</h2>
<?php
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
        $uploaded = wp_handle_upload($_FILES['recipe_image'], ['test_form' => false]);
        if($uploaded && !isset($uploaded['error'])) {
            $filename = $uploaded['file'];
            $attachment = [
                'post_mime_type' => $uploaded['type'],
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
?>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="recipe_name" placeholder="Recipe Name" required>
    <textarea name="ingredients" placeholder="Ingredients (comma separated)" required></textarea>
    <textarea name="instructions" placeholder="Instructions"></textarea>
    <label>
        Recipe Image (optional):
        <input type="file" name="recipe_image" accept="image/*">
    </label>
    <button type="submit" name="submit_recipe">Add Recipe</button>
</form>

<hr>

<h2>Search Recipes</h2>
<form method="get">
    <input type="text" name="search" placeholder="Search recipes or ingredients">
    <button type="submit">Search</button>
</form>

<?php
if(isset($_GET['search'])) {
    $keyword = sanitize_text_field($_GET['search']);


    $args_meta = [
        'post_type' => 'recipes',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'ingredients',
                'value' => $keyword,
                'compare' => 'LIKE'
            ]
        ]
    ];
    $query_meta = new WP_Query($args_meta);


    $args_title = [
        'post_type' => 'recipes',
        'posts_per_page' => -1,
        's' => $keyword
    ];
    $query_title = new WP_Query($args_title);


    $recipes = [];
    if($query_meta->have_posts()) {
        while($query_meta->have_posts()) {
            $query_meta->the_post();
            $recipes[get_the_ID()] = [
                'title' => get_the_title(),
                'content' => get_the_content(),
                'ingredients' => get_post_meta(get_the_ID(), 'ingredients', true),
                'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail(get_the_ID(), 'medium') : ''
            ];
        }
    }
    wp_reset_postdata();

    if($query_title->have_posts()) {
        while($query_title->have_posts()) {
            $query_title->the_post();
            $recipes[get_the_ID()] = [
                'title' => get_the_title(),
                'content' => get_the_content(),
                'ingredients' => get_post_meta(get_the_ID(), 'ingredients', true),
                'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail(get_the_ID(), 'medium') : ''
            ];
        }
    }
    wp_reset_postdata();

    if(!empty($recipes)) {
        foreach($recipes as $recipe) {
            echo '<div class="recipe-card">';
            echo '<h2>' . $recipe['title'] . '</h2>';
            if($recipe['thumbnail']) echo $recipe['thumbnail'];
            echo '<p><strong>Ingredients:</strong> ' . $recipe['ingredients'] . '</p>';
            echo '<p>' . $recipe['content'] . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No recipes found.</p>';
    }
}
?>

<?php get_footer(); ?>
