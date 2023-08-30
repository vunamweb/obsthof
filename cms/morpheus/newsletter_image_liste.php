<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

$stelle = $_REQUEST["stelle"];
$newsletter_in = 'in';

include("cms_include.inc");


function morp_newsletter_img_group ($imgid) {
	$query  = "SELECT * FROM morp_newsletter_img_group order by name";
	$result = safe_query($query);
	while ($row = mysqli_fetch_object($result)) {
	 	$id = $row->gid;
		$nm = $row->name;
		if ($imgid == $id) $sel = "selected";
		else unset ($sel);
		$tmp .= "<option value=\"$id\" $sel>$nm</option>\n";
	}
	return $tmp;
}

$del 	 = $_REQUEST["del"];
$delete	 = $_REQUEST["delete"];
$save	 = $_REQUEST["save"];
$gid	 = $_REQUEST["gid"];

$liste	= $_REQUEST["liste"];
$imgid	= $_REQUEST["imgid"];

# beschreibenden text verwalten
$txtedit = $_REQUEST["txt"];
$newtext = $_REQUEST["newtext"];
$ltext 	 = $_REQUEST["longtext"];

# wenn bild in content eingesetzt wird
$stelle = $_REQUEST["stelle"];
$imglnk = $_REQUEST["imglnk"];
$navid  = $_REQUEST["navid"];
$edit   = $_REQUEST["edit"];
$cedit  = $_REQUEST["cedit"];
$db		= $_REQUEST["db"];
$art	= $_REQUEST["art"];
$vorlage= $_REQUEST["vorlage"];

$newsletter = $_REQUEST["newsletter"];
if ($_GET["db"] == "ec_kurs_art") $kurs = 1;

if ($liste)  		$temp_lnk = "newsletter_edit.php?edit=$edit&liste=$liste";
elseif ($cedit)  		$temp_lnk = "content_template.php?navid=$navid&edit=$cedit&vorlage=$vorlage";
elseif ($navid || $kurs)  	$incl_lnk = "content_edit.php?db=$db&stelle=$stelle&navid=$navid&edit=$edit&art=$art&vorlage=$vorlage";

# wenn bild in news eingesetzt wird
$nid	= $_REQUEST["nid"];
$ngid	= $_REQUEST["ngid"];

# deko bilder bestimmen
$inr 	= $_REQUEST["inr"];
$cid	= $_REQUEST["cid"];
$back	= $_REQUEST["back"];

# print_r($_REQUEST);

