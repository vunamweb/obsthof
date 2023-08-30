<?php
global $navarray, $lan, $navID, $produkt_group_arr, $multilang, $cid;

$tmp 	= explode("|", $text);
$anker 	= explode("#", $tmp[0]);
$link 	= trim($anker[0]);
$anker	= $anker[1];
$txt 	= $tmp[1];

if(!$link) $link=$cid;

$output .= '		<p class="button"><a href="'.($link != "bitte ziel wählen" ? getUrl($link) : '#').($anker ? '#'.$anker : '').'" class="btn btn-info btn-text mb-2 mb-lg-0 mr-2"'.($anker ? ' data-scroll' : '').' itemprop="url">'.$txt.'</a></p>
';

$morp = '<b>Link</b>: '.$txt.'<br/>';

