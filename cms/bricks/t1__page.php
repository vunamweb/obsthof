<?php
# $output .= $text;
$page = explode("?", trim($text));
$ziel = isset($page[1]) ? $page[1] : '';
include("page/".$page[0]);

