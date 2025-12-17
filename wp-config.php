<?php
use function Env\env;

require_once __DIR__ . '/vendor/autoload.php';
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
    if (!getenv('DATABASE_URL')) {
        $dotenv->required(['WORDPRESS_DB_NAME', 'WORDPRESS_DB_USER', 'WORDPRESS_DB_PASSWORD']);
    }
}

// Set the home url to the current domain.
define('WP_HOME', getenv('WP_HOME'));

define('WP_SITEURL', getenv('WP_SITEURL') ?: sprintf('%s/%s', WP_HOME, getenv('WP_DIR') ?: 'wp'));

// Set the WordPress directory path.
if (!defined('ABSPATH')) {
    define('ABSPATH', sprintf('%s/%s/', __DIR__, getenv('WP_DIR') ?: 'wp'));
}


define('WP_DEFAULT_THEME', getenv('WP_THEME') ?: 'opatrimoine');

define('DB_NAME', getenv('WORDPRESS_DB_NAME'));
define('DB_USER', getenv('WORDPRESS_DB_USER'));
define('DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD'));
define('DB_HOST', getenv('WORDPRESS_DB_HOST') ?: 'localhost');
const DB_CHARSET = 'utf8mb4';
const DB_COLLATE = 'utf8mb4_general_ci';
$table_prefix = getenv('WORDPRESS_TABLE_PREFIX') ?: 'wp_';

define('AUTH_KEY', 'A{MV2jX]JKP;2.<LV<xR/V5i,)mMdl7})NSepoRFk$#p)PhJeFnCPJ8W8YfO(7?f');
define('SECURE_AUTH_KEY', 'Rn.65/#UY]jT[Z[N<3`;QE^3Fs4y@V2PDo^<Hv5HoM0wVL(B@=aS+6D9N8zG[XmF');
define('LOGGED_IN_KEY', 'U9zMS/S9m>O%LERQf+03UV$$63P<9:Gu)]1QqswE*d_?usQ0adP,qJ!.|+gmRV|Q');
define('NONCE_KEY', '..to%}{DI#BYKdcAI8Ovm8hZhNtL]{>.27.0i:g+2w^FRh26]](4xr;x}EXO#2%0');
define('AUTH_SALT', 'v%f|gLgc@6;)k6{ge6A5!}ZNR.CJXc3wA7>]p9Zcj*Bwor;61?kwjLjw{>]33M{{');
define('SECURE_AUTH_SALT', 'FgRT0}.7BD#>)+KkGY>LSDuH/oiK=0lu<54qV&_`]cOeLVB(Kv_<gKifBd*|V:dE');
define('LOGGED_IN_SALT', 'Nq3ustG#!;M$hGIT[ntCp>a3/g$5guCn(S{,W`P(dgLnw@`ZO2k{W7<s:*jNyDV1');
define('NONCE_SALT', 'Y|x5Ty$ZRwaqy%ECxMnJj[E:1K}O`nN(9!5J&R<};`OIX=wn-8vI5F>G@uih$%49');



const AUTOMATIC_UPDATER_DISABLED = true;
define('DISABLE_WP_CRON', getenv('DISABLE_WP_CRON') ?: false);
// Disable the plugin and theme file editor in the admin
const DISALLOW_FILE_EDIT = true;
// Limit the number of post revisions that Wordpress stores (true (default WP): store every revision)
define('WP_POST_REVISIONS', getenv('WP_POST_REVISIONS') ?: true);

/**
 * Debugging Settings
 */
define('WP_ENV', getenv('WP_ENV'));
if (getenv('WP_ENV') && getenv('WP_ENV') === 'development') {
    define('WP_DEBUG', true);
    define('WP_DEBUG_DISPLAY', false);
    define('WP_DEBUG_LOG', true);
    define('SCRIPT_DEBUG', true);
    define('DISALLOW_INDEXING', true);
}

ini_set( 'upload_max_filesize' , '512m' );
ini_set( 'post_max_size', '512m');
ini_set( 'memory_limit', '512m' );
ini_set( 'max_execution_time', '300' );
ini_set( 'max_input_time', '300' );

ini_set('display_errors', '0');

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

require_once ABSPATH . 'wp-settings.php';
