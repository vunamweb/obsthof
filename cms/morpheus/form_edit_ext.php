<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #
$form = '';

include("cms_include.inc");

$db 	= "morp_cms_form_field";
$getid 	= "fid";

# get requests
$del	= $_GET["del"];
$edit	= $_REQUEST["edit"];
$save	= $_REQUEST["save"];
$sort	= $_REQUEST["sort"];
$sortid	= $_REQUEST["sortid"];
$stelle = $_REQUEST["stelle"];
$brick  = $_REQUEST["brickname"];
$ffidsav= $_REQUEST["ffidsave"];

$einfuegen = $_REQUEST["einfuegen"];
/*
# # # # # # # !!!!!!!!!!! name einsetzen
$query = "SELECT * FROM $db where fid='$edit' ORDER BY reihenfolge";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
# $ort = $row->fname;
# # # # # # # !!!!!!!!!!! # # # # # # # !!!!!!!!!!!
*/
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

if ($save && $brick) {
	$brickname 	= $_POST["brickname"];
	$stelle 	= $_POST["stelle"];
	$set 		= " art='$brickname', reihenfolge=$stelle, fid=$edit";
	echo $query 		= "insert $db set " .$set;
	$result = safe_query($query);
	$c = mysqli_insert_id($mylink);
	protokoll($uid, $db, $c, "neu");
}
elseif ($del) {
	$query 	= "DELETE FROM $db WHERE ffid=" .$del;
	$result = safe_query($query);
	protokoll($uid, $db, $edit, "delete");
}
elseif ($ffidsav) {
	$ffid 	= $_POST["ffid"];
	$stelle = $_POST["reihenfolge"];
	$feld 	= $_POST["feld"];
	$desc 	= $_POST["desc"];
	$size	= $_POST["size"] ? $_POST["size"] : 0;
	$parent = $_POST["parent"];
	$klasse	= $_POST["klasse"];
	$fehler = $_POST["fehler"];
	$email  = $_POST["email"];
	$cont 	= $_POST["cont"];
	$auswahl = trim($_POST["auswahl"]);
	$hilfe 	= trim($_POST["hilfe"]);
	$pflicht = $_POST["pflicht"];
	$spalte = $_POST["spalte"];

	$set 		= " reihenfolge='$stelle', fehler='$fehler', cont='$cont', feld='$feld', klasse='$klasse', size='$size', parent='$parent', auswahl='$auswahl', `desc`='$desc', hilfe='$hilfe', spalte='$spalte', pflicht='". ($pflicht ? 1 : 0) ."', email='". ($email ? 1 : 0) ."'";
	$query 		= "UPDATE $db set " .$set . " WHERE ffid=$ffid";
	$result 	= safe_query($query);
	protokoll($uid, $db, $ffid, "edit");
}
elseif ($setdb = $_GET["setdb"]) {
	$query 	= "SELECT feld FROM $db WHERE fid=" .$edit;
	$res	= safe_query($query);
    $arr1	= array();
    $arr2	= array();
	while ($row = mysqli_fetch_object($res)) {
        if ($row->feld) $arr1[] = strtolower($row->feld);
    }

	$res 	= safe_query("SHOW COLUMNS FROM morp_cms_form_auswertung");
    while ($row = mysqli_fetch_object($res)) {
        $arr2[] = $row->Field;
    }

	$arr = array_diff($arr1, $arr2);

	foreach($arr as $val) {
		if ($val) {
			$sql = 'ALTER TABLE `morp_cms_form_auswertung` ADD `'.$val.'` VARCHAR( 255 ) NULL DEFAULT NULL ';
			safe_query($sql);
		}
	}
	$warn = "<h2>DATENBANK gesetzt</h2>";
}
elseif ($_REQUEST["repair"]) {
	$arr 		= array();
	$xx 		= 0;
	$sql  		= "SELECT * FROM $db WHERE fid=$edit ORDER BY reihenfolge";
	$res 		= safe_query($sql);

	while ($rw = mysqli_fetch_object($res)) $arr[] = $rw->ffid;

	foreach ($arr as $val) {
		$xx++;
		$sql  = "update $db set reihenfolge=$xx where ffid=$val";
		$res = safe_query($sql);
	}
}

