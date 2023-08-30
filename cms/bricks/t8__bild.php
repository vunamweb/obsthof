<?php
global $img_pfad, $ausrichtung, $ausrichtungArray, $dir, $morpheus, $imageFolder;
global $tref, $noHeader, $grIMG;

$data = explode("|", $text); $imgid = $data[0]; $ausrichtung = $data[1]; if(!$ausrichtung) $ausrichtung = 1;

$w = 1500;

if($imgid) {
	$que  	= "SELECT `longtext`, itext, text2, imgname, name FROM `morp_cms_image` i, `morp_cms_img_group` g WHERE g.gid=i.gid AND imgid=$imgid";
	$res 	= safe_query($que);
	$rw     = mysqli_fetch_object($res);
	$itext 	= $rw->itext;
	$ltext 	= $rw->longtext;
	$text2 	= $rw->text2;
	$inm 	= $rw->imgname;
	$label 	= substr($inm,0,-4);
	$altText = $itext ? $itext : $ltext; if(!$altText) $altText = $morpheus["client"].' '.$inm;
	$folder	= str_replace(array(";", " / ", "/", "  ", " "), array("","-","-", "-", "-"), $rw->name);
	$extLink = '';
	if(isin("https", $itext)) {
		$extLink = '<a href="'.$itext.'" target="_blank">'; 
	}
	
	$grIMG = '<div class="box"><img src="'.$img_pfad.$inm.'" class="img-fluid" alt="'.$altText.'" /></div>';

}

$morp = $inm . ' / ';

$titel = '';
$titel2 = '';

$socialImage = urlencode($inm);
global $socialImg; if(!$socialImg) $socialImg = $dir.$imageFolder.urlencode($folder).'/'.($inm).'?w='.$w;
