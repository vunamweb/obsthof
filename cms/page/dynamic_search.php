<?php
require("../nogo/config.php");
require("../nogo/db.php");
dbconnect();

$table = 'morp_suche_keyw';
$c1 = 'keyw';
$c2 = 'de';
$table2 = 'morp_suche_keyw_signition';

$search = 'a';

$sql = "SELECT $c1 FROM $table WHERE $c1 LIKE = '%%search%'";
$sql = "SELECT $c1 FROM $table WHERE 1";
$res = safe_query($sql);

while($row = mysqli_fetch_object($res)) {
	$list[] = array(
    'id'  => $row->$c1,
    'name'  => $row->$c1
   ); 
}

echo json_encode($list);