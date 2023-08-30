<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
# edit 27.11.2006                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

session_start();
#$box = 1;
$myauth = 60;
include("cms_include.inc");
include("_tinymce.php");

// print_r($_REQUEST);

global $arr_form;

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

// NICHT VERAENDERN ///////////////////////////////////////////////////////////////////
$edit 	= $_REQUEST["edit"];
$delimg = $_REQUEST["delimg"];
$neu	= $_REQUEST["neu"];
$save	= $_REQUEST["save"];
$del	= $_REQUEST["del"];
$delete	= $_REQUEST["delete"];
$id		= $_REQUEST["id"];
$unvis	 = $_REQUEST["unvis"];
$vis	 = $_REQUEST["vis"];
///////////////////////////////////////////////////////////////////////////////////////


//// EDIT_SKRIPT
$um_wen_gehts 	= "Mitarbeiter / Team";
$titel			= "Verwaltung Mitarbeiter";

global $table, $tid;

$table = "morp_mitarbeiter";
$tid = "mid";
$imgFolder = "../images/team/";
$imgFolderShort = "team/";
$scriptname = basename(__FILE__);
///////////////////////////////////////////////////////////////////////////////////////


echo '<div id=vorschau>
	<h2>'.$titel.'</h2>

	'. ($edit || $neu ? '<p><a href="?pid='.$pid.'" class="btn btn-info">&laquo; zur&uuml;ck</a></p>' : '') .'
	<form action="" onsubmit="" name="verwaltung" method="post">
';


$new = '<p><a href="?neu=1">&raquo; NEU</a></p>';

