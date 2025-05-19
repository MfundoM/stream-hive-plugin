<?php

/**
 * Plugin Name: StreamHive
 * Description: A TMDB-powered movie browser.
 * Version: 1.0
 * Author: Mfundo Mthethwa
 */

defined('ABSPATH') || exit;

define('STREAMHIVE_PATH', plugin_dir_path(__FILE__));
define('STREAMHIVE_URL', plugin_dir_url(__FILE__));

require_once STREAMHIVE_PATH . 'includes/tmdb-service.php';
require_once STREAMHIVE_PATH . 'includes/api.php';
require_once STREAMHIVE_PATH . 'includes/shortcodes.php';
require_once STREAMHIVE_PATH . 'includes/favorites.php';
require_once STREAMHIVE_PATH . 'includes/admin-settings.php';

register_activation_hook(__FILE__, 'streamhive_activate');

add_action('rest_api_init', function () {
    register_rest_route('streamhive/v1', '/search', [
        'methods' => 'GET',
        'callback' => function (WP_REST_Request $request) {
            $query = sanitize_text_field($request->get_param('q'));

            if (empty($query)) {
                return rest_ensure_response(['results' => []]);
            }

            $tmdb = new StreamHive_TMDB();
            $response = $tmdb->search_movies($query);
            // error_log(print_r($response, true));
            $results = [];

            if (!empty($response['results'])) {
                foreach ($response['results'] as $movie) {
                    $results[] = [
                        'id' => $movie['id'],
                        'title' => $movie['title'],
                        'release_date' => $movie['release_date'] ?? '',
                        'poster_path' => $movie['poster_path'] ?? '',
                    ];
                }
            }

            return rest_ensure_response(['results' => $results]);
        },
        'permission_callback' => '__return_true',
    ]);
});

function streamhive_enqueue_assets()
{
    wp_enqueue_style('streamhive-css', STREAMHIVE_URL . 'assets/css/streamhive.css');
    wp_enqueue_script('streamhive-js', STREAMHIVE_URL . 'assets/js/streamhive.js', ['jquery'], null, true);

    wp_localize_script('streamhive-js', 'streamhive_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('streamhive_nonce'),
        'search_api_url' => esc_url_raw(rest_url('streamhive/v1/search')),
    ]);
}
add_action('wp_enqueue_scripts', 'streamhive_enqueue_assets');
