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


# content_edit.php?stelle=3&edit=1&imgid=16&back=ebene;:;1;;p_0;:;0;;n_0;:;Hauptnavigation&db=content#3

$pgid 	 = $_REQUEST["pgid"];
if (!$pgid) $db = "morp_cms_pdf_group";
else $db = "morp_cms_pdf";

# # # von content pflege kommend
$pid	= $_REQUEST["pid"];
$imglnk = $_REQUEST["imglnk"];
$back	= $_REQUEST["back"];
$stelle	= $_REQUEST["stelle"];
$cid	= $_REQUEST["cid"];
$back	= $_REQUEST["back"];
$bck 	= repl(";;", "&", $back);
$bck 	= repl(";:;", "=", $bck);

# # # von termin listen kommend
$abt	= $_REQUEST["abt"];
$tid	= $_REQUEST["tid"];
$pos	= $_REQUEST["pos"];
$liste	= $_REQUEST["liste"];

# # # von news kommend
$nid	= $_REQUEST["nid"];
$ngid	= $_REQUEST["ngid"];

if ($back) 		$link  = "content_edit.php?edit=$cid&db=content&back=$back";
elseif($nid)	$link  = "news.php?edit=$nid&ngid=$ngid";
else			$link  = "termine.php?db=termine&liste=$liste&tid=$tid&abt=$abt";

echo "<div id=content_big><p><a href=\"$link\">&laquo; zurück</a></p><p>&nbsp;</p>";

if (!$pgid) {
	echo "<p><b>Wähle Download Gruppe</b></p>";
	$query  = "SELECT * FROM $db ORDER BY pgname";
	$result = safe_query($query);
	while ($row = mysqli_fetch_object($result)) {
		if ($back) echo '<p>' .ilink().' <a href= "pdf_select.php?pgid='.$row->pgid.'&stelle='.$stelle.'&cid='.$cid.'&back='.$back.'&imglnk='.$imglnk.'">'.$row->pgname.'</a></p>';
		elseif ($nid) echo '<p>' .ilink().' <a href= "pdf_select.php?pgid='.$row->pgid.'&nid='.$nid.'&ngid='.$ngid.'">'.$row->pgname.'</a></p>';
		else echo '<p>' .ilink().' <a href= "pdf_select.php?pgid='.$row->pgid.'&pos='.$pos.'&liste='.$liste.'&tid='.$tid.'&abt='.$abt.'">'.$row->pgname.'</a></p>';
	}
}
elseif ($pgid) {
	echo "<p><b>Wähle Download File</b></p>";
	$query  = "SELECT * FROM $db WHERE pgid=$pgid ORDER BY pname ";
	$result = safe_query($query);
	while ($row = mysqli_fetch_object($result)) {
		if ($back) echo '<p><a href= "content_edit.php?pid='.$row->pid.'&stelle='.$stelle.'&edit='.$cid.'&back='.$back.'&db=content&imglnk='.$imglnk.'">' .ilink().'&nbsp;'.$row->pname.'</a></p>';
		elseif($nid) echo '<p><a href= "news.php?pid='.$row->pid.'&edit='.$nid.'&ngid='.$ngid.'">' .ilink().'&nbsp;'.$row->pname.'</a></p>';
		else echo '<p><a href= "termine.php?pid='.$row->pid.'&pos='.$pos.'&liste='.$liste.'&tid='.$tid.'&abt='.$abt.'&db=termine">' .ilink().'&nbsp;'.$row->pname.'</a></p>';
	}
}


?>

</div>

<?
include("footer.php");
?>