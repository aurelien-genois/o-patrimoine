<?php

add_action('init', function () {
    $taxonomies = [];
    $taxonomies[] = [
        'cpt'          => ['place'],
        'id'           => 'place_type',
        'name'         => 'type',
        'name_plural'  => 'types',
        'hierarchical' => true,
        'public'       => true,
    ];
    $taxonomies[] = [
        'cpt'          => ['guided_tour'],
        'id'           => 'tour_thematic',
        'name'         => 'Thématique',
        'name_plural'  => 'Thématiques',
        'hierarchical' => false,
        'public'       => true,
    ];
    $taxonomies[] = [
        'cpt'          => ['guided_tour'],
        'id'           => 'tour_constraint',
        'name'         => 'Contrainte d\'accessibilités',
        'name_plural'  => 'Contraintes d\'accessibilités',
        'hierarchical' => false,
        'public'       => true,
        'rewrite'      => ['slug' => 'constraint']
    ];


    if (!empty($taxonomies) && count($taxonomies)) {
        foreach ($taxonomies as $taxonomy) {
            $args = [
                'labels'            => [
                    'name'                  => ucfirst($taxonomy['name_plural']),
                    'singular_name'         => ucfirst($taxonomy['name']),
                    'menu_name'             => ucfirst($taxonomy['name_plural']),
                    'all_items'             => __('Tous', 'opatrimoine'),
                    'parent_item'           => ucfirst($taxonomy['name']) . __(' parents ', 'opatrimoine'),
                    'parent_item_colon'     => ucfirst($taxonomy['name']) . __(' parents :', 'opatrimoine'),
                    'new_item_name'         => __('Nouveau ', 'opatrimoine') . $taxonomy['name'],
                    'add_new'               => __('Ajouter', 'opatrimoine'),
                    'add_new_item'          => __('Ajouter un ', 'opatrimoine') . $taxonomy['name'],
                    'edit_item'             => __('Modifier le ', 'opatrimoine') . $taxonomy['name'],
                    'update_item'           => __('Mettre à jour le ', 'opatrimoine') . $taxonomy['name'],
                    'view_item'             => __('Voir le ', 'opatrimoine') . $taxonomy['name'],
                    'add_or_remove_items'   => __('Ajouter ou Supprimer des ', 'opatrimoine') . $taxonomy['name_plural'],
                    'choose_from_most_used' => __('Choisir parmi les plus populaires', 'opatrimoine'),
                    'popular_items'         => __('Populaires', 'opatrimoine'),
                    'search_items'          => __('Rechercher par ', 'opatrimoine') . $taxonomy['name'],
                    'not_found'             => __('Aucun élément trouvé', 'opatrimoine')
                ],
                'hierarchical'      => $taxonomy['hierarchical'],
                'public'            => $taxonomy['public'],
                'show_ui'           => true,
                'show_admin_column' => true,
                'show_in_nav_menus' => true,
                'show_in_rest'      => true,
                'rewrite'           => (isset($taxonomy['rewrite'])) ? $taxonomy['rewrite'] : [],
                'show_tagcloud'     => false,
                'show_admin_column' => true,
                'capabilities'      => [
                    'manage_terms' => 'manage_' . $taxonomy['id'] . 's',
                    'edit_terms'   => 'edit_' . $taxonomy['id'] . 's',
                    'delete_terms' => 'delete_' . $taxonomy['id'] . 's',
                    'assign_terms' => 'assign_' . $taxonomy['id'] . 's',
                ],
            ];

            register_taxonomy(
                sanitize_title($taxonomy['id']),
                $taxonomy['cpt'],
                $args,
            );
        }
    }

    add_rewrite_rule(
        'place_type/([a-z0-9-]+)[/]?$',
        'index.php?post_type=place&place_type=$matches[1]',
        'top',
    );
}, 6, 0);

add_filter('query_vars', function ($query_vars) {
    $query_vars[] = 'place_type';
    return $query_vars;
});

add_action('template_include', function ($template) {
    if (get_query_var('place_type') == false || get_query_var('place_type') == '') {
        return $template;
    }
    return get_template_directory() . '/archive-place.php';
});