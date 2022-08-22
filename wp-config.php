<?php

use function Env\env;

require_once __DIR__.'/vendor/autoload.php';
/**
 * CONFIG START
 */

/**
 * Use Dotenv to set required environment variables and load .env file in root
 * .env.local will override .env if it exists
 */
$env_files = file_exists(__DIR__ . '/.env.local')
    ? ['.env', '.env.local']
    : ['.env'];

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__, $env_files, false);
if (file_exists(__DIR__ . '/.env')) {
    $dotenv->load();
    $dotenv->required(['WP_HOME']);
    if (!env('DATABASE_URL')) {
        $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD']);
    }
}

// Set the home url to the current domain.
define('WP_HOME', env('WP_HOME'));

// Set the WordPress directory path.
define('WP_SITEURL', env('WP_SITEURL') ?: sprintf('%s/%s', WP_HOME, env('WP_DIR') ?: 'wp'));

// Set the WordPress content directory path.
define('WP_CONTENT_DIR', env('WP_CONTENT_DIR') ?: __DIR__);
define('WP_CONTENT_URL', env('WP_CONTENT_URL') ?: WP_HOME);


const WP_DEFAULT_THEME = 'opatrimoine';

define('DB_NAME', env('DB_NAME'));
define('DB_USER', env('DB_USER'));
define('DB_PASSWORD', env('DB_PASSWORD'));
define('DB_HOST', env('DB_HOST') ?: 'localhost');
const DB_CHARSET = 'utf8mb4';
const DB_COLLATE = 'utf8mb4_general_ci';
$table_prefix = env('DB_PREFIX') ?: 'wp_';

define('AUTH_KEY', env('AUTH_KEY'));
define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
define('NONCE_KEY', env('NONCE_KEY'));
define('AUTH_SALT', env('AUTH_SALT'));
define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
define('NONCE_SALT', env('NONCE_SALT'));

const AUTOMATIC_UPDATER_DISABLED = true;
define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?: false);
// Disable the plugin and theme file editor in the admin
const DISALLOW_FILE_EDIT = true;
// Limit the number of post revisions that Wordpress stores (true (default WP): store every revision)
define('WP_POST_REVISIONS', env('WP_POST_REVISIONS') ?: true);

/**
 * Debugging Settings
 */
define('WP_ENV', env('WP_ENV'));
if(env('WP_ENV') && env('WP_ENV') === 'local') {
    define('WP_DEBUG', true);
    define('WP_DEBUG_DISPLAY', false);
    define('WP_DEBUG_LOG', true);
    define('SCRIPT_DEBUG', true);
    define('DISALLOW_INDEXING', true);
}

ini_set('display_errors', '0');

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', sprintf('%s/%s/', __DIR__, 'wp'));
}
/**
 * CONFIG END
 */
require_once ABSPATH.'wp-settings.php';