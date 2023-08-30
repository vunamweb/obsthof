<?php
include_once ("../../nogo/config.php");
include_once("../../nogo/db.php");
include_once("function.php");
include_once('../csv.inc.php');
dbconnect();

$db = "morp_newsletter";
$id = "nlid";
$anz = "text";

#$sql = "UPDATE $db set nlsetdatum='xx' WHERE nlid>=1";
#$res = safe_query($sql); 

/* 
$nlid = $_GET["nlid"];
$sql = "SELECT * FROM $db WHERE $id=".$nlid;
$res = safe_query($sql); 
$row = mysql_fetch_object($res);
*/

if($live) $sql = "SELECT * FROM $db WHERE nlid='".$nlid."' LIMIT 0,1";
else $sql = "SELECT * FROM $db WHERE nlvdatum='".date("Y-m-d")."' AND nlsetdatum<>'".date("Y-m-d")."' LIMIT 0,1";

echo $sql;

$res = safe_query($sql); 
$row = mysql_fetch_object($res);

/* DIE falls keine NL zum Versand */
if(mysql_num_rows($res) < 1) die();


$nlid = $row->nlid;
$vt = $row->csv;
$gruppe = $row->nlgruppe;
$platzhalter = $row->platzhalter;
$platzhalter = explode(",", $platzhalter);

$pl = '';
foreach($platzhalter as $val) {
	$val = trim($val);
	$pl .= $val."\n";
}


/* setze nlsetdatum, damit kein 2. NL gesetzt werden kann */
$sql = "UPDATE $db set nlsetdatum='".date("Y-m-d")."' WHERE nlid=".$nlid;
$res = safe_query($sql); 

$versandid = date("Ymd")."-".rand() ; // stunden minuten sekunden

// echo "<pre>";

/* Verteiler Liste wurde hochgeladen */
if($vt) {
	// verteiler auslesen
	$csv = read_dat('../../nlverteiler/'.$vt);
	$csv = get_csv($csv, "\r");
	//print_r($csv);
	
	foreach($csv as $val) {
		$set = "INSERT morp_newsletter_vt set nlid='$nlid', platzhalter='$pl',";
		$sp = explode(";", $val);		
		$verteiler = array();
		
		foreach($val_arr as $get) {
			$verteiler[$get] = $val[$get];
		}
		
		#print_r($verteiler);
		#echo "-----".$verteiler["firma"]."----<br>";
				
		$pl_txt = '';
		foreach($platzhalter as $p) {
			$p=trim($p);
			$pl_txt .= addslashes($verteiler[$p])."\n";
		}
		
		$set .= "platzhaltertext='$pl_txt',";
		
		$set .= "firma='".addslashes($verteiler["firma"])."',email='".$verteiler["email"]."', versandid='$versandid'";
		safe_query($set); 
	}
	mail('post@pixel-dusche.de', 'Apothekerkammer', 'Verteiler CSV');
}
/* Newsletter Verteiler online */
elseif($gruppe) {
	// name	vorname	email	anrede
	$art = "art".$gruppe;
	$sql = "SELECT * FROM morp_register WHERE checked=1 AND $art=1";
	$res = safe_query($sql); 
		
	while($row = mysql_fetch_object($res)) {
		$set = 'INSERT morp_newsletter_vt set nlid='.$nlid.',';

		If($row->anrede == "Herr") $an = 'Sehr geehrter '.$row->anrede;
		else $an = 'Sehr geehrte '.$row->anrede;
		
		$an .= ' '.$row->name;

		$set .= "name='".$row->name."',email='".$row->email."', versandid='$versandid', platzhalter='anrede', platzhaltertext='".$an."'";
		safe_query($set); 
	}
	mail('post@pixel-dusche.de', 'Apothekerkammer', 'Verteiler DB art='.$gruppe);
}
else {
	die();
}
 print_r($verteiler);

?>