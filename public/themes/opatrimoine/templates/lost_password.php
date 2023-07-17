<?php
// Template Name: Mot de passe oublié

// todo
// * => better in registration page ?

// 1 - form with email
// --- script call $results = retrieve_password( $user->user_login ); (return true if success, else return $errors)
// --- if success display message, if error display errors

// --- => can manage in ajax with action send_password_reset and create nonce " 'reset-password-for-' . $user_id " and user_id
// --- wp_ajax_send_password_reset() call retrieve_password() then return in js json_success/json_error
// --- if ajax, only form inputs (user_id, nonce, action) and JS is necessay for this step
// * no user_id if lost password => cannot use this Ajax function

// ! by default, retrieve_password() : link in email is to wp_login URL
// (network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '&wp_lang=' . $locale . "\r\n\r\n";)
// => use filter 'retrieve_password_message' to customize

// 2 - check the action 'resetpass' and token "user_activation_key" to display the reset password form
// --- script : check check_password_reset_key() then reset_password() (similar to wp-login.php case 'resetpass'...)

$url_lost = get_page_url_by_template('templates/lost_password.php');
$url_connexion = get_page_url_by_template('templates/connection.php');
$msg = isset($_GET['msg']) ? $_GET['msg'] : false;
$action = isset($_GET['action']) ? $_GET['action'] : false;

if ($action && $action == 'rp'):

    // for WP strong password check
    // wp_enqueue_script('utils');
    // wp_enqueue_script('user-profile');


    // from wp-login.php
    list($rp_path) = explode('?', wp_unslash($_SERVER['REQUEST_URI']));
    $rp_cookie = 'wp-resetpass-' . COOKIEHASH;
    if (isset($_GET['key']) && isset($_GET['login'])) {
        $value = sprintf('%s:%s', wp_unslash($_GET['login']), wp_unslash($_GET['key']));
        setcookie($rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true);

        wp_safe_redirect(remove_query_arg(array('key', 'login')));
        exit;
    }

    // from wp-login.php
    if (isset($_COOKIE[$rp_cookie]) && 0 < strpos($_COOKIE[$rp_cookie], ':')) {
        list($rp_login, $rp_key) = explode(':', wp_unslash($_COOKIE[$rp_cookie]), 2);
        $user = check_password_reset_key($rp_key, $rp_login);
        if (isset($_POST['pass1']) && !hash_equals($rp_key, $_POST['rp_key'])) {
            wp_redirect($url_lost . '?msg=invalidkey');
            exit();
        }
    } else {
        $user = false;
    }


    if (!$user || is_wp_error($user)) {
        setcookie($rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true);
        if ($user && $user->get_error_code() === 'expired_key') {
            wp_redirect($url_lost . '?&msg=expiredkey');
            exit();
        } else {
            wp_redirect($url_lost . '?&msg=invalidkey');
            exit();
        }
    }
    $errors = new WP_Error();

    // Check if password is one or all empty spaces.
    if (!empty($_POST['pass1'])) {
        $_POST['pass1'] = trim($_POST['pass1']);

        if (empty($_POST['pass1'])) {
            wp_redirect($_SERVER['HTTP_REFERER'] . '&msg=pass_empty_space');
            exit();
        }
    }

    // Check if password fields do not match.
    if (!empty($_POST['pass1']) && trim($_POST['pass2']) !== $_POST['pass1']) {
        wp_redirect($_SERVER['HTTP_REFERER'] . '&msg=pass_mismatch');
        exit();
    }

    if ((!$errors->has_errors()) && isset($_POST['pass1']) && !empty($_POST['pass1'])) {
        reset_password($user, $_POST['pass1']);
        setcookie($rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true);
        wp_redirect($url_lost . '?action=new_pass_set');
        exit();
    }
endif;

// TODO do not used Wordpress reset password script ?

get_header();
the_post();
?>


<section class="container px-6 md:px-8 lg:px-12 xl:px-18 2xl:px-28 mx-auto">
    <h2 class="titles text-center">
        <?= the_title() ?>
    </h2>
    <div>
        <?= the_content() ?>
    </div>

    <?php if ($action && $action == 'rp'): ?>
        <!-- set-new-pass form -->

        <?php switch ($msg):
            case 'pass_empty_space': ?>
                <p class="text-center text-red-500">Le mot de passe ne peut pas être des espaces</p>
                <?php break;
            case 'pass_mismatch': ?>
                <p class="text-center text-red-500">Les mots de passe ne correspondent pas</p>
                <?php break;
        endswitch; ?>
        <form class="flex flex-col items-center xl:px-20 px-4 mb-8" name="renew_password_form" id="renew_password_form"
            action="<?php // get_theme_file_uri('functions/user/lost_password.php') ?>" method="post">
            <input type="hidden" name="rp_key" value="<?php echo esc_attr($rp_key); ?>" />
            <label class="block mb-2" for="pass1">
                <?php _e("Mot de passe*", 'opatrimoine'); ?> :
            </label>
            <input class="border border-grey px-4 py-2 mb-4" type="password" name="pass1" id="pass1" required
                autocomplete="new-password" spellcheck="false">
            <label class="block mb-2" for="pass2">
                <?php _e("Confirmer votre mot de passe", 'opatrimoine'); ?> :
            </label>
            <input class="border border-grey px-4 py-2 mb-4" type="password" name="pass2" id="pass2" required
                autocomplete="new-password" spellcheck="false" data-reveal="1">
            <button class="btn mx-auto" type="submit" name="new_pass">Valider</button>
        </form>

    <?php elseif ($action == 'new_pass_set'): ?>
        <p>Votre mot de passe a été renouvelé</p>
        <a class="btn" href="<?= $url_connexion ?>">
            <?php _e('Se connecter', 'opatrimoine'); ?>
        </a>
    <?php else: ?>
        <!-- Default enter-your-email form -->
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
        <form class="flex flex-col items-center xl:px-20 px-4 mb-8" name="lost_password_form" id="lost_password_form"
            action="<?= get_theme_file_uri('functions/user/lost_password.php') ?>" method="post">
            <label class="block mb-2" for="user_login">
                <?php _e("E-mail*", 'opatrimoine'); ?> :
            </label>
            <input class="border border-grey px-4 py-2 mb-4" name="user_login" id="user_login" type="email" required>
            <button class="btn mx-auto" type="submit" name="check_email">Valider</button>
        </form>

    <?php endif; ?>



</section>


<?php
get_footer();
?>