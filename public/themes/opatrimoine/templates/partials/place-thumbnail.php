<li class="shadow-xl flex flex-col h-[400px] max-w-sm">
    <a href="<?php the_permalink(); ?>" alt="Lien vers <?= get_the_title() ?>" title="Lien vers <?= get_the_title() ?>">
        <figure class="overflow-hidden">
            <?php the_post_thumbnail('medium', ['class' => 'h-32 w-full object-cover transition-all duration-200 hover:scale-110']); ?>
        </figure>
    </a>
    <div class="flex flex-col justify-between h-full p-3">
        <div class="">
            <a class="self-end hover:underline" href="<?php the_permalink(); ?>" alt="Lien vers <?= get_the_title() ?>"
                title="Lien vers <?= get_the_title() ?>">
                <h3 class="font-bold md:text-lg text-third hover:text-second">
                    <?= get_the_title() ?>
                </h3>
            </a>
            <p class="text-xs md:text-sm text-third hover:text-second hover:underline">
                <?php the_terms(get_the_ID(), 'place_type'); ?>
            </p>
            <?php
            $departmentNumber = get_field('place_department', get_the_ID());
            $department = get_departments($departmentNumber);
            if (is_array($department) && count($department)): ?>
                <p class="text-xs md:text-sm">
                    <?= current($department) ?>
                </p>
            <?php endif; ?>
            <p class="text-sm md:text-base">
                <?= get_the_excerpt(); ?>
            </p>
        </div>

        <div class="flex flex-row-reverse justify-between">
            <!-- // todo note
            <span>note</span> -->
            <a class="self-end hover:underline text-second hover:text-third" href="<?php the_permalink(); ?>"
                alt="Lien vers <?= get_the_title() ?>" title="Lien vers <?= get_the_title() ?>">
                Voir le lieu
            </a>
        </div>
    </div>
</li>