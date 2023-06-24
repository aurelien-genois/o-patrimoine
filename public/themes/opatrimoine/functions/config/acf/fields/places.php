<?php

acf_add_local_field_group(
    [
        'key' => 'group_place',
        'title' => 'Détail du lieu',
        'fields' => [
            [
                'key' => 'field_place_phone',
                'label' => 'Téléphone',
                'name' => 'place_phone',
                'type' => 'text',
                'wrapper' => ['width' => '50'],
                'required' => 0,
            ],
            [
                'key' => 'field_place_site',
                'label' => 'Url du site',
                'name' => 'place_site',
                'wrapper' => ['width' => '50'],
                'type' => 'url',
                'required' => 0,
            ],
            [
                'key' => 'field_place_adress',
                'label' => 'Adresse',
                'name' => 'place_adress',
                'type' => 'textarea',
                'required' => 0,
            ],
            [
                'key' => 'field_place_department',
                'label' => 'Département',
                'name' => 'place_department',
                'wrapper' => ['width' => '50'],
                'type' => 'select',
                'required' => 0,
                'choices' => get_departments(),
                'default_value' => '',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
            ],
            // exemple from googlemap lat 50.69095245841029, lng 3.181566614446075
            [
                'key' => 'field_place_coordinates',
                'label' => 'Coordonnées',
                'instructions' => '(pour la carte intéractive)',
                'name' => 'place_coordinates',
                'wrapper' => ['width' => '50'],
                'type' => 'group',
                'required' => 0,
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_place_lat',
                        'label' => 'Latitude',
                        'name' => 'lat',
                        'type' => 'text',
                        'required' => 0,
                    ],
                    [
                        'key' => 'field_place_lng',
                        'label' => 'Longitude',
                        'name' => 'lng',
                        'type' => 'text',
                        'required' => 0,
                    ],
                ]
            ],
            [
                'key' => 'field_place_rating',
                'label' => 'Note',
                'name' => 'place_rating',
                'wrapper' => ['width' => '50'],
                'type' => 'number',
                'min' => 0,
                'max' => 5,
                'required' => 0,
                'readonly' => 1,
            ],
        ],
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'place',
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
