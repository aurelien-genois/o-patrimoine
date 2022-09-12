<?php

$theme_locations = get_nav_menu_locations();
$menu_obj = get_term( $theme_locations['footer'], 'nav_menu' );
$nav = wp_get_nav_menu_items($menu_obj->slug, array());

$socialsLinks = get_field('options_socials_links','option');
$adress = get_field('options_adress','option');
?>
    <footer class="footer flex justify-between items-center flex-wrap p-4 md:py-8">

        <?php if(is_array($socialsLinks) && !empty($socialsLinks)) : ?>
            <section class="footer__socials flex justify-evenly mx-auto max-w-lg w-full mb-4 md:w-1/3 md:order-2 md:mb-0">
                <?php foreach($socialsLinks as $socialsLink) : 
                    $iconUrl = wp_get_attachment_image_url($socialsLink['icon'],'medium', false);
                    ?>
                    <a class="block w-11 hover:scale-110" href="<?= $socialsLink['link']['url'] ?>" target="<?= $socialsLink['link']['target'] ?>" title="<?= $socialsLink['link']['title'] ?>" alt="<?= $socialsLink['link']['title'] ?>">
                        <?php 
                            if(substr_count($iconUrl,'.svg')){
                                echo file_get_contents( get_attached_file($socialsLink['icon']), false);
                            }else{
                                echo wp_get_attachment_image($socialsLink['icon'],'medium', false, ['class' => '']);
                            }
                        ?>
                    </a>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <?php if(is_array($nav) && !empty($nav)) : ?>
            <nav class="footer__menu w-full mb-4 md:w-1/3 md:order-1 md:mb-0">
                <ul class="grid md:grid-cols-2 justify-items-center md:justify-items-start">
                    <!-- <span class="hidden md:col-start-2 md:row-start-1 md:row-start-2 md:row-start-3"></span> -->
                    <?php foreach($nav as $k => $menuItem): ?>
                        <li class="menu-item relative text-md lg:text-base mx-4 text-main col-start-1 <?php if($k>2) echo 'md:col-start-2 md:row-start-'.($k-2) ?>">
                            <a href="<?= $menuItem->url ?>" title="<?= $menuItem->title ?>" alt="<?= $menuItem->title ?>" class="<?php if($menuItem->object_id == get_the_ID()) echo 'underline'; ?> hover:underline whitespace-nowrap <?= implode(' ',$menuItem->classes) ?>">
                                <?= $menuItem->title ?>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </nav>
        <?php endif ?>


            
        <?php if(!empty($adress)) : ?>
            <section class="w-full text-center mb-4 md:w-1/3 order-3 md:mb-0">
                <p class="text-main">
                    <?= $adress ?>
                </p>
            </section>
        <?php endif; ?>

        <section class="w-full flex justify-center flex-wrap space-y-2 md:space-x-2 md:space-y-0 order-4">
            <a class="w-full md:w-auto text-center text-third visited:text-fourth hover:underline" href="<?= get_privacy_policy_url() ?>" title="Page de mentions légales" alt="Page de mentions légales">Mentions légales</a>
            <p class="w-full md:w-auto text-center">©O'Patrimoine</p>
            <p class="w-full md:w-auto text-center">Site réalisé par <a class="text-third visited:text-fourth hover:underline" href="https://wugenois.com/" target="_blank" title="Lien vers Wugenois.com" alt="Lien vers Wugenois.com">Aurélien Genois</a></p>
        </section>
                                                              
    </footer>

    <?php wp_footer(); ?>
</body>

</html>