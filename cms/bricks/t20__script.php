<?php
# $output .= $text;
$page = explode("?", trim($text));
$ziel = $page[1];
include("inc/".$page[0]);

