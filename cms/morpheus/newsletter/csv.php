<style>
* {
	font-family: arial;
	font-size: 10px;
}
</style>

<?php 
	include_once('csv.inc.php');
	$csv = new csv_uploder('verteiler.csv', 2000 , ';');
	
	$arr = $csv->getCsv();
	$a = 0;
	
	foreach($arr as $val) {
		$a++;
		echo '<br>'.$a.': '.$val["a_anrede"].' '.$val["a_titel"].' '.$val["a_vorname"].' '.$val["a_nachname"].'--'.$val["a_subnr"].'--'.$val["briefanred"].'--'.$val["a_email"];
	
	}
	
?>
