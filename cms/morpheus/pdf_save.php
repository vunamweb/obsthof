<?php
session_start();
$SID = session_id();

global $language, $lan;

include("../nogo/config.php");
include("../nogo/funktion.inc");
$lan = $_POST["lan"];
include("../nogo/".$lan.".inc");
include("function.php");

include("../nogo/db.php");
dbconnect();


$add = $_POST["add"];
$del = $_POST["del"];
$data = $_POST["data"];
$data = explode("|", $data);
$gid = $data[0];
$pid = $data[1];

if($add) {
	$sql = "INSERT morp_gremien_datei set pid='$pid', gid='$gid'";
	safe_query($sql);
}
elseif($del) {
	$sql = "DELETE FROM morp_gremien_datei WHERE pid='$pid' AND gid='$gid'";
	safe_query($sql);
}

// echo $echo;

?>