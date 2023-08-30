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
$data = explode("=",$_POST["data"]);
// print_r($data);
$z = $_POST["z"]; 

$pos = $_POST["pos"];
$feld = $_POST["feld"];
$table = $_POST["table"];

array_unshift($z, $data[1]);

 // print_r($z);


if(count($z)>0) {
	$x = 0;
	foreach($z as $val) {
		if($val) {
			$x++;
			$sql = "UPDATE $table set $pos=$x WHERE $feld=$val";
			 echo "\n";
			safe_query($sql);
		}
	}
}

