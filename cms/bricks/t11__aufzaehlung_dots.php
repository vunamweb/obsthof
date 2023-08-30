<?php
global $color;

$text = explode("\n", $text);

$output .= '<ul class="list_content">
';

foreach($text as $val) {
	if ($val) $output .= '		<li>'.$val.'</li>
';
}

$output .= '</ul>
';
