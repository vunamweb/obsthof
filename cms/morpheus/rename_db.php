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


$arr = array (
"dokumente",
"dozenten",
"doz_fb",
"fachbereiche",
"kurs_art",
"orte",
"projekt",
"projekt_user",
"rechnungnr",
"seminar",
"seminar_detail",
);

foreach ($arr as $val) {
	$sql = "RENAME TABLE `".$val."` TO `ec_".$val."`" ;
	safe_query($sql);
}


$sql = "ALTER TABLE `ec_seminar` ADD `pdf2` VARCHAR( 255 ) NOT NULL AFTER `morp_cms_pdf`" ;
safe_query($sql);

?>