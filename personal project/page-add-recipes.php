<?php
get_header();

rf_handle_add_recipe_form();
?>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="recipe_name" placeholder="Recipe Name" required>
    <textarea name="ingredients" placeholder="Ingredients (comma separated)" required></textarea>
    <textarea name="instructions" placeholder="Instructions"></textarea>
    <input type="file" name="recipe_image" accept="image/*">
    <button type="submit" name="submit_recipe">Add Recipe</button>
</form>

<?php get_footer(); ?>
