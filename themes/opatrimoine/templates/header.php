<?php
$blogTitle = get_bloginfo('name');

$logoId = get_field('options_logo','option');
if( !empty($logoId)){
    $logo = wp_get_attachment_image($logoId,'medium',false,['class'=>'']);
}

$theme_locations = get_nav_menu_locations();
$menu_obj = get_term( $theme_locations['header'], 'nav_menu' );
$nav = wp_get_nav_menu_items($menu_obj->slug, array());
?>
<header class="header flex justify-between items-center p-4">
    <div class="flex items-center">
        <a class="block max-w-[50px]" href="'.home_url().'">
            <?= $logo ?>
        </a>
        <h1 class="hidden sm:block ml-4"><?= $blogTitle ?></h1>
    </div>

    <div id="deskMenu" class="hidden md:block" >
        <!-- menu append in JS on desktop from #mobileMenu -->
    </div>

    <div>
        <!-- account btns -->
    </div>

    <div id="btnMenu">
        <span></span><span></span><span></span>
    </div>
</header>

<nav id="navMenu">
    <div id="mobileMenu" class="relative">
        <div id="menuClose" class="absolute top-0 right-0 z-20p-4 text-white hover:text-main cursor-pointer">X</div>
        <?php if(is_array($nav) && !empty($nav)) : ?>
            <nav class="header__menu mx-4">
                <ul>
                    <?php foreach($nav as $k => $menuItem): 
                        if($menuItem->menu_item_parent) continue;

                        $subMenuItems = [];
                        foreach ($nav as $index => $item){
                            if($item->menu_item_parent == $menuItem->ID){
                                $subMenuItems[$index] = $item;
                            }
                        }
                        ?>
                        
                        <li class="menu-item relative uppercase text-sm lg:text-base mx-4 text-white md:text-main <?= (!empty($subMenuItems)) ? 'menu-item-has-children' : ''?>">
                            <a href="<?= $menuItem->url ?>" title="<?= $menuItem->title ?>" alt="<?= $menuItem->title ?>" class="<?php if($menuItem->object_id == get_the_ID()) echo 'underline'; ?> hover:underline <?= implode(' ',$menuItem->classes) ?>">
                                <?= $menuItem->title ?>
                            </a>
                            <?php if(is_array($subMenuItems)) : ?>
                                <ul class="sub-menu">
                                <li class="sub-menu-back cursor-pointer w-fit py-1 md:hidden hover:border-white hover:underline hover:text-second">< Retour</li>
                                <?php foreach($subMenuItems as $c => $subItem) : ?>
                                    <li class="menu-item text-white md:text-main">
                                        <a href="<?= $subItem->url ?>" title="<?= $subItem->title ?>" alt="<?= $subItem->title ?>" class="<?php if($subItem->object_id == get_the_ID()) echo 'underline'; ?> hover:underline <?= implode(' ',$subItem->classes) ?>">
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