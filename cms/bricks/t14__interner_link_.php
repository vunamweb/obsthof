<?php
global $navarray, $lan, $navID, $produkt_group_arr, $multilang;

$tmp 	= explode("|", $text);
$anker 	= explode("#", $tmp[0]);
$link 	= trim($anker[0]);
$anker	= $anker[1];
$txt 	= $tmp[1];


$output .= '<a href="'.($link != "bitte ziel wählen" ? $dir.($multilang ? $lan.'/' : '').$navID[$link] : '#').'" class="btn btn-info btn-weiss">'.$txt.'</a>';

$morp = '<b>Link</b>: '.$txt.'<br/>';

