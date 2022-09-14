<?php

acf_add_local_field_group(
    [
        'key' => 'group_home',
        'title' => 'Page d\'accueil',
        'fields' => [
            [
                'key' => 'field_home_banner_slides',
                'label' => 'Bannière',
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

