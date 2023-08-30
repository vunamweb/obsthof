<?php
global $img_pfad, $ausrichtung, $ausrichtungArray, $dir, $img__header, $templ_id, $css, $mobile;

$data = explode("|", $text); $imgid = $data[0]; $ausrichtung = $data[1]; if(!$ausrichtung) $ausrichtung = 1;

if($text) {
	$que  	= "SELECT itext, imgname, `longtext` FROM `morp_cms_image` WHERE imgid=$imgid";
	$res 	= safe_query($que);
	$rw     = mysqli_fetch_object($res);
	$itext 	= $rw->itext;
	$ltext 	= $rw->longtext;
	if (isin("http", $itext)) $ext = '<a href="'.$itext.'" target="_blank" title="'.$itext.'">';

	$inm 	= $rw->imgname;
	$altText = $itext ? $itext : $ltext; if(!$altText) $altText = $morpheus["client"].' '.$inm;

	$output .= $ext.'<img src="'.$img_pfad.$inm.'" alt="'.$altText.'" class="partner-logo" />'.($ext ? '</a>' : '');
}

$morp = $inm;

$socialImage = urlencode($inm);


// .section_topbanner.topbanner_services {
// 	background: url('.$dir.'images/userfiles/image/'.$inm.') no-repeat center top;
// 	background-size: cover; height: 400px;
// }