<div class="wrapper">
    <?php include plugin_dir_path(__FILE__) . '../includes/navbar.php'; ?>
    <h1 class="section-title">Popular Movies</h1>

    <form method="GET" class="search-form" id="searchForm">
        <input
            id="search"
            type="text"
            name="q"
            placeholder="Search movies..."
            autocomplete="off"
            value=""
            class="search-input" />

        <ul id="autocomplete-results" class="autocomplete-list hidden"></ul>
    </form>

    <div class="grid">
        <?php foreach ($movies as $movie): ?>
            <div class="movie" data-movie-id="<?= esc_attr($movie['id']) ?>">
                <img src="https://image.tmdb.org/t/p/w500<?= esc_attr($movie['poster_path']) ?>" alt="<?= esc_attr($movie['title']) ?>" />
                <div class="overlay">
                    <h5 style="margin: 0"><strong><?= esc_html($movie['title'], 23) ?></strong></h5>
                    <h5 style="margin: 0"><?= esc_html($movie['release_date']) ?></h5>
                    <?php $isFavorited = in_array($movie['id'], $favoriteMovieIds); ?>
                    <?php if (is_user_logged_in()): ?>
                        <?php if ($isFavorited): ?>
                            <button type="button" class="favorite-btn" disabled onclick="event.stopPropagation();" style="background-color: #374151;">
                                Favorited
                            </button>
                        <?php else: ?>
                            <button type="button" class="favorite-btn"
                                onclick="event.stopPropagation();"
                                data-movie-id="<?php echo esc_attr($movie['id']); ?>"
                                data-title="<?php echo esc_attr($movie['title']); ?>"
                                data-poster="<?php echo esc_attr($movie['poster_path'] ?? ''); ?>"
                                data-release="<?php echo esc_attr($movie['release_date'] ?? ''); ?>">
                                Add to Favorites
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?<?= $query ? 'q=' . urlencode($query) . '&' : '' ?>page=<?= $i ?>"
                    class="<?= $i == $currentPage ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <div id="movieModal" class="modal hidden">
        <div class="modal-content">
            <button id="closeModalBtn" class="close-btn" aria-label="Close modal">&times;</button>
            <div id="modalContent" class="modal-body">
                <p class="loading-text">Loading...</p>
            </div>
        </div>
    </div>
</div>