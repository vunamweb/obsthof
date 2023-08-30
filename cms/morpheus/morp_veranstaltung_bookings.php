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
$um_wen_gehts 	= "Buchungen";
$titel			= "Buchungen";
///////////////////////////////////////////////////////////////////////////////////////
$table 		= 'morp_cms_form_auswertung';
$tid 		= 'aid';
$nameField 	= "eventName";

$table2 	= 'morp_events_date';
$tid2 		= 'dateID';
$nameField2 = "datum";

$table4 	= 'morp_events';
$tid4 		= 'eventid';
$nameField4 = "eventName";

$table3 	= 'historie_payment';
$tid3 		= 'payID';
$nameField3 = "datum";

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
		array("name", "Name Teilnehmer", '<input type="Text" value="#v#" class="form-control" name="#n#"  />'),
		// array("eventName", "Name Event", '<input type="Text" value="#v#" class="form-control" name="#n#"  />'),
		// array("number", "Nummer Gutschein", '<input type="Text" value="#v#" class="form-control" name="#n#"  />'),
		// array("email", "E-Mail", '<input type="Text" value="#v#" class="form-control" name="#n#"  />'),
		// array("mitteilung", "Mitteilung", '<input type="Text" value="#v#" class="form-control" name="#n#"  />'),
		// array("amount", "Wert", '<input type="Text" value="#v#" class="form-control" name="#n#"  />'),
		// array("datum", "Erstellt", '<input type="Text" value="#v#" class="form-control" name="#n#"  />', 'date'),
		// array("paymentid", "PayPal Payment ID", '<input type="Text" value="#v#" class="form-control" name="#n#"  />'),
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
$change = isset($_REQUEST["change"]) ? $_REQUEST["change"] : 0;
$storno = isset($_REQUEST["storno"]) ? $_REQUEST["storno"] : 0;
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
	$ord = 't1.dateID,'.$nameField;
	$anz = $nameField;

	////////////////////
	$where = 1;
	$heute = date("Y-m-d");

	$echo .= '<p>&nbsp;</p>
	<table class="autocol p20 newTable" style="width:100%">
		<tr>
			<th></th>
			<th>ID</th>
			<th style="text-align:left">'.$arr_form[0][1].'</th>
			<th style="text-align:left">E-Mail</th>
			<th style="text-align:left">Event</th>
			<th style="text-align:left">Event</th>
			<th style="text-align:left">Anzahl</th>
			<th style="text-align:right">EZ Preis</th>
			<th style="text-align:right">Total</th>
			<th style="text-align:right">PayPal</th>
			<th style="text-align:right">Gutschein</th>
			<th></th>
			<th></th>
		</tr>
';

	$sql = "SELECT * FROM $table t1, $table2 t2 WHERE t1.$tid2=t2.$tid2 AND $where ORDER BY ".$ord."";
	$res = safe_query($sql);
	// echo mysqli_num_rows($res);

	$oldKat = '';

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$tid;
		$dateID = $row->dateID;

		// $sql = "SELECT sum(value) AS summe FROM `$table2` WHERE $tid=$edit";
		// $rs = safe_query($sql);
		// $rw = mysqli_fetch_object($rs);
		// $wert = round($rw->summe,2);

		$si   = $row->aktiv;
		// if ($si == 1) 	$si = '<a href="?unvis='.$edit.'" class="btn btn-success"><i class="fa fa-eye vis" ref="0"></i></a>';
		// else			$si = '<a href="?vis='.$edit.'" class="btn btn-info"><i class="gray fa fa-eye-slash vis" ref="1"></i></a>';

		$echo .= '			<tr'.($oldKat != $dateID ? ' style="border-top:solid 2px #000;"' : '').'>
			<td width="50" align="center">
				<a href="?edit='.$edit.'" class="btn btn-primary"><i class="fa fa-info"></i></a>
			</td>
			<td width="50" align="center">
				<a href="?edit='.$edit.'">'.$row->$tid.' </a>
			</td>
			<td><a href="?edit='.$edit.'">'.$row->name.' </a></td>
			<td><a href="?edit='.$edit.'">'.$row->email.' </a></td>
			<td><a href="?edit='.$edit.'">'.$row->eventName.' </a></td>
			<td><a href="?edit='.$edit.'">'.euro_dat($row->datum).' </a></td>
			<td align="right"><a href="?edit='.$edit.'">'.$row->quantity.' </a></td>
			<td align="right"><a href="?edit='.$edit.'">'.$row->preis.' </a></td>
			<td align="right"><a href="?edit='.$edit.'">'.$row->total.' </a></td>
			<td align="right"><a href="?edit='.$edit.'">'.$row->total_paypal.' </a></td>
			<td align="right"><a href="?edit='.$edit.'">'.$row->total_coupon.' </a></td>
			<td><a href="?edit='.$edit.'">'.$row->paymentid.' </a></td>
			<td>
				<!--<a href="?del='.$edit.'" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>-->
			</td>
		</tr>
