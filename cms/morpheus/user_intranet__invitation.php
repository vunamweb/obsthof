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
$pp_in = 'in';
include("cms_include.inc");
include("../inc/arr/prueferprofil_anfrage.php");

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

# print_r($_SESSION);
# print_r($_REQUEST);

///////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
//// EDIT_SKRIPT
$um_wen_gehts 	= "Freigabe Protokoll Anforderung";
$titel			= "Freigabe Protokoll Anforderung";
///////////////////////////////////////////////////////////////////////////////////////
$table 		= 'prueferprofil_anforderung';
$tid 		= 'paID';
$nameField 	= "name";
$sortField 	= 'name';

$table2 	= 'prueferprotokoll';
$tid2 		= 'ppID';
$nameField2 	= "pruefungs_tag";

// $table2 	= 'morp_faq_category';
// $tid2 		= 'catId';
// $nameField2	= "category";
// $sortField2	= 'reihenfolge';
// print_r($_FILES);
///////////////////////////////////////////////////////////////////////////////////////

$edit = isset($_REQUEST["edit"]) ? $_REQUEST["edit"] : 0;

global $faecher, $laender, $land, $send, $strg;

$land = isset($_GET["land"]) ? $_GET["land"] : '';
$send = isset($_GET["send"]) ? $_GET["send"] : '';
$strg = isset($_GET["strg"]) ? $_GET["strg"] : '';

$select1 = setSelectField('land', $laender, $land, 'Bundesland',1);
// $select2 = setSelectField('fach', $faecher, $fach, 'Fachbereich',1);

// $new = '<a href="?neu=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a>';
$new = '<p><a href="?new=1" class="btn btn-info"><i class="fa fa-plus"></i> NEU</a></p>';
$new = '';

echo '

<style>
.doPflicht { display:none; }
label { display:inline-block; width: 180px; font-weight:normal; }
label.showlabel { display:inline-block; width: 100%; font-weight:normal; }
b.bg { background: yellow; }
.form-group.col { display:inline-block; width: 45%; margin-right: 4%; }
</style>

<div id=vorschau>
	'. ($edit || $neu ? '<p class="mt2 mb2"><a href="?" class="btn btn-info">&laquo; zur&uuml;ck</a></p><hr>
	<h2 class="mb3">'.$titel.'</h2>
	' : '
	<h2>'.$titel.'</h2>
		<hr>
			<form method="GET">
		<div class="row mb1">
			<div class="col-md-2">
				'.$select1.'
			</div>
			<div class="col-md-2">
				<select class="form-control" id="send" name="send" onchange="this.form.submit()">
					<option value="">Status</option>
					<option value="2"'.($send==2 ? ' selected' : '').'>nicht freigeschaltet</option>
					<option value="1"'.($send==1 ? ' selected' : '').'>freigeschaltet</option>
				</select>
			</div>
			<div class="col-md-2">
				<input type="text" name="strg" value="'.$strg.'" class="form-control" placeholder="Suche nach Name" />
			</div>
			<div class="col-md-2"><button type="submit" class="btn btn-info">Filter</button></div>
		</div>
			</form>
		<hr class="mt2">
	') .'
	
	<form action="" onsubmit="" name="verwaltung" method="post">
'.($edit || $neu ? '' : '').'
'.(!$edit && !$neu ? '' : '').'
';

// print_r($_POST);

/////////////////////////////////////////////////////////////////////////////////////////////////////
#$sql = "ALTER TABLE  $table ADD  `fFirmenFilterID` INT( 11 ) NOT NULL";
#safe_query($sql);
/////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
// foreach($arr_form as $arr) {
// 	setCol($table, $arr[0], $arr[9]);
// }
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
///////////////////////////////////////////////////////////////////////////////////////

$neuerDatensatz = isset($_GET["new"]) ? $_GET["new"] : 0;
$save = isset($_REQUEST["save"]) ? $_REQUEST["save"] : 0;
$del = isset($_REQUEST["del"]) ? $_REQUEST["del"] : 0;
$delete = isset($_REQUEST["delete"]) ? $_REQUEST["delete"] : 0;
$back = isset($_POST["back"]) ? $_POST["back"] : 0;

