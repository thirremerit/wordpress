<?php 


get_header(); ?>

<main id="main" class="site-main" role="main">
    <section class="search-intro">
        <h2>
            <?php _e("Search Posts","your-textdomain");?>
        </h2>
        <p>
            <?php _e('Use the form below to search the site.','your-textdomain'); ?>
        </p>
    </section>

    <section class="search-results container py-4">
        <?php if (have_posts()) : ?>
            <div class="row">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="col-md-6 mb-4">
                        <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100'); ?>>
                            <div class="card-body">
                                <h3 class="card-title"><a href="<?php the_permalink(); ?>" class="stretched-link"><?php the_title(); ?></a></h3>
                                <div class="card-text"><?php the_excerpt(); ?></div>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php mytheme_pagination(); ?>
        <?php else : ?>
            <p><?php _e('No results found.','your-textdomain'); ?></p>
        <?php endif; ?>
    </section>
</main>
<?php get_footer(); ?>

