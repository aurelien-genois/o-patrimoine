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

    <form class="flex flex-wrap">
        <div class="w-full sm:w-auto">
            <?= ($totalReservations) ? $totalReservations : '0' ?>
            /
            <?= ($totalPersons) ? $totalPersons : '0' ?>
            &nbsp;places réservées
        </div>
        <div class="w-full sm:w-auto">
            <!-- // todo Reservation select nb_places or msg -->
        </div>
        <div class="w-full sm:w-auto">
            <!-- // todo Reservation submit (inscrire/désinscrire) or links connexion/inscription -->
        </div>
    </form>
</article>