  <?php wp_head(); ?>
<h1>This our DS theme</h1>


<?php


if(have_posts()):
    while( have_posts(  )):the_post(  );?>
    <h2><?php  the_title(); ?></h2>
    <p><?php  the_content(); ?></p>
    <?php   endwhile;
endif;
?>
<?php if (have_posts()): while(have_posts()):the_post();?>
   
<h2><?php the_title(); ?></h2>


<div class="thumbnail-img">
    <?php the_post_thumbnail('thumbnail'); //  150x150?>
    <?php the_post_thumbnail('medium'); //  150x150?>
    <?php the_post_thumbnail('large'); //  150x150?>
    <?php the_post_thumbnail('full'); //  150x150?>
</div>


    



<p><?php the_content()?></p>


<?php endwhile; endif;?>



<button type="button" class="btn btn-primary">Primary</button>
<button type="button" class="btn btn-secondary">Secondary</button>
<button type="button" class="btn btn-success">Success</button>
<button type="button" class="btn btn-danger">Danger</button>
<button type="button" class="btn btn-warning">Warning</button>
<button type="button" class="btn btn-info">Info</button>
<button type="button" class="btn btn-light">Light</button>
<button type="button" class="btn btn-dark">Dark</button>

<?php wp_footer(); ?>