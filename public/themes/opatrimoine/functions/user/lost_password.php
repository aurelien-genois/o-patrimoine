<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-load.php';

$url_lost = get_page_url_by_template('templates/lost_password.php');

$postValues = $_POST;
array_walk_recursive($postValues, 'cleanOutput');


if (isset($postValues['check_email'])) {

    $email = $postValues['user_login'];

    if (empty($email)) {
        wp_redirect($url_lost . '?msg=empty_field');
        exit();
    } else {
        // verify email
        if (is_email($email)) {
            $email = sanitize_email($email);
            $domainExp = explode('@', $email);
            if (isset($domainExp[1])) {
                $domain = $domainExp[1];
                if (!checkdnsrr($domain, 'MX')) {
                    wp_redirect($url_lost . '?msg=invalid');
                    exit();
                } else {
                    // ok
                }
            } else {
                wp_redirect($url_lost . '?msg=invalid');
                exit();
            }
        } else {
            wp_redirect($url_lost . '?msg=invalid');
            exit();
        }

        if (!email_exists($email)) {
            wp_redirect($url_lost . '?msg=not_exist');
            exit();
        }

        $results = retrieve_password($email);

        if ($results) {
            wp_redirect($url_lost . '?msg=send_renew');
            exit();
        }

    }
}

if (isset($postValues['new_pass'])) {

    // TODO do not used Wordpress reset password script ?
    // from wp-login.php
    $rp_cookie = 'wp-resetpass-' . COOKIEHASH;

    // $_COOKIE[$rp_cookie] does not exist here ?
    var_dump($_COOKIE);
    var_dump($rp_cookie);
    die();

    if (isset($_COOKIE[$rp_cookie]) && 0 < strpos($_COOKIE[$rp_cookie], ':')) {
        list($rp_login, $rp_key) = explode(':', wp_unslash($_COOKIE[$rp_cookie]), 2);
        $user = check_password_reset_key($rp_key, $rp_login);
        if (isset($_POST['pass1']) && !hash_equals($rp_key, $_POST['rp_key'])) {
            wp_redirect($url_lost . '?msg=invalidkey');
            exit();
        }
    } else {
        wp_redirect($url_lost . '?msg=invalidkey');
        exit();
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

}