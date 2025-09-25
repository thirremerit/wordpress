<?php 
get_header(); ?>

<main id="main" class="site-main">
    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'ds-theme-12' ); ?></h1>
        </header><!-- .page-header --> 
        <div class="page-content">
            <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'ds-theme-12' ); ?></p>

            <?php get_search_form(); ?>

            <?php the_widget( 'WP_Widget_Recent_Posts' ); ?>




