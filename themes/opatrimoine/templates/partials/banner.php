<?php
$slides = get_field('home_banner_slides',get_the_ID());

?>

<div class="home-banner mb-4">
    <?php if (is_array($slides) && !empty($slides)) : ?>
        <div class="glide slider-homepage" data-autoplay='5000'>
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    <?php foreach ($slides as $slide) : ?>
                        <li class="glide__slide page-banner relative -z-40 py-8">
                            <?php if($slide['bg_mobile']) : ?>
                                <img src="<?= ($slide['bg_mobile']['url']) ?>" class="absolute inset-0 w-full h-full object-cover -z-30" alt="Image de bannière" title="Image de bannière" aria-label="Image de bannière">
                            <?php endif; ?>
                            <?php if( $slide['bg']) : ?>
                                <img src="<?= ($slide['bg']['url']) ?>" class="absolute inset-0 w-full h-full object-cover -z-20 <?php if($slide['bg_mobile']) echo 'hidden md:block' ?>" alt="Image de bannière" title="Image de bannière" aria-label="Image de bannière">
                            <?php endif; ?>
                            <div class="absolute bottom-0 inset-x-0 -z-10 w-full h-1/2" style="background: linear-gradient(0deg, rgba(0,0,0,0.7441112187062324) 0%, rgba(0,0,0,0.6880888097426471) 33%, rgba(0,0,0,0.43318684895833337) 70%, rgba(0,0,0,0) 100%);"></div>
                            <div class="container__inner px-4 z-0">
                                <?php if ($slide['title']) : ?>
                                    <h1 class="text-<?= $slide['text_color'] ?> text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold mb-2"><?= $slide['title']; ?></h1>
                                <?php endif; ?>

                                <?php if ($slide['link']) : 
                                    ?>
                                    <a href="<?= $slide['link']['url'] ?>"
                                        class="btn" alt="<?= $slide['link']['title'] ?>" title="<?= $slide['link']['title'] ?>" aria-label="<?= $slide['link']['title'] ?>" ><?= $slide['link']['title'] ?></a>
                                <?php endif; ?>
                            </div>

                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="glide__bullets absolute bottom-[8px] left-0 right-0 max-w-max mx-auto" data-glide-el="controls[nav]">
                <?php foreach ($slides as $key => $slide) : ?>
                    <button class="glide__bullet  w-[12px] h-[12px] bg-white rounded-full border border-greyLight border-solid" data-glide-dir="=<?= $key ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>