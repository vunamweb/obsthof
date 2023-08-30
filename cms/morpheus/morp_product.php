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
$myauth = 52;
$prod_in = 'in';
$p_active = 'active';
include("cms_include.inc");
include("_tinymce.php");
// include("editor_css_edit.php");

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

# print_r($_SESSION);
# print_r($_REQUEST);

///////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
//// EDIT_SKRIPT
$um_wen_gehts 	= "Produkte";
$titel			= "Produkte";
///////////////////////////////////////////////////////////////////////////////////////
$table 		= 'morp_product';
$tid 		= 'pID';
$nameField 	= "product";
$sortField 	= 'reihenfolge';

$table2 	= 'morp_product_company';
$tid2 		= 'pcID';
$nameField2	= "company";
$sortField2	= 'reihenfolgeC';

$table3 	= 'morp_product_wg';
$tid3 		= 'wgID';
$nameField3	= "wg";
$sortField3	= 'reihenfolgeWG';

global $imgFolder, $imgFolderShort;
global $imgFolderPDF, $imgFolderShortPDF;
$imgFolder = "../images/products/";
$imgFolderShort = "products/";
$imgFolderPDF = "../images/pdf/";
$imgFolderShortPDF = "pdf/";

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
// for($i=1;$i<=10;$i++) {
// 	echo $sql = "ALTER TABLE $table ADD  `txt_example$i` VARCHAR(100) NULL DEFAULT NULL";
// 	 safe_query($sql);
// }
/////////////////////////////////////////////////////////////////////////////////////////////////////

