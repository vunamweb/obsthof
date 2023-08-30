<?php
include("../../nogo/config.php");
include("../../nogo/db.php");
include("function.php");
dbconnect();

$db = "morp_newsletter_cont";
$id = "nlid";
$anz = "text";
$nlid = $_GET["nlid"];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* Verteiler Liste wurde hochgeladen */
if($vt) {
	// verteiler auslesen
	$pfad = '../../nlverteiler/';
	include_once('../xlsx.inc.php');
	$xlsx = new SimpleXLSX($pfad.$vt);
	$spalten = spaltenBez ($xlsx);
	$bezeichnung = array_flip($spalten);
	$csv = spaltenInhalte ($xlsx, $bezeichnung, 1, 1);
	$csv = $csv[0];
	//print_r($csv);
	$verteiler = array();
	//echo "---".$csv[20];
	foreach($spalten as $key=>$val) {
		//echo $key.'-'.$val."<br>";
		$verteiler[$val] = $csv[$key];
	}
	//print_r($verteiler);
}
/* Newsletter Verteiler online */
else {
	// name	vorname	email	anrede
	$sql = "SELECT * FROM morp_register WHERE checked=1 LIMIT 0,1";
	$res = safe_query($sql);
	$row = mysql_fetch_object($res);
	$tmp_arr = array("name","vorname","anrede");
	foreach($tmp_arr as $val) {
		if($val == "anrede") {
			$tmp = $row->$val;
			If($tmp == "Herr") $verteiler[$val] = "Sehr geehrter ".$row->$val;
			else $verteiler[$val] = "Sehr geehrte ".$row->$val;
		}
		else $verteiler[$val] = $row->$val;
	}
}
// print_r($verteiler);
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

$sql = "SELECT * FROM $db WHERE $id=".$nlid;
$res = safe_query($sql);

$getHTML = '';

function getCont($res) {
	while($row = mysql_fetch_object($res)) {
		$lnk = $row->nllink;
		$txt = $row->text;
		$img1 = $row->nlimg1;
		$img2 = $row->nlimg2;
		$tn = $row->nlimg3;
		$cta = $row->nlcta;
		$s1 = $row->soc1;
		$s2 = $row->soc2;
		$s3 = $row->soc3;
		$s4 = $row->soc4;
		$s5 = $row->soc5;
		$art = $row->nlart;

		$sql = "SELECT * FROM morp_newsletter_template WHERE nltid=".$art;
		$rs = safe_query($sql);
		$rw = mysql_fetch_object($rs);

		$html = $rw->nlthtml;
		$i1 = $rw->img1;
		$i2 = $rw->img2;
		$i3 = $rw->img3;
		$ctatrue = $rw->cta;
		$pd1 = $rw->pdf1;
		$pd2 = $rw->pdf2;
		$pd3 = $rw->pdf3;
		$link = $rw->link;
		$texttrue = $rw->texte;
		$social = $rw->social;

		$getHTML .= $html;
	}

	return $getHTML;
}



echo $container = getCont($res);



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/****** PLATZHALTER ******************************/
$platzhalter = $row->platzhalter;
$platzhalter = explode(",", $platzhalter);

$raus = array('/#banner#/');
$rein = array(getImg ($row->banner));

foreach($platzhalter as $val) {
	$val = trim($val);
	if($val) {
		$rein[] = $verteiler[$val];
		$raus[] = '/#'.$val.'#/';
	}
}

# print_r($raus);
# print_r($rein);

// $txt = preg_replace($raus, $rein, $row->text);
# $text = read_data("text.txt");
/******* _____ PLATZHALTER ******************************/
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

$data = create_html_doc($row->text, $nlid);

echo $data = preg_replace($raus, $rein, $data);

# echo create_email ($txt);
?>