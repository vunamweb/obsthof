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
$pos = $_POST["pos"];
$feld = $_POST["feld"];
$table = $_POST["table"];
$id = $_POST["id"];

$id = check_valid_value($id, "i");
if(!$id) die("Fehler 333");
$feld = check_valid_value($feld, "s", "40", 0);
if(!$feld) die("Fehler 333a");

// print_r($z);


if($table && $pos && $feld && $id) {
	$sql = "UPDATE $table set $pos='$data' WHERE $feld=$id";
	safe_query($sql);
}

