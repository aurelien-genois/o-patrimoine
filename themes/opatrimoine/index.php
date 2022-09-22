<?php
get_header();
the_post();
?>


    <?php get_template_part('templates/partials/banner') ?>
    <div class="container mx-auto px-5 md:px-8 lg:px-12 xl:px-18 2xl:px-28mx-auto">
        
        <!-- //* title présentation -->
        <section>
            <h2 class="titles"><?= (get_field('home_presentation_title',get_the_ID()) ?? 'Présentation') ?></h2>
            <div class="text-sm md:text-base"><?= the_content() ?></div>
        </section>

        <!-- //* title visites du jour -->
        <section>
            <h2 class="titles"><?= (get_field('home_visit_of_the_day_title',get_the_ID()) ?? 'Les visites du jour') ?></h2>
            <a href="#"><?= (get_field('home_visit_of_the_day_link_text',get_the_ID()) ?? 'Les autres visites du jour') ?></a>
        </section>
        <!-- // todo visites du jour template part ? => same for both  -->

        <!-- //* title highlight lieux -->
        <section>
            <h2 class="titles"><?= (get_field('home_location_highlight_title',get_the_ID()) ?? 'Les visites du lieu') ?></h2>
            <a href="#"><?= (get_field('home_location_highlight_link_text',get_the_ID()) ?? 'Les autres visites du lieu') ?></a>
        </section>
        <!-- // todo highlight lieux template part ? => same for both -->
    </div>

<?php
get_footer();
?>