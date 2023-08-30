<?php
global $img_pfad, $ausrichtung, $ausrichtungArray, $dir, $imgGal, $imageFolder, $jumpIDProdukt, $lan;

$data = explode("|", $text); $imgid = $data[0]; $ausrichtung = $data[1]; if(!$ausrichtung) $ausrichtung = 1;

$w = 1500;

if($text) {
	$que  	= "SELECT `longtext`, itext, imgname, name FROM `morp_cms_image` i, `morp_cms_img_group` g WHERE g.gid=i.gid AND imgid=$imgid";
	$res 	= safe_query($que);
	$rw     = mysqli_fetch_object($res);
	$itext 	= trim($rw->itext);
	// print_r($rw);
	$extLink = '';
	if(isin("https", $itext)) {
		$extLink = '<a href="'.$itext.'" target="_blank">'; 
	}
	$ltext 	= trim($rw->longtext);
	$inm 	= $rw->imgname;
	$type = substr($inm, -3);
	$altText = $itext ? $itext : $ltext; if(!$altText) $altText = $morpheus["client"].' '.$inm;
	$folder	= str_replace(array(";", " / ", "/", "  ", " "), array("","-","-", "-", "-"), $rw->name);
	
	$img_size = getimagesize($dir.$imageFolder.urlencode($folder).'/'.($inm).'?w='.$w);
	$img_w = $img_size[0];
	$img_h = $img_size[1];
	
	$table 		= 'morp_product_wg';
	$tid 		= 'wgID';
	$nameField 	= "wg";
	$name = '';
	if($ltext) {
		$sql = "SELECT $nameField FROM $table WHERE $tid=$ltext";
		$rs  = safe_query($sql);
		$row = mysqli_fetch_object($rs);
		$name = $row->$nameField;
	}
	
	$output .= '
	<div class="col-6 col-md-4 col-lg-4">
		<div class="inner linkbox" ref="'.$dir.$lan.'/'.eliminiere($name).'/">	
			<img src="'.$img_pfad.$inm.'" '.($img_w ? ' width="'.$img_w.'" height="'.$img_h.'"' : '').' alt="'.$altText.'" class="img-fluid">
			'.$name.'
		</div>
	</div>
	';
	// $output .= $extLink.'<img src="'.($type == "svg" ? $img_pfad.$inm : $dir.$imageFolder.urlencode($folder).'/'.urlencode($inm).'?w='.$w).'" alt="'.$altText.'" class="icon '.$ausrichtungArray[$ausrichtung].'" '.($img_w ? ' width="'.$img_w.'" height="'.$img_h.'"' : '').' />'.($extLink ? '</a>' : '');
}

$morp = $inm .' / ';

$socialImage = urlencode($inm);
global $socialImg; if(!$socialImg) $socialImg = $dir.$imageFolder.urlencode($folder).'/'.($inm).'?w='.$w;

