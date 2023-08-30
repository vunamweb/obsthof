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
#$box = 1;
$newsletter_in = 'in';
include("cms_include.inc");
// include("editor.php");
include("newsletter/function.php");


$table 	= "morp_newsletter";
$tid 	= "nlid";
$nameField 	= "nlname";
$sortField 	= 'nlid';

?>
    <script src="js/jquery.sumoselect.js"></script>
    <link href="js/sumoselect.css" rel="stylesheet" />
    <script type="text/javascript">
        $(document).ready(function () {
            window.test = $('.stepsel').SumoSelect({okCancelInMulti:true });
        });
    </script>
<?php
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

function pulldownNL ($tp, $tab, $wname, $wid, $gruppe=0, $spalte=0) {
	if ($gruppe) 	$query = "SELECT * FROM $tab WHERE $spalte=$gruppe ORDER BY $wname";
	else 			$query = "SELECT * FROM $tab ORDER BY $wname";

	// echo $query;

	$result = safe_query($query);

	while ($row = mysqli_fetch_object($result)) {
		if ($row->$wid == $tp) $sel = "selected";
		else $sel = "";

		$nm = $row->$wname;
		$pd .= "<option value=\"" .$row->$wid ."\" $sel>$nm</option>\n";
	}
	return $pd;
}
# dropdown ($_GET["f1"], "f1id", "morp_newsletter_filter_1", "f1name", "f1name")
function dropdown ($sl, $id, $tab, $order, $wname, $nlart=0) {
	if($nlart) $query = "SELECT * FROM $tab WHERE bid=$nlart ORDER BY $order";
	else $query = "SELECT * FROM $tab ORDER BY $order";
	$result = safe_query($query);

	while ($row = mysqli_fetch_object($result)) {
		$active = $row->$id;
		if ($sl == $active) $sel = "selected";
		else $sel = "";

		$nm = $row->$wname;
		$pd .= "<option value=\"" .$row->$id ."\" $sel>$nm</option>\n";
	}
	return $pd;
}

function dropdownM ($sl, $id, $tab, $order, $wname, $nlart=0) {
	if($nlart) $query = "SELECT * FROM $tab WHERE bid=$nlart ORDER BY $order";
	else $query = "SELECT * FROM $tab ORDER BY $order";
	$result = safe_query($query);

	while ($row = mysqli_fetch_object($result)) {
		$active = $row->$id;
		if (in_array($active, $sl)) $sel = "selected";
		else $sel = "";

		$nm = $row->$wname;
		$pd .= "<option value=\"" .$row->$id ."\" $sel>$nm</option>\n";
	}
	return $pd;
}

// print_r($_REQUEST);

global $arr_form, $vorschau, $gruppen_arr, $dir, $f1, $f2, $search, $ascdesc;
$dir = 'http://www.jaguarlandrover-mailings.com/edm/';

// NICHT VERAENDERN ///////////////////////////////////////////////////////////////////
$edit 	= $_REQUEST["edit"];
$dupl 	= $_REQUEST["dupl"];
$delimg = $_REQUEST["delimg"];
$delpdf = $_REQUEST["delpdf"];
$delcsv = $_REQUEST["delcsv"];
$delnm  = $_REQUEST["delnm"];
$neu	= $_REQUEST["neu"];
$save	= $_REQUEST["save"];
$del	= $_REQUEST["del"];
$delete	= $_REQUEST["delete"];
$deletecsv	= $_REQUEST["deletecsv"];
$nlart	= $_REQUEST["nlart"];
$f1		= $_REQUEST["f1"];
$f2		= $_REQUEST["f2"];
//$id		= $_REQUEST["id"];
$search = $_REQUEST["search"];
$ascdesc = $_REQUEST["ascdesc"];
$back = isset($_POST["back"]) ? $_POST["back"] : 0;
	
if(!$ascdesc) $ascdesc = "ASC";
///////////////////////////////////////////////////////////////////////////////////////

$db = "morp_newsletter";
$id = "nlid";

//// EDIT_SKRIPT
$um_wen_gehts 	= "Newsletter";
$titel			= "Newsletter";
$csv = 0;
///////////////////////////////////////////////////////////////////////////////////////

if ($edit || $neu) $vorschau = '<p><a href="newsletter/vorschau.php?nlid='.$edit.'&vt=#vt#" target="_blank">&raquo; Vorschau</a></p>';
else $vorschau = '';

$new = '<p><a href="?neu=1">&raquo; NEUEN Newsletter erstellen</a></p>';

//// EDIT_SKRIPT
// 0 => Feldbezeichnung, 1 => Bezeichnung für Kunden, 2 => Art des Formularfeldes
// nlname	nlsubj	nlmail	nldatum	nlvdatum	nlvzeit	text	nlimg1	nlimg2	edit	layout	sichtbar

