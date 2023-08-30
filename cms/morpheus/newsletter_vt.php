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

# print_r($_REQUEST);

global $arr_form;

$edit 	= $_REQUEST["edit"];
$neu	= $_REQUEST["neu"];
$save	= $_REQUEST["save"];
$del	= $_REQUEST["del"];
$delete	= $_REQUEST["delete"];
$id		= $_REQUEST["id"];
$back = isset($_POST["back"]) ? $_POST["back"] : 0;

$table = "morp_newsletter_vt_live";
$tid = "vid";


echo '<div id=vorschau>
	<h2>Newsletter Verteiler</h2>

	'. ($edit || $neu ? '<p><a href="?">&laquo; zur&uuml;ck</a></p>' : '').'
	<form action="" onsubmit="" name="verwaltung" method="post">
';

$new = '<p><a href="?neu=1">&raquo; NEU</a></p>';

// rid	name	vorname	email	anrede	fon	SID	sprache	checked	art1	art2	art3

$arr_form = array(
#	array("anrede", "Anrede",'<input type="Text" value="#v#" name="#n#" style="#s#">'),
#	array("name", "Name",'<input type="Text" value="#v#" name="#n#" style="#s#">'),
#	array("vorname", "Vorname",'<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("email", "E-Mail",'<input type="Text" value="#v#" name="#n#" style="#s#">'),

#	array("art1", "Politik",'<input type="Text" value="#v#" name="#n#" style="width:50px;">'),
#	array("art2", "Pharmazie",'<input type="Text" value="#v#" name="#n#" style="width:50px;">'),
#	array("art3", "Recht",'<input type="Text" value="#v#" name="#n#" style="width:50px;">'),

#	array("art1", "Politik",'<input type="checkbox" value="1" name="#n#" #c#>', 'cb'),
#	array("art2", "Pharmazie",'<input type="checkbox" value="1" name="#n#" #c#>', 'cb'),
#	array("art3", "Recht",'<input type="checkbox" value="1" name="#n#" #c#>', 'cb'),

	array("verified", "Freischalten (an=1, aus=0)",'chk'),
);


/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
//Verteilermitglied

function liste() {
	$db = "morp_newsletter_vt_live";
	$id = "vid";
	$ord = "name";
	$anz = "name";
	$anz1 = "vorname";
	$anz2 = "email";
	$anz4 = "anrede";

	$anz3 = "verified";
	
	$echo .= '<p>&nbsp;</p><table width="100%" cellspacing="0" class="autocol p20">
		<tr>
			<td width="60"></td>
			<td width="430"></td>
			<td width="40">Bestätigt</td>
			<td valign="top"></td>
			<td valign="top"></td>
		</tr>
';

	$sql = "SELECT * FROM $db WHERE 1 ORDER BY ".$ord."";
#	$sql = "SELECT * FROM $db WHERE 1 ";
	$res = safe_query($sql); 
	$y = 0;
	
	while ($row = mysqli_fetch_object($res)) {	
		$edit = $row->$id;
		$y++;
		$echo .= '<tr>
			<td width="60">'.$y.'</td>
			<td width="430"><a href="?edit='.$edit.'"><strong>'.$row->$anz2.'</a></td>
			<td width="40" align="center">'.$row->$anz3.'</td>
			<td width="80">&nbsp; '.$row->$anz5.'</td>
			<td valign="top"><a href="?edit='.$edit.'"><i class="fa fa-edit"></i></a></td>
			<td valign="top"> &nbsp; <a href="?del='.$edit.'"><i class="fa fa-trash-o"></i></a></td>
		</tr>';
	}
	
	$echo .= '</table><p>&nbsp;</p>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function edit($edit) {
	global $arr_form;
	
	$db = "morp_newsletter_vt_live";
	$id = "vid";
	$ord = "name";
	$anz = "name";

	$sql = "SELECT * FROM $db WHERE $id=".$edit."";
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
		$echo .= setMorpheusForm($row, $arr, $imgFolderShort, $edit, 'morp_referenzen', $tid);
	}

	$echo .= '</div>
		</div>
<p>&nbsp;</p>
				<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
				<button type="submit" id="savebtn2" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>
	</div>
';


	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function neu() {
	global $arr_form;

	$x = 0;
	
	$echo .= '<input type="Hidden" name="neu" value="1"><input type="Hidden" name="save" value="1">
	
	<table cellspacing="6">';
		
	foreach($arr_form as $arr) {	
		$get = $arr[0];
		$echo .= '<tr>
			<td>'.$arr[1].':</td>
			<td>'. str_replace(array("#v#", "#n#", "#s#"), array($row->$get, $get, 'width:400px;'), $arr[2]).'</td>
		</tr>';
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

elseif ($del) {
	die('<p>M&ouml;chten Sie den Interessenten wirklich l&ouml;schen?</p>
	<p><a href="?delete='.$del.'">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?">Nein</a></p>
	');
}
elseif ($delete) {
	$sql = "DELETE FROM morp_newsletter_vt_live WHERE vid=$delete";
	$res = safe_query($sql);	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

if ($neu) 		echo neu("neu");
elseif ($edit) 	echo edit($edit);
else			echo liste($id).$new;

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
	
