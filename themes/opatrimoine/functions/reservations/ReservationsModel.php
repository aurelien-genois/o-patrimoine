<?php
class ReservationsModel
{
    private $wpdb;
    private $tableName;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->tableName = $wpdb->prefix . 'reservations';

        $sql = "
            CREATE TABLE IF NOT EXISTS " . $this->tableName . " (
                `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `guided_tour_id` int unsigned NOT NULL,
                `visitor_id` int unsigned NOT NULL,
                `nb_of_reservations` int NOT NULL,
                `created_at` datetime NULL,
                `updated_at` datetime NULL
            );
        ";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $result = dbDelta($sql);
    }

    public function executeQuery($sql, $parameters = [])
    {
        if (empty($parameters)) {
            return $this->wpdb->get_results($sql);
        } else {
            $preparedStatement = $this->wpdb->prepare(
                $sql,
                $parameters,
            );
            return $this->wpdb->get_results($preparedStatement);
        }
    }

    public function insert($data = [])
    {
        if (isset($data['guided_tour_id']) && isset($data['visitor_id']) && isset($data['nb_of_reservations'])) {
            $tz = new DateTimeZone('EUROPE/PARIS');
            $d = new DateTime('now', $tz);

            $row = $this->wpdb->get_row('
                SELECT * FROM ' . $this->tableName . '
                    WHERE `guided_tour_id` = "' . $data['guided_tour_id'] . '"
                    AND `visitor_id` = "' . $data['visitor_id'] . '"
            ');
            if ($row) {
                // if update the nb_of_reservations
                $data['updated_at'] = $d->format("Y-m-d H:i:s");

                $update = $this->wpdb->update(
                    $this->tableName,
                    $data,
                    ['id' => $row->id]
                );
                return $update;
            } else {
                $data['created_at'] = $d->format("Y-m-d H:i:s");
                $data['updated_at'] = $d->format("Y-m-d H:i:s");

                $insert = $this->wpdb->insert(
                    $this->tableName,
                    $data
                );
                return $insert;
            }
        }
    }

    public function getAll()
    {
        return $this->wpdb->get_results('SELECT * FROM ' . $this->tableName . ' ORDER BY updated_at DESC');
    }

    public function deleteByTourIdAndVisitorId($guidedTourId, $visitorId)
    {
        $where = [
            'guided_tour_id' => $guidedTourId,
            'visitor_id' => $visitorId,
        ];

        $this->wpdb->delete(
            $this->tableName,
            $where
        );
    }

    public function getGuidedToursByVisitorId($visitorId)
    {
        $sql = "
            SELECT * FROM " . $this->tableName . "
            WHERE
                visitor_id = %d
        ";

        if ($visitorId === 0) {
            return;
        }

        $results = $this->executeQuery(
            $sql,
            [
                $visitorId,
            ],
        );
        // executeQuery return a array of objects representating each lines found :
        // 0 => {#1488 ▼
        //     +"id": "2"
        //     +"guided_tour_id": "967"
        //     +"visitor_id": "51"
        //     +"nb_of_reservations": "3"
        //     +"created_at": "2021-10-04 19:38:19"
        //     +"updated_at": null
        //   }

        $guidedToursIdList = [];
        $numbersOfReservationsByGuidedToursId = [];
        foreach ($results as $reservationsResult) {
            $guidedTourId = $reservationsResult->guided_tour_id;

            $guidedToursIdList[] = $guidedTourId;

            $numbersOfReservationsByGuidedToursId[$guidedTourId] = $reservationsResult->nb_of_reservations;
        }

        $guidedTours = [];
        if (!empty($guidedToursIdList)) {
            $guidedTours = get_posts([
                'include' => $guidedToursIdList,
                'numberposts' => -1,
                'post_type' => 'guided_tour',
            ]);
        }
        return $guidedTours;
    }

    public function getReservationByGuidedTourIdAndVisitorId($guidedTourId, $visitorId)
    {
        $sql = "
            SELECT * FROM " . $this->tableName . "
            WHERE
                guided_tour_id = %d
                AND
                visitor_id = %d
        ";

        $results = $this->executeQuery(
            $sql,
            [
                $guidedTourId,
                $visitorId,
            ],
        );

        $idList = [];
        foreach ($results as $reservationsResult) {
            $idList[] = $reservationsResult->id;
        }

        if (!empty($idList)) {
            // return only the first reservation on this guided tour
            // because can only have one reservation at a time on a guided tour
            return $results[0]->nb_of_reservations;
        } else {
            return false;
        }
    }

    public function canReserve($guidedTourId, WP_User $user)
    {
        $roles = $user->roles;
        if (!in_array('visitor', $roles)) {
            $canReserve = false;
        } else {
            $canReserve = false;
            // if visitor already reserved on this guided-tour, cannot make another reservation
            if ($this->getReservationByGuidedTourIdAndVisitorId($guidedTourId, $user->ID)) {
                $canReserve = false;
            } else {
                $canReserve = true;
            }
        }
        return $canReserve;
    }
}
