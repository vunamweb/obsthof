<?php
	global $H1_count, $design;

	if(!$H1_count) $H1_count = 1;
	else $H1_count++;

	$output .= '<h1 class="handscript">'.nl2br($text).'</h1>';
	$morp = $text;
