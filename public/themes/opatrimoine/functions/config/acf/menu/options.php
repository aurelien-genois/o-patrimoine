<?php

// create B0 page option
add_action('acf/init', function () {
    if (function_exists('acf_add_options_sub_page')) {
        $slug = 'opatrimoine-options';

        acf_add_options_page(
            array(
                'page_title' => __('Options Générales du Thème'),
                'menu_title' => __('Options du thème'),
                'menu_slug'  => $slug,
                'redirect'   => false,
            )
        );

        $subpages_acf = array(
            [
                'title' => 'Options de test',
                'menu'  => 'Test',
            ],
        );

        foreach ($subpages_acf as $subpage) {
            if (current_user_can('administrator') || current_user_can('editor')) {
                acf_add_options_sub_page(
                    array(
                        'page_title'  => __($subpage['title']),
                        'menu_title'  => __($subpage['menu']),
                        'parent_slug' => $slug
                    )
                );
            }
        }
    }
});