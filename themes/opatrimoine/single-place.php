<?php
get_header();
the_post();

$image = get_the_post_thumbnail(get_the_ID(), 'large', ['class' => 'w-full object-cover transition-all max-h-32 sm:max-h-52 lg:max-h-96']);
$tel = get_field('place_phone', get_the_ID());
$urlSite = get_field('place_site', get_the_ID());
$adress = get_field('place_adress', get_the_ID());
$coordinates = get_field('place_coordinates', get_the_ID());
// $rating = get_field('place_rating',get_the_ID());
// $types = get_the_terms(get_the_ID(),'place_type'); // if need more custom structure

$guidedToursQuery = new WP_Query([
    'posts_per_page' => 5,
    'paged'          => 1,
    'post_type'      => 'guided_tour',
    'meta_query'     => [
        'relation' => 'AND',
        [
            // do not show passed guided tours
            'key'     => 'guided_tour_date',
            'value'   => date('Ymd'),
            'compare' => '>=',
        ],
        [
            'key'   => 'guided_tour_place',
            'value' => get_the_ID(),
        ]
    ]
]);
?>

<section class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <?php if (!empty($_GET['error-reservation'])): ?>
        <div>
            <div class="p-5 my-5 text-center border border-fourth" style="color:darkred">
                <p><b>
                        <?= $_GET['error-reservation'] ?>
                    </b></p>
            </div>
        </div>
    <?php endif; ?>
    <?= $image ?>
</section>

<section class="container flex flex-col px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <h2 class="text-center titles sm:text-left">
        <?= get_the_title() ?>
    </h2>

    <div class="flex flex-col justify-between sm:flex-row">
        <p class="text-sm text-center sm:text-left sm:text-base">
            <?= the_terms(get_the_ID(), 'place_type'); ?>
        </p>

        <div class="flex justify-between sm:justify-end">
            <!-- // todo note
                    <span>note</span> -->
            <!-- // todo nb commentaires
                    <span>Comments</span> -->
        </div>
    </div>

</section>

<section class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <h3 class="titles">Description</h3>
    <div>
        <?= the_content() ?>
    </div>
</section>

<section class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <!-- map -->
</section>

<section class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <h3 class="titles">Contact</h3>
    <!-- telephone -->
    <p>
        <?php if ($tel)
            // todo format phone
            echo '<span>' . $tel . '</span><br/>' ?>
        <?php if ($adress)
            echo '<span>' . nl2br($adress) . '</span><br/>' ?>
        <?php if ($urlSite): ?>
            <a target="_blank" class="link" href="<?= $urlSite ?>" alt="Lien vers le site de <?= the_title() ?>"
                title="Lien vers le site de <?= get_the_title() ?>" aria-label="Lien vers le site de <?= the_title() ?>">
                Voir le site
            </a>
        <?php endif; ?>
    </p>
    <!-- url -->
</section>

<!-- Listing Guided Tours -->
<section class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <h3 class="titles">Visites</h3>
    <?php if ($guidedToursQuery->have_posts()): ?>


        <button class="block mx-auto mb-2 accordion-btn btn lg:hidden" id="resp-filter-place">
            Filter
            <i class="transition-all fa-solid fa-chevron-down"></i>
        </button>
        <form action="<?= admin_url('admin-ajax.php') ?>" method="get"
            class="flex flex-col items-center gap-4 px-4 mx-auto mb-4 overflow-hidden text-white transition-all duration-500 ease-out filter-auto w-max bg-second rounded-xl lg:flex-row max-h-0 lg:max-h-56 lg:overflow-visible lg:py-4">

            <input type="hidden" name="place_id" value="<?= get_the_id() ?>">
            <input type="hidden" name="nonce" value="<?= wp_create_nonce('opatrimoine_filter_guided_tours') ?>">
            <input type="hidden" name="action" value="filter_guided_tours">

            <label for="tour_date" class="max-lg:mt-4">
                <input type="date" name="tour_date" id="tour_date" class="border auto-filter-input"
                    value="<?= filter_input(INPUT_GET, 'tour_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '' ?>">
            </label>
            <label for="tour_thematic_select_filter">
                <?php wp_dropdown_categories([
                    'taxonomy'        => 'tour_thematic',
                    'name'            => 'tour_thematic',
                    'id'              => 'tour_thematic_select_filter',
                    'value_field'     => 'slug',
                    'show_option_all' => __('Thèmatiques', 'opatrimoine'),
                    'selected'        => filter_input(INPUT_GET, 'tour_thematic', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '',
                    'class'           => 'auto-filter-input border',
                ]); ?>
            </label>
            <label for="tour_constraint_select_filter">
                Accessible pour :
                <?php wp_dropdown_categories([
                    'taxonomy'        => 'tour_constraint',
                    'name'            => 'tour_constraint',
                    'id'              => 'tour_constraint_select_filter',
                    'value_field'     => 'slug',
                    'show_option_all' => __('Accessibilité', 'opatrimoine'),
                    'selected'        => filter_input(INPUT_GET, 'tour_constraint', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '',
                    'class'           => 'auto-filter-input border',
                ]); ?>
            </label>
            <label for="available_only" class="max-lg:mb-4">
                Uniquement disponibles ?
                <input type="checkbox" class="auto-filter-input" name="available_only" id="available_only">
            </label>
        </form>


        <div class="guided_tours">
            <?php while ($guidedToursQuery->have_posts()) {
                $guidedToursQuery->the_post();
                get_template_part('templates/partials/guided-tour', null, ['currentTemplateSlug' => '']);
            }
            wp_reset_postdata(); ?>
        </div>
        <button class="block mx-auto btn load-more-guided-tours-btn"
            data-ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>"
            data-nonce="<?php echo wp_create_nonce('opatrimoine_load_guided_tours'); ?>" data-action="load_guided_tours"
            data-place_id="<?= get_the_id() ?>" data-page="1">
            Voir plus de visites
        </button>
    <?php else: ?>
        <p class="text-center text-third">Pas encore de visites sur ce lieux.</p>
    <?php endif; ?>
</section>

<section class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <!-- // todo commentaires
            <h3 class="titles">Commentaires</h3> -->
    <form>
        <!-- textarea -->
        <!-- submit -->
    </form>
    <div>
        <!-- foreach commentaires -->
    </div>
</section>

<?php
get_footer();
?>