<?php
// Template Name: Inscription

get_header();
the_post();
?>


<section class="container mx-auto px-6 md:px-8 lg:px-12 xl:px-18 2xl:px-28mx-auto">
    <h2 class="text-main"><?= the_title() ?></h2>

    <?php if (!empty(get_the_content())) : ?>
        <div class="editor px-4 xl:px-24">
            <?php the_content(); ?>
        </div>
    <?php endif; ?>
    <form class="flex flex-col items-center xl:px-20 px-4 mb-8" name="registrationform" id="registrationform" action="<?= get_theme_file_uri('functions/user/registration.php') ?>" method="post">
        <?php if (!empty($_GET['error-registration'])) : ?>
            <div>
                <div class="border border-fourth p-5 my-5 text-center">
                    <p><b><?= $_GET['error-registration'] ?></b></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center w-auto">
            <label class="block mb-2" for="user_login"><?php _e("Login", 'opatrimoine'); ?> :</label>
            <input class="border border-grey px-4 py-2 mb-4" name="login" id="user_login" type="text" required>
        </div>

        <div class="text-center w-auto">
            <label class="block mb-2" for="user_email"><?php _e("Email", 'opatrimoine'); ?> :</label>
            <input class="border border-grey px-4 py-2 mb-4" name="email" id="user_email" type="email" required>
        </div>

        <div class="text-center w-auto">
            <label class="block mb-2" for="user_pass"><?php _e("Mot de passe", 'opatrimoine'); ?> :</label>
            <input class="border border-grey px-4 py-2 mb-4" type="password" name="pwd" id="user_pass" required>
        </div>

        <div class="text-center w-auto">
            <label class="block mb-2" for="user_pass_confirm"><?php _e("Confirmer votre mot de passe", 'opatrimoine'); ?> :</label>
            <input class="border border-grey px-4 py-2 mb-4" type="password" name="pwd_confirm" id="user_pass_confirm" required>
        </div>

        <button class="btn mx-auto" type="submit">Valider</button>
    </form>
</section>



<?php
get_footer();
?>