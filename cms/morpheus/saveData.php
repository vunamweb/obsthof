<?php
session_start();

global $mylink;

include("../nogo/config.php");
include("../nogo/config_morpheus.inc");
include("../nogo/db.php");
dbconnect();
include("login.php");
include("../nogo/funktion.inc");

/*
	   [data] => 16|34|
    [sum] => 1373.79
    [kg] => 1047
    [kgB] => 1103
 */

$ids = $_POST["data"];
$sum = $_POST["sum"];
$kg = $_POST["kg"];
$kgB = $_POST["kgB"];
$liste = $_POST["liste"];
// print_r($mylink);

$_SESSION["myIDs"] = $ids;
// print_r($_POST);

if($liste) $neu = '';
else $neu = 1;


// packlisten ID holen oder erzeugen
$table = "tPankreasPackliste";
$tid = "fPacklisteID";

$jetzt = date("Y-m-d");
$arr = explode("|",$ids);
$arr = array_filter($arr); // leere Werte delete
$anz = count($arr);


if($liste) {
	$sql = "UPDATE tPankreas set fPacklisteID=0 WHERE fPacklisteID=$liste";
	safe_query($sql);

	// $sql = "SELECT * FROM $table WHERE $tid=$liste";
	// $res = safe_query($sql);
	// $row = mysqli_fetch_object($res);

	$sql = "UPDATE $table set fMenge=$anz , fNettoGewich='$kg', fBruttoGewich='$kgB', fGesamtPreis='$sum' WHERE $tid=$liste";
	// echo $sql = "UPDATE $table set fMenge=$me , fNettoGewich='$ng', fBruttoGewich='$bg', fGesamtPreis='$pr' WHERE $tid=$liste";
	safe_query($sql);
	$plID = $liste;
}
elseif($neu) {
	$sql = "INSERT $table set fDatum='$jetzt', fName='NEU $jetzt', fMenge=$anz , fNettoGewich='$kg', fBruttoGewich='$kgB', fGesamtPreis='$sum'";
	$res = safe_query($sql);
	$plID = mysqli_insert_id($mylink);
	if(!$plID) die("konnte die ID vom Datensatz nicht erfassen");
}


$table = "tPankreas";
$tid = "fPankreasID";

foreach($arr as $val) {
	$sql = "UPDATE $table set fPacklisteID=$plID WHERE $tid=$val";
	safe_query($sql);
}

//$sql = "SELECT * FROM $table WHERE $where AND fPacklisteID='' ORDER BY ".$ord." ".($asc ? "ASC" : "DESC");
//$res = safe_query($sql);
// while ($row = mysqli_fetch_object($res)) {

$_SESSION["myIDs"] = '';

?>