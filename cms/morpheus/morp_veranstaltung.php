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
include("cms_include.inc");
include("_tinymce.php");

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

# print_r($_SESSION);
# print_r($_REQUEST);

///////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
//// EDIT_SKRIPT
$um_wen_gehts 	= "Veranstaltung";
$titel			= "Veranstaltung Verwaltung";
///////////////////////////////////////////////////////////////////////////////////////
$table 		= 'morp_events';
$tid 		= 'eventid';
$nameField 	= "eventName";
$imgFolder = "../images/event/";
$imgFolderShort = "event/";
$scriptname = basename(__FILE__);
///////////////////////////////////////////////////////////////////////////////////////

// $new = '<a href="?neu=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a>';
$new = '<p><a href="?new=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a></p>';

echo '<div id=vorschau>
	<h2>'.$titel.'</h2>

	'. ($edit || $neu ? '<p><a href="?">&laquo; zur&uuml;ck</a></p>' : '') .'
	<form action="" onsubmit="" name="verwaltung" method="post">
'.($edit || $neu ? '' : '').'
'.(!$edit && !$neu ? '' : '').'
';

// print_r($_POST);

/////////////////////////////////////////////////////////////////////////////////////////////////////
// $sql = "ALTER TABLE  $table ADD  `leitung` VARCHAR( 255 ) NULL";
// safe_query($sql);
/////////////////////////////////////////////////////////////////////////////////////////////////////

