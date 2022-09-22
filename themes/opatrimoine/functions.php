<?php
declare(strict_types=1);

remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
add_action( 'shutdown', function() {
    while ( @ob_end_flush() );
 } );

add_action(
    'after_setup_theme', // fire at each pages load
    'opatrimoine_load_theme',
);
add_action(
    'after_switch_theme', // fire only when the theme change
    'opatrimoine_initialize_theme',
);

function opatrimoine_load_theme() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support('post-formats', array('video','link','image','quote','audio'));
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

function opatrimoine_initialize_theme() {
    update_option('blogname', 'O\'Patrimoine'); // define site title, not necessaty here because default is "OPatrimoine"
    update_option('blogdescription', 'Des visites chaque semaine'); // define site tagline
};

function includeCustomsAssets($entry = null): void
{
    if($entry){
        // ---- ACF COLORS ----
        $colors = get_field('options_colors', 'option');
        if(is_array($colors) && count($colors)){
            $custom_css = "html{";
            foreach ($colors as $name => $color){
                $custom_css .= "--color_".$name.": ".$color.";";
            }
            $custom_css .="}";
            echo '<style>'.$custom_css.'</style>';
        }

        //INCLUDE CSS / JS FILES
        if(WP_ENV === 'local'){
            //VITE LOCAL DEVELOPMENT (CSS & JS included)
            echo '<script type="module" src="https://localhost:5173/'.$entry.'"></script>';
        }else {
            //VITE PRODUCTION FILES FROM MANIFEST
            try {
                $manifest = json_decode(file_get_contents(get_theme_file_path('assets/manifest.json')), true, 512, JSON_THROW_ON_ERROR);

                // ---- JS ----
                echo '<script type="module" src="'.get_theme_file_uri('assets/' . $manifest[$entry]['file']).'" defer></script>';

                // ---- CSS ----
                if(!empty($manifest[$entry]['css'])){
                    foreach ($manifest[$entry]['css'] as $manifestCSS){
                        echo '<link rel="stylesheet" href="'.get_theme_file_uri('assets/' . $manifestCSS).'">';
                    }
                }
            } catch (JsonException $e) {/**/}
        }
    }
}

add_action( 'wp_head',function () {
    includeCustomsAssets('resources/js/app.js');
}, 5);

include_once 'functions/helpers.php';
include_once 'functions/config/config.php';
include_once 'functions/config/admin.php'; // the include does'nt work in config.php ?
include_once 'functions/queries/queries.php';
include_once 'functions/user/connection.php';
