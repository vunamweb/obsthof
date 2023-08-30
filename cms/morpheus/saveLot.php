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


$lots = $_POST["data"];
$lot = $_POST["lot"];
$dat = $_POST["dat"];

$dat = us_dat($dat);

// print_r($_POST);

// packlisten ID holen oder erzeugen
$table = "tPankreas";
$tid = "fPankreasID";

if($lots && $lot) {
	$lots=explode(",", $lots);
	// print_r($lots);


// fEingangsDatumLager

	foreach($lots as $val) {
		if($val) {
			$sql = "UPDATE $table set fLot2='$lot', fEingangsDatumLager='$dat' WHERE $tid=$val";
			safe_query($sql);
		}
	}
}

?>