<?php
header("Access-Control-Allow-Origin: none");
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

include("../../nogo/config.php");
include("../../nogo/funktion.inc");
include("../../nogo/db.php");
dbconnect();

// print_r($_POST);
$navid = $_POST["navid"];

if(!$navid) exit();

// Set the uplaod directory
// $uploadDir = '/pdf/';

$uploadDir = ("../../images/navbar/"); // '/secure/dfiles/vxcDfgH/';

// Set the allowed file extensions
// 		$fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // Allowed file extensions
$imgTypes = array('jpg', 'jpeg', 'png'); // Allowed file extensions
$docFiles = array("gif", "svg");
$fileTypes = array_merge($imgTypes, $docFiles);

$verifyToken = md5('pixeld' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	$file 		=  urlencode($_FILES['Filedata']['name']);
	$targetFile = $uploadDir .$file;

	$filesize = filesize($tempFile);
	$filetime = date ("Y-m-d", filectime($tempFile));

	$fileParts = pathinfo($_FILES['Filedata']['name']);

	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
		if(!move_uploaded_file($tempFile, $targetFile)) echo ":(";
		chmod($targetFile, 0755);

		setData ($file, $navid, strtolower($fileParts['extension']), $filesize, $filetime);

		echo $file;
	} else {

		// The file type wasn't allowed
		echo '';

	}
}


function setdata ($file ,$navid, $extension, $filesize, $date) {
		if(!$date) $date = date(Y ."-" .m ."-" .d);
		$query = "UPDATE morp_cms_nav SET navbar_image='$file' WHERE navid=$navid";
		safe_query($query);
}

?>