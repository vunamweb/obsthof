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

$myauth = 10;

include("cms_include.inc");


$neu 	 = $_REQUEST["neu"];
$edit	 = $_REQUEST["edit"];
$name	 = $_REQUEST["name"];
$save	 = $_REQUEST["upload"];
$dir 	 = '../images/top';

echo "<div id=content class=text>\n<p><b>Verwaltung Top-Bilder</b></p>
";

if ($save) {
	$tmp = $_FILES['image']['tmp_name'];
	$img  = strtolower($_FILES['image']['name']);

	# type auswerten
	$type_ = explode(".", $img);
	$type_ = $type_[1];
	if ($type_ != "jpg")	die("upload von unbekannten datenformat");
	# _type

	if (!copy($tmp, $dir."/$name")) die("upload fehlgeschlagen!");
#	else echo "did it $dir $name";
	chmod($dir."/$name", 0777);
	unlink($tmp);
}

if ($edit || $neu) {
	echo '<p>&nbsp;</p>
	<p><strong>Folgende Größen werden verwendet</strong><br>
	&nbsp;	</p>
	<table>
	<tr><td><p>Kleines Top-Bild und MindFlash &nbsp; &nbsp; </p></td><td><p><strong>669 x 75</strong></p></td></tr>
	<tr><td><p>Großes Top-Bild</p></td><td><p><strong>669 x 226</strong></p></td></tr>
	</table>
	<p>&nbsp;</p>
	<form action="image_top.php" method=post enctype="multipart/form-data"><input type="hidden" name="name" value="'.$edit.'">
		<p><input name="image" type="file" style="width:500px"></p>
		<p><input type="submit" class="button" name="upload" value="upload"></p>
		<p>&nbsp;</p>
	</form>
	';

	if (!$neu) echo "<p><u>Auszutauschendes Bild</u><br>
	<img src=\"$dir/$edit\" vspace=10 border=0></p>";
}

else {
	echo "<p>Zum Austauschen, eines Top-Bildes, bitte auf das Bild klicken,</p>
	<p><!-- oder für ein <strong>neues Bild</strong> <a href=\"image_top.php?neu=1\" title=\"Neu\">".ilink()." hier</a> klicken --></p>
	";

	$ord = opendir($dir);
	$arr = array();

	while ($name = readdir($ord)) {
		if ($name != "." && $name != ".." && !preg_match("/.db/", $name)) $arr[] = $name;
	}
	sort ($arr);

	foreach ($arr as $val) {
		echo "<p><a href=\"image_top.php?edit=$val\" title=\"$val\"><img src=\"$dir/$val\" vspace=10 border=0></a></p>";
	}

	echo "<p><a href=\"image.php?log=$log\" title=\"zurück\">" .backlink() ." zurück</a></p>";
}

?>

</div>

<?
include("footer.php");
?>