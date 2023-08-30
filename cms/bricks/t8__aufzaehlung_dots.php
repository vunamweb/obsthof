<?php
global $liste;

$text = explode("\n", $text);
$liste = '<ul>';

foreach($text as $val) {
	if ($val) $liste .= '		<li>'.$val.'</li>
';
}

$output .= $liste.'</ul>';

?>