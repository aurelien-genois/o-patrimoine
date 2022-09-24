<?php
require_once 'ReservationsController.php';

function getReservationByGuidedTourIdForCurrentUser($guidedTourId)
{
    $reservationsController = new ReservationsController();
    return $reservationsController->getReservationByGuidedTourIdForCurrentUser($guidedTourId);
}
