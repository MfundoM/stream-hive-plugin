<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-left">
            <div class="nav-links">
                <a href="<?php echo home_url('/streamhive-movies'); ?>" class="nav-link active">Home</a>
                <?php if (is_user_logged_in()): ?>
                    <a href="<?php echo home_url('/favorites'); ?>" class="nav-link">Favorites</a>
                <?php endif; ?>
                <a href="<?php echo home_url('/contact'); ?>" class="nav-link">Contact</a>
            </div>
        </div>
        <button class="mobile-toggle" id="mobileToggle">&#9776;</button>
    </div>
    <div class="mobile-menu" id="mobileMenu">
        <a href="<?php echo home_url('/streamhive-movies'); ?>" class="nav-link">Movies</a>
        <?php if (is_user_logged_in()): ?>
            <a href="<?php echo home_url('/favorites'); ?>" class="nav-link">Favorites</a>
        <?php endif; ?>
        <a href="<?php echo home_url('/contact'); ?>" class="nav-link">Contact</a>
    </div>
</nav>