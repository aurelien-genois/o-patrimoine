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

if (empty($login) || empty($email) || empty($pwd) || empty($pwdConfirm)) {
    wp_redirect($url_creat_account . '?msg=empty_field');
    exit();
} else {
    // verify email
    if (is_email($email)) {
        $email = sanitize_email($email);
        $domainExp = explode('@', $email);
        if (isset($domainExp[1])) {
            $domain = $domainExp[1];
            if (!checkdnsrr($domain, 'MX')) {
                wp_redirect($url_creat_account . '?msg=invalid');
                exit();
            } else {
                // ok
            }
        } else {
            wp_redirect($url_creat_account . '?msg=invalid');
            exit();
        }
    } else {
        wp_redirect($url_creat_account . '?msg=invalid');
        exit();
    }

    // ! $error will be replaced

    if (email_exists($email)) {
        wp_redirect($url_creat_account . '?msg=email_exist');
        exit();
    }

    // Check if password is one or all empty spaces.
    if (!empty($pwd)) {
        $pwd = trim($pwd);
        if (empty($pwd)) {
            wp_redirect(add_query_arg('msg', 'pass_empty_space'));
            exit();
        }
    }

    if ($pwd != $pwdConfirm) {
        wp_redirect($url_creat_account . '?msg=pass_mismatch');
        exit();
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*[!?\/\\;:%@#\-_&*])(?=.*[0-9])(?=.*[a-z]).{8,20}$/', $pwd)) {
        wp_redirect($url_creat_account . '?msg=pass_not_complexe');
        exit();
    }


    $userId = wp_create_user($login, $pwd, $email);
    if ($userId) {
        $user = new WP_User($userId);
        $user->remove_role('subscriber');
        $user->add_role('visitor');
        // todo send email to user
        wp_redirect($url_connexion . '?message=inscrit');
        exit();
    } else {
        wp_redirect($url_creat_account . '?msg=error_bdd');
        exit();
    }
}