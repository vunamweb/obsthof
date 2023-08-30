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

include("cms_include.inc");

$from	= $_REQUEST["from"];
$edit 	= $_REQUEST["id"];
$tbl  	= $_REQUEST["tbl"];
$col  	= $_REQUEST["col"];
$prim  	= $_REQUEST["prim"];

echo '<div id="content">
	<h2>Bildarchiv</h2>';

echo '<p class="mt2"><a href="'.$from.'.php?edit='.$edit.'"><i class="fa fa-arrow-circle-left"></i> zur&uuml;ck</a></p>';

$query  = "SELECT * FROM morp_cms_img_group ORDER BY name";
$result = safe_query($query);

echo '<table class="autocol p10" id="sverw">';

$x = 0;
$y = mysqli_num_rows($result);

while ($row = mysqli_fetch_object($result)) {
	$id = $row->gid;
	$nm = $row->name;
	if ($nm) {
		$x++;
		echo '
		<tr>
			<td width="50" align="center">'.$x.'</td>
			<td valign="top">
				<a href="image_select.php?gid='. $id .'&id='. $edit .'&tbl='. $tbl .'&from=' .$from. '&col='. $col .'&prim=' . $prim .'">'.$nm.'</a>
			</td>
		</tr>';
	}
}

echo '</table>';

?>
</div>
<?php
include("footer.php");
