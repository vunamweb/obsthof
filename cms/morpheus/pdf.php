<?php
session_start();

// phpinfo();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #
$ekko=1;
include("cms_include.inc");

function pdf ($pgid, $pgart) {
	$query  = "SELECT * FROM `morp_cms_pdf_group` WHERE pgart=$pgart ORDER BY pgname";
	$result = safe_query($query);
	while ($row = mysqli_fetch_object($result)) {
	 	$id = $row->pgid;
		$nm = $row->pgname;
		if ($pgid == $id) $sel = "selected";
		else unset ($sel);
		$tmp .= "<option value=\"$id\" $sel>$nm</option>\n";
	}
	return $tmp;
}


function filter ($kat="") {
	$sql = "SELECT * FROM morp_customer_kat WHERE 1";
	$res = safe_query($sql);

	$echo = '<form method="get" onsubmit="" name="katfilter"><select name="filter" onchange="document.pdf.submit();"><option value="">Alle</option>';

	while($row = mysqli_fetch_object($res)) {
		$echo .= '<option value="'.$row->kid.'"'. ($row->kid == $kat ? ' selected' : '') .'>'.$row->kategorie.'</option>';
	}

	$echo .= '</select>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
#$table = "morp_cms_pdf";
//$sql = "ALTER TABLE  $table ADD  `sichtbar` INT( 11 ) NOT NULL";
#$sql = "UPDATE $table SET `sichtbar`=1 WHERE 1";
#safe_query($sql);
/////////////////////////////////////////////////////////////////////////////////////////////////////


$pgid 	 = $_REQUEST["pgid"];
if (!$pgid) $db = "morp_cms_pdf_group";
else $db = "pdf";

$save 	 = $_REQUEST["save"];
$neu 	 = $_REQUEST["neu"];
$del 	 = $_REQUEST["del"];
$delete	 = $_REQUEST["delete"];
$delimg	 = $_REQUEST["delimg"];
$edit	 = $_REQUEST["edit"];
$pid	 = $_REQUEST["pid"];
$date	 = $_REQUEST["pdate"];
$pstart	 = $_REQUEST["pstart"];
$pend	 = $_REQUEST["pend"];
$pdesc	 = $_REQUEST["pdesc"];
$reload	 = $_REQUEST["reload"];
$pdf 	 = $_FILES['userfile']['name'];
$ptmp 	 = $_FILES['userfile']['tmp_name'];
$kat 	 = $_REQUEST["filter"];
$plong 	 = $_REQUEST["plong"];

$vis = isset($_GET["vis"]) ? $_GET["vis"] : 0;

echo "<div>\n<h2>Verwaltung Download Dokumente</h2>";

if ($delimg && $pgid && $edit) {
	$query = "UPDATE `morp_cms_pdf` SET $delimg='' WHERE pid=$edit";
	safe_query($query);
}
else if ($del && $pgid) {
	$query = "SELECT * FROM `morp_cms_pdf` WHERE pid=$del";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
 	$de = $row->pname;

	echo '<p>&nbsp;</p><p><font color=#ff0000><b>Sind Sie sich sicher, da&szlig; sie den Download l&ouml;schen wollen?</b> | '.$de.'</font></p>
		<p>&nbsp; &nbsp; &nbsp; <a href="pdf.php?delete=' .$del .'&pgid=' .$pgid .'" class="button">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="pdf.php?pgid='.$pgid.'" class="button">Nein</a></p></body></html>';
	die();
}
elseif ($vis) {
	$si = isset($_GET["sichtbar"]) ? $_GET["sichtbar"] : 0;
	$sql = "UPDATE morp_cms_pdf SET sichtbar=$si WHERE pid=".$vis;
	safe_query($sql);
}
elseif ($delete && $pgid) {
	$query = "SELECT * FROM `morp_cms_pdf` where pid=$delete";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
 	$de = $row->pname;

	$query = "delete FROM `morp_cms_pdf` where pid=$delete";
	$result = safe_query($query);

	unlink("../pdf/$de");
}
elseif ($save && $pgid) {
	$name = $_POST["pname"];
	$sichtbar = isset($_POST["sichtbar"]) ? $_POST["sichtbar"] : 0;
	$lock	 = $_REQUEST["locked"];
	$datum = explode(".", $date);
	$query = "UPDATE `morp_cms_pdf` set pdesc='$pdesc', plong='".addslashes($plong)."', pdate='" .us_dat($date) ."', pstart='" .us_dat($pstart) ."', pend='" .us_dat($pend) ."', pgid=$pgid , jahr=".$datum[2].", monat=".$datum[1].", sichtbar=$sichtbar, locked=".($lock ? "1" : "0"). " WHERE pid=$edit";
	safe_query($query);
	protokoll($uid, "pdf", $edit, "edit");

	unset($edit);
}
elseif ($save) {
	$pgname = $_POST["pgname"];
	$pgart = $_POST["pgart"];
	$lock	 = $_REQUEST["locked"];

	if ($neu) $query = "insert $db ";
	else $query = "update $db ";

	$query .= "set pgname='$pgname', pgart = $pgart , locked=".($lock ? "1" : "0");
	if (!$neu) $query .= " where pgid=$edit";
	# echo $query;
	$res = safe_query($query);

	if (!$neu) protokoll($uid, $db, $edit, "edit");
	else {
		$c = mysqli_insert_id($mylink);
		protokoll($uid, $db, $c, "neu");
	}

	unset($edit);
	unset($neu);
}

if ($edit && $pgid) {
		$query  = "SELECT * FROM $db where pid=$edit";
		$query  = "SELECT * FROM `morp_cms_pdf` p,  `morp_cms_pdf_group` g WHERE p.pgid=g.pgid AND pid=$edit";
		$result = safe_query($query);
		$row = mysqli_fetch_object($result);
	 	$id = $row->pid;
	 	$pg = $row->pgid;
		$de = $row->pdesc;
		$nm = $row->pname;
		$si = $row->psize;
		$pstart = euro_dat($row->pstart);
		$pend = euro_dat($row->pend);
		$da = $row->pdate;
		$da = euro_dat($da);
		$lo = $row->locked;
		$plong = $row->plong;

		$pgart = $row->pgart;

// ($pgart >= 4 ? '' :"

	echo "<form method=post name=pdf>
		<input type=hidden name=save value=1>
		<input type=hidden name=edit value=$id>
		<input type=hidden name=pgid value=$pg>
		<input type=hidden name=neu value=$neu>
		<br/>".'
		<table class="autocol p20">
		'

		."<tr>
			<td width=\"140\"><p>Dateiname<!-- , der auf der Homepage stehen soll --></p></td>
			<td><p>Upload-Datum</p></td>
			<td><p>Start Datum</p></td>
			<td><p>End Datum</p></td>
		</tr>
		<tr>
			<td>$nm  &nbsp; <br><br>".'
					<a href="pdf_upload.php?reload='.$id.'&pgid='.$pgid.'&pid='.$edit.'" data-title="Upload" data-width="500" data-toggle="lightbox" data-gallery="remoteload"  class="button"><i class="fa fa-upload"></i> Neues Dokument hochladen</a>'."
			</td>
			<td><input type=text name=pdate value='$da'></td>
			<td><input type=text name=pstart value='$pstart'></td>
			<td><input type=text name=pend value='$pend'></td>
		</tr>
		<tr>
			<td><p>&nbsp;</p><p>Dateibeschreibung (max. 256 Zeichen)</p>
			<p><input name=\"pdesc\" style=\"width:500;\" size=255 maxlength=255 value=\"$de\"></p>
			<p><textarea name=\"plong\" style=\"width:100%;\">$plong</textarea><br/><br/></p>
			</td>
			<td><p>geschütztes Dokument</p> <input type=\"checkbox\" name=\"locked\" ".($lo ? " checked" : "")." style=\"width:25;\" value=\"1\"></td>
			<td>".'
			<div class="fotoG mb2">
				<input type="hidden" name="img" id="img" value="'.$row->img.'">
				<a href="image_folder_upload.php?id='.$edit.'&pgid='.$pgid.'&amp;tbl=morp_cms_pdf&amp;from=pdf&amp;tn=120&amp;imgid=img&amp;folder=preview&amp;setid=pid">
			
				<img src="../mthumb.php?w=200&amp;src=images/preview/'.$row->img.'"></a>

				<div class="inner">
					Foto &nbsp; &nbsp; &nbsp;
					 &nbsp; &nbsp; &nbsp;
					<a href="?delimg=img&amp;edit='.$edit.'&amp;pgid='.$pgid.'" style="float:right; margin: 4px 0 0;" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
				</div>

			</div>
			<td><p><!--Gruppen-Zugeh&ouml;rigkeit</p> <select name=\"pgid\">" .pdf($pgid, $pgart) ."</select>--></td>
		</td></tr>
		</table>
		<br><br>
		<input type="submit" name="erstellen" value="speichern">
';

		///// 2015-02-17
		////  Mitglieder Zuordnung

		if ($pgart >= 2) {
			echo "<h2 style=\"border-bottom: solid 1px #000; width:700px;\">Gruppen / Gremien Zuweisung</h2><p>&nbsp;</p>";

			$col 	= array("FFFFFF","cccccc", "cde6fa");
			$sql  	= "SELECT * FROM morp_gremien ORDER BY gid";
			$res  	= safe_query($sql);
			$ct   	= 0;

			while($row = mysqli_fetch_object($res)) {
				$sq  	= "SELECT * FROM morp_gremien_datei WHERE gid=".$row->gid." AND pid=".$id;
				$rs 	= safe_query($sq);
				$c	 	= mysqli_num_rows($rs);
				echo '<p class="vers"><input type="checkbox" value="'. ($row->gid) .'" name="datei"'. ($c > 0 ? ' checked' : '') .' ref="'. ($row->gid) .'|'. ($id) .'" class="gremium"> &nbsp; '.$row->gremium.'</p>';
			}
		}

		echo "
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		</form>
		";

	echo "<p><a href=\"pdf.php?pgid=$pgid\" title=\"zur&uuml;ck\" class=\"button\"><i class=\"fa fa-arrow-circle-left\"></i> zur&uuml;ck</a></p>";
}
elseif (($neu || $reload) && $pgid) {
	echo "<form action=\"pdf.php\" method=post enctype=\"multipart/form-data\">\n\n
		<input type=\"File\" name=\"userfile\" cla&szlig;=text><p>
		<input name=pid type=hidden value=$reload>
		<input name=pgid type=hidden value=$pgid>
		<input type=\"submit\" value=\"upload starten\">
		</form>";

	echo "<p><a href=\"pdf.php?pgid=$pgid\" title=\"zur&uuml;ck\" class=\"button\"><i class=\"fa fa-arrow-circle-left\"></i> zur&uuml;ck</a></p>";
}
elseif ($edit || $neu || $rn) {
	if (!$neu) {
		$query  = "SELECT * FROM $db where pgid=$edit";
		$result = safe_query($query);
		$row = mysqli_fetch_object($result);
		$nm  = $row->pgname;
		$art = $row->pgart;
	}

	$bereich_bez = array("","Standard Download");
	$bereich_anz = count($bereich_bez)-1;
	if (!$bereich) $bereich = 1;

	for($i=1; $i <= $bereich_anz; $i++) {
		$radio .= '<p><input type="radio" name="pgart" value="'.$i.'"';
		if ($art == $i) $radio .= ' checked';
		$radio .= '> &nbsp; '.$bereich_bez[$i].'</p>';
	}

	echo "<form method=post name=pdf>
		<input type=hidden name=save value=1>
		<input type=hidden name=edit value=$edit>
		<input type=hidden name=neu value=$neu>
		<p>Name der Download-Gruppe &nbsp; <input type=text name=pgname value='$nm'></p>
		<p>&nbsp;</p>
		<p>$radio</p>
		<p>&nbsp;</p>
		<p><input type=\"submit\" name=\"erstellen\" value=\"speichern\" ></p>
		</form>
		";

	echo "<p><a href=\"pdf.php\" title=\"zur&uuml;ck\" class=\"button\"><i class=\"fa fa-arrow-circle-left \"></i> zur&uuml;ck</a></p>";
}
elseif($pdf) {
	$pfad = getDownloadDirectoy($pgid);
	$newpdf = $pdf;

	if (!copy($ptmp, $pfad.$newpdf)) {
		echo ("<p>failed to copy $tmp...$val<br>\n");
		die();
	}
	else {
		$size  = filesize($ptmp);
		$size  = $size/1024;
		$date = date("Y-m-d");
		if ($pid) $sql = "UPDATE `morp_cms_pdf` set pname='$newpdf', pdate='$date', psize=$size, edit=1, pgid=$pgid where pid=$pid";
		else $sql = "INSERT `morp_cms_pdf` set pname='$newpdf', pdate='$date', psize=$size, pgid=$pgid";
		safe_query($sql);
		echo "<p>Upload erfolgreich abgeschlo&szlig;en</p>
			<p><a href='pdf.php?pgid=$pgid' class=\"button\"><i class=\"fa fa-arrow-circle-left \"></i> zur&uuml;ck</a></p>";
	}
}
elseif ($pgid)	{
	$pfad = getDownloadDirectoy($pgid);

	echo "<p><a href=\"pdf_group.php\" class=\"button\"><i class=\"fa fa-arrow-circle-left\"></i> zur&uuml;ck</a></p>";

	echo '
		<table class="autocol p20" id="sverw">'."
		<tr style=\"font-weight:bold;\" height=20>
			<td width=160><b>name</b></td>
			<td></td>
			<td><b>beschreibung</b></td>
			<td><b>gr&ouml;&szlig;e</b></td>
			<td><b>Upload</b></td>
			<td><b>sichtbar</b></td>
			<td><b>Start</b></td>
			<td><b>Ende</b></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>";

	$col = array("#FFFFFF","#EFECEC");
	$ct  = 0;

	$query  = "SELECT * FROM `morp_cms_pdf` where pgid=$pgid order by jahr DESC, monat DESC, pname";
	$result = safe_query($query);
	while ($row = mysqli_fetch_object($result)) {
	 	$id = $row->pid;
		$de = $row->pdesc;
		$nm = $row->pname;
		$si = $row->psize;
		$da = $row->pdate;
	 	$da = euro_dat($da);
	 	$pstart = euro_dat($row->pstart);
	 	$pend = euro_dat($row->pend);
		$sichtbar = $row->sichtbar;

		$lo = $row->locked;

	 	if(file_exists($pfad.$nm.'.jpg')) $thumb = '<img src="mthumb.php?w=50&h=50&src='.$pfad.$nm.'.jpg" />';
	 	else $thumb = '';

		echo "<tr bgcolor=$col[$ct] height=24>
				<td><p><font color=#000000><a name=\"Download anzeigen\" href=\"".$pfad.$nm."\" target=\"_blank\" title=\"Download anzeigen\">$nm</a></p></td>
				<td><p>$thumb</p></td>
				<td><p>$de</p></td>
				<td><p>$si kb</p></td>
				<td><p>$da</p></td>".'
				<td><a href="?pgid='.$pgid.'&vis='.$id.'&sichtbar='.($row->sichtbar ? 0 : 1).'"><i class="fa fa-eye'.($row->sichtbar ? '' : '-slash gray').'"></i></a></td>
				'."<td width=80><p>$pstart</p></td>
				<td><p>$pend</p></td>
				<td><p><a href=\"pdf.php?edit=$id&pgid=$pgid\"><i class=\"fa small fa-pencil-square-o\"></i></a></p></td>
				<td><p><a href=\"pdf.php?del=$id&pgid=$pgid\"><i class=\"fa small fa-trash-o\"></i></a></p></td>
				<td><p>".($lo ? "<a href=\"#\"><i class=\"fa small fa-lock\"></i></a>" : "")."</p></td>
			</tr>
			";
		if ($ct == 0) $ct = 1;		//farbendefenition
		else $ct = 0;
	}
	echo '</table>
		<p>&nbsp;</p>
		<p><a href="pdf_upload.php?neu=1&pgid='.$pgid.'" data-title="" data-width="500" data-toggle="lightbox" data-gallery="remoteload"  class="button"><i class="fa fa-upload"></i> Neues Dokument hochladen</a> &nbsp; &nbsp; &nbsp; &nbsp;
			<!-- <a href="zip_upload.php?reload='.$id.'&pgid='.$pgid.'" class="button" style="color:#000; background:#ccc"><i class="fa fa-upload"></i> Neues ZIP zum UNZIP hochladen</a> -->
		</p>';
}
else {
	$col = array("#FFFFFF","#EFECEC");
	$ct  = 0;

	echo '<table cellspacing="1">';

	$query  = "SELECT * FROM $db order by pgname";
	$result = safe_query($query);
	while ($row = mysqli_fetch_object($result)) {
	 	$id = $row->pgid;
	 	$nm = $row->pgname;
		echo "<tr bgcolor=$col[$ct] height=20><td width=250>&nbsp; <a href=\"pdf.php?pgid=$id\"><i class=\"fa small fa-arrow-circle-left\"></i> <b>$nm</b></a>";
		if ($admin) echo '&nbsp; &nbsp; <a href="pdf.php?edit='.$id.'&db='.$db.'"><img src="images/stift.gif" width="9" height="9" alt="editiere name" border="0"></a>';
		echo '</td><td width="50" align="center"><a href="pdf.php?pgid='.$id.'"><img src="images/edit.gif" alt="&ouml;ffne ordner" border="0"></a></td></tr>';

		if ($ct == 0) $ct = 1;		//farbendefenition
		else $ct = 0;
	}
	echo "</table>";
	if ($admin) echo '<p><a href="pdf.php?neu=1" class="button"><i class="fa fa-chevron-right"></i> NEUES DOKUMENT HOCHLADEN</a></p>';
}
?>

</div>

<?php
include("footer.php");
?>