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

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
// print_r($_REQUEST);

global $arr_form;

// NICHT VERAENDERN ///////////////////////////////////////////////////////////////////
$edit 	= $_REQUEST["edit"];
$delimg = $_REQUEST["delimg"];
$neu	= $_REQUEST["neu"];
$save	= $_REQUEST["save"];
$del	= $_REQUEST["del"];
$delete	= $_REQUEST["delete"];
$id		= $_REQUEST["id"];
///////////////////////////////////////////////////////////////////////////////////////


//// EDIT_SKRIPT
$um_wen_gehts 	= "Kategorien";
$titel			= "Veranstaltungs Kategorien";
///////////////////////////////////////////////////////////////////////////////////////


echo '<div id=vorschau>
	<h2>'.$titel.'</h2>

	'. ($edit || $neu ? '<p><a href="?pid='.$pid.'">&laquo; zur&uuml;ck</a></p>' : '') .'
	<form action="" onsubmit="" name="verwaltung" method="post">
';


$new = '<p><a href="?neu=1">&raquo; NEU</a></p>';

//// EDIT_SKRIPT
// 0 => Feldbezeichnung, 1 => Bezeichnung für Kunden, 2 => Art des Formularfeldes
$arr_form = array(
	// array("reihenfolge1", "Reihenfolge", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("bezeichnung", "Art der Veranstaltung", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
 	array("ziel", "Ziel ID Morpheus", 'sel', 'cms_morp_nav', 'name'),

);
///////////////////////////////////////////////////////////////////////////////////////


