<?php

function search_only_in_titles($where, $wp_query)
{
    global $wpdb;

    if (!$wp_query->is_main_query()) {
        $queryArgs = isset($_POST['query']) ? array_map('esc_attr', $_POST['query']) : array();
        $placeName = $queryArgs['custom_s'];
    }
    if (!empty($_GET['s']) && mb_strlen($_GET['s']) > 0 && mb_strlen($_GET['s']) <= 100) {
        $placeName = htmlspecialchars($_GET['s']);
    }
    if (isset($placeName)) {

        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . $wpdb->esc_like(($placeName)) . '%\'';
    }
    return $where;
}

// ***************************
// ********* SEARCH **********
// ***************************
function custom_place_archive_query($query)
{

    if ($query->is_main_query() && !is_admin() && is_post_type_archive('place')) {
        $query->set('posts_per_page', 4);
        $query->set('paged', 1);
        if (!empty($_GET) || get_query_var('guided_tour_thematic') || get_query_var('place_type')) {

            $placesIdsFromTourDate = [];
            if (isset($_GET['tour_date']) && !empty($_GET['tour_date'])) {
                // get all guided-tours by date
                $date = sanitize_text_field($_GET['tour_date']);
                $findGuidedTours = get_posts(
                    [
                        'posts_per_page' => -1,
                        'post_type'      => 'guided_tour',
                        'meta_query'     => [
                            [
                                // $date format is 2022-09-29
                                // guided_tour_date format is 20220929 in BDD
                                'key'     => 'guided_tour_date',
                                'value'   => str_replace('-', '', $date),
                                'compare' => '=',
                            ],
                        ],
                    ]
                );
                // make an array of id from field_guided_tour_place field
                if (!empty($findGuidedTours) && is_array($findGuidedTours)) {
                    foreach ($findGuidedTours as $guidedTour) {
                        $placeId = get_field('field_guided_tour_place', $guidedTour->ID);
                        if ($placeId && !in_array($placeId, $placesIdsFromTourDate)) {
                            $placesIdsFromTourDate[] = $placeId;
                        }
                    }
                }
                if (empty($placesIdsFromTourDate)) {
                    $placesIdsFromTourDate[] = 0; // not found
                }
            }

            $placesIdsFromTourThematic = [];
            if (
                (isset($_GET['guided_tour_thematic']) && !empty($_GET['guided_tour_thematic'])) ||
                get_query_var('guided_tour_thematic')
            ) {
                // get all guided-tours by thematic
                $thematic = $_GET['guided_tour_thematic'] ?: get_query_var('guided_tour_thematic');
                $thematic = sanitize_text_field($thematic);
                $findGuidedTours = get_posts(
                    [
                        'posts_per_page' => -1,
                        'post_type'      => 'guided_tour',
                        'tax_query'      => [
                            [
                                'taxonomy' => 'tour_thematic',
                                'field'    => 'slug',
                                'terms'    => $thematic,
                            ]
                        ],
                    ]
                );
                // make an array of id from field_guided_tour_place field
                if (!empty($findGuidedTours) && is_array($findGuidedTours)) {
                    foreach ($findGuidedTours as $guidedTour) {
                        $placeId = get_field('field_guided_tour_place', $guidedTour->ID);
                        if ($placeId && !in_array($placeId, $placesIdsFromTourThematic)) {
                            $placesIdsFromTourThematic[] = $placeId;
                        }
                    }
                }
                if (empty($placesIdsFromTourThematic)) {
                    $placesIdsFromTourThematic[] = 0; // not found
                }
            }

            // two arrays => array_intersect for apply both filters on guided tour
            if (count($placesIdsFromTourDate) && count($placesIdsFromTourThematic)) {
                $placesIds = array_intersect($placesIdsFromTourDate, $placesIdsFromTourThematic);
                if (empty($placesIds)) {
                    $placesIds[] = 0; // not found
                }
                $query->set('post__in', $placesIds);
            } else if (count($placesIdsFromTourDate)) {
                $query->set('post__in', $placesIdsFromTourDate);
            } else if (count($placesIdsFromTourThematic)) {
                $query->set('post__in', $placesIdsFromTourThematic);
            }

            if (isset($_GET['place_type']) && $_GET['place_type'] !== 0) {
                $_GET['place_type'] = htmlspecialchars($_GET['place_type']);
            } else {
                unset($_GET['place_type']);
            }

            if (!empty($_GET['place_department']) && $_GET['place_department'] > 0) {
                $query->set('meta_query', [
                    [
                        'key'   => 'place_department',
                        'value' => htmlspecialchars($_GET['place_department']),
                    ],
                ]);
            }
        }
        add_filter('posts_where', 'search_only_in_titles', 10, 2);
    }
}
add_action('pre_get_posts', 'custom_place_archive_query', 10, 1);



