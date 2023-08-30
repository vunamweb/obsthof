<?php
global $dir;

$tmp 	= explode("|", $text);
$link 	= $tmp[0];
$txt 	= $tmp[1];

if (isin('^/', $link)) $link = $dir.substr($link, 1);
else {
	$link = (isin("^http", $link) ? '' : 'http://').$link;
	$blank = " target=\"_blank\"";
}

$output .= '<p><a href="'.$link.'" class="myLink" '.$blank.'>'.$txt.' &nbsp; <i class="fa fa-chevron-right"></i></a></p>
';

$morp = '<b>Link</b>: '.$txt.'<br/>';

?>