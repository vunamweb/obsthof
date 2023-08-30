<?php

$tmp 	= explode("|", $text);
$link 	= $tmp[0];
$txt 	= $tmp[1];

$link 	= email_code ($link);
if (isin("@", $txt)) $txt = email_code($txt);

$output .= "<p><a href=\"mailto:$link\">$txt</a></p>";

$morp = '<b>Mail</b>: '.$txt.'<br/>';

?>