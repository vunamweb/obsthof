<?php
global $dir, $morpheus;

$f = explode('#', $text);

$file = $f[0];
$w = $v[1];
$h = $v[2];

$output .= '

<iframe src="'.$morpheus["subFolder"] .'page/'.$file.'" class="iframe"></iframe>

';

