<?php 

function search_only_in_titles( $where, $wp_query ) {
    global $wpdb;

    if (!$wp_query->is_main_query()) {
        $queryArgs = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();
        $placeName = $queryArgs['custom_s'];
    }
    if (!empty($_GET['s']) && mb_strlen($_GET['s']) > 0 && mb_strlen($_GET['s']) <= 100) {
        $placeName = htmlspecialchars($_GET['s']);
    }
    if (isset($placeName))  {

        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . $wpdb->esc_like (( $placeName ) ) . '%\'';
    }
    return $where;
}

function custom_place_archive_query($query) {
    
    if($query->is_main_query() && !is_admin() && is_post_type_archive('place')) {
        $query->set('posts_per_page', 9);
        $query->set('paged', 1);
        if(!empty($_GET)) {
            if(isset($_GET['place_type'])) {
                $_GET['place_type'] = htmlspecialchars($_GET['place_type']);   
            }
            if (!empty($_GET['place_department']) && $_GET['place_department'] > 0 && $_GET['place_department'] <= 3) {
                $query->set('meta_query', [
                    [
                        'key' => 'place_department',
                        'value' => htmlspecialchars($_GET['place_department']),
                    ],
                ]);
            }
        }   
        add_filter( 'posts_where', 'search_only_in_titles', 10, 2 );  
    }
    
}
add_action('pre_get_posts', 'custom_place_archive_query',10,1);

function load_places() {
    if(!isset( $_REQUEST['nonce'] ) || !wp_verify_nonce( $_REQUEST['nonce'], 'opatrimoine_load_places' ) ) {
    	wp_send_json_error( 'Vous n\'avez pas l\'autorisation d\'effectuer cette action.', 403 );
  	}
    // post data are defined in ajax, not the same name as php
    if(!isset($_POST['place_type']) || !isset($_POST['deparment']) || !isset($_POST['s']) || !isset($_POST['page'])) {
    	wp_send_json_error( 'Il manque des données pour effectuer cette action.', 403 );
    }
    $place_type = intval($_POST['place_type']);
    $deparment = sanitize_text_field($_POST['deparment']);
    $s = sanitize_text_field($_POST['s']);
    $page = sanitize_text_field($_POST['page']);

    $args = [
        'posts_per_page' => 9,
        'post_type' => 'place',
        'paged' => $page,
        's' => $s,
    ];

    $meta_query['relation'] = 'AND';
    $meta_query[] = [];
    if(!empty($deparment)) {
        $meta_query[] = [
            'key' => 'place_department',
            'value' => $deparment, 
            'compare' => '=',
        ];
    }

    $tax_query['relation'] = 'AND';

    if(!empty($constraint)) {
        $tax_query[] = [
            'taxonomy' => 'place_type',
            'field' => 'slug',
            'terms' => $place_type,
        ];
    }
    $args['meta_query'] = $meta_query;
    $args['tax_query'] = $tax_query;
    $places = new WP_Query($args);

    ob_start();
    if($places->have_posts()) {
        while($places->have_posts()) {
            $places->the_post();
            get_template_part('templates/partials/place-thumbnail');
        }
        wp_reset_postdata();
    }
    $data = ob_get_clean();

    wp_send_json_success($data);
}

add_action( 'wp_ajax_load_places', 'load_places' );
add_action( 'wp_ajax_nopriv_load_places', 'load_places' );