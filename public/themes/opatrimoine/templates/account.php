<?php
// Template Name: Mon compte

$user = wp_get_current_user();
$roles = $user->roles;
if (!is_user_logged_in() || !in_array('visitor', $roles)) {
    wp_redirect(home_url());
    exit();
}

get_header();
the_post();

$guidedToursQuery = getGuidedToursByCurrentUser();

$pageTemplateSlug = get_page_template_slug();
?>


<section class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <h2 class="text-center titles">
        <?= the_title() ?>
    </h2>
    <p class="mb-8 text-center">Bonjour
        <?= $user->display_name ?>
    </p>

    <!-- messages -->
    <?php if (!empty($_GET['message']) && $_GET['message'] == 'edited'): ?>
        <div>
            <div class="p-5 my-5 text-center border border-fourth">
                <p><b>
                        <?php _e('Informations modifiées.', 'opatrimoine'); ?>
                    </b></p>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($_GET['message']) && $_GET['message'] == 'pass_edited'): ?>
        <div>
            <div class="p-5 my-5 text-center border border-fourth">
                <p><b>
                        <?php _e('Mot de passe modifié.', 'opatrimoine'); ?>
                    </b></p>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($_GET['error-edited'])): ?>
        <div>
            <div class="p-5 my-5 text-center border border-fourth" style="color:darkred">
                <p><b>
                        <?= $_GET['error-edited'] ?>
                    </b></p>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($_GET['error-reservation'])): ?>
        <div>
            <div class="p-5 my-5 text-center border border-fourth" style="color:darkred">
                <p><b>
                        <?= $_GET['error-reservation'] ?>
                    </b></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- edit infos form -->
    <button class="block mx-auto mb-2 accordion-btn btn" id="edit-user-accordion">
        Modifier mes informations
        <i class="transition-all fa-solid fa-chevron-down"></i>
    </button>
    <form
        class="flex flex-col items-center gap-4 px-4 mb-8 overflow-hidden transition-all duration-500 ease-out xl:px-20 max-h-0"
        action="<?= get_theme_file_uri('functions/user/edit-user.php') ?>" method="post">

        <div class="w-auto text-center">
            <label class="block" for="user_login">
                <?php _e("Login", 'opatrimoine'); ?> :
            </label>
            <input class="px-4 py-2 border border-grey" value="<?= $user->display_name ?>" name="login" id="user_login"
                type="text" required>
        </div>

        <div class="w-auto text-center">
            <label class="block" for="user_email">
                <?php _e("Email", 'opatrimoine'); ?> :
            </label>
            <input class="px-4 py-2 border border-grey" value="<?= $user->user_email ?>" name="email" id="user_email"
                type="email" required>
        </div>

        <input class="mx-auto btn" name="edit_user" id="edit_user" type="submit" value="Valider" />
    </form>

    <!-- edit pass form -->
    <button class="block mx-auto mb-2 accordion-btn btn" id="edit-user-accordion">
        Modifier mon mot de passe
        <i class="transition-all fa-solid fa-chevron-down"></i>
    </button>
    <form
        class="flex flex-col items-center gap-4 px-4 mb-8 overflow-hidden transition-all duration-500 ease-out xl:px-20 max-h-0"
        action="<?= get_theme_file_uri('functions/user/edit-user.php') ?>" method="post">

        <div class="w-auto text-center">
            <label class="block" for="user_pass">
                <?php _e("Mot de passe", 'opatrimoine'); ?> :
            </label>
            <input class="px-4 py-2 border border-grey" type="password" name="pwd" id="user_pass" required>
        </div>

        <div class="w-auto text-center">
            <label class="block" for="user_pass_confirm">
                <?php _e("Confirmer votre mot de passe", 'opatrimoine'); ?> :
            </label>
            <input class="px-4 py-2 border border-grey" type="password" name="pwd_confirm" id="user_pass_confirm"
                required>
        </div>

        <input class="mx-auto btn" name="edit_pass" id="edit_pass" type="submit" value="Valider" />
    </form>

    <a class="block mx-auto btn btn-third" href="<?php echo wp_logout_url(home_url()); ?>"
        class="header__disconnect-btn">Se
        déconnecter</a>
</section>

<!-- Listing Guided Tours -->
<section class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <h3 class="text-center titles">Mes visites réservées</h3>
    <?php if (is_a($guidedToursQuery, 'WP_Query') && $guidedToursQuery->have_posts()): ?>
        <div class="guided_tours">
            <?php while ($guidedToursQuery->have_posts()) {
                $guidedToursQuery->the_post();
                get_template_part('templates/partials/guided-tour', null, ['currentTemplateSlug' => $pageTemplateSlug]);
            }
            wp_reset_postdata(); ?>
        </div>
    <?php else: ?>
        <p>Pas encore de visites sur ce lieux.</p>
    <?php endif; ?>
</section>


<?php
get_footer();
?>