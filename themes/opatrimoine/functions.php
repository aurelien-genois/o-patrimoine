<?php

add_action(
    'after_setup_theme',
    'opatrimoine_initialize_theme',
);

function opatrimoine_initialize_theme() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
};

add_action(
    'wp_enqueue_scripts',
    function() {
        wp_enqueue_style(
            'global-style',
            get_theme_file_uri('assets/css/style.css'),
        );
        wp_enqueue_script(
            'sample_script',
            get_theme_file_uri('assets/css/script.js'),
            [],
            '1.0.0',
            true,
        );
    },
);
