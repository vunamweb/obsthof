<?php
global $navarray, $lan, $navID, $produkt_group_arr, $multilang, $cid;

$tmp 	= explode("|", $text);
$anker 	= explode("#", $tmp[0]);
$link 	= trim($anker[0]);
$anker	= $anker[1];
$txt 	= $tmp[1];

if(!$link) $link=$cid;

$output .= '		<p><a href="'.($link != "bitte ziel wählen" ? getUrl($link) : '#').($anker ? '#'.$anker : '').'"'.($anker ? ' data-scroll' : '').' itemprop="url">'.$txt.'</a></p>
';

$morp = '<b>Link</b>: '.$txt.'<br/>';

