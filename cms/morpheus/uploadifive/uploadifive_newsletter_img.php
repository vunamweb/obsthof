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
$gid = $_POST["gid"];

if(!$gid) exit();

// Set the uplaod directory
// $uploadDir = '/pdf/';

$uploadDir = ("../../img/"); // '/secure/dfiles/vxcDfgH/';

// Set the allowed file extensions
// 		$fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // Allowed file extensions
$imgTypes = array('jpg', 'jpeg', 'png'); // Allowed file extensions
$docFiles = array("gif", "svg");
$fileTypes = array_merge($imgTypes, $docFiles);

$verifyToken = md5('pixeld' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
#	$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
#	$uploadDir  = $uploadDir;
	$file 		= ($_FILES['Filedata']['name']);
	echo $targetFile = $uploadDir . $file;

	$filesize = filesize($tempFile);
	$filetime = date ("Y-m-d", filectime($tempFile));

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	// print_r($fileParts);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
		if(!move_uploaded_file($tempFile, $targetFile)) echo ":(";

		setData ($file, $gid, strtolower($fileParts['extension']), $filesize, $filetime);
		// if (in_array(strtolower($fileParts['extension']), $imgTypes)) include("convert.php");

		echo "finish";
	} else {

		// The file type wasn't allowed
		echo 'Invalid file type.';

	}
}


function setdata ($file ,$gid, $extension, $filesize, $date) {
		if(!$date) $date = date(Y ."-" .m ."-" .d);

		$query 	= "SELECT * from morp_newsletter_image WHERE imgname='$file' AND gid=$gid";
		$result = safe_query($query);
		$edit 	= mysqli_num_rows($result);
		if ($edit) 	$query = "UPDATE ";
		else 		$query = "INSERT ";

		$query .= " morp_newsletter_image SET gid=$gid, imgname='$file', size='$filesize'";

		if ($edit)  $query .= " WHERE imgname='$file' AND gid=$gid";

		safe_query($query);
}
