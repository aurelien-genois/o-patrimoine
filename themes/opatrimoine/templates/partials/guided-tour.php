<?php
$date = get_field('guided_tour_date', get_the_ID());
$dateTime = new DateTime($date);
// to get month in french https://www.php.net/manual/fr/class.intldateformatter.php
$fmt = datefmt_create(
    'fr-FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'Europe/Paris',
    IntlDateFormatter::GREGORIAN,
    "dd LLLL y",
    // format unicode https://unicode-org.github.io/icu/userguide/format_parse/datetime/
);
$dateStr = datefmt_format($fmt, $dateTime);
$hour = get_field('guided_tour_hour', get_the_ID());
$duration = get_field('guided_tour_duration', get_the_ID());
$totalPersons = get_field('guided_tour_total_persons', get_the_ID());
$totalReservations = get_field('guided_tour_total_reservations', get_the_ID());
$constraints = get_the_terms(get_the_ID(), 'tour_constraint');

$user = wp_get_current_user();

$currentLocationId = 0;
if (isset($args['currentTemplateSlug']) && $args['currentTemplateSlug'] == 'templates/account.php') {
    $currentLocationId = get_page_id_by_template($args['currentTemplateSlug']);
} else {
    $currentLocationId = get_field('field_guided_tour_place', get_the_ID());
}

$currentMemberReservations = getReservationByGuidedTourIdForCurrentUser(get_the_ID());
?>

<article class="bg-grey/20 mb-4 rounded-xl p-4 border border-black border-solid">
    <div class="flex flex-wrap">
        <div class="w-1/2 sm:w-1/3 order-1">
            <?php if ($dateStr) echo '<span>' . $dateStr . '</span>&nbsp;' ?>
            <?php if ($hour) echo '<span>à ' . $hour . '</span>&nbsp;' ?>
            <?php if ($duration) : ?>
                <span class="block sm:inline whitespace-nowrap"><i class="fa-solid fa-hourglass-half"></i>&nbsp;<?= $duration ?></span>
            <?php endif; ?>
        </div>

        <div class="w-1/2 sm:w-1/3 order-2 sm:order-3 text-right">
            <?php if (is_array($constraints) && !empty($constraints)) : ?>
                <div class="flex justify-end">
                    Déconseillé&nbsp;:&nbsp;
                    <?php foreach ($constraints as $constraint) {
                        $iconId = get_field('constraint_icon', $constraint);
                        $iconUrl = wp_get_attachment_image_url($iconId, 'medium', false);
                        echo '<span class="constraint-icon">';
                        if (substr_count($iconUrl, '.svg')) {
                            echo file_get_contents(get_attached_file($iconId), false);
                        } else {
                            echo wp_get_attachment_image($iconId, 'medium', false, ['class' => '']);
                        }
                        echo '</span>';
                    }; ?>
                </div>
            <?php endif; ?>
            <div><?= the_terms(get_the_ID(), 'tour_thematic'); ?></div>
        </div>

        <!-- contact organisateur
        => get_the_author_meta('user_email',$user_id)
        ou seulement $user_id (car sélection du mail sur formulaire de contact)
        si on peut cacher l'adresse mail pour plus de confidentialité -->

        <p class="w-full sm:w-1/3 order-3 sm:order-2 text-center font-bold text-lg md:text-xl">
            <?= get_the_title() ?>
        </p>
    </div>

    <form class="flex flex-wrap" action="<?= get_theme_file_uri('functions/reservations/reservations.php') ?>" method="post">
        <div class="w-full sm:w-auto">
            <?= ($totalReservations) ? $totalReservations : '0' ?>
            /
            <?= ($totalPersons) ? $totalPersons : '0' ?>
            &nbsp;places réservées
        </div>
        <input type="hidden" name="current_location" value="<?= $currentLocationId; ?>">
        <input type="hidden" name="guided_tour_id" value="<?= get_the_ID() ?>">


        <div class="w-full sm:w-auto">
            <?php if ($currentMemberReservations) : ?>
                <p class="mx-2"><?= $currentMemberReservations ?> places réservées</p>
            <?php else : ?>
                <input class="border mx-2 min-w-[100px]" type="number" name="nb_places" id="nb_places" max="<?= $totalPersons ?>" min="0">
            <?php endif ?>
        </div>
        <div class="w-full sm:w-auto">
            <?php if (!is_user_logged_in()) : ?>
                <a class="btn mx-2" href="<?= get_page_url_by_template('templates/connection.php') ?>">Connexion</a>
                <a class="btn mx-2" href="<?= get_page_url_by_template('templates/registration.php') ?>">Inscription</a>
            <?php else : ?>
                <?php if ($currentMemberReservations) : ?>
                    <input class="btn mx-2" type="submit" name="delete_reservations" value="Se désinscrire">
                <?php else : ?>
                    <input class="btn mx-2" type="submit" name="register_reservations" value="S'inscrire">
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </form>
</article>