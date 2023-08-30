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

echo '<div id=vorschau>
	<h2>Newsletter Verteiler</h2>

	'. ($edit || $neu ? '<p><a href="?">&laquo; zur&uuml;ck</a></p>' : '').'
	<form action="" onsubmit="" name="verwaltung" method="post">
';

$new = '<p><a href="?neu=1">&raquo; NEU</a></p>';

// rid	name	vorname	email	anrede	fon	SID	sprache	checked	art1	art2	art3

$arr_form = array(
	array("anrede", "Anrede",'<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("firma", "Firma",'<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("name", "Name",'<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("email", "E-Mail",'<input type="Text" value="#v#" name="#n#" style="#s#">'),

#	array("art1", "Politik",'<input type="Text" value="#v#" name="#n#" style="width:50px;">'),
#	array("art2", "Pharmazie",'<input type="Text" value="#v#" name="#n#" style="width:50px;">'),
#	array("art3", "Recht",'<input type="Text" value="#v#" name="#n#" style="width:50px;">'),

#	array("checked", "Freischalten (an=1, aus=0)",'<input type="Text" value="#v#" name="#n#" style="width:50px;">')
);


/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function liste() {
	$db = "morp_newsletter_vt";
	$id = "vid";
	$ord = "name";
	$anz = "name";
	$anz2 = "email";
	$anz3 = "firma";
	$anz4 = "anrede";

	$echo .= '<p>&nbsp;</p><table class="autocol p20">';

	$sql = "SELECT * FROM $db WHERE 1 ORDER BY ".$ord."";
#	$sql = "SELECT * FROM $db WHERE 1 ";
	$res = safe_query($sql);
	$y = 0;

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$id;
		$y++;
		$echo .= '<tr>
			<td width="60">'.$y.'</td>
			<td width="550"><a href="?edit='.$edit.'"><strong>'.$row->$anz.'</strong> - '.$row->$anz2.', '.$row->$anz3.'</a></td>
			<td valign="top"><a href="?edit='.$edit.'"><img src="images/edit.gif" alt="" width="18" height="10" border="0"></a></td>
			<td valign="top"><a href="?del='.$edit.'"><img src="images/delete.gif" alt="" width="9" height="10" border="0"></a></td>
		</tr>';
	}

	$echo .= '</table><p>&nbsp;</p>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function edit($edit) {
	global $arr_form;

	$db = "morp_newsletter_vt";
	$id = "vid";
	$ord = "name";
	$anz = "name";

	$sql = "SELECT * FROM $db WHERE $id=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);

	$echo .= '<input type="Hidden" name="neu" value="'.$neu.'">
		<input type="Hidden" name="edit" value="'.$edit.'">
		<input type="Hidden" name="save" value="1">

	<table cellspacing="6">';

	$echo .= '<tr>
		<td></td>
	</tr>
';

	foreach($arr_form as $arr) {
		$get = $arr[0];
		if ($arr[0] == "aktiv") {
			if ($row->$arr[0] == "1") $sel1 = " checked";
			else $sel2 = " checked";
		}

		$echo .= '<tr>
		<td>'.$arr[1].':</td>
		<td>'. str_replace(
					array("#v#", "#n#", "#s#", "#db#", '#e#', '#id#', '#s1#', '#s0#'),
					array($row->$get, $get, 'width:400px;', $db2, $edit, $id2, $sel1, $sel2),
			$arr[2]).'</td>
	</tr>';
	}

	$echo .= '
	<tr>
		<td></td>
		<td><input type="submit" name="speichern" value="speichern"></td>
	</tr>
</table>';

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
	global $arr_form;
	$sql = '';
	$db = "morp_newsletter_vt";
	$id = "vid";

	foreach($arr_form as $arr) {
		$tmp = $arr[0];
		$val = $_POST[$tmp];

		if ($val) $sql .= "`".$tmp. "`='" .$val. "', ";
	}

	$sql = substr($sql, 0, -2);

	if ($neu) {
		$sql  = "INSERT $db set $sql";
		$res  = safe_query($sql);
		$edit = mysqli_insert_id($mylink);
		unset($neu);
	}
	else {
		$sql = "update $db set $sql WHERE $id=$edit";
		$res = safe_query($sql);
	}
	unset($edit);
}
elseif ($del) {
	die('<p>M&ouml;chten Sie den Interessenten wirklich l&ouml;schen?</p>
	<p><a href="?delete='.$del.'">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?">Nein</a></p>
	');
}
elseif ($delete) {
	$sql = "DELETE FROM morp_newsletter_vt WHERE vid=$delete";
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
