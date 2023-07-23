<?php

add_action('init', function () {
    $cpts = [];
    $cpts[] = [
        'id'           => 'place',
        'name'         => 'lieu',
        'name_plural'  => 'lieux',
        'supports'     => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'comments'],
        'hierarchical' => false,
        'public'       => true,
        'archive'      => true,
        'icon'         => 'dashicons-bank',
        'capability'   => 'place',
        'rewrite'      => true
    ];
    $cpts[] = [
        'id'           => 'guided_tour',
        'name'         => 'visite',
        'name_plural'  => 'visites',
        'supports'     => ['title', 'author', 'revisions'],
        'hierarchical' => false,
        'public'       => true,
        'archive'      => true,
        'icon'         => 'dashicons-calendar-alt',
        'capability'   => 'guided_tour',
        'rewrite'      => true
    ];

    if (count($cpts)) {
        foreach ($cpts as $cpt) {
            $args = [
                'label'               => __($cpt['name'], 'opatrimoine'),
                'description'         => __('Gestion', 'opatrimoine'),
                'labels'              => [
                    'name'               => ucfirst($cpt['name_plural']),
                    'singular_name'      => ucfirst($cpt['name']),
                    'add_new'            => __('Ajouter'),
                    'add_new_item'       => __('Ajouter un ') . $cpt['name'],
                    'edit_item'          => __('Modifier'),
                    'new_item'           => __('Nouveau ') . $cpt['name'],
                    'view_item'          => __('Voir le ') . $cpt['name'],
                    'search_items'       => __('Rechercher des ') . $cpt['name_plural'],
                    'not_found'          => __('Aucun élément trouvé'),
                    'not_found_in_trash' => __('Aucun élément trouvé'),
                    'parent_item_colon'  => ucfirst($cpt['name']) . __(' parents :'),
                    'menu_name'          => ucfirst($cpt['name_plural']),
                ],
                'supports'            => $cpt['supports'],
                'hierarchical'        => $cpt['hierarchical'],
                'public'              => $cpt['public'],
                'show_ui'             => true,
                'show_in_menu'        => true,
                'menu_position'       => 4,
                'menu_icon'           => $cpt['icon'],
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => true,
                'can_export'          => true,
                'has_archive'         => $cpt['archive'],
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'show_in_rest'        => true,
                'rewrite'             => $cpt['rewrite'],
                // 'capability_type'     => $cpt['capability'], // ! wp-json 403 forbidden (API REST, only on Laragon ?)
            ];
            $return = register_post_type($cpt['id'], $args);
        }
    }
}, 5, 0);