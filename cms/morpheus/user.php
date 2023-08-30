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

$myauth = 40;
$settings_in = 'in';
$set1_active = ' class="active"';

include("cms_include.inc");

$arr = array( 4=>"Kein Zugang Morpheus", 2=>"Redakteur", 1=>"Administrator");

$uid	= $_REQUEST["uid"];
$neu	= $_REQUEST["neu"];
$save	= $_REQUEST["save"];
$unm	= $_REQUEST["unm"];
$vnm	= $_REQUEST["firstname"];
$nnm	= $_REQUEST["lastname"];
$pwd	= $_REQUEST["pwd"];
$adm	= $_REQUEST["adm"];
$ber	= $_REQUEST["ber"];
$ber = 2;
$newpass= $_REQUEST["newpass"];
$aut	= $_REQUEST["auths"];

$del	= $_REQUEST["del"];
$delete	= $_REQUEST["delete"];

echo "<div>";


if ($delete && $admin) {
	$sql = "DELETE FROM morp_cms_user WHERE uid=$delete";
	$res = safe_query($sql);
}
elseif ($del) {
	$sql = "SELECT uname FROM morp_cms_user WHERE uid=$del";
	$res = safe_query($sql);
	$row 	= mysqli_fetch_object($res);

	echo ('
		<p>M&ouml;chten Sie den morp_cms_user <b>'.$row->uname .'</b> wirklich l&ouml;schen?</p>
		<p><a href="?delete='.$del.'" class="button">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?" class="button">Nein</a></p><br><br><br><br>
');
}


if ($save) {
	$ct = count($arr);

	$pwd = md5($pwd);
	$set .= "uname='$unm', firstname='$vnm', lastname='$nnm'".($neu || $newpass ? ", pw='$pwd'" : '').", admin='$adm', berechtigung='$ber'".($auth ? ", auths='".implode("|", $aut)."'" : "");

	if ($neu) 	$query = "insert morp_cms_user ";
	else 		$query = "update morp_cms_user ";

	$query .= "set " .$set;

	if (!$neu) $query .= " WHERE uid=$uid";
// echo $query;
	safe_query($query);

	unset($neu);
	unset($uid);
}

if ($uid || $neu) {
	echo "<h2>Verwaltung User</h2>";

	if (!$neu) {
		$query  = "SELECT * FROM morp_cms_user where uid=$uid";
		$result = safe_query($query);
		$row 	= mysqli_fetch_object($result);
	}

	foreach ($arr as $val) {
		if ($row->$val == 1) $$val = "checked";
	}

	$admin = $row->admin || $row->berechtigung == 1 ? " checked" : '';
	$bere = $neu ? " checked" : '';

	echo '<p><a href="user.php">' .backlink().' zur&uuml;ck</a></p><p>&nbsp;</p>';
	echo '<form method="post">
		<input type="hidden" name="neu" value="'.$neu.'">
		<input type="hidden" name="uid" value="'.$uid.'">
		<input type="hidden" name="save" value="1">
		<p><label>Login Name</label><input type="text" name="unm" value="'.$row->uname.'" class="form-control" placeholder=""></p>
		<p><label>Vorname</label><input type="text" name="firstname" value="'.$row->vorname.'" class="form-control" placeholder=""></p>
		<p><label>Nachname</label><input type="text" name="lastname" value="'.$row->nachname.'" class="form-control" placeholder=""></p>
		<p><label>Passwort</label><input type="text" name="pwd" value="'.$row->pw.'" class="form-control" placeholder="">
				<input type="checkbox" name="newpass" value="1" class="form-control" placeholder="" '.$bere .'> <b>Passwort speichern</b></p>

<!--		<p><label>Berechtigung</label><select name="ber" class="form-control">';

	foreach ($arr as $key=>$val) {
		if ($key == $row->berechtigung) $sel = " selected";
		else 							$sel = "";
		echo '<option value="'.$key.'"'.$sel.'>'.$val.'</option>';
	}

	echo '</select></p>-->
		<p>&nbsp;</p>
		<p><input type="submit" class="button" name="speichern" value="speichern"></p>
		<p>&nbsp;</p>
		<br><img src="images/leer.gif" alt="" width="1" height="1" border="0"><br>
		<p><input type="checkbox" name="adm" value="1" style="border: 0;" '.$admin .'> Administrator</p>
';

	$auths = explode("|", $row->auths);

	foreach ($auths_arr as $key=>$val) {
		if (in_array($key, $auths))	$sel = " checked";
		else 						$sel = "";
		echo '<p><label>&nbsp; &nbsp; &nbsp; &nbsp;</span><input type="checkbox" name="auths[]" value="'.$key.'"'.$sel.' /> &nbsp; '.$val.'</p>';
	}

	echo '	<!-- <p><input type="checkbox" name="news" value="1" style="border: 0;" '.$news .'> <b>Newsletter</b> erstellen</p>
		<p><input type="checkbox" name="live" value="1" style="border: 0;" '.$live .'> darf <b>veröffentlichen</b></p> -->
		<br><img src="images/leer.gif" alt="" width="1" height="1" border="0"><br>
';
}

elseif ($admin) {
	echo "<h2>Liste berechtigter Mitarbeiter f&uuml;r morpheus IV</h2><p>&nbsp;</p>";

	echo '<table border=0 cellspacing=1 cellpadding=0 class="autocol p20">';
	echo '<tr>
		<td><p>username</p></td>
		<td> &nbsp; </p>
		<td></td>
		<td> &nbsp; </p></td>
		<td><p>Admin</p></td>
		<td><p>Berechtigungen</p></td>
	</tr>';


	$query  = "SELECT * FROM morp_cms_user WHERE uname != 'morpheus' order by uname";
	$result = safe_query($query);
	$ct 	= mysqli_num_rows($result);
	$change = $ct / 3;

	while ($row = mysqli_fetch_object($result)) {
		$c++;

		$auth = explode("|", $row->auths);
		$authliste = array();
		foreach($auth as $val) {
			$authliste[] = $auths_arr[$val];
		}

		echo '<tr>
			<td><a href="user.php?uid='.$row->uid.'">'.$row->uname.'</a></td>
			<td> &nbsp; </p>
			<td><a href="user.php?del='.$row->uid.'"><i class="fa fa-trash-o small"></i></a></td>
			<td> &nbsp; </p>
			<td style="text-align:center;'.($row->admin ? 'background:green' : '').'">'.($row->admin ? 'x' : '').'</p>
			<td><p>'.implode(" | ", $authliste).'</p></p>
		</tr>';
	}

	echo '</table><div style="clear:left;"><p>&nbsp;</p>
		<p><a href="user.php?neu=1" class="button"><i class="fa fa-plus small"></i> NEU </a></p></div>';
}

else die('<p><strong>Keine Berechtigung</strong></p>');
?>

</div>

<?php
include("footer.php");
?>