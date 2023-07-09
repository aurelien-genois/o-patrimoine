<?php
get_header();
the_post();

$todayGuidedTours = new WP_Query(
    [
        'posts_per_page' => -1,
        'post_type'      => 'guided_tour',
        'meta_query'     => [
            [
                'key'   => 'guided_tour_date',
                'value' => date('Ymd'),
            ],
        ],
        'orderby'        => 'rand',
    ]
);

$locationHighlight = get_field('home_location_highlight');
$locationHighlightGuidedTours = false;
if ($locationHighlight) {
    $locationHighlightGuidedTours = new WP_Query(
        [
            'posts_per_page' => -1,
            'post_type'      => 'guided_tour',
            'meta_query'     => [
                [
                    'key'   => 'guided_tour_place',
                    'value' => $locationHighlight,
                ],
            ],
            'orderby'        => 'rand',
        ]
    );
}

?>


<?php get_template_part('templates/partials/banner') ?>

<!-- //* title présentation -->
<section class="container px-6 md:px-8 lg:px-12 xl:px-18 2xl:px-28 mx-auto">
    <h2 class="titles">
        <?= (get_field('home_presentation_title', get_the_ID()) ?: 'Présentation') ?>
    </h2>
    <div class="text-sm md:text-base">
        <?= the_content() ?>
    </div>
</section>

<!-- //* title visites du jour -->
<section class="container px-6 md:px-8 lg:px-12 xl:px-18 2xl:px-28 mx-auto">
    <div class="w-full flex justify-between items-end">
        <h2 class="titles">
            <?= (get_field('home_visit_of_the_day_title', get_the_ID()) ?: 'Les visites du jour') ?>
        </h2>
        <!-- // todo add query date for listing by date -->
        <a class="link mb-2 md:mb-4" href="<?= get_post_type_archive_link('place') ?>">
            <?= (get_field('home_visit_of_the_day_link_text', get_the_ID()) ?: 'Les autres visites du jour') ?>
        </a>
    </div>
    <?php if ($todayGuidedTours->have_posts()): ?>
        <ul class="grid md:grid-cols-2 lg:grid-cols-3 justify-center gap-4 lg:gap-6 mb-8">
            <?php
            $count = 0;
            while ($todayGuidedTours->have_posts()) {
                $todayGuidedTours->the_post();
                $totalPersons = get_field('guided_tour_total_persons', get_the_ID()) ?: 0;
                $totalReservations = get_field('guided_tour_total_reservations', get_the_ID()) ?: 0;
                if (($totalPersons - $totalReservations) <= 0)
                    continue;

                get_template_part('templates/partials/guided-tour-thumbnail', null, []);
                $count++;
                if ($count == 3)
                    break;
            }
            wp_reset_postdata(); ?>
        </ul>
    <?php endif; ?>
</section>

<!-- //* title highlight lieux -->
<?php if ($locationHighlight): ?>
    <section class="container px-6 md:px-8 lg:px-12 xl:px-18 2xl:px-28 mx-auto">
        <div class="w-full flex justify-between items-end">
            <h2 class="titles">
                <?= (get_field('home_location_highlight_title', get_the_ID()) ?: 'Les visites du lieu') ?>
            </h2>
            <a class="link mb-2 md:mb-4" href="<?= get_permalink($locationHighlight) ?>">
                <?= (get_field('home_location_highlight_link_text', get_the_ID()) ?: 'Les autres visites du lieu') ?>
            </a>
        </div>

        <?php if ($locationHighlightGuidedTours->have_posts()): ?>
            <ul class="grid md:grid-cols-2 lg:grid-cols-3 justify-center gap-4 lg:gap-6 mb-8">
                <?php
                $count = 0;
                while ($locationHighlightGuidedTours->have_posts()) {
                    $locationHighlightGuidedTours->the_post();
                    $totalPersons = get_field('guided_tour_total_persons', get_the_ID()) ?: 0;
                    $totalReservations = get_field('guided_tour_total_reservations', get_the_ID()) ?: 0;
                    if (($totalPersons - $totalReservations) <= 0)
                        continue;

                    get_template_part('templates/partials/guided-tour-thumbnail', null, []);
                    $count++;
                    if ($count == 3)
                        break;
                }
                wp_reset_postdata(); ?>
            </ul>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php
get_footer();
?>