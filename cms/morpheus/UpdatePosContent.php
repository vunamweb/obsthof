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
$id = $_POST["id"];
#$lot = $_POST["lot"];
#$dat = $_POST["dat"];

#$dat = us_dat($dat);

// der erste Wert kommt in data // alle folgenden Werte kommen im Array Z

$wert1 = explode("=", $data);
$wert1 = $wert1[1];

$wert1 = check_valid_value($wert1, "i");
if(!$wert1) die("Fehler 338");

// print_r($z);

// packlisten ID holen oder erzeugen
$table = "tPankreas";
$tid = "fPankreasID";

if($data) {
	$sql = "UPDATE `morp_cms_content` set tpos=1 WHERE cid=$wert1";
#	safe_query($sql);

	$x = 1;
	foreach($z as $val) {
		if($val) {
			$x++;
			$sql	= "UPDATE `morp_cms_content` set tpos=$x WHERE cid=$val";
#			safe_query($sql);
		}
	}

}

?>