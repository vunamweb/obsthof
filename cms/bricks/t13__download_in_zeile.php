<?php
global $pdf, $dir;

$query  = "SELECT * FROM pdf where pid=$text";
$result = safe_query($query);
$row = mysqli_fetch_object($result);

$de = $row->pdesc;
$nm = $row->pname;
$si = $row->psize;
$da = $row->pdate;
$pi = $row->pimage;
$da = euro_dat($da);

$typ = explode(".", $nm);
$c	 = (count($typ)-1);
$img = $typ[$c]."_s.gif";

$output .= '<li class="nav-item"><a class="nav-link" href="'.$dir.'pdf/'.$nm.'" target="_blank">'.strtoupper($de).'</a></li>';

$morp = '<b>Download:</b> '.$de.'<br/>';
?>