<?php
global $img_pfad, $ausrichtung, $ausrichtungArray, $dir, $imgGal, $morpheus, $headerImgCt, $imageFolder, $new_header_image, $aArray;
global $tref, $noHeader, $grIMG, $mobileIMG, $cid;

$data = explode("|", $text); $imgid = $data[0]; $ausrichtung = $data[1]; if(!$ausrichtung) $ausrichtung = 1;

$w = 1500;

// if(!$headerImgCt) $headerImgCt=1;
// else $headerImgCt++;

$ausrichtungBG = array(
	1 => ' center',
	2 => ' left',
	3 => ' center',
	4 => ' right',
);

if($new_header_image) {
	$imgName = get_db_field($new_header_image, 'imgname', 'morp_cms_image', 'imgid');
	$output .= '
			<img src="'.$img_pfad.$imgName.'" alt="'.$imgName.'" class="img-emo '.$aArray[$ausrichtung].'" />
	';
}

else if($imgid) {
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
	
	$grIMG = ' style="background:url('.$img_pfad.$inm.'); background-size:cover; background-position: '.$ausrichtungBG[$ausrichtung].' '.($cid == 5 ? 'center' : 'top').';"';
	$mobileIMG = $img_pfad.$inm;
	
	// else $output .= '
	// <div class="rel">
	// 	<img src="'.$img_pfad.$inm.'" srcset="'.$dir.'mthumb.php?w=500&amp;src=images/userfiles/image/'.urlencode($inm).' 600w, '.$dir.'mthumb.php?w=800&amp;src=images/userfiles/image/'.urlencode($inm).' 800w, '.$img_pfad.$inm.' 1000w" alt="'.$altText.'" class="d-block w-100 img-emo '.$aArray[$ausrichtung].'" />
	// 	
	// 	<div class="title">
	// 		'.$titel.'
	// 	</div>
	// 	<div class="title2">
	// 		'.$titel2.'
	// 	</div>
	// </div>';

}

$morp = $inm;

$titel = '';
$titel2 = '';

$socialImage = urlencode($inm);
global $socialImg; if(!$socialImg) $socialImg = $dir.$imageFolder.urlencode($folder).'/'.($inm).'?w='.$w;
