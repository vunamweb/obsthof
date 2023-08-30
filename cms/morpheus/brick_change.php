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

$back 	 = $_REQUEST["back"];
$pos	 = $_REQUEST["pos"];
$db		 = $_REQUEST["db"];
$cid	 = $_REQUEST["cid"];


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
$query 	= "SELECT tid FROM `morp_cms_content` WHERE cid='$cid'";
$result = safe_query($query);
$row 	= mysqli_fetch_object($result);
$tid 	= $row->tid;
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
//
// stelle select mit bricks zusammen
$dir = opendir("../bricks");
$select_brick = "\n<select name=brickname ondblclick=\"document.change.submit();\" size=10 style=\"width:200;height:300px;\">";
$dir_arr = array();

$anz = "t".$tid."_";
while ($name = readdir($dir)) {
	if (!$link_aktiv) {
		if (!is_dir($name)) {
			# echo $name."<br>";
			if (preg_match("/^".$anz."/", $name)) $dir_arr[] = $name;
			elseif (preg_match("/^all/", $name)) $dir_arr[] = $name;
		}
		elseif (!preg_match("/Box/", $name)) $dir_arr[] = $name;
	}
}

sort ($dir_arr);

foreach($dir_arr as $name) {
	$name_ = explode(".", $name);
	$name_ = str_replace("_", " ", $name_[0]);
	$name_ = substr($name_,2);

	if ($counter > 1 && $name_ == "link") {}
	elseif ($name_[0] == "-") {}
	elseif (preg_match("/aufzaehlung/", $name_) || preg_match("/head/", $name_) || preg_match("/block/", $name_) || preg_match("/zitat/", $name_) || preg_match("/^zeile/", $name_) || preg_match("/job/", $name_) || (preg_match("/text/", $name_) && !preg_match("/pdf/", $name_)))
		$select_brick .= "<option value='$name'>$name_</option>\n";
}
$select_brick .= "</select>\n";
// _select
//

echo "\n\n<div id=vorschau>\n<p><a href=\"javascript:history.back();\" title=\"zur&uuml;ck\">" .backlink() ." zur&uuml;ck</a> &nbsp; &nbsp; &nbsp; &nbsp; ";

echo "<form method=post action='content_edit.php' name=\"change\">\n
		<input type=hidden name='change' value='$cid'>
		<input type=hidden name='edit' value='$cid'>
		<input type=hidden name='db' value='$db'>
		<input type=hidden name='back' value='$back'>
		<input type=hidden name='stelle' value='$pos'>
		<img src=\"images/leer.gif\" width=1 height=40>
		<p><b>Neue Formatierung w&auml;hlen</b> &nbsp; </p>
		<p>$select_brick</p>
		<p><input type=\"submit\" name=\"speichern\" value=\"speichern\"></p>
		</form>";

?>

</div>

<?
include("footer.php");
?>