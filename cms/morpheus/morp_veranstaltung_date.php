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
$myauth = 10;
$event_in = 'in';
$event_active = ' class="active"';

include("cms_include.inc");
// include("editor_sn.php");
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

# print_r($_SESSION);
# print_r($_REQUEST);
///////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
//// EDIT_SKRIPT
$um_wen_gehts 	= "Event";
$titel			= "Event Verwaltung Datum";
///////////////////////////////////////////////////////////////////////////////////////
$table 		= 'morp_events_date';
$tid 		= 'dateID';
$nameField 	= "datum";

$table2 		= 'morp_events';
$tid2 		= 'eventid';
$nameField2 	= "eventName";

$table3 		= 'morp_event_orte';
$tid3 		= 'oid';
$nameField3 	= "stadt";

$imgFolder = "../images/event/";
$imgFolderShort = "event/";
$scriptname = basename(__FILE__);
///////////////////////////////////////////////////////////////////////////////////////
$unvis	 = $_REQUEST["unvis"];
$vis	 = $_REQUEST["vis"];
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

// $new = '<a href="?neu=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a>';
$new = '<p><a href="?new=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a></p>';

echo '<div id=vorschau>
	<h2 class="mb4">'.$titel.'</h2>

	'. ($edit || $neu ? '<p><a href="?">&laquo; zur&uuml;ck</a></p>' : '') .'
	<form action="" onsubmit="" name="verwaltung" method="post">
'.($edit || $neu ? '' : '').'
'.(!$edit && !$neu ? '' : '').'
';

// print_r($_POST);

/////////////////////////////////////////////////////////////////////////////////////////////////////
#$sql = "ALTER TABLE  $table ADD  `fFirmenFilterID` INT( 11 ) NOT NULL";
#safe_query($sql);
/////////////////////////////////////////////////////////////////////////////////////////////////////

$arr_form = array(
		array("datum", "Datum", '<input type="Text" value="#v#" class="form-control" name="#n#"  />', 'date'),
		array("enddat", "Datum Ende (nur bei mehrtägigen Seminaren)", '<input type="Text" value="#v#" class="form-control" name="#n#"  />', 'date'),
		// array("anzahlTeilnehmer", "Anzahl Teilnehmer", '<input type="Text" value="#v#" class="form-control" name="#n#"  />'),
		array("eventid", "Event", 'sel2', $table2, $nameField2, $tid2),
		array("oid", "Stadt / Ort", 'sel2', $table3, $nameField3, $tid3),
		// array("eventAbstract", "Abstract", '<textarea id="summernote" class="form-control" name="#n#" />#v#</textarea>'),
		// array("eventText", "Beschreibung", '<textarea id="summernote" class="form-control" name="#n#" />#v#</textarea>'),
		// array("event_reg_text1", "Event freier Zusatztext", '<textarea id="summernote" class="form-control" name="#n#" />#v#</textarea>'),
		
		// array("", "CONFIG", '</div><div class="col-md-6">'),
		
//		array("aktiv", "Online sichtbar", 'chk'),

		// array("", "CONFIG", '</div></div>'),
);
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

$neuerDatensatz = isset($_GET["new"]) ? $_GET["new"] : 0;
$edit = isset($_REQUEST["edit"]) ? $_REQUEST["edit"] : 0;
$save = isset($_REQUEST["save"]) ? $_REQUEST["save"] : 0;
$del = isset($_REQUEST["del"]) ? $_REQUEST["del"] : 0;
$delete = isset($_REQUEST["delete"]) ? $_REQUEST["delete"] : 0;
$back = isset($_POST["back"]) ? $_POST["back"] : 0;
$delguest = isset($_GET["delguest"]) ? $_GET["delguest"] : 0;
$delguestconfirm = isset($_GET["delguestconfirm"]) ? $_GET["delguestconfirm"] : 0;

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