$delimg = isset($_GET["delimg"]) ? $_GET["delimg"] : 0;

$down = isset($_GET["down"]) ? $_GET["down"] : 0;
$up = isset($_GET["up"]) ? $_GET["up"] : 0;
$col = isset($_GET["col"]) ? $_GET["col"] : 0;
$copy = isset($_GET["copy"]) ? $_GET["copy"] : 0;
$imgFolder = "../images/pruefer/";
$imgFolderShort = "pruefer/";

$repair = isset($_GET["repair"]) ? $_GET["repair"] : 0;
$vis = isset($_GET["vis"]) ? $_GET["vis"] : 0;
$sichtbar = isset($_GET["sichtbar"]) ? $_GET["sichtbar"] : 0;

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

function liste() {
	global $arr_form, $table, $tid, $filter, $nameField, $sortField, $imgFolderShort;
	global $table2, $tid2, $sortField2;
	global $faecher, $laender, $land, $send, $strg, $table2, $tid2;

	//// EDIT_SKRIPT
	$ord = "t1.$sortField";
	$anz = $nameField;

	////////////////////
	// $where = " t1.$tid2=t2.$tid2 ";

	$where = "";
	
	if($send || $land || $strg) {
		if($land) $where .= " plID='$land'";
		if($send) $where .= ($where ? ' AND' : '')." send=".($send==1 ? 1 : 0);
		if($strg) $where .= ($where ? ' AND' : '')." ( name LIKE '%$strg%' OR vorname LIKE '%$strg%' )";
	}
	else $where = "1";

	$echo .= '<div class="col-md-12">
			<div class="row">
				<div class="col col-md-1">&nbsp;</div>
				<div class="col col-md-2">&nbsp;</div>
				<div class="col col-md-2">&nbsp;</div>
				<div class="col col-md-1">Bundesland</div>
				<div class="col col-md-1">PW</div>
				<div class="col col-md-1">BWL</div>
				<div class="col col-md-1">WR</div>
				<div class="col col-md-1">STR</div>
				<div class="col col-md-1">&nbsp;</div>
				<div class="col col-md-1 text-right">&nbsp;</div>
			</div>';

	$sql = "SELECT t1.name, t1.vorname, t1.titel, pw_tag, bwl_tag, wr_tag, str_tag, send, t1.email, t2.email AS mail_check, t1.plID, t1.$tid FROM $table t1 LEFT JOIN $table2 t2 ON t1.email=t2.email WHERE $where GROUP BY t1.$tid ORDER BY ".$ord."";
	$res = safe_query($sql);
	$x = mysqli_num_rows($res) / 2;
	$n = 0;
	$nextcol = 0;
	
	while ($row = mysqli_fetch_object($res)) {
		$n++;
		if($n > $x && !$nextcol) {
			$echo .= '</div><div class="col-md-12">';
			$nextcol = 1;
		}
		$edit = $row->$tid;
		$echo .= '
		<div class="row" id="'.$row->$tid.'">
			<div class="col col-md-1"><a href="?edit='.$edit.'" class="btn btn-success ml2"><i class="fa fa-pencil-square-o"></i><span class="small light"> &nbsp; '.$row->$tid.'</span></a></div>
			<div class="col col-md-2"><a href="?edit='.$edit.'">'.$row->name.', '.$row->vorname.' '.$row->titel.'</a></div>
			<div class="col col-md-2"><a href="?edit='.$edit.'">'.$row->email.'</a></div>
			<div class="col col-md-1">'.$row->plID.'</div>
			<div class="col col-md-1">'.($row->pw_tag != '0000-00-00' ? euro_dat($row->pw_tag) : ' &nbsp; ').'</div>
			<div class="col col-md-1">'.($row->bwl_tag != '0000-00-00' ? euro_dat($row->bwl_tag) : ' &nbsp; ').'</div>
			<div class="col col-md-1">'.($row->wr_tag != '0000-00-00' ? euro_dat($row->wr_tag) : ' &nbsp; ').'</div>
			<div class="col col-md-1">'.($row->str_tag != '0000-00-00' ? euro_dat($row->str_tag) : ' &nbsp; ').'</div>
			<div class="col col-md-1">
				<i class=" fa fa-'.($row->send ? 'check' : 'close').'"></i> &nbsp; 
				<i class=" fa fa-'.($row->mail_check ? 'trophy' : 'times-circle-o').'"></i>
			</div>
			<div class="col col-md-1 text-right"><a href="?del='.$edit.'" class="btn btn-danger"><i class="fa fa-trash-o"></i><span class="small light">&nbsp;</span></a></div>
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
<style>
h3{ font-size:1em; }
.funkyradio input[type="radio"]:empty ~ label:before, .funkyradio input[type="checkbox"]:empty ~ label:before { position: relative; }
.funkyradio input[type="radio"]:empty ~ label, .funkyradio input[type="checkbox"]:empty ~ label { line-height:1.2em; font-weight:300; }
.plistid { width: 50px; margin-top: -20px; margin-bottom: 1em; background:rgba(5, 162, 5, .5); color:#fff; }
</style>



		<input type="Hidden" name="edit" value="'.$edit.'">
		<input type="Hidden" name="save" value="1">
		<input type="hidden" value="0" name="back" id="back" />

		
		<div class="row profil">
			<div class="col-md-6">
					<div class="form-group">
					   <button class="btn btn-'.($row->send ? 'success' : 'info firstdraft').'" name="access" value="'.($row->send ? '1' : '0').'" id="frei"><i class="fa fa-'.($row->send ? 'check' : 'close').'"></i> &nbsp; Für Protokoll Download '.($row->send ? 'freigeschaltet' : 'freischalten und E-Mail senden').'</button>
				   </div>

	';
	$x = 0;
	foreach($arr_form as $arr) {
		// if($arr[0]=="row") $arr = array("", "CONFIG", '</div></div><div class="row mt4"><div class="col-md-6">'.$arr[3]);
		// else if($arr[1]=="CONFIG") $arr = array("", "CONFIG", ($x > 0 ? '</div><div class="col-md-6">' : '').$arr[3]);
		// $echo .= setMorpheusFormECONECT($row, $arr, $imgFolderShort, $edit, 'morp_referenzen', $tid);
		$echo .= setMorpheusFormECONECT($row, $arr, 0, $edit, '', $setid, 0);
		$x++;
	}

	$echo .= '</div>
		</div>
		
			<div style="display:block;height:40px;"></div>

			<!-- <button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
			<button type="submit" id="savebtn2" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>-->
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
	// $neu = isset($_POST["neu"]) ? $_POST["neu"] : 0;
	// print_r($_POST);
	// echo "save";
	// $edit = saveMorpheusForm($edit, $neu);

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
	
	$(".firstdraft").on("click", function() {
		event.preventDefault();
		$.ajax({
			url: "Update.php",
			type: "post",
			data: "data=1&pos=send&feld=paID&table=<?php echo $table; ?>&id=<?php echo $edit; ?>",	
			success:function(msg){
				console.log("update send gesetzt: "+msg);
				$.ajax({
					url: "Update.php",
					type: "post",
					data: "data=1&pos=firstdraft&feld=paID&table=<?php echo $table; ?>&id=<?php echo $edit; ?>",	
					success:function(msg){
						console.log("firstdraft gesetzt: "+msg);
						$.ajax({
							url: "../inc/mail_invitation.php",
							type: "post",
							data: "id=<?php echo $edit; ?>",	
							success:function(msg){
								console.log("mail sent: "+msg);
								alert("Mail sent - "+msg);									
							}
						});						
					}
				});
			},
			error:function(){
				alert("Connection Is Not Available");
			}
		});
	});
</script>

