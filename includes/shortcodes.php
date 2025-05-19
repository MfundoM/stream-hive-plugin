<?php
function streamhive_display_movies($atts)
{
    $tmdb = new StreamHive_TMDB();
    // $query = get_query_var('q', '');
    // $currentPage = max(1, (int) get_query_var('page', 1));
    $query = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
    $currentPage = max((int)($_GET['page'] ?? 1), 1);

    $perPage = 9;
    $maxMovies = 45;

    $allMovies = [];
    for ($i = 1; $i <= 3; $i++) {
        $response = $query
            ? $tmdb->search_movies($query, $i)
            : $tmdb->get_popular($i);

        if (!empty($response['results'])) {
            $allMovies = array_merge($allMovies, $response['results']);
        }

        if (count($allMovies) >= $maxMovies) {
            break;
        }
    }

    $allMovies = array_slice($allMovies, 0, $maxMovies);
    $totalPages = ceil(count($allMovies) / $perPage);

    $movies = array_slice($allMovies, ($currentPage - 1) * $perPage, $perPage);

    global $wpdb;
    $user_id = get_current_user_id();
    $table = $wpdb->prefix . 'streamhive_favorites';

    $favoriteMovieRows = $wpdb->get_results(
        $wpdb->prepare("SELECT `movie_id` FROM $table WHERE user_id = %d ORDER BY created_at DESC", $user_id)
    );

    $favoriteMovieIds = array_map(function ($row) {
        return $row->movie_id;
    }, $favoriteMovieRows);

    ob_start();
    include STREAMHIVE_PATH . 'templates/movie-grid.php';
    return ob_get_clean();
}
add_shortcode('streamhive', 'streamhive_display_movies');

function streamhive_display_favorites()
{
    if (!is_user_logged_in()) {
        return '<p>Please <a href="' . wp_login_url() . '">log in</a> to view your favorites.</p>';
    }

    global $wpdb;
    $user_id = get_current_user_id();
    $table = $wpdb->prefix . 'streamhive_favorites';

    $movies = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE user_id = %d ORDER BY created_at DESC", $user_id));

    ob_start();
    extract(['movies' => $movies]);
    include STREAMHIVE_PATH . 'templates/favorite-movie-grid.php';
    return ob_get_clean();
}
add_shortcode('streamhive_favorites', 'streamhive_display_favorites');

function streamhive_contact_shortcode()
{
    ob_start();
    include STREAMHIVE_PATH . 'templates/contact.php';
    return ob_get_clean();
}
add_shortcode('streamhive_contact', 'streamhive_contact_shortcode');
