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

$id = $_POST["todel"];
$table = $_POST["table"];
$tid = $_POST["tid"];

$id = check_valid_value($id, "i");
if(!$id) die("Fehler 333");

$sql  = "DELETE FROM $table WHERE $tid=$id";

safe_query($sql);
