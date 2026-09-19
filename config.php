<?php
/**
 * Cyber Legend — configuration
 * Fill these in with the DB credentials from your host's control panel
 * (InfinityFree: hosting -> MySQL Databases). You can also set them as
 * real environment variables instead of editing this file, if your host
 * supports that.
 */

function cl_env($key, $default) {
    $v = getenv($key);
    return ($v === false || $v === '') ? $default : $v;
}

define('DB_HOST', cl_env('CL_DB_HOST', 'sqlXXX.infinityfree.com'));
define('DB_NAME', cl_env('CL_DB_NAME', 'if0_XXXXXXXX_cyberlegend'));
define('DB_USER', cl_env('CL_DB_USER', 'if0_XXXXXXXX'));
define('DB_PASS', cl_env('CL_DB_PASS', 'your-db-password'));

// Used for session cookie naming only — no need to change.
define('CL_SESSION_NAME', 'cl_admin_session');

// Max upload size in bytes (2 MB default — InfinityFree free tier has limits).
define('CL_MAX_UPLOAD', 2 * 1024 * 1024);
