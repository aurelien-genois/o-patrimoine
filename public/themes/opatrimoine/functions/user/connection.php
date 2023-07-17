<?php
function admin_default_page()
{
    if (!empty($_POST['log'])) {
        $user = get_user_by('email', $_POST['log']);
        if (!$user)
            $user = get_user_by('login', $_POST['log']);

        if ($user && wp_check_password($_POST['pwd'], $user->data->user_pass, $user->ID)) {
            foreach ($user->roles as $role) {
                if ($role == 'administrator') {
                    $redirect_to = admin_url();
                    wp_redirect($redirect_to);
                    exit();
                }
            }
            $redirect_to = $_POST['redirect_to'];
            wp_redirect($redirect_to);
            exit();
        } else {
            $url = add_query_arg('message', 'errorconnexion', wp_get_referer());
            wp_redirect($url);
            exit();
        }
    }
}
add_filter('login_redirect', 'admin_default_page');

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