<?php

add_action('admin_menu', function () {
    add_options_page(
        'StreamHive Settings',
        'StreamHive',
        'manage_options',
        'streamhive-settings',
        'streamhive_settings_page'
    );
});

add_action('admin_init', function () {
    register_setting('streamhive_options_group', 'streamhive_tmdb_api_key');

    add_settings_section(
        'streamhive_main_section',
        'TMDB Configuration',
        null,
        'streamhive-settings'
    );

    add_settings_field(
        'streamhive_tmdb_api_key',
        'TMDB API Key',
        function () {
            $value = esc_attr(get_option('streamhive_tmdb_api_key'));
            echo "<input type='text' name='streamhive_tmdb_api_key' value='{$value}' class='regular-text'>";
        },
        'streamhive-settings',
        'streamhive_main_section'
    );
});

function streamhive_settings_page()
{
    ?>
    <div class="wrap">
        <h1>StreamHive Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('streamhive_options_group');
            do_settings_sections('streamhive-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}