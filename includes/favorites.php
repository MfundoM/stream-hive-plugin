<?php
// defined('ABSPATH') || exit;

// register_activation_hook(__FILE__, 'streamhive_activate');

function streamhive_activate()
{
    error_log("StreamHive activation started");
    global $wpdb;

    $table_name = $wpdb->prefix . 'streamhive_favorites';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        movie_id bigint(20) NOT NULL,
        title varchar(255) NOT NULL,
        poster_path varchar(255),
        release_date varchar(50),
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY user_movie (user_id, movie_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    error_log("StreamHive table creation done");
}

add_action('wp_ajax_streamhive_add_to_favorites', 'streamhive_add_to_favorites');
add_action('wp_ajax_nopriv_streamhive_add_to_favorites', function () {
    wp_send_json_error('You must be logged in to add favorites.');
});

function streamhive_add_to_favorites()
{
    check_ajax_referer('streamhive_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }

    $user_id = get_current_user_id();
    $movie_id = intval($_POST['movie_id']);
    $title = sanitize_text_field($_POST['title']);
    $poster_path = sanitize_text_field($_POST['poster_path']);
    $release_date = sanitize_text_field($_POST['release_date']);

    if (!$movie_id || empty($title)) {
        wp_send_json_error('Invalid data.');
    }

    global $wpdb;
    $table = $wpdb->prefix . 'streamhive_favorites';

    $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE user_id = %d AND movie_id = %d", $user_id, $movie_id));
    if ($exists) {
        wp_send_json_error('Already in favorites.');
    }

    $inserted = $wpdb->insert($table, [
        'user_id' => $user_id,
        'movie_id' => $movie_id,
        'title' => $title,
        'poster_path' => $poster_path,
        'release_date' => $release_date,
        'created_at' => current_time('mysql')
    ]);

    if ($inserted) {
        wp_send_json_success('Added to favorites.');
    } else {
        wp_send_json_error('Failed to add favorite.');
    }
}

add_action('admin_post_streamhive_delete_favorite', 'streamhive_handle_delete_favorite');
add_action('admin_post_nopriv_streamhive_delete_favorite', function () {
    wp_redirect(wp_login_url());
    exit;
});

function streamhive_handle_delete_favorite()
{
    if (!is_user_logged_in()) {
        wp_redirect(wp_login_url());
        exit;
    }

    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'streamhive_delete_favorite')) {
        wp_die('Security check failed');
    }

    $user_id  = get_current_user_id();
    $movie_id = isset($_POST['movie_id']) ? intval($_POST['movie_id']) : 0;

    if (!$movie_id) {
        wp_die('Invalid movie ID');
    }

    global $wpdb;
    $table = $wpdb->prefix . 'streamhive_favorites';

    $wpdb->delete($table, [
        'user_id'  => $user_id,
        'movie_id' => $movie_id,
    ]);

    wp_redirect(wp_get_referer() ?: home_url());
    exit;
}
