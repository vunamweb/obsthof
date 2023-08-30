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


// *****************************************
// print_r($_POST);
// *****************************************

$gnid = $_POST["gnid"];

if(!$gnid) exit();

// *****************************************
// Set the uplaod directory
// *****************************************
$uploadDir = '../'.$_POST["dir"];
// $uploadDir = 'img/';

// *****************************************
// allowed files
// *****************************************
$imgTypes = array('jpg', 'jpeg', 'png'); // Allowed file extensions
$docFiles = array("gif", "svg");
$fileTypes = array_merge($imgTypes, $docFiles);

// *****************************************
/*** TOKEN *****/
// *****************************************
$verifyToken = md5('pixeld' . $_POST['timestamp']);


if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	$file 		= $_FILES['Filedata']['name'];

	$targetFile = $uploadDir . $_FILES['Filedata']['name'];

	$filesize = filesize($tempFile);
	$filetime = date ("Y-m-d", filectime($tempFile));

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);

	// print_r($fileParts);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
		if(!move_uploaded_file($tempFile, $targetFile)) echo ":( -- $tempFile -- ".$targetFile;

		setData ($file, $gnid, strtolower($fileParts['extension']), $filesize, $filetime);

		// echo "finish";
	} else {
		// The file type wasn't allowed
		echo 'Invalid file type.';
	}
}


function setdata ($file ,$gnid, $extension, $filesize, $date) {
		if(!$date) $date = date(Y ."-" .m ."-" .d);

		$sql 	= "SELECT gid FROM morp_cms_galerie WHERE gname='$file' AND gnid=$gnid";
		$res 	= safe_query($sql);
		$edit 	= mysqli_num_rows($res);

		$sql 	= "SELECT gid FROM morp_cms_galerie WHERE gnid=$gnid";
		$res 	= safe_query($sql);
		$anz 	= mysqli_num_rows($res);
		$anz++;

		if ($edit) 	$sql = "UPDATE ";
		else 		$sql = "INSERT ";

		$sql .= " morp_cms_galerie SET gnid=$gnid, gname='$file', gsize='$filesize', `sort`=$anz, gdatum='$date'";

		if ($edit)  $sql .= " WHERE imgname='$file' AND gnid=$gnid";

		safe_query($sql);
}

?>