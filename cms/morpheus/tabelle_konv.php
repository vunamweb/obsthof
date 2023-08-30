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

include("../nogo/config.php");

include("cms_header.php");
include("../nogo/db.php");
dbconnect();

include("../nogo/funktion.inc");
include("cms_navigation.php");

echo "\n\n<div id=content_big class=text>\n<p><b>Tabelle erstellen und editieren</b></p>";

$query  	= "SELECT * FROM tabelle";
$result 	= safe_query($query);

while ($row 		= mysqli_fetch_object($result)) {
	$tabid		= $row->tabid;
	echo $text 		= $row->tabtext;
	echo "<p>&nbsp;</p>";
	$text		= repl("\|", "¿", $text);
	echo $text		= repl("\+", "°", $text);
	echo "<p>&nbsp;</p>";
	$sql  		= "update tabelle set tabtext='$text' where tabid=$tabid";
	safe_query($sql);
}	

?>

<?
include("footer.php");
?>