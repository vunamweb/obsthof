<?php
global $navarray, $lan, $navID, $multilang, $ddID, $anker__link;

$tmp 	= explode("|", $text);
$anker_name 	= explode("#", $tmp[0]);
$link 	= trim($anker_name[0]);
$anker_name	= trim($anker_name[1]);
$txt 	= $tmp[1];


$anker__link .= '<a href="'.$dir.$navID[$link].($anker_name ? '#'.$anker_name : '').'" class="btn btn-info" data-scroll itemprop="url">'.$txt.'</a>';

$morp = '<b>Link</b>: '.$txt.'<br/>';

