<?php
global $morpheus;

$table = "morp_mitarbeiter";
$tid = "mid";

$mid = check_valid_value($text, "i");
if(!$mid) die("Fehler 125");

$sql = "SELECT name, vorname, email FROM $table WHERE $tid=$mid";
$res = safe_query($sql);
$row = mysqli_fetch_object($res);

$name = $row->name;	
$vorname = $row->vorname;	
$output .= '<div><a href="'.getUrl($morpheus["kontaktID"]).'m+'.$mid.'/" class="btn btn-info">'.textvorlage(22).'</a></div>';

$morp = "Kontakt Button $vorname $name / ";