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

include("cms_include.inc");

function morp_cms_img_group ($inr, $edit, $back) {
	$query  = "SELECT * FROM `morp_cms_img_group` order by name";
	$result = safe_query($query);
	while ($row = mysqli_fetch_object($result)) {
	 	$id = $row->gid;
		$nm = $row->name;
		if (isin("rechts", $nm)) $tmp .= "Anderes Foto: <a href='image_liste.php?inr=$inr&cid=$edit&gid=$id&back=$back'>$nm</a><br>\n";
	}
	return $tmp;
}

# get requests
#$db 	= $_REQUEST["db"];
$edit	= $_REQUEST["edit"];
$save	= $_REQUEST["save"];
$inr	= $_REQUEST["inr"];
$del	= $_REQUEST["del"];
$imgid	= $_REQUEST["imgid"];
$nm		= $_REQUEST["seite"];
$ebene	= $_REQUEST["ebene"];

$back	= $_REQUEST["back"];
$bck 	= repl(";;", "&", $back);
$bck 	= repl(";:;", "=", $bck);

$link  = "navigation.php?$bck";

$db 	= "morp_cms_nav";
$getid 	= "navid";

# _historie
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

# neu gewaehltes deko-bild wird in db geschrieben
if ($inr >= "0" && $imgid) {
	$query = "UPDATE `morp_cms_nav` set emotional=$imgid, edit=1 where navid=$edit";
	$result = safe_query($query);
}
# bild rechts loeschen
elseif ($del) {
	# if ($imgid) $query = "UPDATE `morp_cms_content` set img" .$imgid ."=1, edit=1 where cid=$edit";
	$query = "UPDATE `morp_cms_nav` set emotional=0, edit=1 where navid=$edit";
	$result = safe_query($query);
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if ($edit) {
	$query  = "SELECT * FROM $db WHERE $getid=$edit";
	$result = safe_query($query);
	$row 	= mysqli_fetch_object($result);
	$tmp 	= $row->emotional;

	# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

	echo "<div id=content_big><table border=\"0\" cellpadding=\"0\">
		<tr>
			<td colspan=2 class=text>";
	if ($back) echo "<b>Bildverwaltung</b><br>$nm<p><a href=\"$link\">< zurück</a><p><p>&nbsp;</p>";


	if ($tmp)
			echo "<p style=\"clear:left;\"><img src=\"blob.php?imgid=".$tmp."\" border=0 vspace=10 hspace=10 align=left><br>
					".morp_cms_img_group ($i, $edit, $back)."</p>
					<p><a href=\"content_foto.php?imgid=1&del=1&edit=$edit&back=$back\"><img src=\"images/delete.gif\" alt=\"\" width=\"9\" height=\"10\" border=\"0\"></a></p>
";
	else echo "<p style=\"clear:left;\"><a href='image.php?inr=1&cid=$edit&back=$back'>" .ilink() ." Bild bestimmen [keine Pflicht!]</a></p>";



	echo "</td>
			<td>&nbsp;</td>
		</tr>
		</table>";
}
?>

</div>

<?php
include("footer.php");
?>