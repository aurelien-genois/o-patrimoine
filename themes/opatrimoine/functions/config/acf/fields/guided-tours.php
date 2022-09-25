<?php

acf_add_local_field_group(
    [
        'key' => 'group_guided_tour',
        'title' => 'Détail d\'une visite',
        'fields' => [
            [
                'key' => 'field_guided_tour_date',
                'label' => 'Date',
                'name' => 'guided_tour_date',
                'type' => 'date_picker',
                'wrapper' => ['width' => '50'],
                'required' => 1,
                'display_format' => 'd/m/Y',
                'return_format' => 'Y/m/d',
                'first_day' => 1,
            ],
            [
                'key' => 'field_guided_tour_hour',
                'label' => 'Heure',
                'name' => 'guided_tour_hour',
                'type' => 'time_picker',
                'wrapper' => ['width' => '50'],
                'required' => 1,
                'display_format' => 'H:i',
                'return_format' => 'H\hi',
            ],
            [
                'key' => 'field_guided_tour_duration',
                'label' => 'Durée',
                'name' => 'guided_tour_duration',
                'type' => 'time_picker',
                'wrapper' => ['width' => '50'],
                'required' => 0,
                'display_format' => 'H:i',
                'return_format' => 'H\hi',
            ],
            [
                'key' => 'field_guided_tour_total_persons',
                'label' => 'Nombre de places',
                'name' => 'guided_tour_total_persons',
                'type' => 'number',
                'wrapper' => ['width' => '50'],
                'min' => 0,
                'required' => 1,
            ],
            [
                'key' => 'field_guided_tour_total_reservations',
                'label' => 'Nombre de réservations',
                'name' => 'guided_tour_total_reservations',
                'type' => 'number',
                'default_value' => 0,
                'wrapper' => ['width' => '50'],
                'required' => 0,
                'min' => 0,
                'readonly' => 1,
            ],
            [
                'key' => 'field_guided_tour_place',
                'label' => 'Lieu',
                'name' => 'guided_tour_place',
                'type' => 'post_object',
                'wrapper' => ['width' => '50'],
                'required' => 1,
                'post_type' => [0 => 'place',],
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'id',
                'ui' => 1,
            ],
        ],
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'guided_tour',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ]
);
