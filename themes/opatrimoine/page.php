<?php
if(is_front_page()){
	get_template_part('index');
	exit();
}

get_header();
the_post();
?>


<div class="container mx-auto">
    <h2 class="text-main"><?= the_title() ?></h2>

    <div class="text-center"><?= the_content() ?></div>
</div>


<?php
get_footer();
?>