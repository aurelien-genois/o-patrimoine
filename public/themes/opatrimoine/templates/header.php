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
        <h1 class="ml-4 lg:block xl:block">
            <?= $blogTitle ?>
        </h1>
    </div>

    <div id="deskMenu" class="z-50 hidden lg:block">
        <!-- menu append in JS on desktop from #mobileMenu -->
    </div>

    <div class="flex-col hidden gap-2 lg:flex xl:flex-row xl:gap-4">
        <?php if (is_user_logged_in()): ?>
            <a class="btn btn-third" href="<?= get_page_url_by_template('templates/account.php') ?>">Mon compte</a>
        <?php else: ?>
            <a class="btn btn-third " href="<?= get_page_url_by_template('templates/connection.php') ?>">Connexion</a>
            <a class="btn btn-third " href="<?= get_page_url_by_template('templates/registration.php') ?>">Inscription</a>
        <?php endif; ?>
    </div>

    <div id="btnMenu">
        <span></span><span></span><span></span>
    </div>
</header>

<nav id="navMenu">
    <div id="mobileMenu" class="relative">
        <div id="menuClose"
            class="absolute top-0 left-0 z-20 flex items-center justify-center h-7 w-7 rounded-full border border-white p-0.5 text-white font-bold hover:bg-white hover:text-second cursor-pointer">
            > </div>
        <?php if (is_user_logged_in()): ?>
            <a class="mb-2 btn btn-third btn-small" href="<?= get_page_url_by_template('templates/account.php') ?>">Mon
                compte</a>
        <?php else: ?>
            <div class="flex justify-end gap-2 mb-2">
                <a class="btn btn-third btn-small"
                    href="<?= get_page_url_by_template('templates/connection.php') ?>">Connexion</a>
                <a class="btn btn-third btn-small"
                    href="<?= get_page_url_by_template('templates/registration.php') ?>">Inscription</a>
            </div>
        <?php endif; ?>
        <?php if (is_array($nav) && !empty($nav)): ?>
            <nav class="mx-4 header__menu">
                <ul class="flex flex-col justify-between gap-4 lg:flex-row">
                    <?php foreach ($nav as $k => $menuItem):
                        if ($menuItem->menu_item_parent)
                            continue;

                        $subMenuItems = [];
                        foreach ($nav as $index => $item) {
                            if ($item->menu_item_parent == $menuItem->ID) {
                                $subMenuItems[$index] = $item;
                            }
                        }
                        ?>

                        <li
                            class="menu-item relative uppercase text-sm lg:text-base text-white lg:text-main <?= (!empty($subMenuItems)) ? 'menu-item-has-children' : '' ?>">
                            <a href="<?= $menuItem->url ?>" title="<?= $menuItem->title ?>" alt="<?= $menuItem->title ?>"
                                class="<?php if ($menuItem->object_id == get_the_ID())
                                    echo 'underline'; ?> hover:underline text-sm lg:text-base <?= implode(' ', $menuItem->classes) ?>">
                                <?= $menuItem->title ?>
                            </a>
                            <?php if (!empty($subMenuItems)): ?>
                                <ul class="flex flex-col gap-4 sub-menu lg:gap-2">
                                    <li
                                        class="sub-menu-back lg:hidden text-base flex items-center justify-center h-7 w-7 rounded-full border border-white p-0.5 text-white font-bold hover:bg-white hover:text-second cursor-pointer">
                                        < </li>
                                            <?php foreach ($subMenuItems as $c => $subItem): ?>
                                        <li class="text-sm text-left text-white menu-item lg:text-main lg:text-base">
                                            <a href="<?= $subItem->url ?>" title="<?= $subItem->title ?>" alt="<?= $subItem->title ?>"
                                                class="<?php if ($subItem->object_id == get_the_ID())
                                                    echo 'underline'; ?> hover:underline my-3 text-white  <?= implode(' ', $subItem->classes) ?>">
                                                <?= $subItem->title ?>
                                            </a>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                                <!-- arrow appended in JS -->
                            <?php endif ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </nav>
        <?php endif ?>
    </div>
</nav>