';
		if($oldKat != $dateID) {
			$oldKat = $row->dateID;
		}
	}

	$echo .= '</table><p>&nbsp;</p>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function change($edit) {
	global $arr_form, $table, $tid, $imgFolder, $um_wen_gehts, $scriptname, $mylink;
	global $table2, $tid2, $nameField2, $table4, $tid4, $nameField4;

	$sql = "SELECT * FROM $table WHERE $tid=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
	
	$quantity = $row->quantity;
	
	print_r($row);

	$sql = "SELECT t1.eventid,dateID,eventName,t2.datum FROM $table4 t1, $table2 t2 WHERE t1.$tid4=t2.$tid4 ORDER BY eventName,dateID,datum DESC";
	$rs = safe_query($sql);
	$select = '<select name="new_event" class="form-control"><option>bitte neuen Event wählen</option>';
	while($rw = mysqli_fetch_object($rs)) {
		$n = count_free_space($rw->eventid,$rw->dateID);
		if($n > 0  ) $select .= '<option value="'.$rw->dateID.'">'.$rw->eventName.' | '.euro_dat($rw->datum).' | '.$n.' Plätze</option>';
	}
	$select .= '</select>';

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

	$echo .= $select.'

			<br>
				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
				<button type="submit" id="savebtn2" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>
			<br>
			<br>
				<a href="?change='.$edit.'" class="btn btn-info"><i class="fa fa-chevron-left"></i> &nbsp; Anderes Event zuordnen / umbuchen</a>
		</div>
	</div>