//// EDIT_SKRIPT
// 0 => Feldbezeichnung, 1 => Bezeichnung für Kunden, 2 => Art des Formularfeldes
$arr_form = array(
	array("vorname", "Vorname", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("name", "Name", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	// array("sichtbar", "Online sichtbar", 'chk'),
 	array("freitext", "Tätigkeit", '<textarea cols="80" rows="5" name="#n#">#v#</textarea>'),
 	array("beschreibung", "persönlicher Text", '<textarea class="summernote" cols="80" rows="5" name="#n#">#v#</textarea>'),
#	array("position", "Position", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("email", "E-Mail", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("fon", "Telefon", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("mobile", "Fax", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("sms", "SMS", '<input type="Text" value="#v#" name="#n#" style="#s#">'),

	// array("strasse", "Straße", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	// // array("land", "Land (DE=1, CH=3, NL=2, AT=4, NO=5, L=6) &nbsp; ", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	// array("plz", "PLZ", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	// array("ort", "Ort", '<input type="Text" value="#v#" name="#n#" style="#s#">'),

	// array("", "IPCONFIG", '</div><div class="col-md-6">'),
	
	// array("ipb", "IP-Beratung", 'chk'),
	// array("ip", "IP-Coaching", 'chk'),
	// array("ehe", "IP-Ehe- und Partnerschaftsberatung", 'chk'),
	// array("erz", "IP-Erziehungsberatung", 'chk'),
	// array("gest", "IP-Gestaltberatung", 'chk'),
	// array("leben", "IP-Lebensstilanalyse", 'chk'),
	// array("senior", "IP-Seniorenberatung", 'chk'),
	// array("sucht", "IP-Suchtberatung", 'chk'),
	// array("vision", "IP-Supervision", 'chk'),
	// array("trainer", "Familienrat-Training", 'chk'),
	// array("adult", "Ermutigungstraining für Erwachsene", 'chk'),

	
	array("img1", "Portrait", 'foto', 'image', 'imgname', 6, 'gid'),
	// array("frt", "FRT", 'chk'),
	// array("et", "ET", 'chk'),
	// array("et1", "ET Zusatz", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	// array("ipb", "IP-B", 'chk'),
	// array("efe", "Ermutigungstraining für Erwachsene", 'chk'),
	// array("jugend", "Ermutigungstraining für Kinder / Jugendliche", 'chk'),
	// array("kinder", "Kinder", 'chk'),
	// array("coaching", " Coaching mit SYNCHRONIZING", 'chk'),
	// array("teamcoaching", "Teamcoaching", 'chk'),
	// array("step", "STEP Kurse", 'chk'),
);
///////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////
// echo $sql = "ALTER TABLE  $table ADD `coaching2` VARCHAR(255)  NULL DEFAULT NULL";
#$sql = "ALTER TABLE $table CHANGE `step` `step` INT(11) NULL DEFAULT NULL";
// $res = safe_query($sql);
/////////////////////////////////////////////////////////////////////////////////////////////////////	

#	array("mberechtigung", "Berechtigung (ID: 1 = Zugang)", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
# 	array("ausbildungen", "<strong>Ausbildung EN</strong>", '<textarea cols="80" rows="5" name="#n#">#v#</textarea>'),
# 	array("imgid", "Berechtigung (ID: 1 = Zugang)", 'sel', 'image', 'imgname'),

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

if ($vis || $unvis) {
	if($vis) 		$sql = "UPDATE $table SET sichtbar=1 WHERE $tid=".$vis;
	elseif($unvis) 	$sql = "UPDATE $table SET sichtbar=0 WHERE $tid=".$unvis;
	// echo $sql;
	safe_query($sql);
	$jsSAVE = 1;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function liste() {
	global $table, $tid,$imgFolderShort,$imgFolder;
	//// EDIT_SKRIPT

	$ord = "name";
	$anz = "name";
	$anz2 = "bezeichnung";
	$anz3 = "email";
	////////////////////

	$suche = isset($_GET["suche"]) ? $_GET["suche"] : '';
	
	$echo .= '<p>&nbsp;</p>
	
	</form>
	<div class="container-full">
	<form method="get"><input type="text" name="suche" value="'.$suche.'" class="form-control" style="width:200; display:inline-block; margin-right:20px;" placeholder="Suche nach Name"><button type="submit" class="btn btn-info">suche</button>
	<hr>
	<div class="row">';

	$old = '';

	$where = $suche ? " name LIKE '%$suche%' OR vorname LIKE '%$suche%'" : 1;
	$sql = "SELECT * FROM $table WHERE $where ORDER BY ".$ord."";
	$res = safe_query($sql);

	$lastChar = '';
	$endDiv = 0;

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$tid;
		$si   = $row->sichtbar;


		if ($si == 1) 	$si = '<a href="?unvis='.$edit.'"><i class="fa fa-eye vis" ref="0"></i></a>';
		else			$si = '<a href="?vis='.$edit.'"><i class="gray fa fa-eye-slash vis" ref="1"></i></a>';

		$name = $row->name;
		$firstChar = substr($name,0,1);

		if($firstChar != $lastChar) {
			if($endDiv) $echo .= "</div>";
			$endDiv = 1;
			$echo .= '<div class="row"><hr><h5>'.$firstChar.'</h5>';
			$lastChar = $firstChar;
		}

		$echo .= '<div class="col-md-12 mb1">
		<div class="row mitarbeiter">
			<div class="col-sm-1"><a href="?edit='.$edit.'" class="btn btn-info"><i class="fa fa-pencil-square-o"></i></a></div>
			<div class="col-sm-6">'.$row->vorname	.' '.$row->$anz	.'<!--<br/>
			'.$row->$anz3.'--></div>
			<div class="col-sm-3">'.($row->img1 ? '<img src="../mthumb.php?w=40&src=images/'.$imgFolderShort.urlencode($row->img1).'" />' : '').'
			'.($row->img2 ? '<img src="../mthumb.php?w=40&src=images/'.$imgFolderShort.urlencode($row->img2).'" />' : '').'</div>
			<div class="col-sm-1">'.$si.'</div>
			<div class="col-sm-1"><a href="?del='.$edit.'" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></div>
		</div>
	</div>
		';
	}

	$echo .= '</div></div></div><p>&nbsp;</p>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function edit($edit) {
	global $arr_form, $imgFolderShort, $imgFolder;
	global $table, $tid;

	$sql = "SELECT * FROM $table WHERE $tid=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);

	// print_r($row);

	$echo .= '<input type="Hidden" name="neu" value="'.$neu.'">
		<input type="Hidden" name="edit" value="'.$edit.'">
		<input type="Hidden" name="save" value="1">
<style> td { padding:10px; } </style>
	<table cellspacing="6" class="edit_td" class="p20" style="widht:100%">';

	$echo .= '<tr>
		<td></td>
	</tr>
';

	foreach($arr_form as $arr) {
		$echo .= setMorpheusFormTable($row, $arr, $imgFolderShort, $edit, $table, $tid);
	}

	$echo .= '
	<tr>
		<td></td>
		<td><br><input type="submit" name="speichern" value="speichern"></td>
	</tr>
</table>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function neu() {
	global $arr_form;

	$x = 0;

	$echo .= '<input type="Hidden" name="neu" value="1"><input type="Hidden" name="save" value="1">

	<table cellspacing="6">';

	foreach($arr_form as $arr) {
		$get = $arr[0];
		if ($x <= 5) $echo .= '<tr>
			<td>'.$arr[1].':</td>
			<td>'. repl(array("#v#", "#n#", "#s#"), array($row->$get, $arr[0], 'width:300px;'), $arr[2]).'</td>
		</tr>';
		$x++;
	}

	$echo .= '<tr>
		<td></td>
		<td><input type="submit" name="speichern" value="speichern"></td>
	</tr>
</table>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


if ($save) {
	global $arr_form;
	global $table, $tid;

	//// EDIT_SKRIPT
	$sql = '';
	/////////////////////

	foreach($arr_form as $arr) {
		$tmp = $arr[0];
		$val = $_POST[$tmp];

		if ($tmp != "region") $sql .= $tmp. "='" .trim($val). "', ";
	}

	$sql = substr($sql, 0, -2);

	if ($neu) {
		$sql  = "INSERT $table set $sql";
		$res  = safe_query($sql);
		$edit = mysqli_insert_id($mylink);
		unset($neu);
	}
	else {
		$sql = "update $table set $sql WHERE $tid=$edit";
		$res = safe_query($sql);
		// $edit = 0;
	}
	// echo $sql;
	// $edit = 0;
}
elseif ($del) {
	die('<p>M&ouml;chten Sie den '.$um_wen_gehts.' wirklich l&ouml;schen?</p>
	<p><a href="?delete='.$del.'">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?">Nein</a></p>
	');
}
elseif ($delete) {
	global $table, $tid;
	$sql = "DELETE FROM $table WHERE $tid=$delete";
	$res = safe_query($sql);
}
elseif($delimg) {
	$sql = "SELECT $delimg FROM $table WHERE $tid=$edit";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);

	unlink("../images/team/".$row->$delimg);

	$sql = "UPDATE $table SET $delimg='' WHERE $tid=$edit";
	safe_query($sql);

}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

if ($neu) 		echo neu("neu");
elseif ($edit) 	echo edit($edit);
else			echo liste($id).$new;

echo '
</form>
';

include("footer.php");

