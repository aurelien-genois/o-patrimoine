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
$totalPersons = get_field('guided_tour_total_persons', get_the_ID()) ?: 0;
$totalReservations = get_field('guided_tour_total_reservations', get_the_ID()) ?: 0;
$totalAvailable = $totalPersons - $totalReservations;
$constraints = get_the_terms(get_the_ID(), 'tour_constraint');

$user = wp_get_current_user();

$currentLocationId = 0;
$isAccount = false;
if (isset($args['currentTemplateSlug']) && $args['currentTemplateSlug'] == 'templates/account.php') {
    $isAccount = true;
    $currentLocationId = get_page_id_by_template($args['currentTemplateSlug']);
} else {
    $currentLocationId = get_field('field_guided_tour_place', get_the_ID());
}
$currentMemberReservations = getReservationByGuidedTourIdForCurrentUser(get_the_ID());
?>

<article class="bg-grey/20 mb-4 rounded-xl p-4 border border-black border-solid <?php if (!($totalAvailable > 0) && !$isAccount)
    echo 'text-white bg-third' ?>">
    <div class="flex flex-wrap">
        <div class="order-1 w-1/2 text-sm sm:w-1/3 md:text-base">
            <?php if ($dateStr)
    echo '<span>' . $dateStr . '</span>&nbsp;' ?>
            <?php if ($hour)
    echo '<span>à&nbsp;' . $hour . '</span>&nbsp;' ?>
            <?php if ($duration): ?>
                <span class="block sm:inline whitespace-nowrap"><i class="fa-solid fa-hourglass-half"></i>&nbsp;
                    <?= $duration ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="order-2 w-1/2 text-sm text-right sm:w-1/3 sm:order-3 md:text-base">
            <?php if (is_array($constraints) && !empty($constraints)): ?>
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
                    }
                    ; ?>
                </div>
            <?php endif; ?>
            <div class="text-xs md:text-sm text-third hover:text-second hover:underline">
                <?= the_terms(get_the_ID(), 'tour_thematic'); ?>
            </div>
        </div>

        <!-- // todo contact organisateur
        => get_the_author_meta('user_email',$userId)
        ou seulement $userId (car sélection du mail sur formulaire de contact)
        si on peut cacher l'adresse mail pour plus de confidentialité -->

        <p class="order-3 w-full text-lg font-bold text-center sm:w-1/3 sm:order-2 md:text-xl">
            <?= get_the_title() ?>
        </p>
    </div>

    <form class="flex flex-col items-center gap-4 sm:flex-row"
        action="<?= get_theme_file_uri('functions/reservations/reservations.php') ?>" method="post">
        <?php if (!$isAccount && $totalAvailable > 0): ?>
            <div class="">
                <?= $totalAvailable ?>&nbsp;/&nbsp;
                <?= $totalPersons ?>&nbsp;places disponibles
            </div>
        <?php elseif (!$isAccount): ?>
            <div class="font-bold">Aucune place disponible</div>
        <?php else: ?>
            <div>
                à&nbsp;<a class="hover:underline text-second hover:text-third"
                    href="<?= get_permalink(get_field('field_guided_tour_place', get_the_ID())) ?>"><?= get_the_title(get_field('field_guided_tour_place', get_the_ID())) ?></a>
            </div>
        <?php endif; ?>
        <input type="hidden" name="current_location" value="<?= $currentLocationId; ?>">
        <input type="hidden" name="guided_tour_id" value="<?= get_the_ID() ?>">


        <div class="">
            <?php if ($currentMemberReservations): ?>
                <p class="font-bold  <?= ($totalAvailable > 0 || $isAccount) ? 'text-third' : 'text-white' ?>"><?= $currentMemberReservations ?> places réservées</p>
            <?php elseif (!$isAccount && $totalAvailable > 0): ?>
                <input class="border min-w-[50px] text-center" type="number" name="nb_places" id="nb_places"
                    max="<?= $totalPersons ?>" min="0" value="0">
            <?php endif ?>
        </div>
        <div class="">
            <?php if (!is_user_logged_in() && $totalAvailable > 0): ?>
                <a class="btn btn-third" href="<?= get_page_url_by_template('templates/connection.php') ?>">Connexion</a>
                <a class="btn btn-third"
                    href="<?= get_page_url_by_template('templates/registration.php') ?>">Inscription</a>
            <?php else: ?>
                <?php if ($currentMemberReservations): ?>
                    <input class="btn" type="submit" name="delete_reservations" value="Se désinscrire">
                <?php elseif (!$isAccount && $totalAvailable > 0): ?>
                    <input class="btn" type="submit" name="register_reservations" value="S'inscrire">
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </form>
</article>