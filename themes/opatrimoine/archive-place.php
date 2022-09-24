<?php


get_header();
$departments = get_departments();

?>

<section class="px-6 md:px-8 lg:px-12 xl:px-18 2xl:px-28 mx-auto">
    <span class="hidden max-h-52 -rotate-180"></span>
    <button class="btn block mx-auto mb-2 lg:hidden" id="resp-filter-place">
        Rechercher
        <i class="fa-solid fa-chevron-down transition-all"></i>
    </button>
    <form method="get" class="flex flex-col lg:flex-row justify-between mb-8 max-h-0 lg:max-h-52
    overflow-hidden lg:overflow-visible transition-all duration-500 ease-out">
        <fieldset class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-2">
            <?php
            wp_dropdown_categories([
                'taxonomy' => 'place_type',
                'name' => 'place_type',
                'id' => 'place_type_select_filter',
                'value_field' => 'slug',
                'show_option_all' => __('Types de lieux', 'opatrimoine'),
                'selected' => filter_input(INPUT_GET, 'place_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '',
                'class' => 'input mx-1 sm:mx-2 max-w-[150px]',
            ]);
            ?>
            <?php if (is_array($departments) && !empty($departments)) : ?>
                <select name="place_department" id="place_department" class="max-w-[150px]">
                    <option value="">Départements</option>
                    <?php foreach ($departments as $k => $department) : ?>
                        <option value="<?= $k ?>" <?= (filter_input(INPUT_GET, 'place_department', FILTER_SANITIZE_FULL_SPECIAL_CHARS) == $k) ? 'selected' : '' ?>><?= $department ?></option> <?php endforeach; ?>
                </select>
                <input type="text" id="place_name" class="max-w-[150px]" name="s" value="<?= the_search_query() ?>" placeholder="Ajoutez un mot clé">
            <?php endif; ?>
        </fieldset>

        <input type="submit" class="btn mx-auto lg:mx-0 mt-2 lg:mt-0" value="Rechercher">
    </form>
</section>

<section class="px-6 md:px-8 lg:px-12 xl:px-18 2xl:px-28 mx-auto">
    <ul class="archive-places-list grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 justify-center gap-4 lg:gap-6 mb-8">
        <?php if (have_posts()) {
            while (have_posts()) {
                the_post();
                get_template_part('templates/partials/place-thumbnail');
            }
        } ?>
    </ul>
    <!-- //! does'nt work if not logged in (get admin-ajax redirect to login page) -->
    <!-- //! maybe need an Authorization header added to the fetch in viewmore JS -->
    <!-- //todo text if only on local -->
    <button class="block btn mx-auto load-more-places-btn" data-ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>" data-nonce="<?php echo wp_create_nonce('opatrimoine_load_places'); ?>" data-action="load_places" data-place_type="<?= filter_input(INPUT_GET, 'place_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '' ?>" data-deparment="<?= filter_input(INPUT_GET, 'place_department', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '' ?>" data-s="<?= the_search_query() ?? '' ?>" data-page="<?= get_query_var('paged') ?>">
        Voir plus de lieux
    </button>
</section>

<?php
get_footer();
?>