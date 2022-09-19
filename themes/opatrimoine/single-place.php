<?php
get_header();
the_post();

$image = get_the_post_thumbnail(get_the_ID(),'large',['class' => 'w-full object-cover transition-all max-h-32 sm:max-h-52 lg:max-h-96']);
$desc = get_the_content();
$tel = get_field('place_phone',get_the_ID());
$urlSite = get_field('place_site',get_the_ID());
$adress = get_field('place_adress',get_the_ID());
$coordinates = get_field('place_coordinates',get_the_ID());
// $rating = get_field('place_rating',get_the_ID());
// $types = get_the_terms(get_the_ID(),'place_type'); // if need more custom structure

$guidedTours = new WP_Query([
    'posts_per_page' => -1,
    'post_type' => 'guided_tour',
    'meta_key' => 'guided_tour_place',
    'meta_value' => get_the_ID(),
]);
?>

<main>
    <div class="container mx-auto">
        <section>
            <?= $image ?>
        </section>

        <section class="flex flex-col">
            <h2 class="titles text-center sm:text-left"><?= get_the_title() ?></h2>

            <div class="flex flex-col sm:flex-row justify-between">
                <p class="text-center sm:text-left text-sm sm:text-base"><?= the_terms(get_the_ID(),'place_type'); ?></p>

                <div class="flex justify-between sm:justify-end">
                    <!-- // todo note 
                    <span>note</span> -->
                    <!-- // todo nb commentaires 
                    <span>Comments</span> -->
                </div>
            </div>
    
        </section>

        <section>
            <h3 class="titles">Description</h3>
            <div><?= the_content() ?></div>
        </section>

        <section>
            <!-- map -->
        </section>

        <section>
            <h3 class="titles">Contact</h3>
            <!-- telephone -->
            <p>
                <?php if($tel) echo '<span>'.$tel.'</span><br/>' ?>
                <?php if($adress) echo '<span>'.nl2br($adress).'</span><br/>' ?>
                <?php if($urlSite) : ?>
                    <a target="_blank" href="<?= $urlSite ?>"
                        alt="Lien vers le site de <?= the_title() ?>" title="Lien vers le site de <?= get_the_title() ?>" aria-label="Lien vers le site de <?= the_title() ?>">
                        Voir le site
                    </a>
                <?php endif; ?>
            </p>
            <!-- url -->
        </section>
        
        <section>
            <h3 class="titles">Visites</h3>
            <?php if($guidedTours->have_posts()) : ?>
                <form action="<?= admin_url( 'admin-ajax.php' ) ?>" method="get" class="filter-auto"> 
                    
                    <input type="hidden" name="place_id" value="<?= get_the_id() ?>">
                    <input type="hidden" name="nonce" value="<?= wp_create_nonce('opatrimoine_filter_guided_tours') ?>">
                    <input type="hidden" name="action" value="filter_guided_tours">
                    <!-- // todo AJAX -->
                    <input type="date" name="tour_date" id="">
                    <?php wp_dropdown_categories([
                            'taxonomy' => 'tour_thematic',
                            'name' => 'tour_thematic',
                            'id' => 'tour_thematic_select_filter',
                            'value_field' => 'slug',
                            'show_option_all' => __('Thèmatiques','aka_theme'),
                            'selected' => filter_input(INPUT_GET, 'tour_thematic', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '',
                            'class' => 'mx-1 sm:mx-2',
                    ]); ?>
                    <?php wp_dropdown_categories([
                            'taxonomy' => 'tour_accessibility',
                            'name' => 'tour_accessibility',
                            'id' => 'tour_accessibility_select_filter',
                            'value_field' => 'slug',
                            'show_option_all' => __('Accessibilité','aka_theme'),
                            'selected' => filter_input(INPUT_GET, 'tour_accessibility', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '',
                            'class' => 'mx-1 sm:mx-2',
                    ]); ?>
                    <!-- // todo select disponibilité -->
                </form>
                <div class="guided_tours">
                    <?php while($guidedTours->have_posts()) {
                            $guidedTours->the_post();
                            get_template_part('templates/partials/guided-tour');
                    } 
                    wp_reset_postdata(); ?>
                    <!-- btn Voir plus -->
                </div>
            <?php else : ?>
                <p>Pas encore de visites sur ce lieux.</p>
            <?php endif; ?>
        </section>

        <section>
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
    </div>
</main>

<?php
get_footer();
?>