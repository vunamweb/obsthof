<?php
global $navarray, $lan, $navID, $produkt_group_arr, $multilang;

$tmp 	= explode("|", $text);
$anker 	= explode("#", $tmp[0]);
$link 	= trim($anker[0]);
$anker	= $anker[1];
$txt 	= $tmp[1];


$output .= '	<a href="'.$dir.($multilang ? $lan.'/' : '').$navID[$link].'" class="btn btnMore font_weiss"><i class="fas fa-caret-right fa-lg"></i> '.$txt.' </a>
';

$morp = '<b>Link</b>: '.$txt.'<br/>';

?>