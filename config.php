<?php
// HTTP
define('HTTP_SERVER', 'http://localhost/obsthof/');

// HTTPS
define('HTTPS_SERVER', 'http://localhost/obsthof/');

// DIR
define('DIR_APPLICATION', '/Applications/XAMPP/xamppfiles/htdocs/obsthof/catalog/');
define('DIR_SYSTEM', '/Applications/XAMPP/xamppfiles/htdocs/obsthof/system/');
define('DIR_IMAGE', '/Applications/XAMPP/xamppfiles/htdocs/obsthof/image/');
define('DIR_STORAGE', '/Applications/XAMPP/xamppfiles/storage/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'shop');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');

// MORPHEUS
define('MORPHEUS_HOMEPAGE', ['warum/', 'wie/']);

// SECURITY
define('NONE_SCRIPT', 'IY5DJNUZlwx3K4gn6jT3OA');

// SPECIAL EMAIL
//define('SPECIAL_EMAIL', 'vu@pixeldusche.com');
//define('SPECIAL_EMAIL', 'vu@pixeldusche.com');
define('SPECIAL_EMAIL', 'vukynamkhtn@gmail.com');
define('SPECIAL_EMAIL2', 'vukynamkhtn@gmail.com');

define('NEW_URL', 'obsthof-am-steinberg.de');
define('OLD_URL', array("2024neu.obsthof-am-steinberg.de", "neu.obsthofamsteinberg.de", "obsthofamsteinberg.de"));

// Überprüfe, ob das Cookie "needed" gesetzt ist
if (isset($_GET['setcookie'])) {
	define('NEW_COOKIE', 1);
	delCookieX('cookie_disclaimer');
} 
else if (isset($_COOKIE['cookie_disclaimer'])) {
	define('NEW_COOKIE', null);
} 
else {
	define('NEW_COOKIE', 1);
}

function delCookieX($key)
{
	$past = time() - 3600;
	setcookie($key, '', $past, '/');
}