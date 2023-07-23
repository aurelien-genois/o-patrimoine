<?php
// Template Name: Connexion
$url_lost = get_page_url_by_template('templates/lost_password.php');
$url_profil = get_page_url_by_template('templates/account.php');
$url_creat_account = get_page_url_by_template('templates/registration.php');

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
    <form class="flex flex-col items-center gap-4 px-4 mb-8 xl:px-20" name="loginform" id="loginform"
        action="<?php echo site_url('wp-login.php'); ?>" method="post">

        <input type="hidden" name="redirect_to" value="<?= $url_profil; ?>" />
        <?php if (!empty($_GET['message']) && $_GET['message'] == 'errorconnexion'): ?>
            <div>
                <div class="p-5 my-5 text-center border border-fourth">
                    <p><b>
                            <?php _e('Erreur avec votre login ou mot de passe.', 'opatrimoine'); ?>
                        </b></p>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($_GET['message']) && $_GET['message'] == 'inscrit'): ?>
            <div>
                <div class="p-5 my-5 text-center border border-fourth">
                    <p><b>
                            <?php _e('Vous êtes bien inscrit, vous pouvez vous connecter.', 'opatrimoine'); ?>
                        </b></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="w-auto text-center">
            <label class="block" for="user_login">
                <?php _e("Login / E-mail", 'opatrimoine'); ?> :
            </label>
            <input class="px-4 py-2 border border-grey" name="log" id="user_login" type="text" required>
        </div>

        <div class="w-auto text-center">
            <label class="block" for="user_pass">
                <?php _e("Mot de passe", 'opatrimoine'); ?> :
            </label>
            <input class="px-4 py-2 border border-grey" type="password" name="pwd" id="user_pass" required>
        </div>

        <p class="text-center">
            <button class="btn" type="submit" name="wp-submit"><span>
                    <?php _e('Connexion', 'opatrimoine'); ?>
                </span></button>
        </p>
    </form>

    <p class="text-center">
        <a class="link" href="<?php echo $url_lost; ?>" class="underline">
            <?php _e('Mot de passe oublié ?', 'opatrimoine'); ?>
        </a>
        /
        <a class="btn" href="<?php echo $url_creat_account ?>">
            <?php _e('Créer mon compte', 'opatrimoine'); ?>
        </a>
    </p>

</section>



<?php
get_footer();
?>