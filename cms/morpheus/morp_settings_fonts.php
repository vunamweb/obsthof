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
$conf5_active = ' class="active"';

include("cms_include.inc");

?>
<link rel="stylesheet" href="../css/allfonts.css" type="text/css">
<?php
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

# print_r($_SESSION);
# print_r($_REQUEST);

global $arr_form, $table, $tid, $filter, $previewtext, $previewtextsize, $filter;

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



/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

$table = "morp_settings_fonts";
$tid = "fontID";
$nameField = "name";

/////////////////////////////////////////////////////////////////////////////////////////////////////
#$sql = "ALTER TABLE  $table ADD  `fFirmenFilterID` INT( 11 ) NOT NULL";
#safe_query($sql);
/////////////////////////////////////////////////////////////////////////////////////////////////////
//// EDIT_SKRIPT
$um_wen_gehts 	= "Settings CSS";
$titel			= "Settings CSS";
///////////////////////////////////////////////////////////////////////////////////////


$filter = isset($_POST["filter"]) ? $_POST["filter"] : 0;


if(isset($_POST["previewtext"])) {
	$previewtext = $_POST["previewtext"];
	$_SESSION["previewtext"] = $previewtext;
}
else $previewtext = isset($_SESSION["previewtext"]) ? $_SESSION["previewtext"] : 'So wirkt dieser Font - Lorem Ipsum - VERSAL';

if(isset($_POST["previewtextsize"])) {
	$previewtextsize = $_POST["previewtextsize"];
	$_SESSION["previewtextsize"] = $previewtextsize;
}
else $previewtextsize = isset($_SESSION["previewtextsize"]) ? $_SESSION["previewtextsize"] : 16;


// $new = '<a href="?neu=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a>';
$new = '';


/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
// SETTINGS FOR FILTER AND TEXT INPUTS LIKE SIZES ETC

$sql = "SELECT * FROM $table WHERE 1 ORDER BY name";
$res = safe_query($sql);
mysqli_num_rows($res);
$fontSelect = '';
$fontSelectArray = array();

while ($row = mysqli_fetch_object($res)) {
	$short = explode(" ",$row->name);
	$short = $short[0];
	if(in_array($short, $fontSelectArray)) {}
	else {
		$fontSelectArray[] = $short;
		$fontSelect .= '<option value="'.$short.'"'.($filter == $short ? ' selected' : '').'>'.$row->name.'</option>';
	}
}

$a = array(10,12,14,16,20,26,32,40,50,60);
$sizeSelect = '';
foreach($a as $val) { $sizeSelect .= '<option value="'.$val.'"'.($previewtextsize == $val ? ' selected' : '').'>'.$val.'</option>'; }

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


echo '<div id=vorschau>
	<h2>'.$titel.'</h2>

	'. ($edit || $neu ? '<p><a href="?">&laquo; zur&uuml;ck</a></p>' : '') .'

'.(($edit || $neu) && !$save ? '' : '<p style="margin-top:1em;"><a href="?neu=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a></p>
	<form name="refresh" method="post">
		<div class="row">
			<div class="col-sm-7">
				<textarea name="previewtext" id="previewtext" style="width:100%; height:50px;">'.$previewtext.'</textarea>
			</div>
			<div class="col-sm-3">
				<select name="filter" id="filter" style="width:100%;" onChange="document.refresh.submit()"><option value="">alle</option><option value="selected"'.($filter == "selected" ? " selected" : '').'>ausgewählte Fonts</option>'.$fontSelect.'</select>
			</div>
			<div class="col-sm-1">
				<select name="previewtextsize" id="previewtextsize" style="width:50px;" onChange="document.refresh.submit()">'.$sizeSelect.'</select>
			</div>
			<div class="col-sm-1">
				<button class="btn btn-info" name="previewtextsend" id="previewtextsend"><i class="fa fa-refresh"></i></button>
			</div>
		</div>
	</form>
').'
'.(!$edit && !$neu ? '' : '').'

	<form action="" onsubmit="" name="verwaltung" method="post">

';

// print_r($_POST);


$arr_form = array(
	array("name", "Beschreibung", '<input type="Text" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">'),
	array("fontClass", "Klassen Name", '<input type="Text" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">'),
	array("fontWeight", "Weight (Bold, normal, 300, 600, 700)", '<input type="Text" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">'),
	array("sichtbar", "Klassen Name", '<input type="checkbox" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#">'),
	array("value", "Wert", '<textarea class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">#v#</textarea>'),
);
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////


function liste() {
	global $arr_form, $table, $tid, $filter, $nameField, $previewtext, $previewtextsize, $filter;

	//// EDIT_SKRIPT
	$ord = 'name';
	$anz = $nameField;
	$anz2 = 'value';

	////////////////////
	$where = 1;
	if($filter == "selected") $where = "sichtbar=1";
	else if($filter) $where = "name LIKE '$filter%'";

	$echo .= '<p>&nbsp;</p>

	<div class="row">
';

	$sql = "SELECT * FROM $table WHERE $where ORDER BY ".$ord."";
	$res = safe_query($sql);
	// echo mysqli_num_rows($res);

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$tid;
		$echo .= '			<tr>
			<div class="col-md-4 col-sm-6">
				<input type="checkbox" value="'.$edit.'" class="form-control sichtbar" name="vi'.$edit.'" id="vi'.$edit.'" col="sichtbar" '.($row->sichtbar ? ' checked' : '').' />
				<a href="?edit='.$edit.'" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i> '.$row->$anz.' </a>
				<p style="font-family:\''.$row->fontClass.'\'; font-weight:'.$row->fontWeight.'; font-size:'.$previewtextsize.'px;">'.$previewtext.'</p>
			</div>
';
	}

	$echo .= '</div><p>&nbsp;</p>';

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

    $(".sichtbar").click(function () {
		id = $(this).val();
		val = $(this).is(':checked');
		col = $(this).attr("col");

		// console.log(id+' # '+val+' # '+col);

		if(val == true) val = 1;
		else val = 0;

	    request = $.ajax({
	        url: "Update.php",
	        type: "post",
	        data: "pos="+col+"&data="+val+"&id="+id+"&feld=<?php echo $tid; ?>&table=<?php echo $table; ?>",
	        success: function(data) {
				//console.log("saved db");

			    request = $.ajax({
			        url: "UpdateCSS.php",
			        type: "post",
			        data: "",
			        success: function(data) {
				        //console.log(data);
						//console.log("saved CSS");
		  			}
			    });

				// console.log(data);
  			}
	    });

    });

// previewtext

/*
    $(".chngeValue").on("input",function () {
	    id = $(this).attr("ref");
	    col = $(this).attr("col");
		 console.log('col: '+col+' # '+id);
	    $('.save-'+col+id).addClass('btn-danger');
    });
*/

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