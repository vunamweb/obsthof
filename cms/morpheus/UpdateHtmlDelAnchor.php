<?php

// print_r($_POST);
$data = isset($_POST["data"]) ? $_POST["data"] : 0;
$nm = isset($_POST["nm"]) ? $_POST["nm"] : 0;

if(!$data) die("kein Zugriff");

else {
	$data = strip_tags($data);
	$input = explode($nm.'=', $data);
	$array = explode("<a:", $input[1]);
	$anzahl = count($array);

	$ret = '';

	if($anzahl > 1) {
		for($i=0; $i<$anzahl; $i++) {
			if($i>0) {
				$array_inner = explode(":>", $array[$i]);
				$add_text = $array_inner[1];
				$ret .= $add_text;
			}
			else $ret .= $array[$i];
		}
	}
	else {
		foreach($input as $val) {
			$ret .= $val;
		}
	}
}

echo preg_replace("/<\/a>/", '', $ret);

