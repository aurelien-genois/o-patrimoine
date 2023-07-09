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


$placeId = get_field('field_guided_tour_place', get_the_ID());
$departmentNumber = get_field('place_department', $placeId);
$department = get_departments($departmentNumber);
$currentMemberReservations = getReservationByGuidedTourIdForCurrentUser(get_the_ID());
?>

<li class="shadow-xl flex flex-col h-[400px] max-w-sm">
    <a href="<?php the_permalink($placeId); ?>" alt="Lien vers <?= get_the_title($placeId) ?>"
        title="Lien vers <?= get_the_title($placeId) ?>">
        <figure class="overflow-hidden">
            <?= get_the_post_thumbnail($placeId, 'medium', ['class' => 'h-32 w-full object-cover transition-all duration-200 hover:scale-110']); ?>
        </figure>
    </a>
    <div class="p-3 flex flex-col justify-between h-full">

        <div class="">
            <h3 class="text-center max-md:text-sm font-bold text-third">
                <?= get_the_title(get_the_ID()) ?>
            </h3>
            <p class="text-center">
                <?= get_the_title($placeId) ?>
            </p>
            <?php if (is_array($department) && count($department)): ?>
                <p class="text-center">
                    <?= current($department) ?>
                </p>
            <?php endif; ?>

            <div class="text-sm md:text-base mt-4">
                <?php if ($dateStr) {
                    echo '<span>' . $dateStr . '</span>&nbsp;';
                }
                if ($hour) {
                    echo '<span>à&nbsp;' . $hour . '</span>&nbsp;';
                }
                ?>
            </div>


            <?php if ($duration || $constraints): ?>
                <div class="flex justify-between">
                    <?php if ($duration): ?>
                        <span class="block sm:inline whitespace-nowrap"><i class="fa-solid fa-hourglass-half"></i>&nbsp;
                            <?= $duration ?>
                        </span>
                    <?php endif; ?>
                    <?php if (is_array($constraints) && !empty($constraints)): ?>
                        <div class="text-xs md:text-sm flex items-center justify-end">
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
                </div>
            <?php endif; ?>



            <p class="text-center p-4 text-xs md:text-sm text-third hover:text-second hover:underline">
                <?php the_terms(get_the_ID(), 'tour_thematic'); ?>
            </p>

        </div>

        <div class="flex justify-between">
            <div class="text-xs md:text-sm">
                <?= $totalAvailable ?>&nbsp;/&nbsp;
                <?= $totalPersons ?>&nbsp;places disponibles
            </div>
            <a class="self-end hover:underline text-second hover:text-third" href="<?php the_permalink($placeId); ?>"
                alt="Lien vers <?= get_the_title($placeId) ?>" title="Lien vers <?= get_the_title($placeId) ?>">
                Voir le lieu
            </a>
        </div>


    </div>
</li>