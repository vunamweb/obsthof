<?php
session_start();

global $mylink;

// echo "here";

include("../nogo/config.php");
include("../nogo/config_morpheus.inc");
include("../nogo/db.php");
dbconnect();
include("login.php");
include("../nogo/funktion.inc");


$data = $_POST["data"];
$z = $_POST["z"];
$gid = $_POST["gid"];
#$lot = $_POST["lot"];
#$dat = $_POST["dat"];

#$dat = us_dat($dat);

// der erste Wert kommt in data // alle folgenden Werte kommen im Array Z

$wert1 = explode("=", $data);
$wert1 = $wert1[1];

// print_r($_POST);

// print_r($z);

$wert1 = check_valid_value($wert1, "i");
if(!$wert1) die("Fehler 337");


if($data) {
	$sql = "UPDATE `morp_cms_galerie` set `sort`=1 WHERE gid=$wert1";
	safe_query($sql);

	$x = 1;
	foreach($z as $val) {
		if($val) {
			$x++;
			$sql	= "UPDATE `morp_cms_galerie` set `sort`=$x WHERE gid=$val";
			safe_query($sql);
		}
	}

}

