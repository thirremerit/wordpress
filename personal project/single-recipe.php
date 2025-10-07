<?php get_header(); ?>

<?php
if(have_posts()) {
    while(have_posts()) {
        the_post();
        echo '<div class="recipe-card">';
        echo '<h2>' . get_the_title() . '</h2>';
        if(has_post_thumbnail()) the_post_thumbnail('medium');
        echo '<p><strong>Ingredients:</strong> ' . get_post_meta(get_the_ID(), 'ingredients', true) . '</p>';
        echo '<p>' . get_the_content() . '</p>';
        echo '</div>';
    }
}
?>

<?php get_footer(); ?>
