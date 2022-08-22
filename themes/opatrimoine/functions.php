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

function includeCustomsAssets($entry = null): void
{
    if($entry){
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
