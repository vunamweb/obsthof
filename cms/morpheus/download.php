<?php
include("../nogo/config.php");
header("Content-type: application/force-download");
/**
* SubModul Download von Periodicals
*
* Zum Verschicken der Dateien im HTML-Header
* 
* Wichtig!! wenn diese Datei ausgeführt wir, dann dürfen KEINE
* Ausgaben (echo) gemacht werden, da sonst die zu downloadende
* Datei zerstört wird!
*
* @author Daniel Nelle <dnelle@web.de>
* @package modules
* @subpackage periodicals
*/
/**
*/

if(!isset($_GET["dfile"]) ) exit;

$nm   		= $_GET["dfile"];
$download 	= $nm;


// Passenden Dateinamen im Download-Requester vorgeben,
// z. B. den Original-Dateinamen

header("Content-Disposition: attachment; filename=".$nm);
  // Datei ausgeben.
@readfile($download);
unlink($download);
unlink($morpheus["dfile"]);
exit; ## sehr wichtig!!!! Da sonst alle anderen Ausgaben auch in der
# Datei landen, und diese somit zerstören!
## KEINE Leerzeilen nach dem PHP-Code-Ende-Tag !!!!!