if ($vis || $unvis) {
	if($vis) 		$sql = "UPDATE $table SET aktiv=1 WHERE $tid=".$vis;
	elseif($unvis) 	$sql = "UPDATE $table SET aktiv=0 WHERE $tid=".$unvis;
	// echo $sql;
	safe_query($sql);
	$jsSAVE = 1;
}
else if ($delguest) {
	$kunde = get_db_field($delguest, 'name', 'morp_cms_form_auswertung', 'aid');
	die ('<p>&nbsp;</p><p><font color=#ff0000><b>Wollen Sie den Kunden <u>'.$kunde.'</u> wirklich vom Event  l&ouml;schen?</b></font></p>
	<p class="mt4">
		<a href="?edit='.$edit.'" class="btn btn-info">Nein</a> &nbsp;  &nbsp; &nbsp;
		<a href="?delguestconfirm='.$delguest.'&edit='.$edit.'" class="btn btn-danger">JA</a>
	</p>
	
	</body></html>
	');
}
else if ($delguestconfirm) {
	$sql = "DELETE FROM morp_cms_form_auswertung WHERE aid=$delguestconfirm";
	$res = safe_query($sql);
	echo '<script> document.location.href="?edit='.$edit.'"; </script>';
}
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


function liste() {
	global $arr_form, $table, $tid, $filter, $nameField;
	global $table2, $tid2, $nameField2;

	//// EDIT_SKRIPT
	$ord = $nameField;
	$anz = $nameField;

	////////////////////
	$where = 1;
	$heute = date("Y-m-d");

	$echo .= '<p>&nbsp;</p>
	<table class="autocol p20 newTable" style="width:100%">
		<tr>
			<th></th>
			<th>ID</th>
			<th style="text-align:left">Datum</th>
			<th style="text-align:left">Event</th>
<!--			<th style="text-align:left">Anzahl<br>Gesamt</th>
			<th style="text-align:left">Anzahl<br>Registriert</th>-->
			<th></th>
			<th></th>
		</tr>
';

	$sql = "SELECT * FROM $table WHERE $where ORDER BY $tid2,".$ord."";
	$res = safe_query($sql);
	// echo mysqli_num_rows($res);

	$oldKat = '';

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$tid;
		$getDataID = $row->$tid2;

		if($getDataID != $oldKat) {
			if($oldKat) $echo .= '<tr><td colspan="8"><hr></td></tr>';
			$oldKat = $getDataID;
		}
		$sql = "SELECT $nameField2, eventSubline FROM `$table2` WHERE $tid2=$getDataID";
		$rs = safe_query($sql);
		$rw = mysqli_fetch_object($rs);
		$eventName = $rw->$nameField2;
		
		// $sql = "SELECT * FROM `morp_cms_form_auswertung` WHERE event=$getDataID AND register=1 AND $tid=$edit";
		// $rs = safe_query($sql);
		// $eventAnzahlRest = mysqli_num_rows($rs);
		// $eventAnzahlRest = count_free_space($getDataID,$edit);
		// $eventAnzahlRest = count_registered($getDataID,$edit);
		
		// print_r($row);
		$si   = $row->aktiv;
		// if ($si == 1) 	$si = '<a href="?unvis='.$edit.'" class="btn btn-success"><i class="fa fa-eye vis" ref="0"></i></a>';
		// else			$si = '<a href="?vis='.$edit.'" class="btn btn-info"><i class="gray fa fa-eye-slash vis" ref="1"></i></a>';

		$echo .= '			<tr'.($row->$nameField < $heute ? ' class="warn"' : '').'>
			<td width="50" align="center">
				<a href="?edit='.$edit.'" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i></a>
			</td>
			<td width="50" align="center">
				<small><a href="?edit='.$edit.'">'.$row->$tid.' </a></small>
			</td>
			<td>
				<a href="?edit='.$edit.'">'.euro_dat($row->$nameField).' </a>
			</td>
			<td>
				<a href="?edit='.$edit.'">'.$eventName.' – '.$rw->eventSubline.'</a>
			</td>
<!--			<td>
				<a href="?edit='.$edit.'">'. euro_dat($row->eventDatum).$heute.'</a>
			</td>
			<td>'.$row->anzahlTeilnehmer.' &nbsp; &nbsp; </td>
			<td>'.$eventAnzahlRest.' &nbsp; &nbsp; </td>
			<td>
				'.$si.'
			</td>-->
			<td>
				<a href="?del='.$edit.'" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
			</td>
		</tr>
';
	}

	$echo .= '</table><p>&nbsp;</p>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function edit($edit) {
	global $arr_form, $table, $tid, $imgFolder, $um_wen_gehts, $scriptname, $mylink;
	global $table2, $tid2, $nameField2;

	$sql = "SELECT * FROM $table WHERE $tid=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);

	$echo .= '
		<input type="Hidden" name="edit" value="'.$edit.'">
		<input type="Hidden" name="save" value="1">
		<input type="hidden" value="0" name="back" id="back" />

		<div class="row">
			<div class="col-md-6">

	';

	foreach($arr_form as $arr) {
		$echo .= setMorpheusForm($row, $arr, $imgFolder, $edit, substr($scriptname,0,(strlen($scriptname)-4)), $tid);
	}

	$echo .= '
<!--
<p style="margin-bottom:1em;"><a href="content_edit.php?edit='.$cidEn.'&vorlage=1&event='.$edit.'"><i class="fa fa-chevron-right"></i> &nbsp; Langtext EDIT English</a></p>
<p><a href="content_edit.php?edit='.$cid.'&vorlage=1&event='.$edit.'"><i class="fa fa-chevron-right"></i> &nbsp; Langtext EDIT Deutsch</a></p>
-->
			<br>
				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
				<button type="submit" id="savebtn2" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>
			<br>
			<br>
				<a href="morp_veranstaltung.php?edit='.$row->eventid.'" class="btn btn-info"><i class="fa fa-chevron-left"></i> &nbsp; Zum Event ( Änderungen werden nicht gespeichert )</a>
		</div>
	</div>
';

	$ech .= '
<table class="autocol p20 newTable" style="width:100%">
	<tr>
		<td>Name</td>
		<td>Organisation</td>
		<td>Function</td>
		<td>Address</td>
		<td>Phone</td>
		<td>E-Mail</td>
		<td>Date</td>
	</tr>';
		
	$getDataID = $row->$tid2;
	// $sql = "SELECT * FROM `morp_cms_form_auswertung` WHERE event=$edit AND register=1";
	$sql = "SELECT * FROM `morp_cms_form_auswertung` WHERE event=$getDataID AND dateID=$edit";
	$res = safe_query($sql);
	$mail = "E-Mail";
	$all_mails = '';
	while($row = mysqli_fetch_object($res)) {
		
		$all_mails .= $row->$mail.',';
		
		$ech .= '
	<tr>
		<td>'.$row->name.'</td>
		<td>'.$row->Organisation.'</td>
		<td>'.$row->Function.''.$row->Funktion.'</td>
		<td>'.$row->Address.''.$row->Addresse.'</td>
		<td>'.$row->Phone.''.$row->Telefon.'</td>
		<td>'.$row->$mail.'</td>
		<td>'.$row->datum.'</td>
		<td><a href="?delguest='.$row->aid.'&edit='.$edit.'" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>
	</tr>';
	}
	$ech .= '</table><br><br><h3>Alle E-Mail Adressen mit Komma Trennung:</h3>'.substr($all_mails,0,strlen($all_mails)-1);
	
	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function neu() {
	global $arr_form, $table, $tid, $um_wen_gehts;

	$x = 0;


	$echo .= '<input type="Hidden" name="neu" value="1"><input type="Hidden" name="save" value="1">

	<table cellspacing="6" style="width:50%;">';

	foreach($arr_form as $arr) {
		if ($x < 1) $echo .= '<tr>
			<td>'.$arr[1].':</td>
			<td>'. str_replace(array("#v#", "#n#", "#s#"), array($get, $arr[0], 'width:400px;'), $arr[2]).'</td>
		</tr>';
		$x++;
	}

	$echo .= '
	<tr>
		<td></td>
		<td><input type="hidden" value="'.$_GET["eventid"].'" class="form-control" name="eventid"></td>
	<tr>
	<tr>
		<td></td>
		<td>
			<br>
			<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern</button>
		</td>
	</tr>
</table>';


	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

if ($save) {
	$neu = isset($_POST["neu"]) ? $_POST["neu"] : 0;

	// echo "save";
	$edit = saveMorpheusForm($edit, $neu);

	// if(neu) unset($neu);

	$scriptname = basename(__FILE__);

	if($back) {
?>
	<script>
		location.href='<?php echo $scriptname; ?>';
	</script>
<?php
	}
	elseif($neu) {
?>
	<script>
		location.href='<?php echo $scriptname; ?>?edit=<?php echo $edit; ?>';
	</script>
<?php
	}

	// unset($edit);
}

elseif ($delimg) {
	deleteImage($delimg, $edit, $imgFolder);
}

elseif($delete) {
	$sql = "DELETE FROM `$table` WHERE $tid=$delete ";
	safe_query($sql);
}

// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

if($del) {
	echo '	<h2>Wollen Sie '.$um_wen_gehts.' wirklich löschen?</h2>
<br><br>
			<a href="?delete='.$del.'" class="btn btn-danger"><i class="fa fa-trash"></i> &nbsp; Ja</a>
			<a href="?" class="btn btn-info"><i class="fa fa-remove"></i> &nbsp; Nein / Abbruch</a>

';
}
elseif ($neuerDatensatz) 	echo neu("neu");
elseif ($edit) 			echo edit($edit);
else						echo liste().$new;

echo '
</form>
';

include("footer.php");

?>

<script>
	  $(".form-control").on("change", function() {
	  	$("#savebtn").addClass("btn-danger");
	  	$("#savebtn2").addClass("btn-danger");
	  });
	  $("#savebtn2").on("click", function() {
	  	$("#back").val(1);
	  });
</script>