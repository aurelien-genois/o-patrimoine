<?php
get_header();

get_template_part('templates/partials/places-filters'); ?>

<section class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <?php if (have_posts()): ?>
        <ul
            class="grid justify-center gap-4 mb-8 archive-places-list md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 lg:gap-6">
            <?php while (have_posts()) {
                the_post();
                get_template_part('templates/partials/place-thumbnail');
            } ?>
        </ul>

        <button class="block mx-auto btn load-more-places-btn" data-ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>"
            data-nonce="<?php echo wp_create_nonce('opatrimoine_load_places'); ?>" data-action="load_places"
            data-place_type="<?= filter_input(INPUT_GET, 'place_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: get_query_var('place_type') ?>"
            data-deparment="<?= filter_input(INPUT_GET, 'place_department', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '' ?>"
            data-tour_date="<?= filter_input(INPUT_GET, 'tour_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '' ?>"
            data-tour_thematic="<?= filter_input(INPUT_GET, 'guided_tour_thematic', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: get_query_var('guided_tour_thematic') ?>"
            data-s="<?= the_search_query() ?: '' ?>" data-page="<?= get_query_var('paged') ?>">
            Voir plus de lieux
        </button>
    <?php else:
        echo '<p class="text-center text-third">Aucun lieu trouvé pour ces critères.</p>';
    endif; ?>
</section>

<?php
get_footer();
?>