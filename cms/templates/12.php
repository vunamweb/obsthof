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
	<section'.($anker ? ' id="'.$anker.'"' : '').' class="section_aboutus '.$klasse.' '.($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($tclass ? $tclass.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : $video_special).'>  
    	<div class="'.$edit_mode_class.'container">
            '.$hl.'
            <div class="row">
#cont#                
            </div>
		'.edit_bar($content_id,"edit_class").'
        </div>
	</section>
';

$anzahlOffenerDIV=0;

$hl = '';
$farbe = '';
$tclass = '';
$itext = '';
$tabstand = '';
$tabstand_bottom = '';
$headerImg = '';
