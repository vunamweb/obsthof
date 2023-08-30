<?php

$text = str_replace(array("<table", "</table>"), array('<div class="table-responsive"><table', '</table></div>'), $text);
$output .= $text;
$morp = substr(strip_tags($text),0,100);