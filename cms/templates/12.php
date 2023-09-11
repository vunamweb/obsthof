<?php
/* pixel-dusche.de */
global $uniqueID, $design, $itext, $startDIV, $anker, $hl, $bgIMG;
global $fileID, $lastUsedTemplateID, $tabstand, $tabstand_bottom, $anker, $anzahlOffenerDIV, $templateIsClosed, $parallaxText;
global $video, $accordion, $klasse;
global $class_inner, $farbe_inner, $tclass, $farbe, $headerImg;

$fileID = basename(__FILE__, '.php');
$lastUsedTemplateID = $fileID;

$edit_mode_class = 'container_edit ';

if($lastUsedTemplateID && $lastUsedTemplateID != $fileID && !$templateIsClosed) {
	for($i=1; $i<=$anzahlOffenerDIV; $i++) $template .= '					</div>
';

	$template .= '
				</section>
';
	$templateIsClosed=1;
}

if($tref == 1 || $tref == 4 || !$tref) $template = '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="section_news '.$klasse.' '.($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($tclass ? $tclass.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : $video_special).'>  
	<div class="swiper-viewport ">
	  <div id="obsthofNews" class="swiper-container box-carousel">
		<div class="swiper-wrapper">
#cont#                
		</div>
	  </div>
	  <div class="swiper-pagination obsthofNews"></div>
	  <div class="swiper-pager">
		<div class="swiper-button-next"></div>
		<div class="swiper-button-prev"></div>
	  </div>
	</div>
</section>
	<script type="text/javascript"><!--
	$("#obsthofNews").swiper({
		mode: \'horizontal\',
		slidesPerView: 4,
		spaceBetween: 40,
		nextButton: \'.swiper-button-next\',
		prevButton: \'.swiper-button-prev\',
		loop: true,
		breakpoints: {
			600: {
				slidesPerView: 1
			},
			767: {
				slidesPerView: 2
			},
			992: {
				slidesPerView: 2
			},
			1400: {
				slidesPerView: 3
			}
		}
	});
	--></script>
';

$anzahlOffenerDIV=0;

$hl = '';
$farbe = '';
$tclass = '';
$itext = '';
$tabstand = '';
$tabstand_bottom = '';
$headerImg = '';
