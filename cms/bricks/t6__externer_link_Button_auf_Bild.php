<?php
global $dir, $linkButton;

$tmp 	= explode("|", $text);
$link 	= $tmp[0];
$txt 	= $tmp[1];

	// $link = (isin("^http", $link) ? '' : 'http://').$link;
	// $blank = " target=\"_blank\"";

$linkButton .= '<a href="'.substr($dir,0,-5).$link.'" class="btn btn-info btn-text mb-2 mb-lg-0 mr-2">'.$txt.' </a>';

$morp = '<b>Link</b>: '.$txt.'<br/>';

