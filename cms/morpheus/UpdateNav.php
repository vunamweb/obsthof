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


$sprache = $_POST["sprache"];
$sprache = check_valid_value($sprache, "i");
if(!$sprache) die("Fehler 340");

	# # # # # array fuer interne links schreiben
	$query  = "SELECT * FROM `morp_cms_nav` WHERE lang=$sprache";
	$result = safe_query($query);

	$nav_arr 	= '<?php
$navarray = array("0"=>""';
	$nav_arrF 	= '<?php
$navarrayFULL = array("0"=>""';

	while ($row = mysqli_fetch_object($result)) {
		$id		= $row->navid;
		$name	= $row->name;
		$nnm 	= strtolower(eliminiere($name));
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
