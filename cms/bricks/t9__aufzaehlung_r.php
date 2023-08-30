<?php
global $list_r;

$text = explode("\n", $text);

$list_r .= '
<ul>
';

foreach($text as $val) {
	if ($val) $list_r .= '		<li>'.$val.'</li>
';
}

$list_r .= '</ul>
';
