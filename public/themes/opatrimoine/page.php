<?php
if (is_front_page()) {
    get_template_part('index');
    exit();
}

get_header();
the_post();
?>


<section class="container px-6 md:px-8 lg:px-12 xl:px-18 2xl:px-28 mx-auto">
    <h2 class="titles text-center">
        <?= the_title() ?>
    </h2>

    <div class="text-center">
        <?= the_content() ?>
    </div>
</section>


<?php
get_footer();
?>