<?php
// Template Name: Mot de passe oublié

$url_lost = get_page_url_by_template('templates/lost_password.php');
$url_connexion = get_page_url_by_template('templates/connection.php');
$msg = isset($_GET['msg']) ? $_GET['msg'] : false;
$action = isset($_GET['action']) ? $_GET['action'] : false;

get_header();
the_post();
?>


<section class="container px-6 mx-auto md:px-8 lg:px-12 xl:px-18 2xl:px-28">
    <h2 class="text-center titles">
        <?= the_title() ?>
    </h2>
    <div>
        <?= the_content() ?>
    </div>

    <?php if ($action && $action == 'rp'):
        $rp_cookie = 'wp-resetpass-' . COOKIEHASH;
        if (isset($_COOKIE[$rp_cookie]) && 0 < strpos($_COOKIE[$rp_cookie], ':')) {
            list($rp_login, $rp_key) = explode(':', wp_unslash($_COOKIE[$rp_cookie]), 2);
        }
        ?>

        <!-- // *** set-new-pass form ***-->
        <?php switch ($msg):
            case 'pass_not_complexe': ?>
                <p class="text-center text-red-500">Le mot de passe n'est pas assez complexe</p>
                <?php break;
            case 'pass_empty_space': ?>
                <p class="text-center text-red-500">Le mot de passe ne peut pas être des espaces</p>
                <?php break;
            case 'pass_mismatch': ?>
                <p class="text-center text-red-500">Les mots de passe ne correspondent pas</p>
                <?php break;
        endswitch; ?>
        <form class="flex flex-col items-center gap-4 px-4 mb-8 xl:px-20" name="renew_password_form"
            id="renew_password_form" action="" method="post">
            <input type="hidden" name="rp_key" value="<?php echo esc_attr($rp_key); ?>" />
            <label class="block" for="pass1">
                <?php _e("Mot de passe*", 'opatrimoine'); ?> :
            </label>
            <input class="px-4 py-2 border border-grey" type="password" name="pass1" id="pass1" required
                autocomplete="new-password" spellcheck="false">
            <label class="block" for="pass2">
                <?php _e("Confirmer votre mot de passe", 'opatrimoine'); ?> :
            </label>
            <input class="px-4 py-2 border border-grey" type="password" name="pass2" id="pass2" required
                autocomplete="new-password" spellcheck="false" data-reveal="1">
            <button class="mx-auto btn" type="submit" name="new_pass">Valider</button>
        </form>

    <?php elseif ($action == 'new_pass_set'): ?>

        <!-- // *** Confirm change password *** -->
        <p class="text-center">Votre mot de passe a été renouvelé</p>
        <a class="block mx-auto btn" href="<?= $url_connexion ?>">
            <?php _e('Se connecter', 'opatrimoine'); ?>
        </a>

    <?php else: ?>

        <!-- // *** Default enter-your-email form *** -->
        <?php switch ($msg):
            case 'empty_field': ?>
                <p class="text-center text-red-500">Champs obligatoire</p>
                <?php break;
            case 'invalid': ?>
                <p class="text-center text-red-500">Erreur : adresse email non valide</p>
                <?php break;
            case 'expiredkey':
            case 'invalidkey': ?>
                <p class="text-center text-red-500">Erreur : impossible de renouveller le mot de passe,<br> veuillez ré-entrer votre
                    adresse email pour recevoir un nouveau lien</p>
                <?php break;
            case 'not_exist': ?>
                <p class="text-center text-red-500">Erreur : aucun compte avec cette adresse email</p>
                <?php break;
            case 'send_renew': ?>
                <p class="text-center text-green-500">Un mail de renouvellement vous a été envoyé</p>
                <?php break;
        endswitch; ?>
        <form class="flex flex-col items-center gap-4 px-4 mb-8 xl:px-20" name="lost_password_form" id="lost_password_form"
            action="" method="post">
            <label class="block" for="user_login">
                <?php _e("E-mail*", 'opatrimoine'); ?> :
            </label>
            <input class="px-4 py-2 border border-grey" name="user_login" id="user_login" type="email" required>
            <button class="mx-auto btn" type="submit" name="check_email">Valider</button>
        </form>

    <?php endif; ?>



</section>


<?php
get_footer();
?>