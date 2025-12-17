<?php
/**
 * MU Plugin Loader
 *
 * This file loads plugins placed in subdirectories of wp-content/mu-plugins.
 */

// Load Advanced Custom Fields Pro if present
if ( file_exists( __DIR__ . '/advanced-custom-fields-pro/acf.php' ) ) {
    require_once __DIR__ . '/advanced-custom-fields-pro/acf.php';
}