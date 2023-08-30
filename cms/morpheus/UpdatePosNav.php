<?php
session_start();

global $mylink;

// echo "here";

include("../nogo/config.php");
include("../nogo/config_morpheus.inc");
include("../nogo/db.php");
dbconnect();
include("login.php");
include("../nogo/funktion.inc");


$data = $_POST["data"];
$z = explode(",", $data);

$id = $_POST["id"];

$sprache = $_POST["sprache"];

#$dat = $_POST["dat"];

#$dat = us_dat($dat);

#print_r($_POST);
// der erste Wert kommt in data // alle folgenden Werte kommen im Array Z


// print_r($z);

// packlisten ID holen oder erzeugen
$table = "tPankreas";
$tid = "fPankreasID";

if($data) {
	$x = 0;
	foreach($z as $val) {
		if($val) {
			$x++;
			$sql	= "UPDATE `morp_cms_nav` set `sort`=$x WHERE navid=$val";
			// echo "\n";
			safe_query($sql);
		}
	}

}


$sprache = check_valid_value($sprache, "i");
if(!$sprache) die("Fehler 335");

	# # # # # array fuer interne links schreiben
	$query  = "SELECT setlink, navid, name FROM `morp_cms_nav` WHERE lang=$sprache";
	$result = safe_query($query);

	$nav_arr 	= '<?php
$navarray = array("0"=>""';
	$nav_arrF 	= '<?php
$navarrayFULL = array("0"=>""';

	while ($row = mysqli_fetch_object($result)) {
		$id		= $row->navid;
		$name	= $row->name;
		$nnm 	= $row->setlink;
		$name = str_replace(array('"', "'", '„', '“'), "", $name);

		if ($nnm) {
			$nav_arr .= ', "'.$id.'"=>"'.($nnm).'"';
			$nav_arrF .= ', "'.$id.'"=>"'.($name).'"';
		}
	}

	$nav_arr .= ');
?>'.
	$nav_arrF .= ');
?>';

	save_data("../nogo/navarray_".$morpheus["lan_arr"][$sprache].".php",$nav_arr,"w");

	include("quickbar.php");
	include("set_nav_new.php");
	include("sitemap_create.php");
	include("sitemap_create_footer.php");
	include("set_nav.php");

?>