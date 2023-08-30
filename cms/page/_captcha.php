<?php

$secret=$_POST["secret"];
$captcha=$_POST["resp"];

// print_r($_POST);

$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => array(
		'secret' => $secret,
		'response' => $captcha
	)
));
$response = curl_exec($curl);
curl_close($curl);

if(strpos($response, '"success": true') !== FALSE) {
	echo 1;
} else {
	echo 0;
}
