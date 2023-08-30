<?php
$arr = explode('|', $text);

if(count($arr)>1)	$output .= '<h2 class="numbers"><span>'.trim($arr[0]).'</span>'.nl2br($arr[1]).'</h2>';
else				$output .= '<h2>'.nl2br($arr[0]).'</h2>';

$morp = $arr[0];
