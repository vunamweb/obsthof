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

$art 		= $_GET["art"];
$up 		= $_GET["up"];
$down 		= $_GET["down"];
$bereich 	= $_GET["bereich"];
$id 		= $_GET["id"];

if ($up) {	
	$newPos01 	= $up - 1;
	$newPos02 	= $up;
}
else	{	
	$newPos01 	= $down + 1;
	$newPos02 	= $down;
}

if (empty($art)) {
	echo "<div id=vorschau><p>Die Reihenfolge kann nur bei Dokumenten ge&auml;ndert werden, die einer Fachbereich-Untergruppe angeh&ouml;ren!<p>
		<a href=\"admin-download.php?bereich=$bereich\">&raquo; weiter</a></p></div>";
	die();
}

$query 		= "update ec_dokumente set prior ='$newPos02' where bereich = '$bereich' AND prior = '$newPos01' AND art = '$art'";		
$query_		= "update ec_dokumente set prior ='$newPos01' where id='$id'";		

safe_query($query);  			
safe_query($query_);  			
	
// #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #
//           js-sprung zu ausgangsseite
// #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #

echo "<script language='javascript'>\ndocument.location = 'admin-download.php?bereich=$bereich'\n</script>";
	 
?>
 

</font>
<?
include("footer.php");
?>