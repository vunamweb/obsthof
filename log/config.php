<?php
// HTTP
define('HTTP_SERVER', 'https://obsthof-am-steinberg.de/');
define('NEW_URL', 'obsthof-am-steinberg.de');
// HTTPS
define('HTTPS_SERVER', 'https://obsthof-am-steinberg.de/');
// DIR
define('DIR_APPLICATION', '/home/yv87jv228k0b/public_html/catalog/');
define('DIR_SYSTEM', '/home/yv87jv228k0b/public_html/system/');
define('DIR_IMAGE', '/home/yv87jv228k0b/public_html/image/');
define('DIR_STORAGE', '/home/yv87jv228k0b/public_html/storage/');
// define('DIR_STORAGE', '/var/www/vhosts/morpheus-cms.de/storage/');
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
define('DB_USERNAME', 'morpheus_db');
define('DB_PASSWORD', 'ApfelS01!xBirneS02!y');
define('DB_DATABASE', 'Obsthof_24');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');

// MORPHEUS
define('MORPHEUS_HOMEPAGE', ['warum/', 'wie/']);

// SECURITY
define('NONE_SCRIPT', 'IY5DJNUZlwx3K4gn6jT3OA');


// SPECIAL EMAIL
// define('SPECIAL_EMAIL', 'b@7sc.eu');
//define('SPECIAL_EMAIL', 'vu@pixeldusche.com');

define('SPECIAL_EMAIL', 'd0741141-2360-4440-8687-275270141a33@uploadmail.datev.de');
define('SPECIAL_EMAIL2', 'rechnung@obsthof-am-steinberg.de');

// Überprüfe, ob das Cookie "needed" gesetzt ist
if (isset($_GET['setcookie'])) {
	define('NEW_COOKIE', 1);
	delCookieX('setcookie');
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

define('OLD_URL', array("2024neu.obsthof-am-steinberg.de", "neu.obsthofamsteinberg.de", "obsthofamsteinberg.de"));
