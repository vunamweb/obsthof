<?php

include("../nogo/db.inc");
dbconnect();

$arr = array ("de", "en");

foreach($arr as $lang) {
	include("../nogo/".$lang.".inc");
	
	for($i=1; $i<=370; $i++) {
		$query 	= "SELECT * FROM `sprachdatei` WHERE id=$i";
		$result = safe_query($query);
		$x		= mysqli_num_rows($result);
		
		$dat	= addslashes($language[$i]);
		
		if ($x > 0) $sql = "UPDATE sprachdatei set $lang='$dat' WHERE id=$i";
		else		$sql = "INSERT sprachdatei set $lang='$dat', id=$i";
		$res = safe_query($sql);
		echo $i." - ";
		
		#if ($i>100) die();

	}
}

?>
