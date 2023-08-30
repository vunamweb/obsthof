<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # # 
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # # 

include("cms_include.inc");

echo "\n\n<div id=content_big class=text>\n<p><strong>IMPORT</strong></p>\n\n";
echo "<b>Daten werden auf dem Server aktualisiert!</b></p>\n";
// _body

ob_end_flush();
echo "<p><strong>online update</strong></p><p>&nbsp;</p>
<p>Aktueller Datensatz <strong>$sqldata</strong> - Letzter Datensatz: <strong>$anz</strong></p><p>&nbsp;</p>";

flush();

$txt = fopen($morpheus["dfile"], "r");		        //oeffne textdatei zum auslesen der daten
$x = 0;
while (!feof($txt)) {
	$zeile = fgets($txt,4096);
	if ($zeile) { 		// schreibe in die db
		$x = $x+1;
		$result = safe_query($zeile);
		if (!$result) echo "<b>$x: </b>$zeile<p>";
	} 
}

fclose ($txt);
unlink($morpheus["dfile"]);

echo "Import abgeschlossen";

?>

</body>
</html>
