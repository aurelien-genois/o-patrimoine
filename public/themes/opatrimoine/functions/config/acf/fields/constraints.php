<?php

if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group([
        'key'                   => 'group_constraint',
        'title'                 => 'Icône',
        'fields'                => [
            [
                'key'           => 'field_constraint_icon',
                'label'         => 'Icône',
                'name'          => 'constraint_icon',
                'type'          => 'image',
                'required'      => 0,
                'return_format' => 'id',
                'preview_size'  => 'medium',
                'library'       => 'all',
                'min_width'     => '',
                'min_height'    => '',
                'min_size'      => '',
                'max_width'     => '',
                'max_height'    => '',
                'max_size'      => '',
                'mime_types'    => '',
            ],
        ],
        'location'              => [
            [
                [
                    'param'    => 'taxonomy',
                    'operator' => '==',
                    'value'    => 'tour_constraint',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => '',
        'active'                => true,
        'description'           => '',
    ]);

endif;