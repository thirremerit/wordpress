<div class="sidebar" id="sidebar-primary">
    <?php if (is_active_sidebar('primary'  )): ?>
<?php dynamic_sidebar( 'primary' );?>
<?php else:?>
    <aside id="archives" class="widget">
        <h3 class="widget-title"><?php _e('Archives','dstheme');?></h3>
        <ul><?php wp_get_archives( array('type'=>'monthly'));?></ul>
    </aside>
    <aside id="meta" class="widget">
        <h3 class="widget-title"><?php _e('Meta','dstheme');?></h3>

        <ul>
            <?php wp_regjister();?>
            <li><?php wp_loginout( )?></li>
            <?php wp_meta( )?>
        </ul>
    </aside>
    <?php endif;?>
</div>