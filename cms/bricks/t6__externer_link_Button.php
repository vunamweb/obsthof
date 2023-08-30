<?php
global $dir;

$tmp 	= explode("|", $text);
$link 	= $tmp[0];
$txt 	= $tmp[1];

	$link = (isin("^http", $link) ? '' : 'http://').$link;
	$blank = " target=\"_blank\"";

$output .= '	<a href="'.$link.'" class="btn btn-info btn-text mb-2 mb-lg-0 mr-2" '.$blank.'>'.$txt.' </a>
';

$morp = '<b>Link</b>: '.$txt.'<br/>';

