<?php
global $img_pfad, $ausrichtung, $ausrichtungArray, $dir, $icon, $imageFolder;

$data = explode("|", $text); $imgid = $data[0]; $ausrichtung = $data[1]; if(!$ausrichtung) $ausrichtung = 1;

if($text) {
	$que  	= "SELECT `longtext`, itext, imgname, name FROM `morp_cms_image` i, `morp_cms_img_group` g WHERE g.gid=i.gid AND imgid=$imgid";
	$res 	= safe_query($que);
	$rw     = mysqli_fetch_object($res);
	$itext 	= $rw->itext;
	$ltext 	= $rw->longtext;

	$inm 	= $rw->imgname;
	$altText = $itext ? $itext : repl("\n", " ", $ltext); if(!$altText) $altText = $morpheus["client"].' '.$inm;
	$folder	= str_replace(array(";", " / ", "/", "  ", " "), array("","-","-", "-", "-"), $rw->name);

	$output .= '
				<div class="box_video">
					<img src="'.$dir.$imageFolder.urlencode($folder).'/'.urlencode($inm).'?w=600" alt="" class="img-fluid w-100">
					<a class="btnPlay" href="#"><img src="'.$dir.'images/SVG/i_play.svg" alt="" class="img-fluid" width="157"></a>
				</div>
';
}

$morp = $inm;

$socialImage = urlencode($inm);
global $socialImg; if(!$socialImg) $socialImg = $dir.$imageFolder.urlencode($folder).'/'.($inm);

?>