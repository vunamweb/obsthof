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

$edit	= $_REQUEST["edit"];
$cid 	= $_REQUEST["cid"];

echo "<div id=content_big class=text>\n<p><b>W&auml;hlen Sie bitte die Zielseite</b></p>";

echo "<p><a href=\"content.php?edit=$edit\" title=\"zur&uuml;ck\">" .backlink() ." zur&uuml;ck zur navigations-verwaltung</a></p>";



$query  = "SELECT * FROM `morp_cms_content` WHERE navid=$cid ORDER BY `tpos`";
$result = safe_query($query);

echo '<table border=0 cellspacing=1 cellpadding=0 class="autocol p20">';

while ($row = mysqli_fetch_object($result)) {
	$id = $row->cid;
	$nm = $row->theadl;
	$co = substr(get_raw_text_morp($row->content),0,150);
	$tid = $row->tid;

	$vid = $row->vid;
	if($vid) {
		$sql  = "SELECT content FROM `morp_cms_content` WHERE cid=$vid";
		$res = safe_query($sql);
		$rw = mysqli_fetch_object($res);
		$co = substr(get_raw_text_morp($rw->content),0,150);
	}

	echo "<tr>
		<td width=180>". ($vid ? 'Vorlage' : $morpheus["template"][$tid]) ."</td>
		<td>$co</td>
		<td width=180><a href=\"content.php?edit=$edit&amp;copy=".($vid ? $vid : $id)."&".($vid ? "vorl=".$vid : '')."\">" .ilink() ." dieses template w&auml;hlen</a></td>
	</tr>";

}

?>
</table>
</div>

<?php
	include("footer.php");
?>