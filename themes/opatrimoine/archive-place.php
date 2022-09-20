<?php


get_header();
$departments = get_departments();

?>

<div class="container mx-auto">
    <span class="hidden max-h-52 -rotate-180"></span>
    <button class="btn mx-auto mb-2 lg:hidden" id="resp-filter-place">
        Rechercher 
        <i class="fa-solid fa-chevron-down transition-all"></i>
    </button>
    <form action="get" class="flex flex-col lg:flex-row justify-between mb-8 max-h-0 lg:max-h-52 
        overflow-hidden lg:overflow-visible transition-all duration-500 ease-out">
        <fieldset class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-2">
            <?php
                wp_dropdown_categories([
                    'taxonomy' => 'place_type',
                    'name' => 'place_type',
                    'id' => 'place_type_select_filter',
                    'value_field' => 'slug',
                    'show_option_all' => __('Types de lieux','aka_theme'),
                    'selected' => filter_input(INPUT_GET, 'place_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '',
                    'class' => 'input mx-1 sm:mx-2 max-w-[150px]',
                ]);
                wp_dropdown_categories([
                    'taxonomy' => 'tour_thematic',
                    'name' => 'tour_thematic',
                    'id' => 'tour_thematic_select_filter',
                    'value_field' => 'slug',
                    'show_option_all' => __('Types de lieux','aka_theme'),
                    'selected' => filter_input(INPUT_GET, 'tour_thematic', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '',
                    'class' => 'input mx-1 sm:mx-2 max-w-[150px]',
                ]);
            ?>
            <?php if(is_array($departments) && !empty($departments)) : ?>
            <select name="place_department" id="place_department" class="max-w-[150px]">
                <option value="">Départements</option>
                <?php foreach($departments as $k => $department) : ?>
                    <option value="<?= $k ?>"  <?= (filter_input(INPUT_GET, 'place_department', FILTER_SANITIZE_FULL_SPECIAL_CHARS) == $k) ? 'selected' : '' ?>><?= $department ?></option>                    <?php endforeach; ?>
            </select>
            <input type="text" id="place_name" class="max-w-[150px]" name="s" value="<?= the_search_query() ?>" placeholder="Ajoutez un mot clé">
            <?php endif; ?>
        </fieldset>

        <input type="submit" class="btn mx-auto lg:mx-0 mt-2 lg:mt-0" value="Rechercher">
    </form>

    <section class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 justify-center gap-4 lg:gap-6">
        <?php
            if(have_posts()) {
                while (have_posts()) {
                    the_post();
        
                    get_template_part('templates/partials/place-thumbnail');
                }
            }
        ?>
    </section>
</div>

<?php
get_footer();
?>