<?php

add_action('init', function () {

    // set capabilities to administrator for cpts
    $role = get_role('administrator');

    $role->add_cap('delete_places');
    $role->add_cap('delete_others_places');
    $role->add_cap('delete_private_places');
    $role->add_cap('delete_published_places');
    $role->add_cap('edit_places');
    $role->add_cap('edit_others_places');
    $role->add_cap('edit_private_places');
    $role->add_cap('edit_published_places');
    $role->add_cap('publish_places');
    $role->add_cap('read_private_places');

    $role->add_cap('delete_guided_tours');
    $role->add_cap('delete_others_guided_tours');
    $role->add_cap('delete_private_guided_tours');
    $role->add_cap('delete_published_guided_tours');
    $role->add_cap('edit_guided_tours');
    $role->add_cap('edit_others_guided_tours');
    $role->add_cap('edit_private_guided_tours');
    $role->add_cap('edit_published_guided_tours');
    $role->add_cap('publish_guided_tours');
    $role->add_cap('read_private_guided_tours');

    $role->add_cap('manage_place_types');
    $role->add_cap('edit_place_types');
    $role->add_cap('delete_place_types');
    $role->add_cap('assign_place_types');

    $role->add_cap('manage_tour_thematics');
    $role->add_cap('edit_tour_thematics');
    $role->add_cap('delete_tour_thematics');
    $role->add_cap('assign_tour_thematics');

    $role->add_cap('manage_tour_constraints');
    $role->add_cap('edit_tour_constraints');
    $role->add_cap('delete_tour_constraints');
    $role->add_cap('assign_tour_constraints');

    // Rôle visitor
    add_role(
        'visitor',
        'Visiteur',
        [

            'read_private_places' => true,
            'read_private_guided_tours' => true,


        ]
    );

    // Rôle organisator
    add_role(
        'organisator',    // idenfiant du rôle
        'Organisateur',   // Libéllé du rôle


        // Liste des "capabilities" (droits) accordés au rôle "customer"
        [
            'view_admin_as' => true,
            'view_admin_as_role_defaults' => true,

            'read' => true, // important to access to the wordpress dashboard (backoffice)
            'delete_others_pages' => true,
            'delete_pages' => true,
            'delete_private_pages' => true,
            'delete_published_pages' => true,
            'edit_others_pages' => true,
            'edit_pages' => true,
            'edit_private_pages' => true,
            'edit_published_pages' => true,
            'publish_pages' => true,
            'read_private_pages' => true,

            'delete_guided_tours' => true,
            'delete_others_guided_tours' => true,
            'delete_others_places' => true,
            'delete_others_posts' => true,

            'delete_places' => true,

            'delete_private_guided_tours' => true,
            'delete_private_places' => true,
            'delete_private_posts' => true,

            'delete_published_guided_tours' => true,
            'delete_published_places' => true,

            'edit_guided_tours' => true,
            'edit_others_guided_tours' => true,
            'edit_others_places' => true,

            'edit_places' => true,
            'edit_private_guided_tours' => true,

            'edit_published_guided_tours' => true,
            'edit_published_places' => true,

            'publish_guided_tours' => true,
            'publish_places' => true,

            'read_private_guided_tours' => true,
            'read_private_places' => true,
            'read_private_posts' => true,
            'upload_files' => true,

            'create_users' => true,
            'delete_users' => true,
            'edit_users' => true,
            'list_users' => true,
            'promote_users' => true,
            'remove_users' => true,

            'manage_tour_thematics' => true,
            'edit_tour_thematics' => true,
            'delete_tour_thematics' => true,
            'assign_tour_thematics' => true,
            'manage_place_types' => true,
            'edit_place_types' => true,
            'delete_place_types' => true,
            'assign_place_types' => true,
        ]
    );
});
