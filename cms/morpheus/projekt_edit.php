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

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 

echo '<div id=content_big class=text>';

$data	= $_POST["data"];

$delete	= $_GET["delete"];
$new	= $_GET["new"];
$save	= $_GET["projekt"];

if ($new) {
	echo "Bitte geben Sie eine Bezeichnung f&uuml;r das Projekt an
		<form method=get><input type=text name=projekt size=50><p>
			<input type=submit name=save value=speichern></form><p>
			<a href=\"projekt_edit.php\">" .backlink() ." zur&uuml;ck</a>";
}

elseif ($save) {
	$query = "insert projekt set name='$save'";
	$result = safe_query($query);
	echo "<a href=\"projekt_edit.php\">" .ilink() ." weiter</a>";
}

elseif ($data) {
	echo "Sind Sie sich sicher, dass Sie das Projekt und <br>
		alle dazu geh&ouml;rigen Verbindungen l&ouml;schen m&ouml;chten?<p>
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href=\"projekt_edit.php?delete=$data\">Ja</a>
		&nbsp; &nbsp; &nbsp; &nbsp; <a href=\"projekt_edit.php\">Nein</a>";
}

elseif ($delete) {
	$query = "delete FROM projekt where id=$delete";
	safe_query($query);

	$query = "delete FROM projekt_user where projekt=$delete";
	safe_query($query);

	$delete = 0;
	$id = 0;
}

if (!$data && !$new && !$save) {
	$query = "SELECT * FROM projekt order by id";	
	$result = safe_query($query);
	
	echo "<form method=post><b>Projekte verwalten</b>
		<table cellpadding=0 cellspacing=0 border=0>
			<tr>
				<td><select name=\"data\" multiple size=36 style=\"width:300\">\n";

	while ($row = mysqli_fetch_array($result)) {
		$nm = $row["name"];
		$id = $row["id"];
		echo "<option value=$id>$id = $nm</option>\n";
	}
	
	echo '</select><p>
		<p><input type=submit name="del" value="Projekt l&ouml;schen"></td>
	</tr></table>			
	</form>
	<a href="projekt_edit.php?new=1">' .ilink() .' neues projekt anlegen</a>';
}

#echo "<p><a href=\"index.php\">" .backlink() ." zur&uuml;ck</a>";

?>

<?
include("footer.php");
?>