// ***************************
// ********** AJAX ***********
// ***************************
function load_places()
{
    if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'opatrimoine_load_places')) {
        wp_send_json_error('Vous n\'avez pas l\'autorisation d\'effectuer cette action.', 403);
    }
    // post data are defined in ajax, not the same name as php
    if (
        !isset($_POST['place_type']) ||
        !isset($_POST['deparment']) ||
        !isset($_POST['tour_date']) ||
        !isset($_POST['tour_thematic']) ||
        !isset($_POST['s']) ||
        !isset($_POST['page'])
    ) {
        wp_send_json_error('Il manque des données pour effectuer cette action.', 403);
    }
    $place_type = sanitize_text_field($_POST['place_type']);
    $deparment = sanitize_text_field($_POST['deparment']);
    $tour_date = sanitize_text_field($_POST['tour_date']);
    $tour_thematic = sanitize_text_field($_POST['tour_thematic']);
    $s = sanitize_text_field($_POST['s']);
    $page = sanitize_text_field($_POST['page']);

    $args = [
        'posts_per_page' => 4,
        'post_type'      => 'place',
        'paged'          => $page,
        's'              => $s,
    ];

    $meta_query['relation'] = 'AND';
    $tax_query['relation'] = 'AND';

    if (!empty($deparment)) {
        $meta_query[] = [
            'key'     => 'place_department',
            'value'   => $deparment,
            'compare' => '=',
        ];
    }

    $placesIdsFromTourDate = [];
    if (isset($tour_date) && !empty($tour_date)) {
        // get all guided-tours by date
        $date = sanitize_text_field($tour_date);
        $findGuidedTours = get_posts(
            [
                'posts_per_page' => -1,
                'post_type'      => 'guided_tour',
                'meta_query'     => [
                    [
                        // $date format is 2022-09-29
                        // guided_tour_date format is 20220929 in BDD
                        'key'     => 'guided_tour_date',
                        'value'   => str_replace('-', '', $date),
                        'compare' => '=',
                    ],
                ],
            ]
        );
        // make an array of id from field_guided_tour_place field
        if (!empty($findGuidedTours) && is_array($findGuidedTours)) {
            foreach ($findGuidedTours as $guidedTour) {
                $placeId = get_field('field_guided_tour_place', $guidedTour->ID);
                if ($placeId && !in_array($placeId, $placesIdsFromTourDate)) {
                    $placesIdsFromTourDate[] = $placeId;
                }
            }
        }
        if (empty($placesIdsFromTourDate)) {
            $placesIdsFromTourDate[] = 0; // not found
        }
    }

    $placesIdsFromTourThematic = [];
    if (isset($tour_thematic) && !empty($tour_thematic)) {
        // get all guided-tours by thematic
        $thematic = sanitize_text_field($tour_thematic);
        $findGuidedTours = get_posts(
            [
                'posts_per_page' => -1,
                'post_type'      => 'guided_tour',
                'tax_query'      => [
                    [
                        'taxonomy' => 'tour_thematic',
                        'field'    => 'slug',
                        'terms'    => $thematic,
                    ]
                ],
            ]
        );
        // make an array of id from field_guided_tour_place field
        if (!empty($findGuidedTours) && is_array($findGuidedTours)) {
            foreach ($findGuidedTours as $guidedTour) {
                $placeId = get_field('field_guided_tour_place', $guidedTour->ID);
                if ($placeId && !in_array($placeId, $placesIdsFromTourThematic)) {
                    $placesIdsFromTourThematic[] = $placeId;
                }
            }
        }
        if (empty($placesIdsFromTourThematic)) {
            $placesIdsFromTourThematic[] = 0; // not found
        }
    }

    // two arrays => array_intersect for apply both filters on guided tour
    if (count($placesIdsFromTourDate) && count($placesIdsFromTourThematic)) {
        $placesIds = array_intersect($placesIdsFromTourDate, $placesIdsFromTourThematic);
        if (empty($placesIds)) {
            $placesIds[] = 0; // not found
        }
        $args['post__in'] = $placesIds;
    } else if (count($placesIdsFromTourDate)) {
        $args['post__in'] = $placesIdsFromTourDate;
    } else if (count($placesIdsFromTourThematic)) {
        $args['post__in'] = $placesIdsFromTourThematic;
    }

    if (!empty($place_type)) {
        $tax_query[] = [
            'taxonomy' => 'place_type',
            'field'    => 'slug',
            'terms'    => $place_type,
        ];
    }

    $args['meta_query'] = $meta_query;
    $args['tax_query'] = $tax_query;
    $places = new WP_Query($args);

    ob_start();
    if ($places->have_posts()) {
        while ($places->have_posts()) {
            $places->the_post();
            get_template_part('templates/partials/place-thumbnail');
        }
        wp_reset_postdata();
    }
    $data = ob_get_clean();

    wp_send_json_success($data);
}
add_action('wp_ajax_load_places', 'load_places');
add_action('wp_ajax_nopriv_load_places', 'load_places');