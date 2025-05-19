<?php
add_action('wp_ajax_streamhive_movie_details', 'streamhive_movie_details');
add_action('wp_ajax_nopriv_streamhive_movie_details', 'streamhive_movie_details');

function streamhive_movie_details()
{
    if (!isset($_GET['id'])) {
        wp_send_json_error('Missing movie ID.');
    }

    $id = sanitize_text_field($_GET['id']);
    // require_once STREAMHIVE_PATH . 'includes/tmdb-service.php';

    $tmdb = new StreamHive_TMDB();
    $movie = $tmdb->get_movie_details($id);
    $videos = $tmdb->get_trailers($id);
    $trailer = null;

    if (!empty($videos['results'])) {
        foreach ($videos['results'] as $video) {
            if (strtolower($video['type']) === 'trailer') {
                $trailer = $video['key'];
                break;
            }
        }
    }

    wp_send_json_success([
        'movie' => $movie,
        'trailer_key' => $trailer
    ]);
}
