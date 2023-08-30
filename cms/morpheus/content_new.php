<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

include("cms_include.inc");

function repair_nav($navid) {
	$arr 		= array();
	$xx 		= 0;
	$sql  		= "SELECT * FROM `morp_cms_content` WHERE navid=$navid ORDER BY tpos";
	$res 		= safe_query($sql);

	while ($rw = mysqli_fetch_object($res)) $arr[] = $rw->cid;

	foreach ($arr as $val) {
		$xx++;
		$sql  = "UPDATE `morp_cms_content` set tpos=$xx WHERE cid=$val";
		$res = safe_query($sql);
	}
//	repairall();
}

$edit = isset($_GET["edit"]) ? $_GET["edit"] : 0;
$pos = isset($_GET["position"]) ? $_GET["position"] : 0;
	
if(!$edit || !$pos) die();

// $pos++;	
// $tid 	= $morpheus["standard_tid"][0];

$sql = "INSERT `morp_cms_content` SET navid=$edit, tpos=$pos, tid='1', content= '##t1__headline_H1@@Neues Template##t1__texteditor@@<p>Neues Template</p><p></p>'";
$res	= safe_query($sql);

repair_nav($edit);