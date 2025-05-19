<?php
class StreamHive_TMDB
{
    private $base_url = 'https://api.themoviedb.org/3';

    private $api_key;

    public function __construct()
    {
        $this->api_key = get_option('streamhive_tmdb_api_key', '');
    }

    private function get($endpoint, $params = [])
    {
        $params = array_merge($params, [
            'api_key' => $this->api_key,
            'language' => 'en-US'
        ]);
        $url = $this->base_url . '/' . $endpoint . '?' . http_build_query($params);

        $response = wp_remote_get($url);
        if (is_wp_error($response)) return [];

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    public function get_popular($page = 1)
    {
        return $this->get('movie/popular', ['page' => $page]);
    }

    public function search_movies($query, $page = 1)
    {
        return $this->get('search/movie', ['query' => $query, 'page' => $page]);
    }

    public function get_movie_details($id)
    {
        return $this->get("movie/{$id}");
    }

    public function get_trailers($id)
    {
        return $this->get("movie/{$id}/videos");
    }
}
