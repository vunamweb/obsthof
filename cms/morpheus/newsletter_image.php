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

$newsletter_in = 'in';
include("cms_include.inc");

$del 	 = $_REQUEST["del"];
$delete	 = $_REQUEST["delete"];

# wenn bild in content eingesetzt wird
$back	= $_REQUEST["back"];
$imglnk = $_REQUEST["imglnk"];
$stelle = $_REQUEST["stelle"];
$navid  = $_REQUEST["navid"];
$edit  = $_REQUEST["edit"];
$cedit  = $_REQUEST["cedit"];
$db		= $_REQUEST["db"];
$art	= $_REQUEST["art"];
$vorlage= $_REQUEST["vorlage"];
$liste	= $_REQUEST["liste"];
$imgid	= $_REQUEST["imgid"];

# wenn bild in news eingesetzt wird
$nid	= $_REQUEST["nid"];
$ngid	= $_REQUEST["ngid"];

$newsletter = $_REQUEST["newsletter"];

if ($_GET["db"] == "ec_kurs_art") $kurs = 1;

# deko bilder bestimmen
$inr 	= $_REQUEST["inr"];
$cid	= $_REQUEST["cid"];
$back	= $_REQUEST["back"];


if ($navid || $kurs)  $incl_lnk = "newsletter_image_liste.php?stelle=$stelle&navid=$navid&edit=$edit&cedit=$cedit&vorlage=$vorlage";

//$status = "admin";

if ($del && $status == "admin") {
	$warnung = "<p><font color=#ff0000><b>Wollen Sie den Content wirklich l&ouml;schen?</b></font></p>
				<a href=\"image.php?delete=$del\" title=\"Content l&ouml;schen!\">" .ilink() ." endg&uuml;ltig l&ouml;schen</a>";
}
# ein imageordner wird endg&uuml;ltig gel&ouml;scht
elseif ($delete && $status == "admin") {
	$query = "delete from morp_newsletter_img_group WHERE gid=$delete";
	safe_query($query);
}

echo "<div id=content_big class=text>\n<p><b>Image folder</b></p>";
if ($warnung) die ($warnung ."</div></body></html>");

if ($nid) 		echo "<p><a href='news.php?edit=$nid&ngid=$ngid'>" .backlink() ." back</a></p>\n";
elseif ($back) 	echo "<p><a href='content_foto.php?edit=$cid&db=content&back=$back'>" .backlink() ." back</a></p>\n";

$query  = "SELECT * FROM morp_newsletter_img_group ORDER BY name";
$result = safe_query($query);

echo "<table border=0 cellspacing=1 cellpadding=0 class='autocol p20'>";

$x = 0;
$y = mysqli_num_rows($result);

while ($row = mysqli_fetch_object($result)) {
	$id = $row->gid;
	$nm = $row->name;

	if ($nm) {
		$x++;
		echo "<tr>
			<td width=20 align=left><a>$x</a></td>
			<td width=350 valign=top>";

#		image_liste.php?inr=2&cid=$edit&gid=4&back=$back

		if ($inr) $lnk = "<a href=\"newsletter_image_liste.php?gid=$id&inr=$inr&cid=$cid&back=$back&vorlage=$vorlage\" name=\"$id\">";
		elseif ($incl_lnk) $lnk = "<a href=\"" .$incl_lnk ."&gid=$id&back=$back&db=$db&imglnk=$imglnk&art=$art\" name=\"$id\">";
		elseif ($liste) $lnk = "<a href=\"newsletter_image_liste.php?gid=$id&liste=$liste&imgid=$imgid&edit=$edit\" name=\"$id\">";
		elseif ($nid) $lnk = "<a href=\"newsletter_image_liste.php?gid=$id&nid=$nid&ngid=$ngid\" name=\"$id\">";
		elseif ($newsletter) $lnk = "<a href=\"newsletter_image_liste.php?gid=$id&newsletter=$newsletter\" name=\"$id\">";
		elseif ($kurs) $lnk = "<a href=\"newsletter_image_liste.php?gid=$id&kurs=1\" name=\"$id\">ccccc";
		else $lnk = "<a href=\"newsletter_image_liste.php?gid=$id\" title=\"images verwalten, uploaden l&ouml;schen\">";

		echo "$lnk $nm</a>\n";

		if ($admin && !$back && !$nid && !$kurs && $db != "template" && !$liste) echo "&nbsp; <a href=\"newsletter_image_ordner.php?edit=$id\" title=\"image editieren\"><i class=\"fa fa-cogs\"></i></a>
			<td width=50 align=center>$lnk".'<i class="fa fa-pencil-square-o"></i>'."
<!--			<td width=50 align=center><a href=\"image.php?del=$id\" title=\"image l&ouml;schen\"><i class=\"fa fa-trash-o\"></i></a>-->";

		echo "</td>
		</tr>\n";

		if ($ct == 0) $ct = 1;		//farbendefenition
		else $ct = 0;
	}
}
echo "</table>";
$new = "<p><a href=\"newsletter_image_ordner.php?neu=1\" title=\"create new folder\">" .ilink() ." NEW</a></p>";

if ($admin && !$back && !$nid && !$kurs) echo $new;
elseif (!$admin && $ggid && !$back && !$nid && !$kurs) echo $new;

echo '<p>&nbsp;</p>
<!-- <p><a href="image_top.php" title="Top Bilder verwalten">'. ilink().' Top Bilder verwalten</a></p> -->
';

?>

</div>

<?php
include("footer.php");
?>