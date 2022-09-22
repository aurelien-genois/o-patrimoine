<?php

// https://developer.wordpress.org/reference/hooks/wp_ajax_action/
// https://developer.wordpress.org/reference/hooks/wp_ajax_nopriv_action/
// https://capitainewp.io/formations/developper-theme-wordpress/wordpress-ajax/

function filter_guided_tours()
{
    if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'opatrimoine_filter_guided_tours')) {
        wp_send_json_error('Vous n\'avez pas l\'autorisation d\'effectuer cette action.', 403);
    }
    // post data are defined in ajax, not the same name as php
    if (!isset($_POST['placeId']) || !isset($_POST['date']) || !isset($_POST['thematic']) || !isset($_POST['constraint'])) {
        wp_send_json_error('Il manque des données pour effectuer cette action.', 403);
    }
    $placeId = intval($_POST['placeId']);
    $date = sanitize_text_field($_POST['date']);
    $thematic = sanitize_text_field($_POST['thematic']);
    $constraint = sanitize_text_field($_POST['constraint']);

    if (get_post_status($placeId) !== 'publish') {
        wp_send_json_error('Ce lieu n\'est pas publié.', 403);
    }

    $args = [
        'posts_per_page' => 5,
        'post_type' => 'guided_tour',
        'paged' => 1,
    ];

    $meta_query['relation'] = 'AND';
    $meta_query[] = [
        'key' => 'guided_tour_place',
        'value' => $placeId,
        'compare' => '=',
    ];
    if (!empty($date)) {
        // $date format is 2022-09-29
        // guided_tour_date format is 20220929 in BDD
        $meta_query[] = [
            'key' => 'guided_tour_date',
            'value' => str_replace('-', '', $date),
            'compare' => '=',
        ];
    }

    $tax_query['relation'] = 'AND';
    if (!empty($thematic)) {
        $tax_query[] = [
            'taxonomy' => 'tour_thematic',
            'field' => 'slug',
            'terms' => $thematic,
        ];
    }
    if (!empty($constraint)) {
        $tax_query[] = [
            'taxonomy' => 'tour_constraint',
            'field' => 'slug',
            'terms' => $constraint,
            'operator' => 'NOT IN',
        ];
    }
    $args['meta_query'] = $meta_query;
    $args['tax_query'] = $tax_query;
    $guidedTours = new WP_Query($args);

    ob_start();
    if ($guidedTours->have_posts()) {
        while ($guidedTours->have_posts()) {
            $guidedTours->the_post();
            get_template_part('templates/partials/guided-tour');
        }
        wp_reset_postdata();
    } else {
        echo '<p class="text-center">Aucune visite trouvé pour ses critères.</p>';
    }
    $data = ob_get_clean();

    wp_send_json_success($data);
}

add_action('wp_ajax_filter_guided_tours', 'filter_guided_tours');
add_action('wp_ajax_nopriv_filter_guided_tours', 'filter_guided_tours');

function load_guided_tours()
{
    if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'opatrimoine_load_guided_tours')) {
        wp_send_json_error('Vous n\'avez pas l\'autorisation d\'effectuer cette action.', 403);
    }
    // post data are defined in ajax, not the same name as php
    if (!isset($_POST['placeId']) || !isset($_POST['date']) || !isset($_POST['thematic']) || !isset($_POST['constraint']) || !isset($_POST['page'])) {
        wp_send_json_error('Il manque des données pour effectuer cette action.', 403);
    }
    $placeId = intval($_POST['placeId']);
    $date = sanitize_text_field($_POST['date']);
    $thematic = sanitize_text_field($_POST['thematic']);
    $constraint = sanitize_text_field($_POST['constraint']);
    $page = sanitize_text_field($_POST['page']);

    if (get_post_status($placeId) !== 'publish') {
        wp_send_json_error('Ce lieu n\'est pas publié.', 403);
    }

    $args = [
        'posts_per_page' => 5,
        'post_type' => 'guided_tour',
        'paged' => $page,
    ];

    $meta_query['relation'] = 'AND';
    $meta_query[] = [
        'key' => 'guided_tour_place',
        'value' => $placeId,
        'compare' => '=',
    ];
    if (!empty($date)) {
        // $date format is 2022-09-29
        // guided_tour_date format is 20220929 in BDD
        $meta_query[] = [
            'key' => 'guided_tour_date',
            'value' => str_replace('-', '', $date),
            'compare' => '=',
        ];
    }

    $tax_query['relation'] = 'AND';
    if (!empty($thematic)) {
        $tax_query[] = [
            'taxonomy' => 'tour_thematic',
            'field' => 'slug',
            'terms' => $thematic,
        ];
    }
    if (!empty($constraint)) {
        $tax_query[] = [
            'taxonomy' => 'tour_constraint',
            'field' => 'slug',
            'terms' => $constraint,
            'operator' => 'NOT IN',
        ];
    }
    $args['meta_query'] = $meta_query;
    $args['tax_query'] = $tax_query;
    $guidedTours = new WP_Query($args);

    ob_start();
    if ($guidedTours->have_posts()) {
        while ($guidedTours->have_posts()) {
            $guidedTours->the_post();
            get_template_part('templates/partials/guided-tour');
        }
        wp_reset_postdata();
    }
    $data = ob_get_clean();

    wp_send_json_success($data);
}

add_action('wp_ajax_load_guided_tours', 'load_guided_tours');
add_action('wp_ajax_nopriv_load_guided_tours', 'load_guided_tours');
