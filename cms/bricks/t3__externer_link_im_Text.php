<?php
global $dir;

$tmp 	= explode("|", $text);
$link 	= $tmp[0];
$txt 	= $tmp[1];

$link = (isin("^http", $link) ? '' : 'http://').$link;
$blank = " target=\"_blank\"";

$output .= 'ilink<a href="'.$link.'" '.$blank.' class="s12">'.$txt.'</a>ilink';

$morp = '<b>Link</b>: '.$txt.'<br/>';

?>