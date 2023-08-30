<?php
global $navarray, $lan, $navID, $produkt_group_arr, $multilang;

$tmp 	= explode("|", $text);
$anker 	= explode("#", $tmp[0]);
$link 	= trim($anker[0]);
$anker	= $anker[1];
$txt 	= $tmp[1];


$output .= '<li class="d-xl-block d-xxl-none '.eliminiere($txt).' mobileNav"><a href="'.($link != "bitte ziel wählen" ? getUrl($link) : '#').'" class="nav-sub-link">'.$txt.'</a></li>';

$morp = '<b>Link</b>: '.$txt.'<br/>';
