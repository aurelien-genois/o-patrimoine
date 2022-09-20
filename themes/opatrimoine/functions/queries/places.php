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
        $query->set('posts_per_page', 12);
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