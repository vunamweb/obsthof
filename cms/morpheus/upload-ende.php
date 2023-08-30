<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Upload</title>
	<link rel="stylesheet" href="../font.css" type="text/css">
</head>

<script language="JavaScript">
<!--
	function closeupload() {
		upload=window.close();
	}
//-->
</script>	

<body>

<font face="" class="text">
<?php 
print_r($_FILES);
$val 	 = $_FILES['userfile']['name'];
$tmp 	 = $_FILES['userfile']['tmp_name'];
$bereich = $_POST["bereich"];

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//pfad &auml;ndern!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
if (!copy($tmp, "../pdf/$val")) {
	echo "<p>failed to copy $tmp...$val<br>
		<a href=\"download-edit.php?name=$val";
	if ($id) echo "&id=$id";
	echo "&bereich=$bereich\">< zurück</a>\n";
	die();
}

$id = $_POST["id"];
echo "<script language='javascript'>\ndocument.location = 'download-edit.php?name=$val&id=$id&bereich=$bereich'\n</script>";
#else echo "<script language='javascript'>\ndocument.location = 'download-edit.php?name=$val&new=1&bereich=$bereich'\n</script>";
?>
 
<?
include("footer.php");
?>