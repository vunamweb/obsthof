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

# print_r($_POST);

$neu 	 = $_REQUEST["neu"];
$edit	 = $_REQUEST["edit"];
$save	 = $_REQUEST["save"];

echo "<div id=content class=text>\n<h2>Ordnerverwaltung Bilder</h2><br>";

if ($save) {
	$name = $_POST["name"];
	$thumb = isset($_POST["art"]) ? 2 : 1;

	if ($neu) $query = "insert ";
	else $query = "update ";

	$query .= "morp_cms_img_group set name='$name', art=$thumb ";
	if ($edit) $query .= " where gid=$edit";

	safe_query($query);

	echo "<script language='javascript'>
			document.location = 'image.php?log=$log';
		</script>";

}

if ($edit) {
	$query  = "SELECT * FROM morp_cms_img_group where gid=$edit";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
	$id = $row->gid;
	$nm = $row->name;
	$thumb = $row->art;
}


echo "<form method=\"post\" name=\"imageordner\">
	<input type=\"hidden\" name=\"save\" value=\"1\">
	<input type=\"hidden\" name=\"edit\" value=\"$id\">
	<input type=\"hidden\" name=\"neu\" value=\"$neu\">"
	.table(8,0,300)
	."
		<tr><td><p><input type=\"text\" name=\"name\" value='$nm' 0></p></td><td nowrap><p>Name, des Ordner</p></td></tr>
		<!-- <tr><td><p><input type='checkbox' name=\"art\" value='1' ".($thumb == 2 ? ' checked' : '').">  Thumbnail</p></td><td nowrap><p></p></td></tr> -->
	  <tr><td>&nbsp;</td></td><td></tr>
	  <tr><td><input type=\"submit\" name=\"erstellen\" value=\"speichern\"></td></td><td></tr>
	</table></form>
		";

echo "<p>&nbsp;</p><p><a href=\"image.php?log=$log\" title=\"zur&uuml;ck\" class=\"btn btn-info \">" .backlink() ." zur&uuml;ck</a></p>";

?>

</div>

<?
include("footer.php");
?>