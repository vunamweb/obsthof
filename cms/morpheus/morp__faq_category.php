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
$redaktion_in = 'in';
include("cms_include.inc");

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

# print_r($_SESSION);
# print_r($_REQUEST);

///////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
//// EDIT_SKRIPT
$um_wen_gehts 	= "Glossary";
$titel			= "Glossary admin";
///////////////////////////////////////////////////////////////////////////////////////
$table 	= 'morp_faq_category';
$tid 		= 'catId';
$nameField	= "category";
$sortField	= 'reihenfolge';

global $imgFolder, $imgFolderShort;
$imgFolder = "../images/faq/";
$imgFolderShort = "faq/";

///////////////////////////////////////////////////////////////////////////////////////

// $new = '<a href="?neu=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a>';
$new = '<p><a href="?new=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a></p>';

echo '<div id=vorschau>
	<h2>'.$titel.' &nbsp; <img src="images/flag-de.jpg" style="height:30px;"></h2>

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
//	array("reihenfolge", "Reihenfolge", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	array("category", "Category", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
 	// array("faq", "Text", '<textarea class="form-control" name="#n#">#v#</textarea>'),
 	// array("catId",  "Category", 'sel2', $table2, 'category', $tid2),
	// array("img1", "Foto<br>600px Breite", 'foto', 'image', 'imgname', 6, 'gid'),
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
	global $arr_form, $table, $tid, $filter, $nameField, $sortField, $imgFolderShort;
	global $table2, $tid2, $sortField2;

	//// EDIT_SKRIPT
	$ord = "$sortField";
	$anz = $nameField;

	////////////////////
	$where = " t1.$tid2=t2.$tid2 ";
	$where = "1";

	$echo .= '<p>&nbsp;</p>

<div id="sortable" class="grid muuri">

';

	$sql = "SELECT * FROM $table WHERE $where ORDER BY ".$ord."";
	$res = safe_query($sql);
	// echo mysqli_num_rows($res);

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$tid;
		$echo .= '
	<div class="zeile item row"  id="'.$row->$tid.'">
			<div class="col-md-1">
				<a href="?edit='.$edit.'">
					<span class=" ml2 btn btn-primary"><i class="fa fa-pencil-square-o"></i><span class="small light"> &nbsp; '.$row->$tid.'</span>
				</a>
			</div>
			<div class="col-md-1">
				<a href="?edit='.$edit.'">'.$row->$anz.' </a>
			</div>
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
	global $arr_form, $table, $tid, $imgFolder, $imgFolderShort, $um_wen_gehts;

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
		$echo .= setMorpheusForm($row, $arr, $imgFolderShort, $edit, 'morp__faq', $tid);
	}

	$echo .= '</div>
		</div>

				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
				<button type="submit" id="savebtn2" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>
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
	repair();
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

	repair();
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

<script>

$( function() {
	var grid = new Muuri('.grid', {
		dragEnabled: true,
		dragAxis: 'y',
		threshold: 10,
		action: 'swap',
		distance: 0,
		delay: 100,
		layoutOnResize: true,
		setWidth: true,
		setHeight: true,
		sortData: {
			foo: function (item, element) {
				//console.log(item);
			},
			bar: function (item, element) {
				//console.log(77);
			}
  		}
	});

	grid.on('dragEnd', function (item) {

		var order = grid.getItems().map(item => item.getElement().getAttribute('id'));

		console.log(order);

		pos = "<?php echo $sortField; ?>";
		feld = "<?php echo $tid; ?>";
		table = "<?php echo $table; ?>";

	    request = $.ajax({
	        url: "UpdatePos.php",
	        type: "post",
	        data: "data="+order+"&pos="+pos+"&feld="+feld+"&table="+table+"&id=<?php echo $edit; ?>",
	        success: function(data) {
				console.log(data);
  			}
	    });

		// MuuriPosition = ($.inArray(ref, order));

	});
});
</script>



