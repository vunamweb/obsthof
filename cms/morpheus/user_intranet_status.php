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
$aktuere_in = 'in';
$akt3_active = ' class="active"';
include("cms_include.inc");

$editor_height = 200;
/*
include("editor_css_cc.php");
include("editor_cc.php");
*/


///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

# print_r($_SESSION);
# print_r($_POST);
# die();
///////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
//// EDIT_SKRIPT
$um_wen_gehts 	= "Status Akteure";
$titel			= "Status Akteure";
///////////////////////////////////////////////////////////////////////////////////////
$table 	= "morp_intranet_user_status";
$tid 	= "stID";
$nameField 	= "status";
$sortField 	= 'status';

// $table2 	= "morp_ref_gruppe";
// $tid2 		= "gID";
// $nameField2 = "gName";

$imgFolder = "../images/status/";
$imgFolderShort = "status/";
///////////////////////////////////////////////////////////////////////////////////////

// $new = '<a href="?neu=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a>';
$new = '<p><a href="?new=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a></p>';

echo '
<form name="check">
	<input type="hidden" name="check" id="check__value" value="">
</form>

<div id=vorschau>
	<h2>'.$titel.'</h2><form action="" onsubmit="" name="verwaltung" method="post">

	'. ($edit || $neu ? '<p><a href="?">&laquo; zur&uuml;ck</a></p>' : '') .'

'.($edit || $neu ? '' : '').'
'.(!$edit && !$neu ? '' : '').'
';

// print_r($_POST);

/////////////////////////////////////////////////////////////////////////////////////////////////////
#$sql = "ALTER TABLE  $table ADD  `fFirmenFilterID` INT( 11 ) NOT NULL";
#safe_query($sql);
/////////////////////////////////////////////////////////////////////////////////////////////////////

$arr_form = array(
	array("status", "Status", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	// 	array("sichtbar", "sichtbar", 'chk'),
	// 	array("reihenfolge", "Reihenfolge", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	// 	array("kText", "Text", '<textarea class="form-control" name="#n#" />#v#</textarea>'),
	// 	array("kText_en", "Text English", '<textarea class="form-control" name="#n#" />#v#</textarea>'),
	// 
	// 		array("", "CONFIG", '</div><div class="col-md-6">'),
	// 
	// 	array("gID", "Gruppe", 'sel2', $table2, $nameField2, $tid2),
	// 	array("img", "Foto", 'fotoG', 'image', 'imgname', 6, 'gid'),

		// array("eventDatum", "Datum", '<input type="Text" value="#v#" class="form-control" name="#n#" />', 'date'),
		// array("termine", "Termine", '<input type="Text" value="#v#" class="form-control" name="#n#" />', ),
		// array("zusatz2", "Datum Anzeige", '<textarea class="form-control" name="#n#" />#v#</textarea>'),
#  	array("a1", "in Ausstattung anzeigen", 'chk'),
#	array("", "CONFIG", '</div><div class="col-md-4">'),

);


///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

$neuerDatensatz = isset($_GET["new"]) ? $_GET["new"] : 0;
$edit = isset($_REQUEST["edit"]) ? $_REQUEST["edit"] : 0;
$save = isset($_REQUEST["save"]) ? $_REQUEST["save"] : 0;
$del = isset($_REQUEST["del"]) ? $_REQUEST["del"] : 0;
$delete = isset($_REQUEST["delete"]) ? $_REQUEST["delete"] : 0;
$back = isset($_POST["back"]) ? $_POST["back"] : 0;

$delimg = isset($_GET["delimg"]) ? $_GET["delimg"] : 0;

$down = isset($_GET["down"]) ? $_GET["down"] : 0;
$up = isset($_GET["up"]) ? $_GET["up"] : 0;
$col = isset($_GET["col"]) ? $_GET["col"] : 0;
$copy = isset($_GET["copy"]) ? $_GET["copy"] : 0;

$repair = isset($_GET["repair"]) ? $_GET["repair"] : 0;
$vis = isset($_GET["vis"]) ? $_GET["vis"] : 0;
$sichtbar = isset($_GET["sichtbar"]) ? $_GET["sichtbar"] : 0;

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

function liste() {
	global $arr_form, $table, $tid, $filter, $nameField, $sortField, $imgFolderShort, $hID, $table2, $tid2, $nameField2;

	$echo .= '

</form>
<p>&nbsp;</p>

	

<div id="" class="">
';

	//// EDIT_SKRIPT
	$ord = $sortField;
	$anz = $nameField;

	////////////////////
	$where = " 1 ";
	$sql = "SELECT * FROM $table WHERE $where ORDER BY $ord";
	$res = safe_query($sql);
	// echo mysqli_num_rows($res);

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$tid;

		$edit_link = '<a href="?edit='.$edit.'">';
		$edit_end = '</a>';

		$echo .= '
	<div class="zeile row'.( $hID = $row->hID ? ' haendler_edit' : '').'"  id="'.$row->$tid.'">
			<div class="col-md-2">
				'.$edit_link.'
					<span class=" ml2 btn btn-primary">'.($allowed_to_edit ? '<i class="fa fa-pencil-square-o"></i><span class="small light">' : '').' &nbsp; '.$row->$tid.'</span>
				'.$edit_end.'
			</div>
			<div class="col-md-4">
				'.$edit_link.$row->$anz.'
			</div>
<!--
			<div class="col-md-1">
				<a href="?vis='.$edit.'&sichtbar='.($row->sichtbar ? 0 : 1).'"><i class="fa fa-eye'.($row->sichtbar ? '' : '-slash gray').'"></i></a>
			</div>
-->
			<div class="col-md-1 text-right">
				<a href="?del='.$edit.'" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
			</div>
	</div>
';
	}

	$echo .= '
</div>
<p>&nbsp;</p>
';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function edit($edit) {
	global $arr_form, $table, $tid, $imgFolder, $imgFolderShort, $um_wen_gehts, $hID, $is_admin;

	$sql = "SELECT * FROM $table WHERE $tid=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);

	$scriptname = basename(__FILE__);
	$scriptname = substr($scriptname, 0, strlen($scriptname)-4);

	$um_wen_gehts='';

	$echo .= '
		<input type="Hidden" name="edit" value="'.$edit.'">
		<input type="Hidden" name="save" value="1">
		<input type="hidden" value="0" name="back" id="back" />

<!--
		<div class="row" style="margin:2em 0;">
				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
				<button type="submit" id="savebtn2" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>
		</div>
-->

		<div class="row">
			<div class="col-md-6">

	';

	foreach($arr_form as $arr) {
		$echo .= setMorpheusForm($row, $arr, $imgFolderShort, $edit, $scriptname, $tid);
	}

	$echo .= '</div>
		</div>

				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
				<!--<button type="submit" id="savebtn3" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>-->
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

	if($neu) {
		// $sql = "SELECT * FROM $table WHERE 1";
		// $res = safe_query($sql);
		// $x = mysqli_num_rows($res);
		// $zusatz = ", reihenfolge=".$x++;
	}
	else $zusatz = "";

	// echo "save";
	$edit = saveMorpheusForm($edit, $neu, 0, $zusatz);

	// if(neu) unset($neu);

	$scriptname = basename(__FILE__);
	
	$back = 1;
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
	deleteImage($delimg, $edit, $imgFolder, 0);
}

