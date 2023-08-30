<?php
global $color;

$text = explode("\n", $text);

$output .= '						<ul>
';
foreach($text as $val) {
	if ($val) $output .= '							<li>'.$val.'</li>
';
}
$output .= '						</ul>
';

$morp = ' / Auzählung / ';
?>