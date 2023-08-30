<?php
global $grIMG;

$zeilen = explode("#", $text);

$output .= '<div class="vorteile">'.$grIMG.'<h4>'.$zeilen[0].'</h4><p>'.nl2br($zeilen[1]).'</p></div>';

$morp = $text;

