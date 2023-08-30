<?php
global $farbe, $class;

if($text) {
	$que  	= "SELECT color, colname FROM morp_color WHERE colid=$text";
	$res 	= safe_query($que);
	$rw     = mysqli_fetch_object($res);
	$farbe 	= $rw->color;
	$class 	= $rw->colname;
}

$morp = $text;

?>