elseif ($sort) {
	if ($sort == "up") $s2 = $sortid - 1;
	else $s2 = $sortid + 1;

	$sort_    = array($sortid, $s2);
	$sort_new = array($s2, $sortid);
	$sort_arr = array();

	for($i=0; $i<=1; $i++) {
		$query  = "SELECT * FROM $db WHERE fid=$edit AND reihenfolge=$sort_[$i]";
		$result = safe_query($query);
		$row = mysqli_fetch_object($result);
		$sort_arr[] = $row->ffid;
	}

	for($i=0; $i<=1; $i++) {
		$query  = "update $db set reihenfolge=$sort_new[$i] WHERE ffid=$sort_arr[$i]";
		safe_query($query);
	}
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

// eingabearten
$eingabe = array("Zeichen"=>"", "Zahlen"=>"number", "E-Mail"=>"email");

$sql	= "SELECT * FROM $db WHERE fid=$edit ORDER BY reihenfolge";
$res 	= safe_query($sql);
$y 		= mysqli_num_rows($res);
$form  .= '<div id="sortable">';

if (!$ffidsav) $thid = $_GET["ffid"];;

while ($row = mysqli_fetch_object($res)) {
	$opt = array("Dropdown", "Radiobutton");
	$counter++;

	$ffid = isset($_GET["ffid"]) ? $_GET["ffid"] : 0;

	if ($thid == $row->ffid || $c == $row->ffid) {
		$art = $row->art;
		if (isin("^Freitext", $art)) $form .= '
<div class="container-fluid" style="background:#e2e2e2;padding:12px;" id="z_'.$row->ffid.'">
	<form method="post">
		<input type=hidden name="edit" value='.$edit.'><input type=hidden name="ffid" value='.$row->ffid.'>
		<input type="hidden" name="stelle" value="'.$row->reihenfolge.'">
		<input type="hidden" name="reihenfolge" value="'.$row->reihenfolge.'" style="width:50px;">
		
		<div class="row">
			<div class="col-md-1">
				<em style="background:#e2e2e2; width:100px;display:block;padding-left:10px;">'.$row->art.'</em>
			</div>			
			<div class="col-md-10">
				<textarea cols="" rows="4" name="hilfe" style="width:400px;">'.$row->hilfe.'</textarea>
			</div>
			<div class="col-md-1">
				<input type="submit" name="ffidsave" value="speichern">
			</div>
		</div>
	</form>
</div>
	';

		elseif (isin("^Fieldset", $art)) $form .= '
<div class="container-fluid" style="background:#e2e2e2;padding:12px;" id="z_'.$row->ffid.'">
	<form method="post">
		<input type=hidden name="edit" value='.$edit.'>
		<input type=hidden name="ffid" value='.$row->ffid.'>
		<input type=hidden name="stelle" value="'.$row->reihenfolge.'">
		<input type="hidden" name="reihenfolge" value="'.$row->reihenfolge.'">
	
		<div class="row">
			<div class="col-md-1">
				<em style="background:#e2e2e2; width:100px;display:block;padding-left:10px;">'.$row->art.'</em>
			</div>
			<div class="col-md-4">
				'. (isin("start", $art) ? 'Fieldset (kurz/eindeutig/kein Leerzeichen)
			</div>
			<div class="col-md-4">
				<input type="Text" name="feld" value="'.$row->feld.'" style="background:#e2e2e2;">' : '') .'
			</div>
			<div class="col-md-2">
				<input type="submit" name="ffidsave" value="speichern">
			</div>
		</div>
	</form>
</div>
	';

		elseif (isin("^Ende", $art)) $form .= '
<div class="container-fluid" style="background:#e2e2e2;padding:12px;" id="z_'.$row->ffid.'">
	<form method="post">
	<input type=hidden name="edit" value='.$edit.'><input type=hidden name="ffid" value='.$row->ffid.'><input type=hidden name="stelle" value="'.$row->reihenfolge.'">
		<em style="background:#e2e2e2; width:100px;display:block;padding-left:10px;">'.$row->art.'</em><br>Reihenfolge: <input type="Text" name="reihenfolge" value="'.$row->reihenfolge.'" style="width:50px;">
		<br>'. (isin("start", $art) ? 'Fieldset (kurz/eindeutig/kein Leerzeichen)<br>
			<input type="Text" name="feld" value="'.$row->feld.'" style="background:#e2e2e2;">' : '') .'
		&nbsp;<br><input type="submit" name="ffidsave" value="speichern">
	</form>
</div>
	';

		elseif($brick || $ffid) {
			$set = $row->cont;
			foreach($eingabe as $key=>$val) {
					$dd .= '<option value="'.$val.'"'. ($set == $val ? ' selected' : '') .'>'.$key.'</option>';
			}
			$dd = '<select name="cont" style="width:100px;">'.$dd.'</select>';

			$form .= '
<div class="container-fluid" style="background:#d0ecf5;padding:12px;" id="z_'.$row->ffid.'">
	<form method="post">
		<div class="col-md-12">
			<p style="text-transform:uppercase; font-weight:bold;">'.$set.'</p>
		</div>
		<div class="col-md-2">
			<input type=hidden name="edit" value='.$edit.'>
			<input type=hidden name="ffid" value='.$row->ffid.'>
			<input type="hidden" name="reihenfolge" value="'.$row->reihenfolge.'">

			<em style="font-weight:bold;"> &nbsp; '.$row->art.'</em><br>
		</div>
		<div class="col-md-2">
			<input type="Text" name="desc" value="'.htmlspecialchars($row->desc).'"><br/>
			Bezeichnung / Anzeige<br>
		</div>
		<div class="col-md-3">
			<input type="Text" name="feld" value="'.$row->feld.'"><br>
			Feldname (kein Leerzeichen)
		</div>
		<div class="col-md-2">
			<input type="text" name="klasse" value="'. $row->klasse .'"><br>
			Class / CSS
		</div>
		<div class="col-md-1">
			<input type="checkbox" name="pflicht" value="1" '. ($row->pflicht == 1 ? 'checked' : '') .'> <br/>Pflichtfeld
		</div>'.

		(in_array($row->art, $opt) ? '
		<div class="col-md-12">
				W&auml;hlbare Optionen<br><textarea cols="" rows="4" name="auswahl" style="width:200px;">'.$row->auswahl.'</textarea>
		</div>
			' : '')
			.(
				$art == "Checkbox" ? '
			<div class="col-md-2">
				<input type="text" name="parent" value="'. $row->parent .'" placeholder="Schalte Fieldset frei">
			</div>' : ''
			).
			($art == "Eingabefeld" ? '
<!--				<br>Breite des Eingabefeld (0 bei Standard)<br><input type="text" name="size" value="'. $row->size .'">-->
			' : '')


		.'<div class="col-md-12">
			<input type="submit" name="ffidsave" value="speichern">
		</div>
	</form>
</div>
	'; }

	}
	else {
		unset($sort);
		if ($counter > 1) $sort .= "&nbsp;&nbsp;<a href=\"?stelle=".$counter."&sort=up&sortid=".$row->reihenfolge."&edit=$edit\" title=\"eine Position nach oben\"><li class=\"fa fa-chevron-up\"></li></a>";
		if ($counter < $y) $sort .= "&nbsp;&nbsp;<a href=\"?stelle=".$counter."&sort=down&sortid=".$row->reihenfolge."&edit=$edit\" title=\"eine Position nach unten\"><li class=\"fa fa-chevron-down\"></li></a>\n";

		$text = $row->auswahl ? str_replace("\n", ' | ', $row->auswahl) : $row->hilfe;

		$form .= '

<div class="container-fluid" style="border:solid 1px #ccc; padding:12px;
	'.($row->art == "Fieldset Start" || $row->art == "Fieldset Ende" ? ' background:#ccc' : '').'" id="z_'.$row->ffid.'">
		<div class="col-md-1">
		';

		$form .= ''.$row->reihenfolge.'';

		$form .= '
		</div>
		<div class="col-md-2">
			<a href="?stelle='.$counter.'&ffid='.$row->ffid.'&edit='.$edit.'">'.$row->art.'</a>

		</div>
		<div class="col-md-2">
			'.substr(htmlspecialchars($row->desc),0,60).'
			'.($row->parent ? '// => '.$row->parent : '').'
		</div>
		<div class="col-md-1">
			'. ($row->pflicht ? 'Pflicht' : '') .'
		</div>
		<div class="col-md-1">
			'. $row->klasse .'
		</div>
		<div class="col-md-2">
			'.$sort.'
		</div>
		<div class="col-md-1">
			<a href="?stelle='.$counter.'&ffid='.$row->ffid.'&edit='.$edit.'"><i class="fa fa fa-pencil-square-o"></i></a>
		</div>
		<div class="col-md-1">
			<a href="?stelle='.$counter.'&del='.$row->ffid.'&edit='.$edit.'"><i class="fa fa fa-trash-o"></i></a>
		</div>
</div>
';
	}

	$form .= '

';
}

$form .= '</div>';

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
$counter++;
// _textboxen zusammenstellen

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
//
// stelle select mit bricks zusammen
$select_brick = '<select name="brickname" class="form-control" >
	<option value="">Bitte Formularfeld w&auml;hlen</option>
';

$dir_arr = array("Eingabefeld", "Mitteilungsfeld", "Dropdown", "Checkbox", "Radiobutton", "Datum", "Freitext", "Upload" );
// $dir_arr = array("Eingabefeld", "Mitteilungsfeld", "Dropdown", "Checkbox", "Radiobutton", "Datum", "Freitext", "Freitext Fett", "Fieldset Start", "Fieldset Ende", "Upload" );

foreach($dir_arr as $name) {
	$select_brick .= "<option value='$name'>$name</option>\n";
}

$select_brick .= "</select>\n";
// _select
//

//
// stelle select mit counter zusammen
if (!$counter) $counter = 1;
$select_stelle = '<select name="stelle" style="width:50;" class="form-control">
	<option value="'.$counter.'">'.$counter.'</option>
';

if ($counter > 1) {
	for ($i=1; $i < $counter; $i++) {
		$select_stelle .= "<option value='$i'>$i</option>\n";
	}
}
$select_stelle .= "</select>
";
// _select
//
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
echo "
<div>
	<H2><font color=#cccccc></font>Formular Editor</h2><br/>

	<p>
		<a href=\"formular.php\" title=\"zur&uuml;ck\">" .backlink() ." zur&uuml;ck</a> 		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href=\"?edit=$edit&repair=1\">&raquo; sortierung aktualisieren</a>		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href=\"?edit=$edit&setdb=1\">&raquo; Datenbankeintr&auml;ge setzen</a>
	</p>
</div>
";

// print_r($_REQUEST);

#if (!$descr) $descr = "Pflichtfeld";

echo "

<div>
	<form method=post action=\"\" name=\"content_edit\">
		<input type=\"hidden\" name=\"save\" value=\"1\">
		<input type=\"hidden\" name=\"edit\" value=\"$edit\">
";

	echo '
		<div class="row">
			<div class="col-md-3 col-xs-3">'	.
				$select_brick .'
			</div>
			<div class="col-md-1 col-xs-3">
			' .$select_stelle .'
			</div>
			<div class="col-md-6 col-xs-3">
				<button class="btn btn-info" type="submit" name=”einfuegen" value="1">EINFÜGEN</button>
			</div>
		</div>

	</form>
</div>


';

	echo $form;

	#echo "<br>$show";

?>

</div>

<?php
include("footer.php");
?>

  <script>
  $( function() {
    $( "#sortable" ).sortable({
		start: function(e, ui) {
		    // var old_position = ui.item.index();
		    // console.log(old_position);
		},
		update: function(event, ui) {
			var data = $(this).sortable('serialize');
		    // grabs the new positions now that we've finished sorting
		    var new_position = ui.item.index();
		    // console.log(data);

			pos = "reihenfolge";
			feld = "ffid";
			table = "morp_cms_form_field";

		    request = $.ajax({
		        url: "UpdatePosForm.php",
		        type: "post",
		        data: "data="+data+"&pos="+pos+"&feld="+feld+"&table="+table+"&id=<?php echo $edit; ?>",
		        success: function(msg) {
	                 console.log(msg);
	            }
		    });

		}
	});

    $( "#sortable" ).disableSelection();


  });
  
<?php 
$jump = isset($_GET["ffid"]) ? $_GET["ffid"] : 0; 
if($jump) { ?>
	$(document).ready(function() {
		target = '#z_<?php echo $jump; ?>';
		if (target) {
			$("html, body").animate({
				scrollTop: 0
			}, 0);
			$('html, body').stop().animate({
				scrollTop: $(target).offset().top - 300
			}, 500, 'easeInOutExpo');
		}
	});
<?php }
?>  

</script>