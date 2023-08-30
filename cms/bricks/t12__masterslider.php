<?php
global $img_pfad, $ausrichtung, $ausrichtungArray, $dir, $emotional, $socialImage, $morpheus, $imageFolder, $slideCt;

$table 		= 'morp_masterslider_slides';
$tid 		= 'slidesID';
$nameField 	= "slidesName";
$sortField 	= 'slidesPos';

$table2 	= 'morp_masterslider';
$tid2 		= 'msID';
$nameField2 = "msName";

if(!$slideCt) $slideCt = 1;
else $slideCt++;

	/// parent table

	$sql = "SELECT * FROM $table2 WHERE $tid2=$text";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
	$bgImage = $row->msBgImg;
	$altText = $row->msName;
	$delay = $row->msDelay;

	//// ______________________________________________________________

	$output .= '
	    <div class="ms-slide slide-'.$slideCt.'" data-delay="'.$delay.'">
	        <img src="'.$dir.'images/blank.gif" data-src="'.$dir.'images/masterslider/'.$bgImage.'" alt="'.$altText.'"/>
';


	$sql = "SELECT * FROM $table WHERE $tid2=$text";
	$rs = safe_query($sql);
	// echo mysqli_num_rows($rs);

	while($rw = mysqli_fetch_object($rs)) {
		$output .= setMasterSliderSingle($rw->slidesImg, $rw->slidesText, $rw->slidesAni, $rw->slidesAlt, $rw->slidesLeft, $rw->slidesTop, 'images/masterslider/', $dir);

	}




$output .= '
	    </div>
	    <!-- end of slide -->
';


$morp = 'Masterslider / ';

?>