$arr_form = array(
//	array("reihenfolge", "Reihenfolge", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	array("product", "Produkt Name intern", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
 	array("pcID",  "Hersteller", 'sel2', $table2, $nameField2, $tid2),
	array("wgID",  "Warengruppe", 'sel2', $table3, $nameField3, $tid3),
	// array("headline", "Headline", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
 	array("headline", "Headline", '<textarea class="form-control" name="#n#">#v#</textarea>'),
	array("text", "Text", '<textarea class="form-control summernote" name="#n#">#v#</textarea>'),
	array("funktionen", "Funktionen", '<textarea class="form-control" name="#n#">#v#</textarea>'),
	
	array("", "CONFIG", '</div><div class="col-md-6">'),
	
	array("video1", "YouTube Code", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	array("shopUrl", "Url zum Shop", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	array("pUrl", "Url zum Hersteller", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
 	// array("faq", "Text", '<textarea class="form-control" name="#n#">#v#</textarea>'),
	// array("logo", "Logo", 'foto', 'image', 'imgname', 6, 'gid'),
	array("", "CONFIG", '<hr>'),
	
	array("pdf1", "PDF / File 1 Download", 'PDF', 'image', 'imgname', 6, 'gid'),
	array("pdf1Name",  "Bezeichnung PDF", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),

	array("", "CONFIG", '<hr>'),
	
	array("img", "Headerbild", 'foto', 'image', 'imgname', 6, 'gid'),
	// array("img2", "xxx Foto<br>600px Breite", 'foto', 'image', 'imgname', 6, 'gid'),
	
	array("", "CONFIG", '<hr>'),
	
	array("pb",  "Privatbereich", 'chk'),
	array("sh",  "Smart Home", 'chk'),
	array("hg",  "Handel, Gewerbe, Wirtschaft", 'chk'),
	array("be",  "Behörden, öffentliche Einrichtungen", 'chk'),
	array("ws",  "Werteschutz", 'chk'),
	
	array("", "CONFIG", '</div><div class="col-md-3"><hr>'),
	array("img_example1", "Anwendungsbeispiel", 'foto', 'image', 'imgname', 6, 'gid'),
	array("txt_example1", "Anwendungsbeispiel Text", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),

	array("", "CONFIG", '</div><div class="col-md-3"><hr>'),
	array("img_example2", "Anwendungsbeispiel", 'foto', 'image', 'imgname', 6, 'gid'),
	array("txt_example2", "Anwendungsbeispiel Text", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),

	array("", "CONFIG", '</div><div class="col-md-3"><hr>'),
	array("img_example3", "Anwendungsbeispiel", 'foto', 'image', 'imgname', 6, 'gid'),
	array("txt_example3", "Anwendungsbeispiel Text", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	
	array("", "CONFIG", '</div><div class="col-md-3"><hr>'),
	array("img_example4", "Anwendungsbeispiel", 'foto', 'image', 'imgname', 6, 'gid'),
	array("txt_example4", "Anwendungsbeispiel Text", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	
	array("", "CONFIG", '</div><div class="col-md-3"><hr>'),
	array("img_example5", "Anwendungsbeispiel", 'foto', 'image', 'imgname', 6, 'gid'),
	array("txt_example5", "Anwendungsbeispiel Text", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	
	array("", "CONFIG", '</div><div class="col-md-3"><hr>'),
	array("img_example6", "Anwendungsbeispiel", 'foto', 'image', 'imgname', 6, 'gid'),
	array("txt_example6", "Anwendungsbeispiel Text", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	
	array("", "CONFIG", '</div><div class="col-md-3"><hr>'),
	array("img_example7", "Anwendungsbeispiel", 'foto', 'image', 'imgname', 6, 'gid'),
	array("txt_example7", "Anwendungsbeispiel Text", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	
	array("", "CONFIG", '</div><div class="col-md-3"><hr>'),
	array("img_example8", "Anwendungsbeispiel", 'foto', 'image', 'imgname', 6, 'gid'),
	array("txt_example8", "Anwendungsbeispiel Text", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	
	array("", "CONFIG", '</div><div class="col-md-3"><hr>'),
	array("img_example9", "Anwendungsbeispiel", 'foto', 'image', 'imgname', 6, 'gid'),
	array("txt_example9", "Anwendungsbeispiel Text", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	
	array("", "CONFIG", '</div><div class="col-md-3"><hr>'),
	array("img_example10", "Anwendungsbeispiel", 'foto', 'image', 'imgname', 6, 'gid'),
	array("txt_example10", "Anwendungsbeispiel Text", '<input type="Text" value="#v#" class="form-control" name="#n#" />'),
	

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
	global $arr_form, $table, $tid, $filter, $nameField, $sortField, $imgFolderShort,  $table2, $tid2, $nameField2, $sortField2;
	global $table2, $tid2, $sortField2;
	global $table3, $tid3, $nameField3, $anwendungen;

	//// EDIT_SKRIPT
	// $ord = "t1.$sortField2, t1.$sortField";
	$ord = "$sortField , $tid3";
	$anz = $nameField;

	////////////////////
	$where = " t1.$tid2=t2.$tid2 AND  t1.$tid3=t3.$tid3 ";
	$where = " 1 ";
	
	$anwendungen_table = '<p>&nbsp;</p><table style="width:800px"><tr>';
	foreach($anwendungen as $key=>$val) {
		$anwendungen_table .= '<td>'.$val.' (<b>'.$key.'</b>)</td>';
	}
	$echo .= $anwendungen_table . '</table>';
	
	$echo .= '<p>&nbsp;</p>

<div id="sortable" class="grid muuri">

';

	$old_cat = 0;
	
	$sql = "SELECT * FROM $table WHERE $where ORDER BY ".$ord."";
	// $sql = "SELECT * FROM $table t1, $table2 t2, $table3 t3 WHERE $where ORDER BY ".$ord."";
	$res = safe_query($sql);
	// echo mysqli_num_rows($res);

	
	
	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$tid;
		$val2 = $row->$tid2;
		$val3 = $row->$tid3;
		
		// $cat = get_db_field($edit, $nameField2, $table2, $tid2);
		$cat = get_db_field($val3, $nameField3, $table3, $tid3);
		
		// $cat = $row->$tid2;
		// if($old_cat != $cat) {
		// 	$echo .= '<div><hr><h2>'.$row->$nameField2.'</h2></div>';
		// 	
		// 	$old_cat=$cat;
		// }
		
		$anwendungen_table = '<table style="width:100%;"><tr>';
		foreach($anwendungen as $key=>$val) {
			$anwendungen_table .= '<td>'.($row->$key ? $key : '--').'</td>';
		}
		$anwendungen_table .= '</table>';
		
		$echo .= '
	<div class="zeile item row"  id="'.$row->$tid.'">
			<div class="col-md-1">
				<a href="?edit='.$edit.'">
					<span class=" ml2 btn btn-primary"><i class="fa fa-pencil-square-o"></i><span class="small light"> &nbsp; '.$row->$tid.'</span>
				</a>
			</div>
			<div class="col-md-4">
				<a href="?edit='.$edit.'">'.$row->$nameField.' </a>
			</div>
			<div class="col-md-2">
				'.$anwendungen_table.'
			</div>
			<div class="col-md-2">
				<a href="?edit='.$edit.'">'.$cat.' </a>
			</div>
			<div class="col-md-2 text-right">
				<a href="?copy='.$edit.'" class="btn btn-info"><i class="fa fa-copy"></i></a>
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
	global $arr_form, $imgFolder, $imgFolderShort, $um_wen_gehts, $imageNameZusatz;
	global $table, $tid, $nameField;
	global $table2, $tid2, $nameField2;
	global $table3, $tid3, $nameField3;
	global $imgFolderPDF, $imgFolderShortPDF;

	$sql = "SELECT * FROM $table WHERE $tid=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);

	$scriptname = basename(__FILE__);
	$scriptname = substr($scriptname, 0, strlen($scriptname)-4);
	// HIS Special
	$imageNameZusatz = $row->product;
	
	$echo .= '
		<input type="Hidden" name="edit" value="'.$edit.'">
		<input type="Hidden" name="save" value="1">
		<input type="hidden" value="0" name="back" id="back" />
		<div class="row">
			<div class="col-md-6">
	';

	foreach($arr_form as $arr) {
		$echo .= setMorpheusForm($row, $arr, $imgFolderShort, $edit, $scriptname, $tid);
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

	if($neu) {
		$sql = "SELECT $tid FROM $table WHERE 1";
		$res = safe_query($sql);
		$x = mysqli_num_rows($res);
		$x++;
		$zusatz = ", $sortField=$x";
	} else $zusatz = '';
	
	// echo "save";
	$edit = saveMorpheusForm($edit, $neu, 0, $zusatz);

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



