<?php
// Template Name: Inscription

$msg = isset($_GET['msg']) ? $_GET['msg'] : false;

get_header();
the_post();
?>


<section class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <h2 class="text-center titles">
        <?= the_title() ?>
    </h2>

    <?php if (!empty(get_the_content())): ?>
        <div class="px-4 editor xl:px-24">
            <?php the_content(); ?>
        </div>
    <?php endif; ?>
    <form class="flex flex-col items-center gap-4 px-4 mb-8 xl:px-20" name="registrationform" id="registrationform"
        action="<?= get_theme_file_uri('functions/user/registration.php') ?>" method="post">
        <?php if ($msg): ?>
            <div>
                <p class="p-5 my-5 font-bold text-center border border-fourth">
                    <?php switch ($msg):
                        case 'empty_field': ?>
                            <span class="text-red-500">Champs obligatoire</span>
                            <?php break;
                        case 'invalid': ?>
                            <span class="text-red-500">Erreur : adresse email non valide</span>
                            <?php break;
                        case 'email_exist': ?>
                            <span class="text-red-500">Erreur : adresse email déjà existante</span>
                            <?php break;
                        case 'pass_not_complexe': ?>
                            <span class="text-red-500">Le mot de passe n'est pas assez complexe</span>
                            <?php break;
                        case 'pass_empty_space': ?>
                            <span class="text-red-500">Le mot de passe ne peut pas être des espaces</span>
                            <?php break;
                        case 'pass_mismatch': ?>
                            <span class="text-red-500">Les mots de passe ne correspondent pas</span>
                            <?php break;
                        case 'error_bdd': ?>
                            <span class="text-red-500">Erreur lors de la création du compte</span>
                            <?php break;
                    endswitch; ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="w-auto text-center">
            <label class="block" for="user_login">
                <?php _e("Login", 'opatrimoine'); ?> :
            </label>
            <input class="px-4 py-2 border border-grey" name="login" id="user_login" type="text" required>
        </div>

        <div class="w-auto text-center">
            <label class="block" for="user_email">
                <?php _e("Email", 'opatrimoine'); ?> :
            </label>
            <input class="px-4 py-2 border border-grey" name="email" id="user_email" type="email" required>
        </div>

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

        <button class="mx-auto btn" type="submit">Valider</button>
    </form>
</section>



<?php
get_footer();
?>