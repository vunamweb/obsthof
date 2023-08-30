<?php
global $navarray, $lan, $navID, $multilang, $ddID, $internal_link;

$tmp 	= explode("|", $text);
$anker_name 	= explode("#", $tmp[0]);
$link 	= trim($anker_name[0]);
$anker_name	= trim($anker_name[1]);
$txt 	= $tmp[1];

$internal_link = getUrl($link).($anker_name ? '#'.$anker_name : '');

$morp = '<b>Link</b>: '.$txt.'<br/>';

