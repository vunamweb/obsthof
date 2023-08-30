<?php
global $list_l;

$text = explode("\n", $text);

$list_l .= '
<ul>
';

foreach($text as $val) {
	if ($val) $list_l .= '		<li>'.$val.'</li>
';
}

$list_l .= '</ul>
';
