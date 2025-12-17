<?php
require_once 'ReservationsModel.php';

class ReservationsController
{
    public $reservationModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationsModel();
    }

    protected function mustBeConnected()
    {
        if (!is_user_logged_in()) {
            wp_redirect(
                wp_login_url()
            );
            return false;
        }
        return true;
    }

    public function registerReservationsToGuidedTour($guidedTourId, $visitorId, $npPlaces)
    {
        $redirection = get_home_url();

        $this->mustBeConnected();

        if ($visitorId === null) {
            $user = wp_get_current_user();
        } else {
            $user = new WP_User(($visitorId));
        }

        $guidedTour = get_post($guidedTourId);
        if ($guidedTour && $guidedTour->post_type === "guided_tour") {
            $redirection = get_the_permalink($guidedTour->guided_tour_place);

            // acf function get_field() isn't necessary to get a custom field => WP automatically check for custom field
            $maxPersons = $guidedTour->guided_tour_total_persons;
            $currentNbReservations = $guidedTour->guided_tour_total_reservations;
            $availablePlacesReservations = (int) $maxPersons - (int) $currentNbReservations;


            if (
                is_int(+$npPlaces) &&
                $npPlaces <= $availablePlacesReservations &&
                $npPlaces > 0
            ) {

                if ($this->reservationModel->canReserve($guidedTourId, $user)) {

                    // insert reservation in the custom-table
                    $isInsert = $this->reservationModel->insert(['guided_tour_id' => $guidedTourId, 'visitor_id' => $visitorId, 'nb_of_reservations' => $npPlaces]);

                    if ($isInsert) {
                        // increment totalreservations
                        $newNbReservations = (int) $currentNbReservations + (int) $npPlaces;
                        update_field('guided_tour_total_reservations', $newNbReservations, $guidedTourId);
                    } else {
                        $error = 'Erreur en BDD';
                        wp_redirect($redirection . '?error-reservation=' . $error);
                    }
                }
            }
        }
        wp_redirect($redirection);
    }

    public function deleteByTourIdAndVisitorId($guidedTourId, $visitorId, $currentLocationUrl)
    {
        $redirection = get_home_url();

        $guidedTour = get_post($guidedTourId);
        if ($guidedTour && $guidedTour->post_type == "guided_tour") {

            $redirection = $currentLocationUrl;

            // decrement guidedTour
            $currentReservation = $this->reservationModel->getReservationByGuidedTourIdAndVisitorId($guidedTourId, $visitorId);
            $newNbReservations = (int) $guidedTour->guided_tour_total_reservations - (int) $currentReservation;
            update_field('guided_tour_total_reservations', $newNbReservations, $guidedTourId);

            // delete reservation
            $this->reservationModel->deleteByTourIdAndVisitorId($guidedTourId, $visitorId);
        }
        wp_redirect($redirection);
    }

    public function getReservationByGuidedTourIdForCurrentUser($guidedTourId)
    {
        $guidedTour = get_post($guidedTourId);
        if ($guidedTour->post_type != 'guided_tour') {
            wp_redirect(home_url());
            exit();
        }


        $user = wp_get_current_user();

        $roles = $user->roles;
        if (!in_array('visitor', $roles)) {
            return 0;
        } else {
            return $this->reservationModel->getReservationByGuidedTourIdAndVisitorId($guidedTourId, $user->ID);
        }
    }

    public function getGuidedToursByCurrentUser()
    {

        $user = wp_get_current_user();

        $roles = $user->roles;
        if (!in_array('visitor', $roles)) {
            return 0;
        } else {
            return $this->reservationModel->getGuidedToursByVisitorId($user->ID);
        }
    }
}