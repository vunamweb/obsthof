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

echo '<div id=content class=text><p><b>Dozenten Verwaltung</b></p>
	<p><a href="admin-dozenten.php?bereich=1&bereich_desc=WP&sorted=name" class="nav" title="Dozenten-Datei bearbeiten">' .ilink() .' dozenten bearbeiten</a></p>

	<p><a href="dozenten-fb.php" class="nav" title="Fachbereiche verwalten">' .ilink() .' fachbereiche verwalten</a></p>
	

';

	
?>

</div>
</font>
<?
include("footer.php");
?>