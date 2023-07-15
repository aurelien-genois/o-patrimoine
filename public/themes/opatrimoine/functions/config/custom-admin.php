<?php

// add department column for Lieux listing
function set_custom_place_column($columns)
{
    $columns['department'] = __('Département', 'opatrimoine');

    return $columns;
}
add_filter('manage_place_posts_columns', 'set_custom_place_column', 10, 1);

function custom_place_column($column, $post_id)
{
    switch ($column) {
        case 'department':
            $fieldObj = get_field_object('place_department', $post_id);
            if ($fieldObj) {
                $choices = $fieldObj['choices'];
                if (is_array($choices) && !empty($choices)) {
                    $selectedChoice = $choices[$fieldObj['value']];
                    echo $selectedChoice;
                }
            }
            break;
    }
}
add_filter('manage_place_posts_custom_column', 'custom_place_column', 10, 2);

// add lieu column for visite listing
function set_custom_guided_tour_column($columns)
{
    $columns['place'] = __('Lieu', 'opatrimoine');

    return $columns;
}
add_filter('manage_guided_tour_posts_columns', 'set_custom_guided_tour_column', 10, 1);

function custom_guided_tour_column($column, $post_id)
{
    switch ($column) {
        case 'place':
            $placeId = get_field('field_guided_tour_place', $post_id);
            if ($placeId) {
                echo get_the_title($placeId) ?: '';
            }
            break;
    }
}
add_filter('manage_guided_tour_posts_custom_column', 'custom_guided_tour_column', 10, 2);