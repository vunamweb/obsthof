<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

// Define a destination
$targetFolder = "../../images/userfiles/image/"; // Relative to the root and should match the upload folder in the uploader script

if (file_exists($targetFolder . $_POST['filename'])) {
	echo 0;
} else {
	echo 0;
}
?>