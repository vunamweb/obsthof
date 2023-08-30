<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # # 
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
# edit 27.11.2006                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # # 

session_start();
$box = 1;

//error_reporting(E_ALL);
include("cms_include.inc");

$vt = $_GET["vt"];	
$pfad = '../nlverteiler/';
	
include 'xlsx.inc.php';

?>

<style>
* {
	font-family: arial;
	font-size: 10px;
}
td {
	border: solid 1px #e2e2e2;
	padding: 2px;
}
</style>

<div id=vorschau>

<p><br><b>Vorhandene Felder</b></p>

<?php 
	$edit = $_GET["edit"];

/*
	echo '<pre>';

	$bezeichnung = spaltenBez ($xlsx);
	print_r($bezeichnung);
	$bezeichnung = array_flip($bezeichnung);
	
	echo '</pre><br><br>';
*/	
	/*
	$nm = $bezeichnung["name"];
	$fa = $bezeichnung["firma"];
	$em = $bezeichnung["email"];
	$ausgabe = spaltenInhalte ($xlsx, array($nm, $fa, $em),3,4);
	print_r($ausgabe);
	*/	
?>

<br><br>

<p><a href="newsletter.php?edit=<?php echo $edit; ?>">&laquo; zurück</a></p>
	


<?php
	xlsxTable ($xlsx);
?>

</div>

</body>
</html>