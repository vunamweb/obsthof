<?php
// error_reporting(E_ALL);

#$uploadDir = '../../pdf/';
$uploadDir = '../../secure/dfiles/vxcDfgH/';
// $file = $_POST["file"];

$nm = $uploadDir.$file;

/* imagemagick start */
$im = new imagick(); 
$im->pingImage($nm);
echo "---".$anz = $im->getNumberImages();
echo "---";

$getfile = $nm."[0]";
$im->pingImage($getfile);
	
		$setwidth = 0;
		$im->readImage($getfile);	
		$im->stripImage ();					
		$im->setImageResolution(72,72); 
		$im->resampleImage(72,72,imagick::FILTER_UNDEFINED,0); 
		$im->setCompression(Imagick::COMPRESSION_JPEG);
		$im->setCompressionQuality(70);
		$im->setImageFormat('jpg');					 
		# $im->scaleImage($nw,0);
		$im->scaleImage(300,300,1);
		$im->writeImage($uploadDir.$file.'.jpg');
		$im->clear();
		$im->destroy();

/* Dokumenten groesse */
#$filesize = filesize($nm);
echo $uploadDir.$file.'.jpg';
?>