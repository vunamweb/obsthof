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

	global $pid;


// print_r($_POST);
$pgid = $_POST["pgid"];
$pid = $_POST["pid"];
$reload = $_POST["reload"];

if(!$pgid) exit();
$pgid = check_valid_value($pgid, "i");
if(!$pgid) die("Fehler 234");

// Set the uplaod directory
// $uploadDir = '/pdf/';

$uploadDir = getDownloadDirectoy ($pgid, "../../"); // '/secure/dfiles/vxcDfgH/';

// Set the allowed file extensions
// 		$fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // Allowed file extensions
$imgTypes = array('jpg', 'jpeg', 'png', 'pdf', 'swf', 'ai', 'eps', 'tif', 'tiff', 'psd'); // Allowed file extensions
$docFiles = array("doc", "docx", "xls", "xlsx", "mov", "pdf", "zip", "rar", "mp4", "vcf");
$fileTypes = array_merge($imgTypes, $docFiles);

$verifyToken = md5('pixeld' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
#	$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
#	$uploadDir  = $uploadDir;
	$file 		= $_FILES['Filedata']['name'];
	echo $targetFile = $uploadDir . $_FILES['Filedata']['name'];

	$filesize = filesize($tempFile);
echo $filetime = date ("Y-m-d", filectime($tempFile));

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	// print_r($fileParts);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
		if(!move_uploaded_file($tempFile, $targetFile)) echo ":(";

		if (in_array(strtolower($fileParts['extension']), $fileTypes)) setData ($file, $pgid, strtolower($fileParts['extension']), $pfad, $filesize, $reload, $filetime);
		// if (in_array(strtolower($fileParts['extension']), $imgTypes)) include("convert.php");

		echo "finish";
	} else {

		// The file type wasn't allowed
		echo 'Invalid file type.';

	}
}


function setdata ($file ,$pgid, $extension, $pfad, $filesize, $reload, $date) {
	global $pid;

	if(!$date) $date = date(Y ."-" .m ."-" .d);
	$reld = '';

	if($reload) {
		$sql = "SELECT pname FROM `morp_cms_pdf` WHERE pid='$pid'";
		$res = safe_query($sql);
		$row = mysqli_fetch_object($res);
		$del = $row->pname;
		if($del != $file) unlink("../../pdf/$del");
	}
	else {
		$sql  = "SELECT pid FROM `morp_cms_pdf` WHERE pname='$file'";
		$res = safe_query($sql);
		if(mysqli_num_rows($res)>0)  $reld = 1;
	}


	if ($reload) 	$sql = "UPDATE `morp_cms_pdf` SET pname='$file', pdate='$date', psize='$filesize', edit=1 WHERE pid=$reload";
	elseif ($reld) 	$sql = "UPDATE `morp_cms_pdf` SET pdate='$date', psize='$filesize', edit=1 WHERE pname='$file'";
	else 			$sql = "INSERT `morp_cms_pdf` SET pname='$file', pdate='$date', psize='$filesize', pgid=$pgid";

//		$sql = "INSERT `morp_cms_pdf` SET pname='$file', pdate='$date', psize='$filesize', pgid=$pgid";
	safe_query($sql);
}

?>