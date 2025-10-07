<header>
    <h1>Recipe Finder</h1>
    <div style="margin-top:10px;">
        <?php if(is_user_logged_in()): ?>
            <a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>
        <?php else: ?>
            <a href="<?php echo site_url('/login'); ?>">Login / Signup</a>
        <?php endif; ?>
    </div>
</header>
