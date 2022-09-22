<?php

add_action( 'init',function(){

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

    // var_dump($role->capabilities);die();
    // todo add roles
    // member
    // organisator (= editor/contributor/author ? check capabilities (page, post, ...))
});