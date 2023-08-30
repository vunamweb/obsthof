<?php
global $color;

$text = explode("\n", $text);

$output .= '
<ul class="dreieck">
';

foreach($text as $val) {
	if ($val) $output .= '		<li><span></span>'.$val.'</li>
';
}

$output .= '</ul>
';

$morp = "Liste / ";