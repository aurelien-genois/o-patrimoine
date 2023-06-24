<?php

declare(strict_types=1);

remove_action('shutdown', 'wp_ob_end_flush_all', 1);
add_action('shutdown', function () {
    while (@ob_end_flush());
});

add_action(
    'after_setup_theme', // fire at each pages load
    'opatrimoine_load_theme',
);
add_action(
    'after_switch_theme', // fire only when the theme change
    'opatrimoine_initialize_theme',
);

function opatrimoine_load_theme()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('post-formats', array('video', 'link', 'image', 'quote', 'audio'));
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'widgets',
    ]);

    // hide admin bar on front if not a admin
    if (!current_user_can('administrator') && !is_admin() && !current_user_can('customer')) {
        show_admin_bar(false);
    }

    // Register menus locations
    register_nav_menus([
        'header' => __('Navigation', 'opatrimoine'),
        'footer' => __('Pied de page', 'opatrimoine'),
    ]);
}

function opatrimoine_initialize_theme()
{
    update_option('blogname', 'O\'Patrimoine'); // define site title, not necessaty here because default is "OPatrimoine"
    update_option('blogdescription', 'Des visites chaque semaine'); // define site tagline
};

add_action('wp_enqueue_scripts', function () {

    // TODO Adapt

    if(WP_ENV === 'local'){
        // wp_enqueue_script('opatrimoine_js-defer_uri', get_theme_file_uri('assets/app.bundle.js'), '', filemtime(get_template_directory().'/assets/app.bundle.js'), true);
        wp_enqueue_script('opatrimoine_js-defer', 'http://localhost:3000/themes/opatrimoine/assets/app.bundle.js', '', filemtime(get_template_directory().'/assets/app.bundle.js'), true);
    } else {
        // wp_enqueue_script('opatrimoine_js-defer_uri', get_theme_file_uri('assets/app.bundle.js'), '', filemtime(get_template_directory().'/assets/app.bundle.js'), true);
        wp_enqueue_script('opatrimoine_js-defer', get_theme_file_uri('assets/app.bundle.js'), '', filemtime(get_template_directory().'/assets/app.bundle.js'), true);
    }

    // wp_enqueue_script('app-rest-defer');


    // TODO Adapt
    // for now (in dev mode) css is include in app.js
    // wp_enqueue_style('opatrimoine_css', get_theme_file_uri('assets/styles/app.css'), '', filemtime(get_template_directory().'/assets/styles/app.css'));
    // wp_enqueue_style('opatrimoine_custom_css', get_theme_file_uri('assets/test.css'), '', filemtime(get_template_directory().'/assets/test.css'));

    //custom style
    $colors = get_field('options_colors', 'option');
    $array_color = ['main','second','third','fourth','five'];
    if(is_array($colors) && count($colors)){
        $custom_css = "html{";
            foreach ($array_color as $i => $item){
                $custom_css .= (!empty($colors[$item]))? "--color_".$item.": ".$colors[$item].";" : '';
            }
        $custom_css .="}";
        // add css to existing enqueued css
        wp_add_inline_style( 'opatrimoine_custom_css', $custom_css );
    }

});


include_once 'functions/helpers.php';
include_once 'functions/config/config.php';
include_once 'functions/config/admin.php'; // the include does'nt work in config.php ?
include_once 'functions/queries/queries.php';
include_once 'functions/user/roles.php';
include_once 'functions/user/connection.php';
include_once 'functions/reservations/functions.php';