elseif($delete) {
	$sql = "DELETE FROM `$table` WHERE $tid=$delete ";
	safe_query($sql);
}

elseif($repair) {
	repair_hID();
}
elseif ($vis) {
	$sql = "UPDATE $table SET sichtbar=$sichtbar WHERE $tid=".$vis;
	safe_query($sql);
}
elseif($copy) {
	$sql  	= "SELECT * FROM $table WHERE $tid=$edit";
	$res 	= safe_query($sql);
	$y		= mysqli_num_rows($res);
	$y++;

	$sql  	= "SELECT * FROM $table WHERE $tid=$copy";
	$res 	= safe_query($sql);
	$row 	= mysqli_fetch_object($res);

	saveMorpheusForm($edit, 1, $row);

	repair_hID();
}

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

if($up || $down) {
	if($down) { $sort1 = $down; $sort2 = $down+1; }
	elseif($up) { $sort1 = $up; $sort2 = $up-1; }

	$col1 = $col.$sort1;
	$col2 = $col.$sort2;

	$sql = "SELECT $col1, $col2 FROM $table WHERE $tid = $edit";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
	$file1 = $row->$col1;
	$file2 = $row->$col2;

	$sql = "UPDATE $table SET $col2='$file1', $col1='$file2' WHERE $tid = $edit";
	safe_query($sql);
}

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

if($del) {
	echo '	<h2>Wollen Sie '.$um_wen_gehts.' wirklich löschen?</h2>
			<p>&nbsp;</p>
			<p><a href="?delete='.$del.'" class="btn btn-danger"><i class="fa fa-trash"></i> &nbsp; Ja</a>
			<a href="?" class="btn btn-info"><i class="fa fa-remove"></i> &nbsp; Nein / Abbruch</a></p>

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
		  $("#savebtn3").addClass("btn-danger");
	  });
	  $("#savebtn2, #savebtn3").on("click", function() {
		  $("#back").val(1);
	  });



	$(".chngeValue").click(function () {
		id = $(this).attr("ref");
		col = $(this).attr("col");
		// val = $('.'+col+id).val();

		val = ($(this).is(':checked'));

		if(val) val = 1;
		else val=0;

		 console.log(val+' # col: '+col+' # '+id);


		request = $.ajax({
			url: "Update.php",
			type: "post",
			data: "pos="+col+"&data="+val+"&id="+id+"&feld=<?php echo $tid; ?>&table=<?php echo $table; ?>",
			success: function(data) {
				//$('.save-'+col+id).removeClass('btn-danger');
				// console.log(data);
			  }
		});

	});


</script>
