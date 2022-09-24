<?php
$blogTitle = get_bloginfo('name');

$logoId = get_field('options_logo', 'option');
if (!empty($logoId)) {
    $logo = wp_get_attachment_image($logoId, 'medium', false, ['class' => '']);
}

$theme_locations = get_nav_menu_locations();
$menu_obj = get_term($theme_locations['header'], 'nav_menu');
$nav = wp_get_nav_menu_items($menu_obj->slug, array());
?>
<header class="header flex justify-between items-center p-4 min-h-[120px]">
    <div class="flex items-center">
        <a class="block max-w-[50px]" href="<?= home_url() ?>">
            <?= $logo ?>
        </a>
        <h1 class="lg:block xl:block ml-4"><?= $blogTitle ?></h1>
    </div>

    <div id="deskMenu" class="hidden lg:block z-50">
        <!-- menu append in JS on desktop from #mobileMenu -->
    </div>

    <div class="hidden lg:flex flex-col xl:flex-row space-y-2 xl:space-y-0 xl:space-x-4">
        <?php if (is_user_logged_in()) : ?>
            <a class="btn ml-2 btn-2" href="<?= get_page_url_by_template('templates/account.php') ?>">Mon compte</a>
        <?php else : ?>
            <a class="btn ml-2 btn-2" href="<?= get_page_url_by_template('templates/connection.php') ?>">Connexion</a>
            <a class="btn ml-2 btn-2" href="<?= get_page_url_by_template('templates/registration.php') ?>">Inscription</a>
        <?php endif; ?>
    </div>

    <div id="btnMenu">
        <span></span><span></span><span></span>
    </div>
</header>

<nav id="navMenu">
    <div id="mobileMenu" class="relative">
        <div id="menuClose" class="absolute top-0 left-0 z-20 flex items-center justify-center h-7 w-7 p-0.5 text-white font-bold hover:border hover:rounded-3xl hover:border-white cursor-pointer">
            > </div>
        <?php if (is_user_logged_in()) : ?>
            <a class="btn block mx-auto mb-2 btn-2" href="<?php get_page_url_by_template('templates/account.php') ?>">Mon compte</a>
        <?php else : ?>
            <a class="btn block mx-auto mb-2 btn-2" href="<?= get_page_url_by_template('templates/connection.php') ?>">Connexion</a>
            <a class="btn block mx-auto mb-2 btn-2" href="<?= get_page_url_by_template('templates/registration.php') ?>">Inscription</a>
        <?php endif; ?>
        <?php if (is_array($nav) && !empty($nav)) : ?>
            <nav class="header__menu mx-4">
                <ul class="flex flex-col lg:flex-row justify-between space-y-4 lg:space-y-0 lg:space-x-4">
                    <?php foreach ($nav as $k => $menuItem) :
                        if ($menuItem->menu_item_parent) continue;

                        $subMenuItems = [];
                        foreach ($nav as $index => $item) {
                            if ($item->menu_item_parent == $menuItem->ID) {
                                $subMenuItems[$index] = $item;
                            }
                        }
                    ?>

                        <li class="menu-item relative uppercase text-sm lg:text-base text-white lg:text-main <?= (!empty($subMenuItems)) ? 'menu-item-has-children' : '' ?>">
                            <a href="<?= $menuItem->url ?>" title="<?= $menuItem->title ?>" alt="<?= $menuItem->title ?>" class="<?php if ($menuItem->object_id == get_the_ID()) echo 'underline'; ?> hover:underline text-sm lg:text-base <?= implode(' ', $menuItem->classes) ?>">
                                <?= $menuItem->title ?>
                            </a>
                            <?php if (!empty($subMenuItems)) : ?>
                                <ul class="sub-menu">
                                    <li class="sub-menu-back text-base cursor-pointer p-0.5 flex items-center justify-center lg:hidden h-7 w-7 font-bold hover:border hover:rounded-3xl hover:border-white">
                                        < </li>
                                            <?php foreach ($subMenuItems as $c => $subItem) : ?>
                                    <li class="menu-item text-white text-base text-left lg:text-main">
                                        <a href="<?= $subItem->url ?>" title="<?= $subItem->title ?>" alt="<?= $subItem->title ?>" class="<?php if ($subItem->object_id == get_the_ID()) echo 'underline'; ?> hover:underline my-3 text-white  <?= implode(' ', $subItem->classes) ?>">
                                            <?= $subItem->title ?>
                                        </a>
                                    </li>
                                <?php endforeach ?>
                                </ul>
                            <?php endif ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </nav>
        <?php endif ?>
    </div>
</nav>