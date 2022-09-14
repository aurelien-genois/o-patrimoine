<?php
get_header();
the_post();
?>

<main>
    <?php get_template_part('templates/partials/banner') ?>
    <div class="container mx-auto">

        <div class="text-center"><?= the_content() ?></div>
    </div>
</main>

<?php
get_footer();
?>