<?php
include("../nogo/config.php");
include("../nogo/db.php");
dbconnect();
include("../nogo/funktion.inc");

$gid = $_GET["gid"];
$imgFolder = '../images/userfiles/image/';
$imgname = 'crop_'.date("Y-Gis").'_'.$_GET['imgname'];
$ziel = $imgFolder.$imgname; 
$quelle = 'crop/crop.jpg';

copy($quelle ,$ziel);

$n_file = $imgFolder.$imgname;
$n_file_size = round(filesize($n_file) / 1024);
 
echo $sql  = "INSERT `morp_cms_image` SET imgname='$imgname',
	`size`='$n_file_size',
	`gid`=$gid
";
$res = safe_query($sql);	
 
