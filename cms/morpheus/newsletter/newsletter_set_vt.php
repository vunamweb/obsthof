<?php
# echo "here";

# echo "here";
include_once ("/var/www/vhosts/apothekerkammer.de/httpdocs/nogo/config.php");
# echo " - 2here";
include_once ("/var/www/vhosts/apothekerkammer.de/httpdocs/nogo/db_nl.php");
include_once("/var/www/vhosts/apothekerkammer.de/httpdocs/mor_edm/newsletter/function.php");
# echo " - 3here";
#
dbconnect();
# echo " - 4here";

// mail('post@pixel-dusche.de', 'Apothekerkammer', 'MAILING VERTEILER  LAK LIVE');

#	mail('post@pixel-dusche.de', 'Apothekerkammer', 'SET NEWSLETTER - DB CONNECTED - Funktionen geladen');

$db = "morp_newsletter_lak";
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

if($live) $sql = "SELECT * FROM $db WHERE versendet<1 AND nlid='".$nlgetid."' LIMIT 0,1";
else $sql = "SELECT * FROM $db WHERE nlvdatum='".date("Y-m-d")."' AND versendet<1 LIMIT 0,1";

// echo $sql;
// mail('post@pixel-dusche.de', 'Apothekerkammer', $sql);

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

	// echo "<pre>";

	/* Verteiler Liste wurde hochgeladen */
	if($vt) {
		$sql = "UPDATE $db set nlsetdatum='".date("Y-m-d")."', versendet=1 WHERE nlid=".$nlid;
		$res = safe_query($sql);

		$versandid = date("Ymd")."-".rand() ; // stunden minuten sekunden

		// verteiler auslesen
		// Pfad setzen
		$pfad = '/var/www/vhosts/apothekerkammer.de/httpdocs/nlverteiler/';
		// funktionen laden
		include_once('/var/www/vhosts/apothekerkammer.de/httpdocs/morp_edm/xlsx.inc.php');
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
			$set = "INSERT morp_newsletter_vt_lak_live set nlid='$nlid', platzhalter='$pl',";

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
		 mail('post@pixel-dusche.de', 'Apothekerkammer', 'LAK LIVE // Verteiler XLXS importiert - Anzahl: '.$n);
		unlink($pfad.$vt);
		die("FERTIG !!!!");
	}
	/* Newsletter Verteiler online */
	else {
		die("KEIN IMPORT");
	}
	mail('post@pixel-dusche.de', 'Apothekerkammer', 'ERFOLGREICH // VERTEILER LAK LIVE');

// print_r($verteiler);
}

?>