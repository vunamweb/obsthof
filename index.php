<?php
/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/
// phpinfo();
//echo 'index'; die();
error_reporting(0);
// Version
define('VERSION', '3.0.2.0');

// SECURITY
$GLOBALS['script_nonce'] = base64_encode(random_bytes(16));
$GLOBALS['style_nonce'] = base64_encode(random_bytes(16));

header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
header("Cross-Origin-Embedder-Policy: require-corp;");
// header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$script_nonce' https://www.paypal.com; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self' data:; frame-src 'self' https://www.youtube-nocookie.com https://www.paypal.com https://www.sandbox.paypal.com; connect-src 'self' https://www.paypal.com https://www.sandbox.paypal.com;");

header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$script_nonce' https://www.paypal.com https://www.googletagmanager.com https://widgets.trustedshops.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https://widgets.trustedshops.com; font-src 'self' data:; frame-src 'self' https://www.youtube-nocookie.com https://www.paypal.com https://www.sandbox.paypal.com; connect-src 'self' https://www.paypal.com https://www.sandbox.paypal.com;");
// _start SECURE
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + 

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}
// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');
