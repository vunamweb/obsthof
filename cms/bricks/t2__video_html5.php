<?php
global $pdf, $dir, $video, $fliesstext, $countSlider;
global $map, $mobile, $mobileJPG, $videoPlay, $img_pfad;


if(!$countSlider) $countSlider=1;
else $countSlider++;

if($text) {
$query  = "SELECT * FROM `morp_cms_pdf` WHERE pid=$text";
$result = safe_query($query);
$row = mysqli_fetch_object($result);

$video = 1;

$de = $row->pdesc;
$nm = $row->pname;
$si = $row->psize;
$da = $row->pdate;
$pi = $row->pimage;
$da = euro_dat($da);

$typ = explode(".", $nm);
$c	 = (count($typ)-1);
$img = $typ[$c]."_s.gif";

$w = 820;
$h= 484;
$videoPlay = 0;

/*
$mobileJPG = $dir.'pdf/'.$typ[0].'.jpg';

if($mobile) $output .= '
				'.($fliesstext ? '<div class="headerText">
					'.$fliesstext.'
				</div>' : '').'
';
else {
*/
	$videoPlay = 1;
	$output .= '
				'.($fliesstext ? '<div class="headerText">
					'.$fliesstext.'
				</div>' : '').'

	           <div class="responsive-video">
	                <video src="'.$dir.'pdf/'.$typ[0].'.mp4"  controls playsinline  class="video-absolute my-video" poster="'.$img_pfad.$typ[0].'.jpg"></video>
	            </div>
';
// }
	$morp = $typ[0];
}