#	array("mberechtigung", "Berechtigung (ID: 1 = Zugang)", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
# 	array("ausbildungen", "<strong>Ausbildung EN</strong>", '<textarea cols="80" rows="5" name="#n#">#v#</textarea>'),
# 	array("imgid", "Berechtigung (ID: 1 = Zugang)", 'sel', 'image', 'imgname'),

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function liste() {
	//// EDIT_SKRIPT
	$db = "morp_event_kategorie";
	$tid = "kid";
	$ord = "reihenfolge1";
	$anz = "bezeichnung";
	$anz2 = "reihenfolge1";
	////////////////////
	$transfer = $_GET["transfer"] ? $_GET["transfer"] : 0;
		
	include("../inc/api_start.php");
	$type = "getEventTypeList2";
	$type = "getEventTypeGroupList";
	
		// array("EventTypeId",1,"ID"),
		// array("EventTypeName",1,"Title"),
		// array("EventTypeInfo",1,"Text"),
		// array("EventTypeImage",0,""),
	
	$GetUrl = $url."/$type/$AccessToken";
	$resp = getCurlData($GetUrl, $headers);	
	$results = ($resp["Results"]);
	// print_r($results);
	$echo .= '<p>&nbsp;</p>';
	
	foreach($results as $arr) {
		$id = $arr["EventTypeGroupId"];
		
		if($transfer == $id) {
			$sql = "SELECT * FROM $db WHERE $tid=$id";
			$res = safe_query($sql);	
			$x = mysqli_num_rows($res);
			$sql = ($x > 0 ? 'UPDATE' : 'INSERT')." $db SET $anz='".addslashes($arr["EventTypeGroupName"])."'".($x > 0 ? " WHERE $tid=$id" : ", $tid=$id");
			$res = safe_query($sql);		
		}
		$sql = "SELECT * FROM $db WHERE $tid=$id";
		$res = safe_query($sql);	
		$row = mysqli_fetch_object($res);
		$edit = $row->$tid;
		$echo .= '<div class="row">
		<div class="col-md-5">
			'.$arr["EventTypeGroupId"].' |
			'.$arr["EventTypeGroupName"].' | 
			'.$arr["EventTypeImageGroup"].' 
			&nbsp;
		</div>
		<div class="col-md-4">
			<p'.($arr["EventTypeGroupName"] != $row->$anz ? ' style="background:#ccc;color:#fff;font-weight:600;"' : '').'><a href="?edit='.$edit.'">'.$row->$anz.'</a></p>
			&nbsp;
		</div>
		<div class="col-md-1"'.($row->ziel ? '' : ' style="background:green"').'>
		'.$row->ziel.'
		</div>
		<div class="col-md-2">
			<a href="?transfer='.$arr["EventTypeGroupId"].'">Transfer zur Datenbank <i class="fa fa-chevron-right"></i></a>
			&nbsp;
		</div>
	</div>
		';
	
	}

	$echo .= '
	<p>&nbsp;</p>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function edit($edit) {
	global $arr_form, $navID;
	include_once("../nogo/navID_de.inc");
	//// EDIT_SKRIPT
	$db = "morp_event_kategorie";
	$id = "kid";
	/////////////////////

	$sql = "SELECT * FROM $db WHERE $id=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);

	$echo .= '<input type="Hidden" name="neu" value="'.$neu.'">
		<input type="Hidden" name="edit" value="'.$edit.'">
		<input type="Hidden" name="save" value="1">

	<table class="autocol p20" cellspacing="6">';

	$echo .= '<tr>
		<td></td>
	</tr>
';

	foreach($arr_form as $arr) {
		$get = $arr[0];

		if ($arr[2] == "sellan") {
			$echo .= '<tr><td>'.$arr[1].'</td><td><select name="lan">';
			$echo .= '<option value="1" '. ($row->lan == 1 ? ' selected' : '') .'>Deutsch</option>';
			$echo .= '<option value="2" '. ($row->lan == 2 ? ' selected' : '') .'>English</option>';
			$echo .= '<option value="3" '. ($row->lan == 3 ? ' selected' : '') .'>Francais</option>';
			$echo .= '</select></td></tr>';
		}
		elseif ($arr[2] == "sel") {
			$echo .= '<tr><td>'.$arr[1].'</td><td><select name="'.$arr[0].'"  style="width:100%">';
			$sql = "SELECT navid, name FROM morp_cms_nav WHERE 1 ORDER BY name";
			$rs = safe_query($sql);
			$echo .= '<option value="">Bitte Zielseite wählen </option>';			
			while ($rw = mysqli_fetch_object($rs)) {
				if ($rw->navid == $row->$get) {
					$sel = "selected";
				} else {
					$sel = "";
				}			
				$echo .= '<option value="' . $rw->navid . '" '.$sel.'>'.$rw->name.' | | | ' . $navID[$rw->navid] . '</option>';
			}			
			$echo .= '</select></td></tr>';
		}
		elseif ($arr[2] == "text") {
			$echo .= '<tr><td>'.$arr[1].'</td><td>'.repl("#e#", $edit, $arr[3]).'</td></tr>';
		}
		else $echo .= '<tr>
		<td>'.$arr[1].':</td>
		<td>'. repl(
					array("#v#", "#n#", "#s#", "#db#", '#e#', '#id#', '#s1#', '#s0#'),
					array($row->$get, $arr[0], 'width:400px;', $db2, $edit, $id2, $sel1, $sel2),
			$arr[2]).'</td>
	</tr>';
	}

	$echo .= '<tr><td><td><input type=hidden name="image" value="' .$image .'" style="width:500px"></td></tr>';

	$echo .= '
	<tr>
		<td></td>
		<td><input type="submit" name="speichern" value="speichern"></td>
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
		if ($x <= 2) $echo .= '<tr>
			<td>'.$arr[1].':</td>
			<td>'. repl(array("#v#", "#n#", "#s#"), array($row->$get, $arr[0], 'width:400px;'), $arr[2]).'</td>
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

	//// EDIT_SKRIPT
	$sql = '';
	$db = "morp_event_kategorie";
	$id = "kid";
	/////////////////////

	foreach($arr_form as $arr) {
		$tmp = $arr[0];
		$val = $_POST[$tmp];

		if ($tmp != "region") $sql .= $tmp. "='" .addslashes(trim($val)). "', ";
	}

	$sql = substr($sql, 0, -2);

	if ($neu) {
		$sql  = "INSERT $db set $sql";
		$res  = safe_query($sql);
		$edit = mysqli_insert_id($mylink);
		unset($neu);
	}
	else {
		$sql = "update $db set $sql WHERE $id=$edit";
		$res = safe_query($sql);
	}
	// echo $sql;
	unset($edit);
}
elseif ($del) {
	die('<p>M&ouml;chten Sie den '.$um_wen_gehts.' wirklich l&ouml;schen?</p>
	<p><a href="?delete='.$del.'">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?">Nein</a></p>
	');
}
elseif ($delete) {
	$sql = "DELETE FROM morp_event_kategorie WHERE kid=$delete";
	$res = safe_query($sql);
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

