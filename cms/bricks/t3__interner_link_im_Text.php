<?php
global $navarray, $lan, $navID, $produkt_group_arr, $multilang;

$tmp 	= explode("|", $text);
$anker 	= explode("#", $tmp[0]);
$link 	= trim($anker[0]);
$anker	= $anker[1];
$txt 	= $tmp[1];


if(strlen($link)>2) {
	if($lan == "de") $artLAN = "art";
	else $artLAN = $lan;

	if(!$artLAN) $artLAN="de";

	$sql = "SELECT * FROM morp_art WHERE art LIKE '$link%'";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
	$link = $navID[$produkt_group_arr[$row->art2]].strtolower($row->$artLAN).'-'.$row->aid.'/';
}
else $link = $navID[$link];

//$output .= 'ilink<a class="btn2" href="'.$dir.$navID[$link].'"><span class="fa '.($islock ? 'fa-lock' : 'fa-external-link-square').'"></span> '.$txt.'</a>ilink';
$output .= 'ilink<a href="'.$dir.($multilang ? $lan.'/' : '').$link.'">'.$txt.' &nbsp;<i class="fa fa-chevron-right green"></i></a>ilink';

?>