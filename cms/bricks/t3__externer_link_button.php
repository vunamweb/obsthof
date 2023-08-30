<?php
global $dir;

$tmp 	= explode("|", $text);
$link 	= $tmp[0];
$txt 	= $tmp[1];

	$link = (isin("^http", $link) ? '' : 'http://').$link;
	$blank = " target=\"_blank\"";

$output .= '	<a href="'.$link.'" class="btn btn-info" '.$blank.'>'.$txt.' </a>
';

$morp = '<b>Link</b>: '.$txt.'<br/>';

?>