<?php
/* pixel-dusche.de */

global $fileID, $lastUsedTemplateID, $Slider, $title;
global $cid, $uniqueID, $morpheus, $lan, $ipad, $buttons, $styles, $ankerLink, $farbe, $tabstand, $tabstand_bottom, $class, $grIMG, $templateIsClosed, $mobileIMG;

if(!$Slider) $Slider = 1;
else $Slider++;

$fileID = basename(__FILE__, '.php');
$lastUsedTemplateID = $fileID;

$edit_mode_class = 'container_edit ';

if($tref == 1 || !$tref) {
		// <img src="'.$mobileIMG.'" alt="'.$title.'" class="img-fluid img-onmobile">
	$template = '
<section class="section header-img"'.$grIMG.'>
	<div class="container-full">		
		<div class="stoerer">
#cont#								
		</div>
	</div>
</section>
';
}
else if($tref == 2) {
	$template = '
<section class="section slider">
	<div class="container-full">
		<div class="swiper myPartner">
			<div class="swiper-wrapper">		    			
#cont#
			</div>		
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>	
		</div>		
	</div>
</section>';
}
else if($tref == 22) {
	$template = '
<section class="section slider">
	<div class="container">
		<div id="carousel_ghls" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
#cont#								
			</div>		
			<button class="carousel-control-prev" type="button" data-bs-target="#carousel_ghls" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#carousel_ghls" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>
		</div>
	</div>
</section>
';
}

else if($tref == 3) $template .= '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="headerimg '.($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($class ? $class.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : '').'>
	<div class="container bildtext bildtext'.($klasse ? ' '.$klasse : '').'">
	   <div class="row">
			<div class="col-md-6 order-2 order-md-1 imgPaddingR">
<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>
			</div>
			<div class="col-md-6 order-1 order-md-2">
'.$grIMG.'
			</div>
		</div>
	</div>
</section>
';

$ankerLink = '';
$farbe = '';
$grIMG = '';
$class = '';
$tabstand = '';
$tabstand_bottom = '';
$templateIsClosed = 1;