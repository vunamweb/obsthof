<?php
global $img_pfad, $dir, $emotional, $socialImage, $imageFolder, $image;

$imgid  = explode("|",$text);
$imgid = $imgid[0];

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

	// $image = '		<img src="'.$dir.$imageFolder.urlencode($folder).'/'.($inm).'?w='.$w.'" class="img-fluid" alt="'.$itext.'" />';
	
	$image = '<img src="'.($type == "svg" ? $img_pfad.$inm : $dir.$imageFolder.urlencode($folder).'/'.urlencode($inm).'?w='.$w).'" alt="'.$altText.'" class="img-fluid '.$ausrichtungArray[$ausrichtung].'" width="'.($img_w ? $img_w : $w).'"'.($img_h ? ' height="'.$img_h.'"' : '').' />';
}

$morp = $inm;
$socialImage = urlencode($inm);
global $socialImg; if(!$socialImg) $socialImg = $dir.$imageFolder.urlencode($folder).'/'.($inm).'?w='.$w;
