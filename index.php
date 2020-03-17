<?php
// Version
define('VERSION', '1.0.0');

// BASES
define('DOMAIN', explode('.', $_SERVER['HTTP_HOST'])[0]);
define('MAIN_DOMAIN', str_replace(DOMAIN . '.', '', $_SERVER['HTTP_HOST']));
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']); // ../../api.domain.com
define('HTTP_SERVER', 'https://api.' . MAIN_DOMAIN . '/v1/');

// COMMON DIR
define('DIR_REPOSITORY', DOCUMENT_ROOT . '/v1/repository/');
define('DIR_APPLICATION', DOCUMENT_ROOT . '/v1/');
define('DIR_SYSTEM', DOCUMENT_ROOT . '/v1/system/');
define('DIR_CONFIG', DOCUMENT_ROOT . '/v1/system/config/');
define('DIR_LOGS', DOCUMENT_ROOT . '/');

// OTHER DEFINES
define('ENVIRONMENT', 'production');
define('COOKIE_DOMAIN', '.' . MAIN_DOMAIN);
define('SESSION', '.' . strtoupper(MAIN_DOMAIN));
define('SESSION_EXPIRE', 2 * 24 * 60 * 60);

// Set Error Display
if (ENVIRONMENT == 'development') {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

$application_config = 'api';

// Application
require_once(DIR_SYSTEM . 'framework.php');