$arr_form = array(
		array("eventName", "Titel Veranstaltung", '<input type="Text" value="#v#" class="form-control" name="#n#" required />'),
		array("zusatz2", "Zusatz", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
		// array("zusatz", "Zusatz", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),

		
		array("kid", "Kategorie", 'sel2', 'morp_event_kategorie', 'bezeichnung', 'kid'),

		array("oid", "Ort", 'sel2m', 'morp_event_orte', 'stadt', 'veranstOrt', 'oid'),
		// array("mid", "Leitung 1", 'sel2m', 'morp_mitarbeiter', 'name', 'vorname', 'mid'),
		// array("mid2", "Leitung 2", 'sel2m', 'morp_mitarbeiter', 'name', 'vorname', 'mid'),

		array("leitung", "Leitung der Veranstaltung", '<input type="Text" value="#v#" class="form-control" name="#n#" />', ),
		array("email", "Anmeldung E-Mail", '<input type="Text" value="#v#" class="form-control" name="#n#" />', ),
		array("eventDatum", "Datum Start", '<input type="Text" value="#v#" class="form-control" name="#n#" />', 'date'),
		array("eventEndDatum", "End Datum", '<input type="Text" value="#v#" class="form-control" name="#n#" />', 'date'),
		array("dauer", "Dauer", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
		array("preis", "Preis – Anzeige Übersicht", '<textarea class="form-control" name="#n#" />#v#</textarea>'),
		
		array("", "CONFIG", '</div><div class="col-md-6">'),
		array("", "CONFIG", '<hr><h3><b>Zusatz Infos große Darstellung</b></h3><p>&nbsp;</p>'),

		array("eventHl", "Headline Beschreibung - große Darstellung", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
		array("eventText", "Beschreibung - große Darstellung", '<textarea class="form-control" name="#n#" />#v#</textarea>'),

		// array("", "CONFIG", '<hr>'),

//		array("eventBegleitung", "Anzahl Begleitpersonen je Mitarbeiter", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
//		array("eventAnzahlTeilnehmer", "Anzahl Teilnehmer", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
		array("img", "Foto", 'imgSel', 'image', 'imgname', 6, 'gid'),
		array("img2", "Foto Header", 'imgSel', 'image', 'imgname', 6, 'gid'),
		array("file", "PDF / File", 'fileUpl', 'image', 'imgname', 6, 'gid'),
		
		array("", "CONFIG", '</div></div><div class="row"><div class="col-md-6">'),

		
		array("", "CONFIG", '<hr><h3><b>Zusatz Infos Formular</b></h3><p>&nbsp;</p>'),
		array("programm", "Programm", '<textarea class="summernote" name="#n#" />#v#</textarea>'),
		array("form_zusatz", "Formular Zusatz Info", '<textarea class="summernote" name="#n#" />#v#</textarea>'),
		
		array("", "CONFIG", '</div><div class="col-md-6">'),
		array("", "CONFIG", '<hr><h3><b>Zusatz Infos Formular</b></h3><p>&nbsp;</p>'),
		
		array("kosten", "Kosten", '<textarea class="summernote" name="#n#" />#v#</textarea>'),
		array("einmal", "€ – Einmalzahlung (Betrag)", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
		array("rate", "€ – Betrag je Ratenzahlung", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
		array("rate_anzahl", "Anzahl Monate für Ratenzahlung", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
//		array("eventText", "Beschreibung", '<textarea class="form-control" name="#n#" />#v#</textarea>'),

		array("", "CONFIG", '</div></div>'),
);
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

$neuerDatensatz = isset($_GET["new"]) ? $_GET["new"] : 0;
$edit = isset($_REQUEST["edit"]) ? $_REQUEST["edit"] : 0;
$save = isset($_REQUEST["save"]) ? $_REQUEST["save"] : 0;
$del = isset($_REQUEST["del"]) ? $_REQUEST["del"] : 0;
$delete = isset($_REQUEST["delete"]) ? $_REQUEST["delete"] : 0;
$back = isset($_POST["back"]) ? $_POST["back"] : 0;
$imgID = isset($_GET["imgID"]) ? $_GET["imgID"] : 0;
$imgCol = isset($_GET["imgCol"]) ? $_GET["imgCol"] : 0;

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

function liste() {
	global $arr_form, $table, $tid, $filter, $nameField;

	//// EDIT_SKRIPT
	$ord = 'kid, eventDatum ASC';
	$anz = $nameField;

	////////////////////
	$where = 1;
	$heute = date("Y-m-d");

	$echo .= '<p>&nbsp;</p><table class="autocol p20 newTable" style="width:100%">
';

	$sql = "SELECT * FROM $table WHERE $where ORDER BY ".$ord."";
	$res = safe_query($sql);
	// echo mysqli_num_rows($res);

	$oldKat = '';

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$tid;
		$kid = $row->kid;

		if($oldKat != $kid) {
			$kat = get_db_field($row->kid, 'bezeichnung', 'morp_event_kategorie', 'kid');

			$echo .= '			<tr>
			<th colspan="6">
				<h5>'.$kat.'</h5>
			</th>
		</tr>
';
			$oldKat = $kid;
		}

		$ort = get_2_db_field($row->oid, 'stadt', 'veranstOrt', 'morp_event_orte', 'oid');

		$echo .= '			<tr'.($row->eventDatum < $heute ? ' class="warn"' : '').'>
			<td width="50" align="center">
				<a href="?edit='.$edit.'" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i></a>
			</td>
			<td width="50" align="center">
				<a href="?edit='.$edit.'">'.$row->eventid.' </a>
			</td>
			<td>
				<a href="?edit='.$edit.'">'.$row->$anz.' </a>
			</td>
			<td>
				<a href="?edit='.$edit.'">'.$ort.' </a>
			</td>
			<td>
				<a href="?edit='.$edit.'">'. euro_dat($row->eventDatum).($row->eventEndDatum != '0000-00-00' ? ' - '. euro_dat($row->eventEndDatum) : '').'</a>
			</td>
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
	global $arr_form, $table, $tid, $imgFolder, $um_wen_gehts, $scriptname;

	$sql = "SELECT * FROM $table WHERE $tid=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);

	$echo .= '
		<input type="Hidden" name="edit" value="'.$edit.'">
		<input type="Hidden" name="save" value="1">
		<input type="hidden" value="0" name="back" id="back" />


		<div class="row">
			<div class="col-md-12 mt2 mb2">
				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
				<button type="submit" id="savebtn2" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>
			</div>
			<div class="col-md-6">

	';

	foreach($arr_form as $arr) {
		$echo .= setMorpheusForm($row, $arr, $imgFolder, $edit, substr($scriptname,0,(strlen($scriptname)-4)), $tid);
	}

	$echo .= '<br>
				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
		</div>
	</div>
';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function neu() {
	global $arr_form, $table, $tid, $um_wen_gehts;

	$x = 0;


	$echo .= '<input type="Hidden" name="neu" value="1"><input type="Hidden" name="save" value="1">

	<table cellspacing="6" style="width:100%;">';

	foreach($arr_form as $arr) {
		if ($x < 1) $echo .= '<tr>
			<td>'.$arr[1].':</td>
			<td>'. str_replace(array("#v#", "#n#", "#s#"), array($get, $arr[0], 'width:400px;'), $arr[2]).'</td>
		</tr>';
		$x++;
	}

	$echo .= '<tr>
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
	else if($neu) {
?>
	<script>
		location.href='<?php echo $scriptname; ?>?edit=<?php echo $edit; ?>';
	</script>
<?php
	}

	// unset($edit);
}

else if ($delimg) {
	deleteImage($delimg, $edit, $imgFolder);
}

else if($delete) {
	$sql = "DELETE FROM `$table` WHERE $tid=$delete ";
	safe_query($sql);
}

else if($imgCol && $imgID) {
	$sql = "UPDATE `$table` SET $imgCol=$imgID WHERE $tid=$edit ";
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
else if ($neuerDatensatz) 	echo neu("neu");
else if ($edit) 			echo edit($edit);
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