';
	
	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function edit($edit) {
	global $arr_form, $table, $tid, $imgFolder, $um_wen_gehts, $scriptname, $mylink;
	global $table2, $tid2, $nameField2, $table3, $tid3, $nameField3;

	$sql = "SELECT * FROM $table t1, $table2 t2 WHERE t1.$tid2=t2.$tid2 AND $tid=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
// print_r($row);

// $table2 	= 'morp_events_date';
// $tid2 		= 'dateID';
// $nameField2 = "datum";
// $table4 	= 'morp_events';
// $tid4 		= 'eventid';
// $nameField4 = "eventName";
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

	$echo .= '<table class="autocol">
    <tr><td>eventName </td><td> &nbsp; </td><td> <b>'.$row->eventName.' am '.euro_dat($row->datum).'</b>	</td></tr>
    <tr><td>aid </td><td> &nbsp; </td><td> '.$row->aid.'	</td></tr>
    <tr><td>event </td><td> &nbsp; </td><td> '.$row->event.'	</td></tr>
    <tr><td>Anzahl </td><td> &nbsp; </td><td> '.$row->quantity.'	</td></tr>
    <tr><td>Preis </td><td> &nbsp; </td><td> '.$row->preis.'	</td></tr>
    <tr><td>Summe </td><td> &nbsp; </td><td> '.$row->total.'	</td></tr>
    <tr class="warn"><td>Total coupon </td><td> &nbsp; </td><td> '.$row->total_coupon.'	</td></tr>
    <tr class="warn"><td>Total PayPal </td><td> &nbsp; </td><td> '.$row->total_paypal.'	</td></tr>
    <tr><td>Adresse </td><td> &nbsp; </td><td> '.$row->Adresse.'	</td></tr>
    <tr><td>Telefon </td><td> &nbsp; </td><td> '.$row->Telefon.'	</td></tr>
    <tr><td>Erstellt_am </td><td> &nbsp; </td><td> '.$row->datum.'	</td></tr>
    <tr><td>email </td><td> &nbsp; </td><td> '.$row->email.'	</td></tr>
    <tr><td>nachricht </td><td> &nbsp; </td><td> '.$row->nachricht.'	</td></tr>
    <tr><td>paymentid </td><td> &nbsp; </td><td> '.$row->paymentid.'	</td></tr>
	</table>
			<br>
				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
				<button type="submit" id="savebtn2" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>
			<br>
			<br>
				<a href="?storno='.$edit.'" class="btn btn-info"><i class="fa fa-chevron-left"></i> &nbsp; STORNO - ! Wichtig: nur Gutscheine werden zurückgebucht. PayPal muss über PayPal zurück gebucht werden, oder Gutschein erstellt werden.</a>
		</div>
	</div>
';
	
	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function storno($edit) {
	global $arr_form, $table, $tid, $imgFolder, $um_wen_gehts, $scriptname, $mylink;
	global $table2, $tid2, $nameField2, $table3, $tid3, $nameField3;

	$confirm_coupon = isset($_REQUEST["confirm_coupon"]) ? $_REQUEST["confirm_coupon"] : 0;
	$confirm_storno = isset($_REQUEST["confirm_storno"]) ? $_REQUEST["confirm_storno"] : 0;

	$sql = "SELECT * FROM $table t1, $table2 t2 WHERE t1.$tid2=t2.$tid2 AND $tid=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
	
	if($edit && $confirm_storno) {
		$sql = "DELETE FROM $table WHERE $tid=$edit";
		$rs = safe_query($sql);				
		$echo .= '<h3>STORNO erfolgreich</h3>';
	}
	else if($edit && $confirm_coupon) {
		$sql = "SELECT value, payID FROM $table3 WHERE $tid=".$edit."";
		$rs = safe_query($sql);
		$rw = mysqli_fetch_object($rs);
		$value = $rw->value;
		$payID = $rw->payID;
		$date = date("Y-m-d H:m:s");
		$sql = "UPDATE $table3 SET value=0, delete_value=$value , delete_day='$date' , delete_dateID=".$row->dateID." WHERE $tid3=$payID";
		$rs = safe_query($sql);		

		$sql = "DELETE FROM $table WHERE $tid=$edit";
		$rs = safe_query($sql);		
		
		$echo .= '<h3>STORNO erfolgreich</h3>';
	}
	
	else {
		
		$echo .= '
			<input type="Hidden" name="edit" value="'.$edit.'">
			<input type="Hidden" name="save" value="1">
			<input type="hidden" value="0" name="back" id="back" />
	
			<div class="row">
				<div class="col-md-6">';
	
		if($row->total_coupon) $echo .= '
		<p class="mb2"><b>Bitte den Kunden über die Stornierung in Kenntnis setzen!</b></p>
		<p class="mb3"><a class="btn btn-danger" href="?storno='.$edit.'&confirm_coupon=1"><i class="fa fa-bullseye"></i> &nbsp; Bestätigung: Event jetzt stornieren und Gutschein-Wert über '.$row->total_coupon.' EURO auf original Gutschein gutschreiben</a></p>';
	
		if($row->total_paypal) $echo .= '
		<p class="mb3"><a class="btn btn-danger" href="?storno='.$edit.'&confirm_storno=1"><i class="fa fa-trash-o"></i> &nbsp; Event löschen, ohne Gutschein erstellen, ohne PayPal Rücküberweisung von '.$row->total_paypal.' EURO</a></p>
		';
		
		if($row->total_paypal && $row->total_coupon) $echo .= '<hr>
		<h2>ODER</h2>
		<p class="mb1 mt1"><b>Schritt 1:</b> Gutschein über '.$row->total_paypal.' EURO erstellen</p>
		<p><b>Schritt 2</b></p>
		<p class="mb3"><a class="btn btn-danger" href="?storno='.$edit.'&confirm_coupon=1"><i class="fa fa-bullseye"></i> &nbsp; Bestätigung: Event jetzt stornieren und Gutschein-Wert über '.$row->total_coupon.' EURO auf original Gutschein gutschreiben</a></p>
		
		<hr>';
		
		$echo .= '<table class="autocol mt6">
    	<tr><td>Name </td><td> &nbsp; </td><td> <b>'.$row->name.'</b></td></tr>
    	<tr><td>eventName </td><td> &nbsp; </td><td> <b>'.$row->eventName.' am '.euro_dat($row->datum).'</b>	</td></tr>
    	<tr><td>name </td><td> &nbsp; </td><td> '.$row->name.'	</td></tr>
    	<tr><td>event </td><td> &nbsp; </td><td> '.$row->event.'	</td></tr>
    	<tr><td>Anzahl </td><td> &nbsp; </td><td> '.$row->quantity.'	</td></tr>
    	<tr><td>Preis </td><td> &nbsp; </td><td> '.$row->preis.'	</td></tr>
    	<tr><td>Summe </td><td> &nbsp; </td><td> '.$row->total.'	</td></tr>
    	<tr class="warn"><td>Total coupon </td><td> &nbsp; </td><td> '.$row->total_coupon.'	</td></tr>
    	<tr class="warn"><td>Total PayPal </td><td> &nbsp; </td><td> '.$row->total_paypal.'	</td></tr>
    	<tr><td>email </td><td> &nbsp; </td><td> '.$row->email.'	</td></tr>
    	<tr><td>paymentid </td><td> &nbsp; </td><td> '.$row->paymentid.'	</td></tr>
		</table>
			</div>
		</div>
	';
	}

	
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
	
	if($neu) {
		$cc = set_coupon_code ($edit);
		$sql = "UPDATE $table SET number='$cc' WHERE $tid=$edit";
		$rs = safe_query($sql);
	}
	
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
elseif ($storno) 			echo storno($storno);
elseif ($change) 			echo change($change);
elseif ($edit) 				echo edit($edit);
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