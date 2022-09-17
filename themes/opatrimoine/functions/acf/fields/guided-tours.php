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
                'type' => 'date_time_picker',
                'wrapper' => ['width' => '50'],
                'required' => 0,
                'display_format' => 'd/m/Y H:i',
                'return_format' => 'Y/m/d H:i', // ! to confirm when manipulate date (ordering, filter, ...)
                'first_day' => 1,
            ],
            [
                'key' => 'field_guided_tour_duration',
                'label' => 'Durée',
                'name' => 'guided_tour_duration',
                'type' => 'text',
                'wrapper' => ['width' => '50'],
                'required' => 0,
            ],
            [
                'key' => 'field_guided_tour_total_persons',
                'label' => 'Nombre de places',
                'name' => 'guided_tour_total_persons',
                'type' => 'text',
                'wrapper' => ['width' => '50'],
                'required' => 0,
            ],
            [   
                'key' => 'field_guided_tour_total_reservations',
                'label' => 'Nombre de réservations',
                'name' => 'guided_tour_total_reservations',
                'type' => 'text',
                'wrapper' => ['width' => '50'],
                'required' => 0,
                'readonly' => 1,
            ],
            [   
                'key' => 'field_guided_tour_total_reservations',
                'label' => 'Nombre de places réservées',
                'name' => 'guided_tour_total_reservations',
                'type' => 'text',
                'wrapper' => ['width' => '50'],
                'required' => 0,
                'readonly' => 1,
            ],
            [
                'key' => 'field_guided_tour_place',
                'label' => 'Lieu',
                'name' => 'guided_tour_place',
                'type' => 'post_object',
                'wrapper' => ['width' => '50'],
                'required' => 0,
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


