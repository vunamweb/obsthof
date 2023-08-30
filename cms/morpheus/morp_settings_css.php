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
$conf3_active = ' class="active"';

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
$um_wen_gehts 	= "Settings CSS";
$titel			= "Settings CSS";
///////////////////////////////////////////////////////////////////////////////////////

// $new = '<a href="?neu=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a>';
$new = '';

echo '

<style>
	.autocol tr td {
	    padding: 0px;
	}
	input, textarea, select {
	    margin: 0;
		padding: 4px;
	}
	.autocol tr td {
		padding: 0;
		margin: 0;
	}
</style>

<div id=vorschau>
	<h2>'.$titel.'</h2>

	'. ($edit || $neu ? '<p><a href="?">&laquo; zur&uuml;ck</a></p>' : '') .'
	<form action="" onsubmit="" name="verwaltung" method="post">
'.($edit || $neu ? '' : '<p style="margin-top:1em;"><a href="?neu=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a></p>').'
'.(!$edit && !$neu ? '' : '').'
';

// print_r($_POST);

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

$table = "morp_settings_css";
$tid = "id";
$nameField = "name";

/////////////////////////////////////////////////////////////////////////////////////////////////////
#$sql = "ALTER TABLE  $table ADD  `fFirmenFilterID` INT( 11 ) NOT NULL";
#safe_query($sql);
/////////////////////////////////////////////////////////////////////////////////////////////////////

$arr_form = array(
	array("name", "Beschreibung", '<input type="Text" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">'),
	array("className", "Klassen Name", '<input type="Text" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">'),
	array("classVal", "Klassen Value", '<input type="Text" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">'),
	array("value", "Wert", '<input type="Text" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">'),
	array("fontID", "Font", 'sel', 'morp_settings_fonts', 'name'),
);
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////


function liste() {
	global $arr_form, $table, $tid, $filter, $nameField;

	//// EDIT_SKRIPT
	$ord = 'name';
	$anz = $nameField;
	$anz2 = 'value';

	////////////////////
	$where = 1;

	$echo .= '<p>&nbsp;</p><table class="autocol newTable">
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
			<td>
				<input type="text" class="chngeValue className'.$edit.'" ref="'.$edit.'" col="className" value="'.($row->className).'" style="min-width:50%;" />
				<span class="btn btn-info save save-className'.$edit.'" ref="'.$edit.'" col="className"><i class="fa fa-save"></i></span>
			</td>
			<td>
				<input type="text" class="chngeValue classVal'.$edit.'" ref="'.$edit.'" col="classVal" value="'.($row->classVal).'" style="min-width:50%;" />
				<span class="btn btn-info save save-classVal'.$edit.'" ref="'.$edit.'" col="classVal"><i class="fa fa-save"></i></span>
			</td>
			<td>
				<input type="text" class="chngeValue value'.$edit.'" ref="'.$edit.'" col="value" value="'.($row->$anz2).'" style="min-width:50%;" />
				<span class="btn btn-info save save-value'.$edit.'" ref="'.$edit.'" col="value"><i class="fa fa-save"></i></span>
			</td>
			<td>
				<div class="">
					<select class="form-control setfont" name="fontID" id="fontID" ref="'.$edit.'" col="fontID">
						<option value=""></option>
';

			//.pulldown ($row->fontID, 'morp_settings_fonts', 'name', 'fontID').
			$sql = "SELECT * FROM morp_settings_fonts WHERE sichtbar=1 ORDER BY name";
			$rs = safe_query($sql);
			while ($rw = mysqli_fetch_object($rs)) {
				$echo .= '<option value="'.$rw->fontID.'" '.($rw->fontID == $row->fontID ? ' selected' : '').'>'.$rw->name.'</option>';
			}

		$echo .= '</select>
				</div>
			</td>
			<td>
				<a class="btn btn-warning" href="?del='.$edit.'"><i class="fa fa-trash-o"></i></a>
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
	if(neu) unset($neu);
?>
<script>
	var new_url = modifyURLQuery(window.location.href, {neu: null, edit: null});
	console.log(new_url);
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
	    col = $(this).attr("col");
		val = $('.'+col+id).val();

		 console.log(val+' # col: '+col+' # '+id);

	    request = $.ajax({
	        url: "Update.php",
	        type: "post",
	        data: "pos="+col+"&data="+val+"&id="+id+"&feld=<?php echo $tid; ?>&table=<?php echo $table; ?>",
	        success: function(data) {
				$('.save-'+col+id).removeClass('btn-danger');
				// console.log(data);
  			}
	    });
    });

    $(".chngeValue").on("input",function () {
	    id = $(this).attr("ref");
	    col = $(this).attr("col");
		 console.log('col: '+col+' # '+id);
	    $('.save-'+col+id).addClass('btn-danger');
    });

    $(".setfont").change(function () {
	    id = $(this).attr("ref");
	    val = $(this).val();
	    col = $(this).attr("col");
		// console.log('val: '+val+'col: '+col+' # '+id);

	    request = $.ajax({
	        url: "Update.php",
	        type: "post",
	        data: "pos="+col+"&data="+val+"&id="+id+"&feld=<?php echo $tid; ?>&table=<?php echo $table; ?>",
	        success: function(data) {
				$('.save-'+col+id).removeClass('btn-danger');
				// console.log(data);
  			}
	    });
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