<?php

acf_add_local_field_group(
    [
        'key' => 'group_home',
        'title' => 'Page d\'accueil',
        'fields' => [
            [
                'key' => 'field_home_banner_accordion',
                'label' => 'Bannière',
                'name' => '',
                'type' => 'tab',
                'required' => 0,
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'key' => 'field_home_banner_slides',
                'label' => 'Slides',
                'name' => 'home_banner_slides',
                'instructions' => '5 slides maximum',
                'type' => 'repeater',
                'min' => 0,
                'max' => 5,
                'button_label' => 'Ajouter une slide',
                'layout' => 'block',
                'sub_fields' => [
                     [
                        'key' => 'field_home_banner_slide_bg',
                        'label' => 'Image de fond',
                        'name' => 'bg',
                        'type' => 'image',
                        'required' => 0,
                        'return_format' => 'array',
                     ],
                     [
                        'key' => 'field_home_banner_slide_bg_mobile',
                        'label' => 'Image mobile',
                        'name' => 'bg_mobile',
                        'type' => 'image',
                        'required' => 0,
                        'return_format' => 'array',
                     ],
                     [
                        'key' => 'field_home_banner_slide_title',
                        'label' => 'Titre',
                        'name' => 'title',
                        'type' => 'text',
                     ],
                     [
                        'key' => 'field_home_banner_slide_link',
                        'label' => 'Lien',
                        'name' => 'link',
                        'type' => 'link',
                        'return_format' => 'array',
                     ],
                     [
                        'key' => 'field_home_banner_slide_text_color',
                        'label' => 'Couleur du texte',
                        'name' => 'text_color',
                        'type' => 'select',
                        'required' => 0,
                        'choices' => [
                            'black' => 'Noir',
                            'white' => 'Blanc',
                        ],
                        'default_value' => 'black',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value',
                     ],
                ],
            ],
            [
                'key' => 'field_home_sections',
                'label' => 'Sections',
                'name' => '',
                'type' => 'tab',
                'required' => 0,
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'key' => 'field_home_presentation_title',
                'label' => 'Titre section présentation',
                'name' => 'home_presentation_title',
                'type' => 'text',
            ],
            [
                'key' => 'field_home_visit_of_the_day_title',
                'label' => 'Titre section visites du jour',
                'name' => 'home_visit_of_the_day_title',
                'type' => 'text',
            ],
            [
                'key' => 'field_home_visit_of_the_day_link_text',
                'label' => 'Texte du lien des visites du jour',
                'name' => 'home_visit_of_the_day_link_text',
                'type' => 'text',
            ],
            [
                'key' => 'field_home_location_highlight_title',
                'label' => 'Titre section mise en avant d\'un lieu',
                'name' => 'home_location_highlight_title',
                'type' => 'text',
            ],
            [
                'key' => 'field_home_location_highlight_link_text',
                'label' => 'Texte du lien pour le lieu mis en avant',
                'name' => 'home_location_highlight_link_text',
                'type' => 'text',
            ],
            [
                'key' => 'field_home_location_highlight',
                'label' => 'lieu',
                'name' => 'home_location_highlight',
                'type' => 'post_object',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'post_type' => 'location',
                'taxonomy' => '',
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'id',
                'ui' => 1,
            ],

            // * title presentation section
            // * title visite section
            // * title highlight lieux section
        ],
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
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

