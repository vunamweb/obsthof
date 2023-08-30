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

$myauth = 40;
$config_in = 'in';
$conf2_active = ' class="active"';

include("cms_include.inc");

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

# print_r($_SESSION);
# print_r($_REQUEST);

global $arr_form, $table, $tid, $filter;

// NICHT VERAENDERN ///////////////////////////////////////////////////////////////////
$edit 	= $_REQUEST["edit"];
$delimg = $_REQUEST["delimg"];
$neu	= $_REQUEST["neu"];
$save	= $_REQUEST["save"];
$del	= $_REQUEST["del"];
$delimg	= $_REQUEST["delimg"];
$delete	= $_REQUEST["delete"];
$tid		= $_REQUEST["id"];
$html	= $_REQUEST["html"];
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
$qs		= $_GET["qs"];
$col	= $_GET["col"];
$setval	= $_GET["val"];
$filter	= $_GET["f"];

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////


//// EDIT_SKRIPT
$um_wen_gehts 	= "Einstellung Kundendaten";
$titel			= "Einstellung Kundendaten";
///////////////////////////////////////////////////////////////////////////////////////

// $new = '<a href="?neu=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a>';
$new = '';

echo '<div id=vorschau>
	<h2>'.$titel.'</h2>

	'. ($edit || $neu ? '<p><a href="?">&laquo; zur&uuml;ck</a></p>' : '') .'
	<form action="" onsubmit="" name="verwaltung" method="post">
'.($edit || $neu ? '' : '').'
'.(!$edit && !$neu ? '' : '').'
';

// print_r($_POST);

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

$table = "morp_settings";
$tid = "id";
$nameField = "name";

/////////////////////////////////////////////////////////////////////////////////////////////////////
#$sql = "ALTER TABLE  $table ADD  `fFirmenFilterID` INT( 11 ) NOT NULL";
#safe_query($sql);
/////////////////////////////////////////////////////////////////////////////////////////////////////

$arr_form = array(
	array("name", "Beschreibung", '<input type="Text" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">'),
	array("var", "Value (VORSICHT !!!)", '<input type="Text" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">'),
	array("value", "Wert", '<textarea class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">#v#</textarea>'),
);
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////


function liste() {
	global $arr_form, $table, $tid, $filter, $nameField;

	//// EDIT_SKRIPT
	$ord = 'id';
	$anz = $nameField;
	$anz2 = 'value';

	////////////////////
	$where = 1;

	$echo .= '<p>&nbsp;</p><table class="autocol p20 newTable">
';

	$sql = "SELECT * FROM $table WHERE $where ORDER BY ".$ord."";
	$res = safe_query($sql);
	// echo mysqli_num_rows($res);

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$tid;
		$echo .= '			<tr>
			<td width="50" align="center">
				<a href="?edit='.$edit.'" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i></a>
			</td>
			<td>
				<p><a href="?edit='.$edit.'">'.$row->$anz.' </a></p>
			</td>
			<td><textarea class="chngeValue changeVal-'.$edit.'" ref="'.$edit.'" style="min-width:50%;" />'.($row->$anz2).'</textarea>
				<span class="btn btn-info save save'.$edit.'" ref="'.$edit.'"><i class="fa fa-save"></i></span>
			</td>
			<td>
				<a class="btn btn-info" href="?"><i class="fa fa-refresh"></i></a>
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
	global $arr_form, $table, $tid, $imgFolder;

	$sql = "SELECT * FROM $table WHERE $tid=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);

	$echo .= '
		<input type="Hidden" name="edit" value="'.$edit.'">
		<input type="Hidden" name="save" value="1">

		<div class="row">
			<div class="col-md-6">

	';

	foreach($arr_form as $arr) {
		$echo .= setMorpheusForm($row, $arr, $imgFolder, $edit, 'morp_blog', $tid);
	}

	$echo .= '<br><br><input type="submit" name="speichern" value="speichern">
		</div>
	</div>
';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function neu() {
	global $arr_form, $table, $tid;

	$x = 0;


	$echo .= '<input type="Hidden" name="neu" value="1"><input type="Hidden" name="save" value="1">

	<table cellspacing="6">';

	foreach($arr_form as $arr) {
		if ($x < 1) $echo .= '<tr>
			<td>'.$arr[1].':</td>
			<td>'. str_replace(array("#v#", "#n#", "#s#"), array($get, $arr[0], 'width:400px;'), $arr[2]).'</td>
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
	$edit = saveMorpheusForm($edit, $neu);
	if($neu) unset($neu);
?>
<script>
	var new_url = modifyURLQuery(window.location.href, {neu: null, edit: null});
	// console.log(new_url);
	set_url(new_url);
</script>
<?php

	unset($edit);
}

elseif ($delete) {
	$sql = "DELETE FROM $table WHERE $tid=$delete";
	$res = safe_query($sql);
}

elseif ($delimg) {
	deleteImage($delimg, $edit, $imgFolder);
}

if ($qs) {
	$sql = "UPDATE $table SET $col='$setval' WHERE $tid=$qs";
	$res = safe_query($sql);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

if ($del) {
	$anz = get_db_field($del, $nameField, $table, $tid);

	echo ('<p>'.$um_wen_gehts.' <strong>'.$anz.'</strong> wirklich l&ouml;schen?</p>
	<p><a href="?delete='.$del.'" class="btn btn-danger">Ja</a> <span style="width:100px; display:inline-block;"></span> <a href="?" class="btn btn-info">Nein</a></p>
	');
}
elseif ($neu) 	echo neu("neu");
elseif ($edit) 	echo edit($edit);
else			echo liste().$new;

echo '
</form>
';

include("footer.php");

?>
<script>

$(document).ready(function () {

    $(".save").click(function () {
	    id = $(this).attr("ref");
		val = $('.changeVal-'+id).val();

		 console.log(val+' # '+id);

	    request = $.ajax({
	        url: "Update.php",
	        type: "post",
	        data: "pos=value&data="+val+"&id="+id+"&feld=<?php echo $tid; ?>&table=<?php echo $table; ?>",
	        success: function(data) {
				$('.save'+id).removeClass('btn-danger');
				// console.log(data);
  			}
	    });
    });

    $(".chngeValue").on("input",function () {
	    id = $(this).attr("ref");
	    $('.save'+id).addClass('btn-danger');
    });

/*
    $(".chngeValue").change(function () {
	    val = $(this).val();
	    id = $(this).attr("ref");

		// console.log(val+' # '+id);

	    request = $.ajax({
	        url: "Update.php",
	        type: "post",
	        data: "pos=value&data="+val+"&id="+id+"&feld=<?php echo $tid; ?>&table=<?php echo $table; ?>",
	        success: function(data) {
				// console.log(data);
  			}
	    });
    });
*/

});


</script>