<?php

function check_lost_password()
{
    if (is_page_template('templates/lost_password.php')) {
        $url_lost = get_page_url_by_template('templates/lost_password.php');

        $postValues = $_POST;
        array_walk_recursive($postValues, 'cleanOutput');

        // ******** STEP 1 : send email *********
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

        // ******** STEP 2 : display new pass form *********
        // adapted from wp-login.php
        // check key/login parameters, set cookie and redirect by removing these parameters
        list($rp_path) = explode('?', wp_unslash($_SERVER['REQUEST_URI']));
        $rp_cookie = 'wp-resetpass-' . COOKIEHASH;
        if (isset($_GET['key']) && isset($_GET['login'])) {
            $value = sprintf('%s:%s', wp_unslash($_GET['login']), wp_unslash($_GET['key']));
            setcookie($rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true);

            wp_safe_redirect(remove_query_arg(array('key', 'login')));
            exit;
        }

        // ******** STEP 3 : check & change new pass *********
        if (isset($postValues['new_pass'])) {
            // adapted from wp-login.php

            // check key in cookies and get user
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

            // check user and clear (empty) cookie if error
            if (!$user || is_wp_error($user)) {
                setcookie($rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true);
                if ($user && $user->get_error_code() === 'expired_key') {
                    wp_redirect($url_lost . '?&msg=expiredkey');
                    exit();
                } else {
                    wp_redirect($url_lost . '?&msg=invalidkey2');
                    exit();
                }
            }

            $errors = new WP_Error();

            // Check if password is one or all empty spaces.
            if (!empty($_POST['pass1'])) {
                $_POST['pass1'] = trim($_POST['pass1']);

                if (empty($_POST['pass1'])) {
                    // $_SERVER['HTTP_REFERER'] to keep get parameters
                    wp_redirect(add_query_arg('msg', 'pass_empty_space'));
                    exit();
                }
            }

            // check password complexity
            if (!preg_match('/^(?=.*[A-Z])(?=.*[!?\/\\;:%@#\-_&*])(?=.*[0-9])(?=.*[a-z]).{8,20}$/', $_POST['pass1'])) {
                // $_SERVER['HTTP_REFERER'] to keep get parameters
                wp_redirect(add_query_arg('msg', 'pass_not_complexe'));
                exit();
            }

            // Check if password fields do not match.
            if (!empty($_POST['pass1']) && trim($_POST['pass2']) !== $_POST['pass1']) {
                // $_SERVER['HTTP_REFERER'] to keep get parameters
                wp_redirect(add_query_arg('msg', 'pass_mismatch'));
                exit();
            }

            // if no error, change password and redirect
            if ((!$errors->has_errors()) && isset($_POST['pass1']) && !empty($_POST['pass1'])) {
                reset_password($user, $_POST['pass1']);
                setcookie($rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true);
                wp_redirect($url_lost . '?action=new_pass_set');
                exit();
            }

        }
    }


}
add_filter('template_redirect', 'check_lost_password');

// replace default link to reset password
add_filter('retrieve_password_message', function ($message, $key, $user_login, $user_data) {
    $url_lost = get_page_url_by_template('templates/lost_password.php');

    // if from front lost_password page, $link in message to same front template
    if (str_contains($_SERVER['HTTP_REFERER'], $url_lost)) {
        $linkBegin = strpos($message, 'http');

        $locale = get_user_locale($user_data);

        $link = $url_lost . '?action=rp&key=' . $key . '&login=' . rawurlencode($user_login) . '&wp_lang=' . $locale . "\r\n\r\n";
        ;
        $message = substr_replace($message, $link, $linkBegin);
    }
    return $message;
}, 10, 4);