<?php
session_start();

global $mylink;

include("../nogo/config.php");
include("../nogo/config_morpheus.inc");
include("../nogo/db.php");
dbconnect();
include("login.php");
include("../nogo/funktion.inc");


// der erste Wert kommt in data // alle folgenden Werte kommen im Array Z


$sql = "SELECT * FROM morp_settings_fonts WHERE sichtbar=1";
$res = safe_query($sql);

$css = '';

while($row = mysqli_fetch_object($res)) {
	$css .= $row->value;
}

echo $css;

echo save_data('../css/fonts.css',$css,'w');

$sql = "SELECT * FROM morp_settings_fonts WHERE 1";
$res = safe_query($sql);

$css = '';

while($row = mysqli_fetch_object($res)) {
	$css .= $row->value;
}

echo $css;

echo save_data('../css/allfonts.css',$css,'w');

?>