if ($save) {
	foreach ($_POST as $key=>$val) {
 		if (preg_match("/^gid/", $key)) {
			$tmp = explode ("_", $key);
			if ($val != $gid) {
				$que = "UPDATE morp_newsletter_image SET gid=$val WHERE imgid=$tmp[1]";
				safe_query($que);
			}
		}
	}

	create_img_liste();
}
elseif ($del) {
	$warnung = "<p><font color=#ff0000><b>Möchten Sie das Bild wirklich löschen?</b></font></p>
				<p><br>&nbsp;</p>
				<a href=\"newsletter_image_liste.php?delete=$del&gid=$gid\" title=\"Bild l&ouml;schen!\">" .ilink() ." Bild löschen</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
				<a href=\"newsletter_image_liste.php?gid=$gid\" title=\"abbrechen\">" .backlink() ." Abbruch</a>";
}
# das bild wird endg&uuml;ltig gel&ouml;scht
elseif ($delete) {
	$query = "SELECT imgname FROM morp_newsletter_image WHERE imgid=$delete";
	$res = safe_query($query);
	$row = mysqli_fetch_object($res);
	$tmp = "../img/".$row->imgname;
	@unlink($tmp);

	$query = "DELETE FROM morp_newsletter_image WHERE imgid=$delete";
	safe_query($query);
	create_img_liste();
}
elseif($txtedit) {
	$query  = "SELECT * FROM morp_newsletter_image WHERE imgid=$txtedit";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
	$tx = $row->itext;
	$lt = $row->longtext;
	$inm 	= $row->imgname;

//	if (!$tx) $tx = "Bitte hier den beschreibenden Text einf&uuml;gen";
	$warnung = "<form action='newsletter_image_liste.php' method=post>
		<input type=\"hidden\" value=\"$txtedit\" name=\"stelle\">
		<input type=\"hidden\" value=\"$txtedit\" name=\"inr\">
		<input type=\"text\" value=$gid name=\"gid\"><br>
		<b>Alt Text oder externer Link (inkl. http:// angeben!)</b><br>
		<input type=\"text\" name=\"newtext\" size=\"100\" maxlength=\"255\" value=\"$tx\"><p>
		<p>&nbsp;</p>
		<b>Langtext</b>. Wird unter dem Galerie-Bild angezeigt<br>
		<textarea cols=\"100\" rows=\"5\" name=\"longtext\" style=\"height:60px;\">$lt</textarea>
		<input type=\"submit\" name=\"speichern\" value=\"speichern\">
		<p>&nbsp;</p><p>Gruppen-Zugeh&ouml;rigkeit</p><p></p></form>

		<img src=\"../img/".$inm."\" border=0 vspace=6>";
	#<select name=\"gid\">" .morp_newsletter_img_group($txtedit) ."</select>
}
elseif ($newtext || $ltext) {
	$query = "UPADTE morp_newsletter_image SET itext='$newtext', `longtext`='$ltext' WHERE imgid=$inr";
	safe_query($query);
	#$gid = $inr;
	create_img_liste();
}

echo "<div id=content_big class=text><table border=0><tr><td>
";
if (!$cid && !$nid) echo "<p><b>Bildarchiv</b><br><br></p>";
if ($warnung) die ($warnung ."</div></body></html>");
if ($navid || $nid || $kurs || $liste) echo "<p><a href='javascript:history.back();'>" .backlink() ." zrück</a></p>\n";
elseif ($cid) echo "<p><a href='content_foto.php?back=$back&edit=$cid'>" .backlink() ." zurück</a></p>\n";
elseif ($newsletter) echo "<p><a href='newsletter.php'>" .backlink() ." zurück</a></p>\n";

if (!$navid && !$cid && !$nid && !$kurs && !$liste) echo "<p><p><a href=\"newsletter_image.php\" title=\"back\">" .backlink() ." zurück</a></p></p><p><a href=\"newsletter_img_upload.php?gid=$gid\" title=\"upload new images\"><i class=\"fa fa-upload\"></i> Neue Bilder hochladen</a><br><br></p>";

#
# query
# query
// THUMB ? --------
$query  = "SELECT * FROM morp_newsletter_img_group WHERE gid=$gid";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$thumb = $row->art;


$query  = "SELECT * FROM morp_newsletter_image WHERE gid=$gid ORDER BY imgid DESC";
$result = safe_query($query);

$t = 0;
$x = 0;

if (!$navid && !$nid && !$inr && !$liste) echo '<form method=post><input type="submit" class="button" name="save" value="Bilder in neuen Ordner verschieben" style="width:200px;"><br/>';

$imgdir = "../img/";

while ($row = mysqli_fetch_object($result)) {
	$id = $row->imgid;
	$nm = $row->imgname;
	$ty = $row->type;
	$tx = nl2br($row->longtext);

	$hires = $thumb == 2 ? 1 : 0;

	if ($tx) $tx = "<p class=bild style=\"background-color: silver; padding: 5px;\">$tx</p>";

	if ($nm) {
		$t++;
		$x++;

		echo "<div style=\"float:left;margin: 12 8 0 0; border: solid 1px #7B1B1B; padding:5px; width:400px;\">";

#  create_image($id, $nm, $ty);
	   $th_img = "<img src=\"".($hires ? '../mthumb.php?src=img/'.urlencode($nm).'&w=360' : $imgdir.$nm)."\" border=0 alt=\"$itext\" title=\"$itext\" style=\"margin: 10px; float:left;\">";


  		#$th_img = "<img src=\"blob.php?imgid=$id\" border=0 vspace=6><p>";

		if ($incl_lnk) 		echo "<a href=\"" .$incl_lnk ."&imgid=$id&back=$back&db=$db&imglnk=$imglnk\" name=\"$id\">$th_img";
		elseif ($liste) 		echo "<a href=\"newsletter_edit.php?liste=$liste&edit=$edit&imgid=$imgid&setimg=$id\" title=\"image w&auml;hlen\" name=\"$id\">$th_img";
		elseif ($temp_lnk) 	echo "<a href=\"" .$temp_lnk ."&imgsav=$nm\" name=\"$id\">$th_img";
		elseif ($back) 		echo "<a href=\"content_foto.php?edit=$cid&inr=$inr&back=$back&imgid=$id\" name=\"$id\">$th_img";
		elseif ($nid) 		echo "<a href=\"news.php?edit=$nid&ngid=$ngid&gid=$id\" title=\"image w&auml;hlen\" name=\"$id\">$th_img";
		elseif ($newsletter) 		echo "<a href=\"newsletter.php?&update=$newsletter&img=$nm\" title=\"image w&auml;hlen\" name=\"$id\">$th_img";
		elseif ($id >= 1) 	echo "$th_img<a href=\"newsletter_image_liste.php?del=$id&gid=$gid\" title=\"image l&ouml;schen\" name=\"$id\"><i class=\"fa fa-trash-o\"></i>";

		if (!$back && !$nid && !$navid && !$newsletter && !$kurs && !$liste) echo "</a> &nbsp; <select name=\"gid_$id\">" .morp_newsletter_img_group($gid) ."</select></p>
			$tx\n<p><a href=\"newsletter_image_liste.php?gid=$gid&txt=$id\"><i class=\"fa fa-edit\"></i> <b>$nm</b></a></p>";
		else echo "<br>\n<b>$nm</b></a>";

		echo "</div>";
		# # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	}
}

if (!$navid && !$nid && !$inr && !$kurs && !$liste) echo '</form>
<td></tr><table>
';

if (!$navid && !$nid && !$inr && !$back && !$kurs && !$liste) echo "<div style=\"clear:left;\"><p>&nbsp;</p><p><a href=\"newsletter_img_upload.php?gid=$gid\"><i class=\"fa fa-upload\"></i> Neue Bilder hochladen</a></p></div>";
?>
</div>

<?php
include("footer.php");
?>