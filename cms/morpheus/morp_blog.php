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
$myauth = 22;

$redaktion_in = 'in';
$blog_active = ' class="active"';

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
$back = isset($_POST["back"]) ? $_POST["back"] : 0;
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
$qs		= $_GET["qs"];
$col	= $_GET["col"];
$setval	= $_GET["val"];
$filter	= $_GET["f"];
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
$vis = isset($_GET["vis"]) ? $_GET["vis"] : 0;
$sichtbar = isset($_GET["sichtbar"]) ? $_GET["sichtbar"] : 0;
//// EDIT_SKRIPT
$um_wen_gehts 	= "Blog";
$titel			= "My Blog";
///////////////////////////////////////////////////////////////////////////////////////

include("editor_edit.php");

$new = '<a href="?neu=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a>';
$katLink = '<a href="morp_blog_kat.php" class="btn btn-success"><i class="fa fa-rss-square"></i> Kategorie verwalten</a>';

echo '<div id=vorschau>
	<h2>'.$titel.'</h2>

	'. ($edit || $neu ? '<p><a href="?">&laquo; zur&uuml;ck</a></p>' : '') .'
	<form action="" onsubmit="" name="verwaltung" method="post">
'.($edit || $neu ? '' : '<br>'.$new.$katLink).'
'.(!$edit && !$neu ? '' : '').'
';

// print_r($_POST);

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

$table = "morp_blog";
$tid = "fBlogID";
$nameField = "fTitle";

global $imgFolder, $imgFolderShort;
$imgFolder = "../images/blog/";
$imgFolderShort = "blog/";

/////////////////////////////////////////////////////////////////////////////////////////////////////
#$sql = "ALTER TABLE  $table ADD  `fFirmenFilterID` INT( 11 ) NOT NULL";
#safe_query($sql);
/////////////////////////////////////////////////////////////////////////////////////////////////////

/**** Array der Datenfelder und Forms werden geladen ***/
/**** diese werden ausgelagert, damit auch der PDF Creator darauf zugreifen kann ***/
$file = $_SERVER["SCRIPT_NAME"];
$path_details=pathinfo($file);
$incl = $path_details["filename"].'_arr.php';
include($incl);
/**** _________________ ****/

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////


function liste() {
	global $arr_form, $table, $tid, $filter, $nameField;

	//// EDIT_SKRIPT
	$ord = 'fDatum DESC';
	$anz = $nameField;
	$anz2 = 'fDatum';

	////////////////////
	$where = 1;

	$echo .= '<p>&nbsp;</p><table class="autocol p20 newTable">';

	$sql = "SELECT * FROM $table WHERE $where ORDER BY ".$ord."";
	$res = safe_query($sql);
	// echo mysqli_num_rows($res);

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$tid;
		$sichtbar = $row->sichtbar;
		$echo .= '			<tr>
			<td width="50" align="center"><a href="?edit='.$edit.'" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i></a></td>
			<td><p><a href="?edit='.$edit.'">'.$row->$anz.' </a></p></td>
			<td><p>'.substr(strip_tags($row->fText),0,100).'</p></td>
			<td><p>'.euro_dat($row->$anz2).'</p></td>
			<td>
				<a href="?vis='.$edit.'&sichtbar='.($row->sichtbar ? 0 : 1).'"><i class="fa fa-eye'.($row->sichtbar ? '' : '-slash gray').'"></i></a>
			</td>

			<td width="50" align="center"><a href="?del='.$edit.'" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>
		</tr>
';
	}

	$echo .= '</table><p>&nbsp;</p>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function edit($edit) {
	global $arr_form, $table, $tid, $imgFolder, $imgFolderShort;

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


	$sel = '';
	$sql = "SELECT * FROM morp_blog_kat t1, morp_blog_kat_assign t2 WHERE t2.fBlogID=$edit AND t1.fBlogKatID=t2.fBlogKatID ORDER BY fKat";
	$res = safe_query($sql);
	$id_name = "fBlogKatID";
	while ($row = mysqli_fetch_object($res)) {	
		$assID = $row->assID;
		$fKat = $row->fKat;
		$fBlogKatID = $row->fBlogKatID;
		$sel .= '				$("#'.$id_name.'").multiList("select", "'.$fBlogKatID.'");
';
		
	}

	$echo .= '
	
		<div class="form-group multi">
			<label for="'.$id_name.'">Kategorien</label>
			<ul name="'.$id_name.'" id="'.$id_name.'">
				'.multiselect($edit, "morp_blog_kat", $id_name, "fKat", "fLanguage=1").'
			</ul>
			<button onclick="$(\'#'.$id_name.'\').multiList(\'selectAll\'); return false;" >Select all</button>
			<button onclick="$(\'#'.$id_name.'\').multiList(\'unselectAll\'); return false;" >Deselect all</button>
		</div>

		<script>
			$("#'.$id_name.'").multiList();
'.$sel.'
		</script>

		';


	$echo .= '
		</div>
		
		<input type="hidden" value="0" name="back" id="back" />

		<div class="row" style="margin:2em 0;">
				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
				<button type="submit" id="savebtn2" value="hier" class="btn btn-info"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>
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
	$neu = isset($_POST["neu"]) ? $_POST["neu"] : 0;
	
	// if($neu) $zusatz = ", fDatum='".(date("Y-m-d"))."'";
	$zusatz = "";
		
	$edit = saveMorpheusForm($edit, $neu, 0, $zusatz);
	unset($neu);
	
	$sql = "DELETE FROM morp_blog_kat_assign WHERE fBlogID=$edit";
	$res = safe_query($sql);
	$kat = $_POST["fBlogKatID"];
	print_r($kat);
	foreach($kat as $val) {
		$sql = "INSERT morp_blog_kat_assign SET fBlogID=$edit, fBlogKatID=$val";
		$res = safe_query($sql);
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
}

elseif ($delete) {
	$sql = "DELETE FROM $table WHERE $tid=$delete";
	$res = safe_query($sql);
}

elseif ($delimg) {
	deleteImage($delimg, $edit, $imgFolder);
}
elseif ($vis) {
	$sql = "UPDATE $table SET sichtbar=$sichtbar WHERE $tid=".$vis;
	safe_query($sql);
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
	  $(".form-control").on("change", function() {
	  	$("#savebtn").addClass("btn-danger");
	  	$("#savebtn2").addClass("btn-danger");
	  	$("#savebtn3").addClass("btn-danger");
	  });
	  $("#savebtn2, #savebtn3").on("click", function() {
	  	$("#back").val(1);
	  });
	  $('form input').keydown(function (e) {
    		if (e.keyCode == 13) {
        		e.preventDefault();
        		return false;
    		}
		});
</script>
