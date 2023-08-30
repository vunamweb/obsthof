<?php
global $farbe_inner, $class_inner;

if($text) {
	$que  	= "SELECT color, colname FROM morp_color WHERE colid=$text";
	$res 	= safe_query($que);
	$rw     = mysqli_fetch_object($res);
	$farbe_inner 	= $rw->color;
	$class_inner 	= $rw->colname;
}

$morp = $text;

