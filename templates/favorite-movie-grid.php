<div class="wrapper">
    <?php include plugin_dir_path(__FILE__) . '../includes/navbar.php'; ?>
    <h1 class="section-title">My Favorites</h1>

    <?php if (!$movies) { ?>
        <h5>You have no favorite movies yet.</h5>
    <?php } ?>

    <div class="grid">
        <?php foreach ($movies as $movie): ?>
            <div class="movie" data-movie-id="<?= esc_attr($movie->movie_id) ?>">
                <img src="https://image.tmdb.org/t/p/w500<?= esc_attr($movie->poster_path) ?>" alt="<?= esc_attr($movie->title) ?>" />
                <div class="overlay">
                    <h5 style="margin: 0"><strong><?= esc_html($movie->title, 23) ?></strong></h5>
                    <h5 style="margin: 0"><?= esc_html($movie->release_date) ?></h5>

                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST" onclick="event.stopPropagation();" style="margin-top: 8px;">
                        <input type="hidden" name="action" value="streamhive_delete_favorite">
                        <input type="hidden" name="movie_id" value="<?php echo esc_attr($movie->movie_id); ?>">
                        <?php wp_nonce_field('streamhive_delete_favorite'); ?>
                        <button type="submit" class="remove-button">Remove</button>
                    </form>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="movieModal" class="modal hidden">
        <div class="modal-content">
            <button id="closeModalBtn" class="close-btn" aria-label="Close modal">&times;</button>
            <div id="modalContent" class="modal-body">
                <p class="loading-text">Loading...</p>
            </div>
        </div>
    </div>
</div>