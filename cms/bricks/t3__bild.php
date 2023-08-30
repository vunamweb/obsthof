<?php
global $img_pfad, $ausrichtung, $ausrichtungArray, $dir, $imgGal, $imageFolder, $grImg;

$data = explode("|", $text); $imgid = $data[0]; $ausrichtung = $data[1]; if(!$ausrichtung) $ausrichtung = 1;

$w = 600;

if($text) {
	$que  	= "SELECT `longtext`, itext, imgname, name FROM `morp_cms_image` i, `morp_cms_img_group` g WHERE g.gid=i.gid AND imgid=$imgid";
	$res 	= safe_query($que);
	$rw     = mysqli_fetch_object($res);
	$itext 	= $rw->itext;
	$ltext 	= $rw->longtext;
	$inm 	= $rw->imgname;
	$type 	= strtolower(substr($inm, -3));
	
	$altText = $itext ? $itext : $ltext; if(!$altText) $altText = $morpheus["client"].' '.$inm;
	$folder	= str_replace(array(";", " / ", "/", "  ", " "), array("","-","-", "-", "-"), $rw->name);

	// $grImg = '						<img src="'.$dir.$imageFolder.urlencode($folder).'/'.($inm).'?w='.$w.'" alt="'.$altText.'" class="img-fluid w-100" />';
	$grImg = '<img src="'.($type == "svg" ? $img_pfad.$inm : $dir.$imageFolder.urlencode($folder).'/'.urlencode($inm).'?w='.$w).'" alt="'.$altText.'" class="img-fluid w-100" width="'.($img_w ? $img_w : $w).'"'.($img_h ? ' height="'.$img_h.'"' : '').' />';
}

$morp = $inm;

$socialImage = urlencode($inm);
global $socialImg; if(!$socialImg) $socialImg = $dir.$imageFolder.urlencode($folder).'/'.($inm).'?w='.$w;

