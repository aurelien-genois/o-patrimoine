<?php
the_post();
?>

<article class="shadow-xl flex flex-col h-[400px] max-w-sm">
    <figure class="">
        <?php the_post_thumbnail('medium',['class' => 'h-32 w-full object-cover']); ?>
    </figure>
    <div class="p-3 flex flex-col justify-between h-full">
        <div class="">
            <h3 class="md:text-lg font-bold">
                <?= get_the_title() ?>
            </h3>
            <p class="text-xs md:text-sm">
                <?php the_terms(get_the_ID(), 'place_type'); ?>
            </p>
            <p class="text-sm md:text-base">
                <?= get_the_excerpt(); ?>
            </p>
        </div>
        
        <div class="flex flex-row-reverse justify-between">
            <!-- // todo note 
            <span>note</span> -->
            <a class="self-end" href="<?php the_permalink(); ?>">
                Voir le lieu
            </a>  
        </div>
    </div>
</article>