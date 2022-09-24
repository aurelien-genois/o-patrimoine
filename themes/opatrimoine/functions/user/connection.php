<?php
function admin_default_page()
{
    if (!empty($_POST['log'])) {
        $user = get_user_by('email', $_POST['log']);
        if (!$user) $user = get_user_by('login', $_POST['log']);

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
