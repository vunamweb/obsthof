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


 print_r($_POST);

$edit = $_POST["edit"];
$edit = check_valid_value($edit, "i");
if(!$edit) die("Fehler 342");

/*************************************************************/
/*************************************************************/
	$db = "morp_cms_content";
	$getid = "cid";
	$spalte = "content";
/*************************************************************/
/*************************************************************/


$x 		= 0;
$brick_arr = array();

foreach($_POST as $key=>$val) {

	$key = explode("#", $key);

	if ($key[0] == "brick") {
		if ((preg_match("/link_/", $key[1]) || preg_match("/image_popup/", $key[1]) || preg_match("/bild/", $key[1]) || preg_match("/anker_link/", $key[1]) || preg_match("/gleicher/", $key[1])) && !preg_match("/linksbuendig/", $key[1]) && !preg_match("/download/", $key[1]) && !preg_match("/TOP/", $key[1]) && !$linktext)
			$linktext = $val ."|";

		else {
			if ($linktext) {
				$linktext .= $val;
				$tmp = explode("_", $key[2]);
				$key[2] = $tmp[0];
				$val = addslashes($linktext);
			}
			$x++;

			if ($dupl == $x)  {
				$tb = explode(".", $brick);
				$brick_arr[$key[2]] =  $key[1] ."@@" .addslashes($val) ."##". $key[1] ."@@" .mysqli_real_escape_string($mylink, $val) ."##";  // neuer datensatz wird eingefuegt
				// echo "<p>datensatz wird dupliziert</p>";
			}
			elseif ($brick && $stelle == $x)  {
				$tb = explode(".", $brick);
				$brick_arr[$key[2]] = $tb[0] ."@@" ."##" .$key[1] ."@@" .mysqli_real_escape_string($mylink, $val) ."##";  // neuer datensatz wird eingefuegt
				# echo "<p>neuer datensatz wird eingefuegt</p>";
			}
			else	{
				# $show .= "$key[0] - $key[1] - $key[2] - $val<br>";
				$brick_arr[$key[2]] = $key[1] ."@@" .mysqli_real_escape_string($mylink, $val) ."##";		  //
			}

			if ($linktext) unset($linktext);
		}
	}
}

if ($brick && $stelle > $x) {
	$tb = explode(".", $brick);
	$brick_arr[] = $tb[0] ."@@";
}

if (count($brick_arr) < 1 && $brick) $brick_arr[] = $brick ."@@";

foreach($brick_arr as $key=>$val) {
	if ($val) $text .= $val;
}

$text = $text;


if (!$layout = $_POST["layout"]) $layout = 1;
$set = "set ".$spalte."='$text', edit=1, layout='$layout' ";

if ($edit) 	$query = "UPDATE $db " .$set ."WHERE $getid=$edit";
else 		$query = "INSERT $db " .$set;

// echo " $query<br>";

$res = safe_query($query);
$c = mysqli_insert_id($mylink);


// SET change datum in db / table nav // EDIT: 2019-08-12

$sql = "SELECT navid FROM $db WHERE $getid=$edit";
$res = safe_query($sql);
$row = mysqli_fetch_object($res);
$navid = $row->navid;

$updated_dat = date("Y-m-d").'T'.date("H:i:s").'+00:00';
$sql  = "UPDATE `morp_cms_nav` SET updated_dat='$updated_dat' WHERE navid=$navid";
safe_query($sql);

// _______________________________________________________________________________


if ($edit) 	protokoll($uid, $db, $edit, "edit");
else 		protokoll($uid, $db, $c, "neu");

?>