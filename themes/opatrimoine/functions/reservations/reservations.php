<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-load.php';

require_once 'ReservationsController.php';
$reservationsController = new ReservationsController();

if (isset($_POST['register_reservations']) || isset($_POST['update_reservations'])) {
    if (empty($_POST['current_location'])) {
        wp_redirect(home_url());
        exit();
    } else {
        $pageID = htmlspecialchars($_POST['current_location']);
        $page_url = get_permalink($pageID);

        $user = wp_get_current_user();
        $roles = $user->roles;
        if (!in_array('visitor', $roles)) {
            $error = 'non autorisé';
            wp_redirect($page_url . '?error-reservation=' . $error);
            exit();
        }


        if (empty($_POST['guided_tour_id']) || empty($_POST['nb_places'])) {
            $error = 'champs manquants';
            wp_redirect($page_url . '?error-reservation=' . $error);
        } else {
            $reservationsController->registerReservationsToGuidedTour(htmlspecialchars($_POST['guided_tour_id']), $user->ID, htmlspecialchars($_POST['nb_places']));
        }
    }
}

if (isset($_POST['delete_reservations'])) {
    if (empty($_POST['current_location'])) {
        wp_redirect(home_url());
        exit();
    } else {
        $pageID = htmlspecialchars($_POST['current_location']);
        $page_url = get_permalink($pageID);

        $user = wp_get_current_user();
        $roles = $user->roles;
        if (!in_array('visitor', $roles)) {
            $error = 'non autorisé';
            wp_redirect($page_url . '?error-reservation=' . $error);
            exit();
        }

        if (empty($_POST['guided_tour_id']) || empty($_POST['current_location'])) {
            $error = 'champs manquants';
            wp_redirect($page_url . '?error-reservation=' . $error);
        } else {
            $reservationsController->deleteByTourIdAndVisitorId(htmlspecialchars($_POST['guided_tour_id']), $user->ID, $page_url);
        }
    }
}