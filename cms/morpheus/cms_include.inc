<?php
$jq = '';
//$box = '';
if(!isset($box)) $box='';
define('CMS',1);

global $mylink, $auditor;

			include("../nogo/config.php");
			include("../nogo/config_morpheus.inc");
			include("inc/de.php");
//			include("inc/morp_function.php");

			include("cms_header.php");
			include("../nogo/db.php");
dbconnect();
			include("../nogo/funktion.inc");
			include("login.php");

// SETTINGS KUNDEN LADEN
$sql = "SELECT * FROM morp_settings WHERE 1";
$res = safe_query($sql);
while($row = mysqli_fetch_object($res)) {
	$morpheus[$row->var] = $row->value;
}

// if ($shop)	include("../nogo/shop.inc");
if (!$box) 	include("cms_navigation.php");


$auditor = 0;
if(in_array(100,$auths)) $auditor = 1;

if($myauth === 1) {}
elseif($admin) {}
#elseif($myauth2 && in_array($myauth2, $auths)) {}
#elseif($myauth3 && in_array($myauth3, $auths)) {}
#elseif($myauth4 && in_array($myauth4, $auths)) {}
elseif(in_array($myauth, $auths)) {}
else {
	include("footer.php");
	die('Keine Berechtigung für diese Seite');
}
