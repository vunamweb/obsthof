<?php
global $navarray, $lan, $navID, $produkt_group_arr, $multilang;

$tmp 	= explode("|", $text);
$anker 	= explode("#", $tmp[0]);
$link 	= trim($anker[0]);
$anker	= $anker[1];
$txt 	= $tmp[1];


$output .= '		<li class="nav-item"><a class="nav-link" href="'.$dir.($multilang ? $lan.'/' : '').$navID[$link].'">'.strtoupper($txt).'</a></li>
';

$morp = '<b>Link</b>: '.$txt.'<br/>';

?>