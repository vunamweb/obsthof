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

# print_r($_POST);

$neu 	 = $_REQUEST["neu"];
$edit	 = $_REQUEST["edit"];
$save	 = $_REQUEST["save"];

echo "<div id=content class=text>\n<p><b>Bildordner</b></p>";

if ($save) {
	$name = $_POST["name"];
	$thumb = isset($_POST["art"]) ? 2 : 1;

	if ($neu) $query = "INSERT ";
	else $query = "UPDATE ";

	$query .= "morp_newsletter_img_group SET name='$name', art=$thumb ";
	if ($edit) $query .= " WHERE gid=$edit";

	safe_query($query);

	echo "<script language='javascript'>
			document.location = 'newsletter_image.php?log=$log';
		</script>";

}

if ($edit) {
	$query  = "SELECT * FROM morp_newsletter_img_group WHERE gid=$edit";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
	$id = $row->gid;
	$nm = $row->name;
	$thumb = $row->art;
}


echo "<form method=post name=imageordner>
	<input type=hidden name=save value=1>
	<input type=hidden name=edit value=$id>
	<input type=hidden name=neu value=$neu>"
	.table(8,0,300)
	."
		<tr><td><p><input type=text name=name value='$nm' size=30></p></td><td nowrap><p>Ordnername</p></td></tr>
		<tr><td><p><input type='checkbox' name=art value='1' ".($thumb == 2 ? ' checked' : '').">  Thumbnail</p></td><td nowrap><p></p></td></tr>
	  <tr><td><input type=submit style=\"background-color:#7B1B1B;color:#FFFFFF;font-weight:bold;width:100px;\" name=erstellen value=speichern style=\"width:70;background-color:#BBBBBB;\"></td></td><td></tr>
	</table></form>
		";

echo "<p>&nbsp;</p><p><a href=\"newsletter_image.php?log=$log\" title=\"back\">" .backlink() ." zurück</a></p>";

?>

</div>

<?php
include("footer.php");
?>