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

echo '<div id="content_big" class="text">';

$id 	 = $_GET["id"];
$bereich = $_GET["bereich"];
$rWert	 = $_GET["rWert"];
$edit	 = $_GET["bearbeiten"];
$pfad 	 = $_GET["pfad"];
$new 	 = $_GET["new"];
$feld 	 = $_GET["feld"];
$order 	 = $_GET["order"];
$rf  	 = $_GET["rf"];

if (!$pfad) $pfad = "pdf";

echo $pfad ;

echo "<a href=\"javascript:history.back();\">" .backlink() ." zurück</a><p>";
echo '<b>W&auml;hlen Sie ein PDF</b><p>
	<p>&nbsp;</p>
';


$dir = opendir ("../$pfad");
$arr = array();
$ord = array();

// seminar-edit.php?pdf=Traegerzulassung_AZWV.pdf&feld=&bereich=1&pfad=pdf/AZWV&bearbeiten=&rWert=&new=


while (false !== ($tmp = readdir($dir))) {
	if ($tmp != "." && $tmp != "..") {
		$pdf = explode(".", $tmp);
		$x 	 = count($pdf);
		$x	 = $x-1;
		$chk = strtolower($pdf[$x]);
		if ($rWert && ($chk == "pdf" || $chk == "ics")) $arr[$tmp] = "<a href='seminar-edit.php?pdf=$tmp&feld=$feld&bereich=$bereich&pfad=$pfad&bearbeiten=$edit&rWert=$rWert&new=$new'>" .ilink() ." $tmp</a><p>";	
		elseif ($chk == "pdf") $arr[$tmp] = "<a href='download-edit.php?pdf=$tmp&id=$id&pfad=$pfad&bereich=$bereich&new=$new'>" .ilink() ." $tmp</a><p>";	
		else $ord[$tmp] = "<a href='pdf_dir.php?pfad=".$pfad.'/'.$tmp."&feld=$feld&id=$id&bereich=$bereich&new=$new&rWert=$rWert&bearbeiten=$edit'>" .ordner() ." $tmp</a><p>";	
	}
}	 

sort($arr);
sort($ord);

foreach($ord as $key=>$val) {
	echo $val;
}
foreach($arr as $key=>$val) {
	echo $val;
}


closedir($dir); 

?>


<?
include("footer.php");
?>