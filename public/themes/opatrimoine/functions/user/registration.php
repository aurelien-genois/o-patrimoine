<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-load.php';

$url_creat_account = get_page_url_by_template('templates/registration.php');
$url_connexion = get_page_url_by_template('templates/connection.php');

$postValues = $_POST;
array_walk_recursive($postValues, 'cleanOutput');

$login = $postValues['login'];
$email = $postValues['email'];
$pwd = $postValues['pwd'];
$pwdConfirm = $postValues['pwd_confirm'];

$error = false;
if (empty($login) || empty($email) || empty($pwd) || empty($pwdConfirm)) {
    $error = 'champs non remplis';
    wp_redirect($url_creat_account . '?error-registration=' . $error);
    exit();
} else {
    // verify email
    if (is_email($email)) {
        $email = sanitize_email($email);
        $domainExp = explode('@', $email);
        if (isset($domainExp[1])) {
            $domain = $domainExp[1];
            if (!checkdnsrr($domain, 'MX')) {
                $error = 'DNS non valide';
            } else {
                // ok
            }
        } else {
            $error = 'mail non valide';
        }
    } else {
        $error = 'mail non valide';
    }

    // ! $error will be replaced

    if (email_exists($email)) {
        $error = 'mail déjà existant';
    }

    if ($pwd != $pwdConfirm) {
        $error = 'Mots de passe différents';
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*[!?\/\\;:%@#\-_&*])(?=.*[0-9])(?=.*[a-z]).{8,20}$/', $pwd)) {
        $error = 'Mot de passe pas assez complexe';
    }

    if ($error) {
        wp_redirect($url_creat_account . '?error-registration=' . $error);
        exit();
    } else {
        $user_id = wp_create_user($login, $pwd, $email);
        if ($user_id) {
            $user = new WP_User($userId);
            $user->remove_role('subscriber');
            $user->add_role('visitor');
            // todo send email to user
            wp_redirect($url_connexion . '?message=inscrit');
            exit();
        } else {
            $error = "Erreur BDD";
            wp_redirect($url_creat_account . '?error-registration=' . $error);
            exit();
        }
    }
}