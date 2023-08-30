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
$folder = $_POST["dir"];

global $table, $tid, $col, $edit;

$table = $_POST["tbl"] ? $_POST["tbl"] : "morp_cms_image";
$tid = $_POST["tid"] ? $_POST["tid"] : "gid";
$col = $_POST["col"] ? $_POST["col"] : "imgname";
$edit = $_POST["edit"] ? $_POST["edit"] : "";

$is_img_archive = $_POST["tbl"] ? 0 : 1;

$gid = check_valid_value($gid, "i");
if(!$gid) die("Fehler 237");
$table = check_valid_value($table, "s", 30);
if(!$table) die("Fehler 238");

// Set the uplaod directory
// $uploadDir = '/pdf/';

echo $uploadDir = $folder; // ("../../images/userfiles/image/"); // '/secure/dfiles/vxcDfgH/';

// Set the allowed file extensions
// 		$fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // Allowed file extensions
$imgTypes = array('jpg', 'jpeg', 'png'); // Allowed file extensions
$docFiles = array("gif", "svg", "mp4");
$fileTypes = array_merge($imgTypes, $docFiles);

$verifyToken = md5('pixeld' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
#	$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
#	$uploadDir  = $uploadDir;
#	$file 		=  $morpheus["imageName"].'-'.date("ymd").'-'.$_FILES['Filedata']['name'];
	$file 		=  $_FILES['Filedata']['name'];
	$targetFile = $uploadDir .$file;

	$filesize = filesize($tempFile);
	$filetime = date ("Y-m-d", filectime($tempFile));

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	// print_r($fileParts);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
		if(!move_uploaded_file($tempFile, $targetFile)) echo ":(";

		if($is_img_archive) setData ($file, $gid, strtolower($fileParts['extension']), $filesize, $filetime);
		else saveData ($file);
		// if (in_array(strtolower($fileParts['extension']), $imgTypes)) include("convert.php");

		echo "finish";
	} else {

		// The file type wasn't allowed
		echo 'Invalid file type.';

	}
}

function setData ($file ,$gid, $extension, $filesize, $date) {
	global $table, $tid, $col;

	if(!$date) $date = date(Y ."-" .m ."-" .d);

	$query 	= "SELECT * FROM $table WHERE $col='$file' AND $tid=$gid";
	$result = safe_query($query);
	$edit 	= mysqli_num_rows($result);
	if ($edit) 	$query = "UPDATE ";
	else 		$query = "INSERT ";

	$query .= " $table SET $tid=$gid, $col='$file', size='$filesize'";

	if ($edit)  $query .= " WHERE $col='$file' AND $tid=$gid";

	safe_query($query);
}

function saveData ($file) {
	global $table, $tid, $col, $edit;
	echo $query = "UPDATE $table SET $col='$file' WHERE $tid=$edit";
	safe_query($query);
}

?>