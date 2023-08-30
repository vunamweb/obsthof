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

include("cms_header.php");
include("login.php");

$counter = $_REQUEST["counter"];

$absenden 	= $_GET["absenden"];
$seite		= $_GET["seite"];
$liste		= $_GET["liste"];
$bis		= $_GET["bis"];

echo '<div id=content class=text><p><a href="index.php">&laquo; startseite</a></p><p>&nbsp;</p>';

if ($_REQUEST["start"] != "go") {
	echo '
	<form action="versenden.php" method="GET">
		<input type="Hidden" name="start" value="go">
		<input type="Submit" name="newsletter versenden" value="newsletter versenden">
		<p style="widht: 500px; height: 400px; overflow:auto;">&nbsp;<br><u>Newsletter wird an folgende Adressen versendet:</u><br>&nbsp;<br>
';
	$txt = fopen("adress.txt","r");
	
	while (!feof($txt)) {
		$zeile = trim(fgets($txt,4096));
		$dat   = $zeile;
		echo "$dat<br>";
	}

	echo '</p></form>
	</body>
	</html>
	';
}

?>

