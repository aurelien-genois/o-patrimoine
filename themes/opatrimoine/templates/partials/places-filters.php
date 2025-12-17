<?php

$departments = get_departments();
$tourThematics = get_terms(['taxonomy' => 'tour_thematic', 'hide_empty' => false]);
?>

<section id="places-filters" class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <span class="hidden -rotate-180 max-h-56"></span>
    <button class="block mx-auto mb-2 accordion-btn btn lg:hidden" id="resp-filter-place">
        Rechercher
        <i class="transition-all fa-solid fa-chevron-down"></i>
    </button>

    <form method="get"
        class="flex flex-col items-center justify-between gap-4 px-4 mx-auto mb-8 overflow-hidden text-white transition-all duration-500 ease-out lg:flex-row max-h-0 lg:max-h-56 bg-second rounded-xl w-max lg:py-4 lg:overflow-visible">
        <label for="tour_date" class="max-lg:mt-4">
            <input type="date" name="tour_date" id="tour_date" class="border auto-filter-input"
                value="<?= filter_input(INPUT_GET, 'tour_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '' ?>">
        </label>
        <?php if (is_array($tourThematics) && !empty($tourThematics)): ?>
            <label for="tour_thematic_select_filter">
                <select name="guided_tour_thematic" id="tour_thematic_select_filter" class="input max-w-[150px]">
                    <!-- no function wp_dropdown_categories() and name different from "tour_thematic" taxo name to prevent auto query arg to place query -->
                    <option value="">Thèmatiques</option>
                    <?php foreach ($tourThematics as $k => $tourThematic): ?>
                        <option value="<?= $tourThematic->slug ?>" <?= (filter_input(INPUT_GET, 'guided_tour_thematic', FILTER_SANITIZE_FULL_SPECIAL_CHARS) == $tourThematic->slug) ? 'selected' : '' ?>
                            <?= (get_query_var('guided_tour_thematic') == $tourThematic->slug) ? 'selected' : '' ?>>
                            <?= $tourThematic->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        <?php endif; ?>
        <label for="place_type_select_filter">
            <?php
            wp_dropdown_categories([
                'taxonomy'        => 'place_type',
                'name'            => 'place_type',
                'id'              => 'place_type_select_filter',
                'hide_empty'      => false,
                'value_field'     => 'slug',
                'show_option_all' => __('Types de lieux', 'opatrimoine'),
                'selected'        => filter_input(INPUT_GET, 'place_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: get_query_var('place_type'),
                'class'           => 'input max-w-[150px]',
            ]);
            ?>
        </label>
        <?php if (is_array($departments) && !empty($departments)): ?>
            <label for="place_department">
                <select name="place_department" id="place_department" class="max-w-[150px]">
                    <option value="">Départements</option>
                    <?php foreach ($departments as $k => $department): ?>
                        <option value="<?= $k ?>" <?= (filter_input(INPUT_GET, 'place_department', FILTER_SANITIZE_FULL_SPECIAL_CHARS) == $k) ? 'selected' : '' ?>><?= $department ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        <?php endif; ?>
        <label for="place_name">
            <input type="text" id="place_name" class="max-w-[150px]" name="s" value="<?= the_search_query() ?>"
                placeholder="Ajoutez un mot clé">
        </label>

        <input type="submit" class="mx-auto mt-2 btn btn-white lg:mx-0 lg:mt-0" value="Rechercher">
    </form>

</section>