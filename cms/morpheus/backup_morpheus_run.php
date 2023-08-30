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

$path = __DIR__;
$path = explode("/", $path);
$x = count($path);
$x = $x-2;
$zippath = $path[$x];

// echo $zippath;

$zipFileName = "backup-morpheus.zip";
$zipFile = $zipFileName;
unlink($zipFile);

// zu zippender ordner
$folder = "../../$zippath/";

// file und dir counter
$fc = 0;
$dc = 0;

// die maximale Ausführzeit erhöhen
ini_set("max_execution_time", 300);

// Objekt erstellen und schauen, ob der Server zippen kann
$zip = new ZipArchive();
if ($zip->open($zipFile, ZIPARCHIVE::CREATE) !== TRUE) {
	die ("Das Archiv konnte nicht erstellt werden!");
}

// echo "<pre>";
// Gehe durch die Ordner und füge alles dem Archiv hinzu
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));
foreach ($iterator as $key=>$value) {
	// echo "Key -- $value <br>";

  if(!is_dir($key)) { // wenn es kein ordner sondern eine datei ist
    // echo $key . " _ _ _ _Datei wurde übernommen</br>";
    $zip->addFile(realpath($key), $key) or die ("FEHLER: Kann Datei nicht anfuegen: $key");
    $fc++;

  } elseif (count(scandir($key)) <= 2) { // der ordner ist bis auf . und .. leer
    // echo $key . " _ _ _ _Leerer Ordner wurde übernommen</br>";
    $zip->addEmptyDir(substr($key, -1*strlen($key),strlen($key)-1));
    $dc++;

  } elseif (substr($key, -2)=="/.") { // ordner .
    $dc++; // nur für den bericht am ende

  } elseif (substr($key, -3)=="/.."){ // ordner ..
    // tue nichts

  } else { // zeige andere ausgelassene Ordner (sollte eigentlich nicht vorkommen)
    echo $key . "WARNUNG: Der Ordner wurde nicht ins Archiv übernommen.</br>";
  }
}
// echo "</pre>";

// speichert die Zip-Datei
$zip->close();

// bericht
// echo "<h4>Das Archiv wurde erfolgreich erstellt.</h4>";
// echo "<p>Ordner: " . $dc . "</br>";
// echo "Dateien: " . $fc . "</p>";

?>

<script>
	location.href="download.php?dfile=<?php echo $zipFile; ?>";
</script>
