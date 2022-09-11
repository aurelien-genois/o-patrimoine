<?php

acf_add_local_field_group(
    [
        'key' => 'group_options',
        'title' => 'Options',
        'fields' => [
            [
                'key' => 'field_options_tab_identity',
                'name' => '',
                'label' => 'Identité',
                'type' => 'tab',
                'placement' => 'top',
            ],
            [
                'key' => 'field_options_logo',
                'name' => 'options_logo',
                'label' => 'Logo',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'thumbnail',
                'library' => 'all',
            ],
            [
                'key' => 'field_options_colors',
                'name' => 'options_colors',
                'label' => 'Couleur',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_options_color_main',
                        'name' => 'main',
                        'label' => 'Principal',
                        'type' => 'color_picker',
                        'wrapper' => array(
                            'width' => '25',
                        )
                    ],
                    [
                        'key' => 'field_options_color_second',
                        'name' => 'second',
                        'label' => 'Second',
                        'type' => 'color_picker',
                        'wrapper' => array(
                            'width' => '25',
                        )
                    ],
                    [
                        'key' => 'field_options_color_third',
                        'name' => 'third',
                        'label' => 'Troisième',
                        'type' => 'color_picker',
                        'wrapper' => array(
                            'width' => '25',
                        )
                    ],
                    [
                        'key' => 'field_options_color_fourth',
                        'name' => 'fourth',
                        'label' => 'Quatrième',
                        'type' => 'color_picker',
                        'wrapper' => array(
                            'width' => '25',
                        )
                    ],
                ],
            ],
        ],
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'opatrimoine-options',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'seamless',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
    ]
);

