<?php
# echo "here";
#	mail('post@pixel-dusche.de', 'Apothekerkammer', 'SET NEWSLETTER VERTEILER START');

#include_once ("../../nogo/config.php");
#include_once("../../nogo/db.php");
#include("function.php");

#dbconnect();

# echo " ---- here5";

#	mail('post@pixel-dusche.de', 'Apothekerkammer', 'SET NEWSLETTER - DB CONNECTED - Funktionen geladen');

$db = "morp_newsletter";
$id = "nlid";
$anz = "text";

//$sql = "UPDATE $db set nlsetdatum='xx' WHERE nlid>=1";
//$res = safe_query($sql);

#$nlid = $_GET["nlid"];
/*
$sql = "SELECT * FROM $db WHERE $id=".$nlid;
$res = safe_query($sql);
$row = mysql_fetch_object($res);
*/

if($live) $sql = "SELECT * FROM $db WHERE nlid='".$nlgetid."' LIMIT 0,1";
else $sql = "SELECT * FROM $db WHERE nlvdatum='".date("Y-m-d")."' AND nlsetdatum<>'".date("Y-m-d")."' AND versendet<1 LIMIT 0,1";

#echo $sql;

$res = safe_query($sql);
$row = mysql_fetch_object($res);

/* DIE falls keine NL zum Versand */
if(mysql_num_rows($res) < 1) die();

$nlid = $row->nlid;
$vt = $row->csv;
$versendet = $row->versendet;
$gruppe = $row->nlgruppe;
$platzhalter = $row->platzhalter;
$platzhalter = explode(",", $platzhalter);

$pl = '';
foreach($platzhalter as $val) {
	$val = trim($val);
	$pl .= $val."\n";
}


/* setze nlsetdatum, damit kein 2. NL gesetzt werden kann */
if(!$versendet) {
	$sql = "UPDATE $db set nlsetdatum='".date("Y-m-d")."', versendet=1 WHERE nlid=".$nlid;
	$res = safe_query($sql);

	$versandid = date("Ymd")."-".rand() ; // stunden minuten sekunden

	 echo "<pre>";

	/* Verteiler Liste wurde hochgeladen */
	if($vt) {
		// verteiler auslesen
		// Pfad setzen
		$pfad = '/var/www/vhosts/apothekerkammer.de/httpdocs/nlverteiler/';
		// funktionen laden
		include_once('/var/www/vhosts/apothekerkammer.de/httpdocs/morpheus/xlsx.inc.php');
		$xlsx = new SimpleXLSX($pfad.$vt);
		// alle spaltenbezeichnungen holen
		$spalten = spaltenBez ($xlsx);
		//print_r($spalten);
		// um position der spalte im array auszulesen
		$bezeichnung = array_flip($spalten);
		$alle = zeilen($xlsx);
		$csv = spaltenInhalte ($xlsx, $bezeichnung, 1, ($alle - 1));
		//print_r($csv);
		//print_r($platzhalter);
		// echo $bezeichnung["firma"];
		$n = 0;
		foreach($csv as $val) {
			$set = "INSERT morp_newsletter_vt set nlid='$nlid', platzhalter='$pl',";

			$pl_txt = '';
			foreach($platzhalter as $p) {
				$p=trim($p);
				$pl_txt .= addslashes($val[$bezeichnung[$p]])."\n";
			}
			$n++;
			$set .= "platzhaltertext='$pl_txt',";
			$set .= "firma='".addslashes($val[$bezeichnung["firma"]])."',email='".$val[$bezeichnung["email"]]."', versandid='$versandid'";
			safe_query($set);
		}
		mail('post@pixel-dusche.de', 'Apothekerkammer', 'Verteiler XLXS importiert - Anzahl: '.$n);
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
	//	die();
	}
// print_r($verteiler);
}

?>