// $gruppen_arr = array('','Politik','Pharmazie','Recht','XLSX Liste','TEST');

$arr_form = array(
//	array("versendet", "verteilt", '', 'checkbox', ''),
//	array("f1id", "Nameplate", 'sel', 'morp_newsletter_filter_1', 'f1name'),
//	array("f2id", "status", 'sel', 'morp_newsletter_filter_2', 'f2name'),


//	array("nlart", "brand", '', 'select', array("select brand", "Jaguar", "Land Rover")),

	array("nlname", "Name Newsletter", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("nlsubj", "Betreff", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("nlpreheader", "Preheader", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
#	array("nlmail", "Absender E-Mail", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
#	array("nlmailname", "Absender Name", '<input type="Text" value="#v#" name="#n#" style="#s#">'),

//	array("nlvdatum", "Versand-Datum", '<input type="Text" value="#v#" name="#n#" style="#s#">', 'dat', "nosave"),
//	array("nlvdatum", "Versand-Datum", '<input type="Text" value="#v#" name="#n#" style="#s#">', 'dat'),
#	array("banner", "Banner", 'banner', 'image', 'imgname', 1, 'gid'),
#	array("text", "Mail Text", '<textarea  name="#n#" style="#s#">#v#</textarea>', 'text'),
#	array("platzhalter", "Platzhalter", '<input type="text" name="#n#" style="width:500px;" value="#v#"/>',),
#	array("nldatum", "Erstellt", '<input type="Text" value="#v#" name="#n#" style="#s#">', 'dat'),
#	array("sonderausgabe", "Sonderausgabe ohne Menü", '', 'checkbox', ''),
#	array("pdf1", "PDF 1", 'sel', 'pdf'),
#	array("pdf2", "PDF 2", 'sel', 'pdf'),
#	array("pdf3", "PDF 3", 'sel', 'pdf'),
#	array("pdf4", "PDF 4", 'sel', 'pdf'),
#	array("pdf5", "PDF 5", 'sel', 'pdf'),
#	array("pdf6", "PDF 6", 'sel', 'pdf'),
#	array("csv", "XLSX Verteiler", 'sel', 'csv'),

//	array("img3", "Foto 3", 'sel', 'image', 'imgname', 6, 'gid'),
);
///////////////////////////////////////////////////////////////////////////////////////


#	array("mberechtigung", "Berechtigung (ID: 1 = Zugang)", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
# 	array("ausbildungen", "<strong>Ausbildung EN</strong>", '<textarea cols="80" rows="5" name="#n#">#v#</textarea>'),
# 	array("imgid", "Berechtigung (ID: 1 = Zugang)", 'sel', 'image', 'imgname'),

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function liste($nlart) {
	global $gruppen_arr, $dir, $f1, $f2, $search, $ascdesc;
	//// EDIT_SKRIPT
	$db = "morp_newsletter";
	$id = "nlid";
	$ord = "nlart,nlname ".$ascdesc;
	$anz = "nlname";
	$anz2 = "nldatum";
	$anz3 = "nlvdatum";
	$anz4 = "nlsubj";
	$anz5 = "csv";
	$anz6 = "versendet";
	////////////////////

	$tabelle_start = '
		<table class="autocol p20">
			<tr style="background:#e2e2e2;">
				<td>Name</td>
				<td ></td>
				<td>Betreff</td>
				<td>bearbeiten</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
';
	$tabelle_ende = '</table><p>&nbsp;</p>';

	$echo = '';

	$sql = "SELECT * FROM $db WHERE ".($nlart ? "nlart='".$nlart."'" : 1);
	if($search) $sql .= " AND nlname LIKE '%".$search."%' ";
	$sql .= " ORDER BY ".$ord."";
	$res = safe_query($sql);

	if(mysqli_num_rows($res) <1) return;

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$id;
		
		$echo .= '<tr>
			<td ><p>'.$row->$anz.'</p></td>
			<td align="center"><p><a href="?edit='.$edit.'"><i class="fa fa-cogs"></i></a></p></td>
			<td ><p><a href="?edit='.$edit.'">'.$row->$anz4.'</p></td>
			<td><p><a href="newsletter_edit.php?liste='.$edit.'"><i class="fa fa-pencil-square-o"></i></a></p></td>
			<td style="text-align:center;">
				<button type="button" class="btn btn-success openBtn" ref="'.$edit.'"><i class="fa fa-mobile"></i></button> &nbsp; 
				<a href="../preview.php?nlid='.$edit.'" class="btn btn-info" target="_blank"><i class="fa fa-desktop"></i> &nbsp; <i class="fa fa-rss"></i></a>
			</td>
			<td style="text-align:center;">
				<a href="../testmail.php?nlid='.$edit.'" class="btn btn-danger" target="_blank"  title="send mail"><i class="fa fa-send"></i></a></p></td>
			<td align="center"><p>'.($row->$anz6 ? 'x' : '').'</p></td>

			<td align="center"><p><a href="?dupl='.$edit.'"><i class="fa fa-copy"></i></a></p></td>
			<td valign="middle"><a href="?del='.$edit.'"><i class="fa fa-trash-o"></i></a></td>
		</tr>';
	}

	$echo = $tabelle_start.$echo.$tabelle_ende;

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function edit($edit) {
	global $arr_form, $vorschau, $csv, $table, $tid;

	$sql = "SELECT * FROM $table WHERE $tid=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);

	$insertsubmit = 1;

	$echo .= '
		<input type="Hidden" name="edit" value="'.$edit.'">
		<input type="Hidden" name="save" value="1">
		<input type="hidden" value="0" name="back" id="back" />

		<div class="row" style="margin:2em 0;">
				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
				<button type="submit" id="savebtn2" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>
		</div>

		<div class="row newsletter">
			<div class="col-md-12">

	';

	foreach($arr_form as $arr) {
		$echo .= setMorpheusForm($row, $arr, $imgFolderShort, $edit, $scriptname, $tid);
	}

	$echo .= '</div>
		</div>

				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
				<button type="submit" id="savebtn3" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>
	</div>
';

// <p><a href ="image_folder_upload.php?cedit='.$edit.'&csv=csv"><i class="fa fa-cloud-upload"></i> <i class="fa fa-send"></i> Importieren der Excelliste und automatischer Versand zum eingestellten Versanddatum</a>'.($is ? ' &nbsp; <b>Datensatz: '.$row->csv.' vorhanden !!!</b>' : '').'</p>
// <p>&nbsp;</p>
// <p>&nbsp;</p>
// <p></p>
// <p><a href ="image_folder_upload.php?cedit='.$edit.'&csv=csv"><i class="fa fa-cloud-upload"></i> Import der Excel Versandliste vor Sofort-Versand</a>'.($is ? ' &nbsp; <b>Datensatz: '.$row->csv.' vorhanden !!!</b>' : '').'</p>
// <p></p>
// <!--<p><a href ="newsletter/versenden.php?nlid='.$edit.'&imp=1&live=1"><i class="fa fa-send"></i> VERSAND (kein Test) bei Versand-Datum in der Zukunft</a></p>
// <p></p>-->
// <p><a href ="newsletter/newsletter_set_vt.php"><i class="fa fa-send"></i> SOFORT VERSAND (Excelliste vorher importieren / taggleicher Versand ('.date("d.m.Y").')</a></p>
// 
// ';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function neu() {
	global $arr_form;

	$x = 0;

	$echo .= '<input type="Hidden" name="neu" value="1"><input type="Hidden" name="save" value="1">

	<table class="autocol p20">';

	foreach($arr_form as $arr) {
		if ($x <= 5) $echo .= '<tr>
			<td>'.$arr[1].':</td>
			<td>'. str_replace(array("#v#", "#n#", "#s#"), array($row->$arr[0], $arr[0], 'width:400px;'), $arr[2]).'</td>
		</tr>';
		$x++;
	}

	$echo .= '<tr>
		<td></td>
		<td><input type="submit" name="speichern" value="save" class="save"></td>
	</tr>
</table>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function verteiler() {
	//// EDIT_SKRIPT
	$db = "morp_newsletter_vt";
	$id = "vid";
	$ord = "vid";
	$anz = "email";
	$anz2 = "firma";
	$anz3 = "nlid";
	////////////////////

	$sql = "SELECT * FROM $db WHERE versand<1";
	$res = safe_query($sql);

	$echo .= "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>Noch zu versendende Mails: <b>".mysqli_num_rows($res)."</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='newsletter_vt_pruef.php'>&raquo; Details</a>";


	return $echo;
}

function gruppen_pd($gruppenid) {
	global $gruppen_arr;

	$pd = '<select name="gruppenid" onchange="document.verwaltung.submit();">';

	foreach($gruppen_arr as $key=>$val) {
		$pd .= '<option value="'.$key.'"'.($key==$gruppenid ? ' selected' : '').'>'.($val ? $val : 'Alle').'</option>';
	}
	return $pd .= '</select>';
}
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


echo '<div id=vorschau>
	<h2>'.$titel.'</h2>

		'. ($edit || $neu ? '<p><a href="?">&laquo; zurück zur Übersicht</a></p>' : '') .'
	<form action="" onsubmit="" name="verwaltung" method="'. ($edit || $neu ? 'post' : 'get').'">
		';

if(!$edit && !$neu && !$del) {
	// echo '<input type="text" name="search" value="'.$search.'" placeholder="Suche nach Name" />  &nbsp; &nbsp; <input type="submit" name="submit" value="suchen" />';
}

/////////////////////////////////////////////////////////////////////////////////////////////////////

if ($save) {
	$neu = isset($_POST["neu"]) ? $_POST["neu"] : 0;

	if($neu) {
		$sql = "SELECT * FROM $table WHERE 1";
		$res = safe_query($sql);
		$x = mysqli_num_rows($res);
		$zusatz = ", reihenfolge=".$x++;
	}
	else $zusatz = "";

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
elseif ($dupl) {
	global $arr_form;

	//// EDIT_SKRIPT
	$db = "morp_newsletter";
	$id = "nlid";
	/////////////////////

	$sql 	= "SELECT * FROM $db WHERE $id=".$dupl."";
	$res 	= safe_query($sql);
	$row 	= mysqli_fetch_object($res);
	$folder = $row->nlid;

	$sql = '';

	foreach($arr_form as $arr) {
		$tmp = $arr[0];
		$art = $arr[3];
		$val = $row->$tmp;

		// if ($art == "dat") $sql .= $tmp. "='" .us_dat($val). "', ";
		if ($tmp == "nlvdatum") {}
		elseif ($tmp == "nldatum") $sql .= $tmp. "='" .date("Y-m-d"). "', ";
		elseif ($tmp == "nlsubj") {}
		elseif ($tmp != "nlname") $sql .= $tmp. "='" .$val. "', ";
	}

	$sql .= "nlname='', nlsubj='new Subject!!!!'";

	$sql  = "INSERT $db set $sql";
	$res  = safe_query($sql);
	$newfolder = mysqli_insert_id($mylink);
	// $newfolder = 4;

	$sql = "SELECT * FROM morp_newsletter_cont WHERE nlid=".$dupl." ORDER by nlsort";
	$res = safe_query($sql);

	/*** Images Ordner erstellen ******/
	if(!is_dir('../img/'.$newfolder)) mkdir('../img/'.$newfolder, 0777);

	/*** newsletter content kopieren // neue nlid einfuegen ***/
	while($row = mysqli_fetch_object($res)) {
		$data = getEdmData($row, $folder, $newfolder);
		$sql = "INSERT morp_newsletter_cont set $data nlid=$newfolder";
		safe_query($sql);
		// echo $sql."<br><br>";
	}

	// unset($edit);
	// echo $sql;
}
elseif ($del) {
	die('<p>Do you like to delete '.$um_wen_gehts.'?</p>
	<p><a href="?delete='.$del.'">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?">Nein</a></p>
	');
}
elseif ($delcsv) {
	die('<p>M&ouml;chten Sie den Verteiler wirklich l&ouml;schen?</p>
	<p><a href="?deletecsv='.$delnm.'&edit='.$edit.'">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?">Nein</a></p>
	');
}
elseif ($delpdf) {
	$sql = "update $db set $delpdf='' WHERE $id=$edit";
	$res = safe_query($sql);
	unlink('../nlnewsletter/'.$delnm);
}
elseif ($deletecsv) {
	$sql = "update $db set csv='' WHERE $id=$edit";
	$res = safe_query($sql);
	unlink('../nlverteiler/'.$deletecsv);
}
elseif ($delete) {
	$sql = "DELETE FROM morp_newsletter WHERE nlid=$delete";
	$res = safe_query($sql);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


if ($neu) 		echo neu("neu");
elseif ($edit) 	echo edit($edit);
else {
				echo '<p>&nbsp;</p><p>&nbsp;</p>'.liste($nlart).$new;
//				echo verteiler();
}

if ($edit) echo '
</form>

<!--		<p><a href="newsletter/versenden.php?nlid='.$edit.'&liste=TEST'.($csv==4 ? '&csv=1' : '').'">&raquo; Test-Mailing</a></p>-->
		<span>&nbsp;</span>
		<!-- <p><a href="newsletter/versenden.php?nlid='.$edit.'&live=1'.($csv==4 ? '&csv=1' : '').'">&raquo; LIVE-Mailing</a></p>-->

';
?>


<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php


include("footer.php");
?>


<style>
	.modal-content { width: 900px; }
</style>
<script>
	  $("#savebtn2, #savebtn3").on("click", function() {
	  	$("#back").val(1);
	  });

</script>

<script>
$('.openBtn').on('click',function(){
	id = $(this).attr("ref");
    $('.modal-body').load('../preview.php?nlid='+id,function(){
        $('#myModal').modal({show:true});
    });
});
</script>
