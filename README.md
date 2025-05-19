=== StreamHive Plugin ===

== Description ==

StreamHive is a WordPress plugin that allows users to search movies via The Movie Database (TMDB) and save favorites. It’s built with PHP and JavaScript (AJAX).

== Features ==

- Search movies using TMDB API
- Add movies to favorites (logged-in users)
- Display user’s favorite movies
- View contact info

== Installation ==

1. Download the plugin ZIP or clone it from GitHub.
2. Upload the folder to the `/wp-content/plugins/` directory.
3. Activate the plugin from the **Plugins** menu in WordPress.
4. Visit **Settings > StreamHive** to configure api-key.

== Usage ==

**Display the popular movie favorites and contact:**

1. Create a new pages (Movies, favorites, contact).
2. Add shortcode inside the content area: ([streamhive] slug: /streamhive-movies, [streamhive_favorites] slug: /favorites, [streamhive_contact] slug: /contact)
