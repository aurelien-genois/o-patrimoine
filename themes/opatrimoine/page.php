<?php
if (is_front_page()) {
    get_template_part('index');
    exit();
}

get_header();
the_post();
?>


<div class="container mx-auto px-5 md:px-8 lg:px-12 xl:px-18 2xl:px-28mx-auto">
    <h2 class="text-main"><?= the_title() ?></h2>

    <div class="text-center"><?= the_content() ?></div>
</div>


<?php
get_footer();
?>