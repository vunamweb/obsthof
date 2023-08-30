<?php
session_start();
include("cms_include.inc");

$query  = "SELECT navid, name FROM `morp_cms_nav` WHERE 1";
$result = safe_query($query);

$updated_dat = date("Y-m-d").'T'.date("H:i:s").'+00:00';

while ($row = mysqli_fetch_object($result)) {
	$id		= $row->navid;
	$name	= $row->name;
	$setlink = strtolower(eliminiere($name));

	echo $sql = "
		UPDATE `morp_cms_nav`SET 
			published='$updated_dat', 
			updated_dat='$updated_dat', 
			setlink='$setlink'
		WHERE 
			navid=$id		
	";
	safe_query($sql);
	echo "<br>";
	$x = 1;	
	foreach($morpheus["standard_tid"] as $tid) {
		$sql 	= "INSERT `morp_cms_content` SET navid=$id, tpos=".$x.", tid=".$tid;
		$res = safe_query($sql);
		$c 		= mysqli_insert_id($mylink);
		protokoll($uid, "nav", $c, "neu");
		$x++;
	}
}
	