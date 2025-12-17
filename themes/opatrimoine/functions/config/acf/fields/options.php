<?php

acf_add_local_field_group(
    [
        'key'                   => 'group_options',
        'title'                 => 'Options',
        'fields'                => [
            [
                'key'       => 'field_options_tab_identity',
                'name'      => '',
                'label'     => 'Identité',
                'type'      => 'tab',
                'placement' => 'top',
            ],
            [
                'key'           => 'field_options_logo',
                'name'          => 'options_logo',
                'label'         => 'Logo',
                'type'          => 'image',
                'return_format' => 'id',
                'preview_size'  => 'thumbnail',
                'library'       => 'all',
            ],
            [
                'key'        => 'field_options_colors',
                'name'       => 'options_colors',
                'label'      => 'Couleur',
                'type'       => 'group',
                'layout'     => 'block',
                'sub_fields' => [
                    [
                        'key'          => 'field_options_color_main',
                        'name'         => 'main',
                        'label'        => 'Principal',
                        'instructions' => 'Textes',
                        'type'         => 'color_picker',
                        'wrapper'      => array(
                            'width' => '25',
                        )
                    ],
                    [
                        'key'          => 'field_options_color_second',
                        'name'         => 'second',
                        'label'        => 'Seconde',
                        'instructions' => 'Titres, boutons, liens',
                        'type'         => 'color_picker',
                        'wrapper'      => array(
                            'width' => '25',
                        )
                    ],
                    [
                        'key'          => 'field_options_color_third',
                        'name'         => 'third',
                        'label'        => 'Troisième',
                        'instructions' => 'Boutons secondaires, liens visités',
                        'type'         => 'color_picker',
                        'wrapper'      => array(
                            'width' => '25',
                        )
                    ],
                    [
                        'key'          => 'field_options_color_fourth',
                        'name'         => 'fourth',
                        'label'        => 'Quatrième',
                        'instructions' => 'Boutons (survol), liens (survol)',
                        'type'         => 'color_picker',
                        'wrapper'      => array(
                            'width' => '25',
                        )
                    ],
                ],
            ],
            [
                'key'          => 'field_options_adress',
                'name'         => 'options_adress',
                'label'        => 'Adresse',
                'type'         => 'textarea',
                'instructions' => 'Adresse à afficher dans le footer',
                'placeholder'  => 'Adresse...',
                'new_lines'    => 'br',
                'rows'         => '5',
                'maxlength'    => '',
            ],
            [
                'key'       => 'field_options_tab_socials',
                'name'      => '',
                'label'     => 'Réseaux sociaux',
                'type'      => 'tab',
                'placement' => 'top',
            ],
            [
                'key'          => 'field_options_socials_links',
                'name'         => 'options_socials_links',
                'label'        => 'Liens réseaux sociaux',
                'instructions' => '5 liens maximum',
                'type'         => 'repeater',
                'min'          => 0,
                'max'          => 5,
                'button_label' => 'Ajouter un réseau social',
                'layout'       => 'block',
                'sub_fields'   => [
                    [
                        'key'     => 'field_options_socials_link',
                        'name'    => 'link',
                        'label'   => 'Réseaux sociaux',
                        'type'    => 'link',
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id'    => '',
                        ),
                    ],
                    array(
                        'key'           => 'group_option_socials_icon',
                        'label'         => 'Icon',
                        'name'          => 'icon',
                        'type'          => 'image',
                        'return_format' => 'id',
                        'preview_size'  => 'thumbnail',
                        'library'       => 'all',
                        'wrapper'       => array(
                            'width' => '50',
                            'class' => '',
                            'id'    => '',
                        ),
                    ),
                ],
            ]
        ],
        'location'              => array(
            array(
                array(
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'opatrimoine-options',
                ),
            ),
        ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'seamless',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => '',
        'active'                => true,
    ]
);

// **********************************
// ******** TEST OPTION PAGE ********
// **********************************
acf_add_local_field_group(
    [
        'key'                   => 'group_options_test',
        'title'                 => 'Options de tests',
        'fields'                => [
            // [
            //     'key'            => 'field_options_registered_date',
            //     'label'          => 'Date',
            //     'name'           => 'options_registered_date',
            //     'instructions'   => '|| ! INUTILISE ! || Champs date utilisée pour générer automatiquement des dates des visites par rapport à aujourd\'hui',
            //     'type'           => 'date_picker',
            //     'wrapper'        => ['width' => '50'],
            //     'required'       => 0,
            //     'display_format' => 'd/m/Y',
            //     'return_format'  => 'Y/m/d',
            //     'first_day'      => 1,
            //     'readonly'       => 1,
            // ],
        ],
        'location'              => array(
            array(
                array(
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'acf-options-test',
                ),
            ),
        ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'seamless',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => '',
        'active'                => true,
    ]
);