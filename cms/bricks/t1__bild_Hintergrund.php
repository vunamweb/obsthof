<?php
global $img_pfad, $ausrichtung, $ausrichtungArray, $dir, $imgGal, $imageFolder, $bgIMG, $morpheus;

$data = explode("|", $text); $imgid = $data[0]; $ausrichtung = $data[1]; if(!$ausrichtung) $ausrichtung = 1;

$w = $ausrichtung > 1 ? 300 : 1500;


if($text) {
	$que  	= "SELECT `longtext`, itext, imgname, name FROM `morp_cms_image` i, `morp_cms_img_group` g WHERE g.gid=i.gid AND imgid=$imgid";
	$res 	= safe_query($que);
	$rw     = mysqli_fetch_object($res);
	$itext 	= $rw->itext;
	$extLink = '';
	if(isin("https", $itext)) {
		$extLink = '<a href="'.$itext.'" target="_blank">'; 
	}
	$ltext 	= $rw->longtext;
	$inm 	= $rw->imgname;
	$type = strtolower(substr($inm, -3));
	$altText = $itext ? $itext : $ltext; if(!$altText) $altText = $morpheus["client"].' '.$inm;
	$folder	= str_replace(array(";", " / ", "/", "  ", " "), array("","-","-", "-", "-"), $rw->name);
	
	// $img_size = getimagesize($dir.$imageFolder.urlencode($folder).'/'.($inm).'?w='.$w);
	// $img_w = $img_size[0];
	// $img_h = $img_size[1];
	
	$bgIMG = $img_pfad.$inm;
}

$morp = $inm;

$socialImage = urlencode($inm);
global $socialImg; if(!$socialImg) $socialImg = $dir.$imageFolder.urlencode($folder).'/'.($inm).'?w='.$w;

