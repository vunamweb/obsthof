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

$output .= '<a href="'.$link.'" class="btn btn-info btn-weiss" '.$blank.'>'.$txt.' </a>';

$morp = '<b>Link</b>: '.$txt.'<br/>';

