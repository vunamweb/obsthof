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


# content_edit.php?stelle=3&edit=1&imgid=16&back=ebene;:;1;;p_0;:;0;;n_0;:;Hauptnavigation&db=content#3

$neu	= $_REQUEST["neu"];
$save	= $_REQUEST["save"];
$kname	= $_REQUEST["kname"];
$pw		= $_REQUEST["pw"];

echo "<div id=content_big>";
	
# print_r($_POST);

if ($save) {
	$ct = count($arr);
	$set .= "kname='$kname', pw='$pw'";
	
	if ($neu) $query = "insert kunden ";
	else $query = "update kunden ";
	$query .= "set " .$set;
	if (!$neu) $query .= " where kid=$kid";
	safe_query($query);
	
	if ($neu) {		// kunden bekommen eigenen ordner. dieser wird in der navigation mit angelegt!
		$par	= mysqli_insert_id($mylink);
		$query 	= "INSERT `morp_cms_nav` set n_de = '$kname', n_en = 'kname', ebene=2, parent=12, sort=10";
		$result = safe_query($query);
		$cid	= mysqli_insert_id($mylink);
		$query 	= "INSERT `morp_cms_content` set cid=$cid";
		$result = safe_query($query);
		$query = "update kunden set kparent=$cid where kid=$par";
		$result = safe_query($query);
	}
	
	unset($neu);
	unset($kid);

	global $admin;
	$admin = 1;
}
elseif ($tuid || $neu) {
	echo "<p><b>Liste aller Kunden</b></p>";
	if (!$neu) {
		$query  = "SELECT * FROM kunden where kid=$kid";
		$result = safe_query($query);
		$row = mysqli_fetch_object($result);
	}
	
	echo '<p><a href="kunden.php">' .backlink().' zurück</a></p>';
	echo '<form method="post">
		<input type="hidden" name="neu" value="'.$neu.'">
		<input type="hidden" name="save" value="1">
		<p>&nbsp;</p><p>&nbsp;</p>
		<p><div style="width:60px; float:left;">Name</div><input type="text" name="kname" value="'.$row->kname.'" style="width:200px;height:21px;"></p>
		<p><div style="width:60px; float:left;">Passwort</div><input type="text" name="pw" value="'.$row->pw.'" style="width:100px;height:21px;"></p>
		<p><input type="submit" class="button" name="speichern" value="speichern"></p>
		<p>&nbsp;</p>
		';
		
		#$relog = 1;
		#include("login_again.php");
}

if (!$kid && !$neu && $admin) {
	echo "<p><b>Liste aller Kunden</b></p><p>&nbsp;</p>";
	$c = 0;
	$x = 0;
	$xx = 0;
	
	$query  = "SELECT * FROM kunden order by kname";
	$result = safe_query($query);
	$ct = mysqli_num_rows($result);
	
	$change = $ct / 3;
	
	while ($row = mysqli_fetch_object($result)) {
		$c++;
		if ($c == 1) {echo '<div style="float:left; width:160px;">'; $xx++; } 
		echo '<p><a href="kunden.php?kid='.$row->kid.'">' .ilink().' '.$row->kname.'</a></p>';
		if ($c > $change) { echo "</div>"; $c = 0; $x++;}
	}
	if ($x < $xx) echo "</div>";
	echo '<div style="clear:left;"><p>&nbsp;</p>
		<p><a href="kunden.php?neu=1">' .ilink().' <b>NEU</b></a></p></div>';
}
	
?>

</div>

</body>
</html>
