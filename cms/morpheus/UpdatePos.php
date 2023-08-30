<?php
session_start();

global $mylink;

include("../nogo/config.php");
include("../nogo/config_morpheus.inc");
include("../nogo/db.php");
dbconnect();
include("login.php");
include("../nogo/funktion.inc");


// der erste Wert kommt in data // alle folgenden Werte kommen im Array Z
$data = $_POST["data"];
// $z = $_POST["z"];
$z = explode(",", $data);

$pos = $_POST["pos"];
$feld = $_POST["feld"];
$table = $_POST["table"];


// print_r($z);


if($data) {
	$x = 0;
	foreach($z as $val) {
		if($val) {
			$x++;
			$sql = "UPDATE $table set $pos=$x WHERE $feld=$val";
			// echo "\n";
			safe_query($sql);
		}
	}
}

