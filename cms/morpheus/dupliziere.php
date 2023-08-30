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

$oldlang = 1;
$newlang = 2;

$que  	= "SELECT * FROM `morp_cms_nav` WHERE lang=$oldlang ORDER BY navid ASC";
$res 	= safe_query($que);

$arr_navid = array();
$arr_S = array();

//
$nav_arr = array("ebene","sort","name","title","lnk","sichtbar","edit","keyw","desc","lock","bereich","button","emotional","design", "fbimage", "anker", "setlink", "nocontent", "accesskey", "blog", "published", "updated_dat" );
$cont_arr = array("content","edit","pos","img0","img1","img2","img3","layout","img4","img5","img6","tid","ton","tpos","tlink","tbackground","timage", "theadl", "theight", "twidth", "tcolor", "tref", "vorlage", "vorl_name", "vid", "tende", "tabstand");



while ($rw 	= mysqli_fetch_object($res)) {
	unset($set);
	foreach ($nav_arr as $val) {
		$set .= "`".$val."`='".$rw->$val."', ";
	}

	if ($arr_navid[$rw->parent]) $set .= "parent=".$arr_navid[$rw->parent].", ";

	$set .= "langpar=".$rw->navid.", ";

	$que  	= "INSERT `morp_cms_nav` SET $set lang=$newlang";
		echo "<p><u>---".$rw->parent."---</u></p><h2>$que</h2>";
	$rs 	= safe_query($que);
	$navid	= mysqli_insert_id($mylink);

	$arr_navid[$rw->navid] = $navid;

	$que  	= "SELECT * FROM `morp_cms_content` WHERE navid=".$rw->navid."";
	$rs 	= safe_query($que);
	$menge	= mysqli_num_rows($rs);

	if ($menge > 0) {
		while ($rx 	= mysqli_fetch_object($rs)) {
			unset($set);
				foreach ($cont_arr as $val) {
					$set .=  "`".$val."`='".addslashes($rx->$val)."', ";
				}
			$que  	= "INSERT `morp_cms_content` SET $set navid=".$navid;
			echo "<p>$que</p>";
//			safe_query($que);
		}
	}
}

print_r($arr_navid);
echo "<h1>fertig</h1>";
