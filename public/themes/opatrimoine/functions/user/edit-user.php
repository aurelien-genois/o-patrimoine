<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-load.php';
$url_account = get_page_url_by_template('templates/account.php');
$error = false;

if (isset($_POST['edit_user'])) {
    if (empty($_POST['login']) || empty($_POST['email'])) {
        $error = 'champs non remplis';
        wp_redirect($url_account . '?error-edited=' . $error);
        exit();
    } else {
        $user = wp_get_current_user();

        $roles = $user->roles;
        if (!in_array('visitor', $roles)) {
            $error = 'non autorisé';
            wp_redirect($url_account . '?error-edited=' . $error);
            exit();
        }

        $postValues = $_POST;
        array_walk_recursive($postValues, 'cleanOutput');

        $login = $postValues['login'];
        $email = $postValues['email'];

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
        ;

        if (($email != $user->user_email) && email_exists($email)) {
            $error = 'mail déjà existant';
        }

        if ($error) {
            wp_redirect($url_account . '?error-edited=' . $error);
            exit();
        } else {
            wp_update_user(['ID' => $user->ID, 'display_name' => $login]);
            wp_update_user(['ID' => $user->ID, 'user_email' => $email]);
            // todo send email to user
            wp_redirect($url_account . '?message=edited');
        }
    }
}

if (isset($_POST['edit_pass'])) {
    if (empty($_POST['pwd']) || empty($_POST['pwd_confirm'])) {
        $error = 'champs non remplis';
        wp_redirect($url_account . '?error-edited=' . $error);
        exit();
    } else {
        $user = wp_get_current_user();

        $roles = $user->roles;
        if (!in_array('visitor', $roles)) {
            $error = 'non autorisé';
            wp_redirect($url_account . '?error-edited=' . $error);
            exit();
        }

        $postValues = $_POST;
        array_walk_recursive($postValues, 'cleanOutput');

        $pwd = $postValues['pwd'];
        $pwdConfirm = $postValues['pwd_confirm'];


        if ($pwd != $pwdConfirm) {
            $error = 'Mots de passe différents';
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[!?\/\\;:%@#\-_&*])(?=.*[0-9])(?=.*[a-z]).{8,20}$/', $pwd)) {
            $error = 'Mot de passe pas assez complexe';
        }

        if ($error) {
            wp_redirect($url_account . '?error-edited=' . $error);
            exit();
        } else {
            wp_update_user(['ID' => $user->ID, 'user_pass' => $pwd]);
            // todo send email to user
            wp_redirect($url_account . '?message=pass_edited');
        }
    }
}