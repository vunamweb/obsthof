<?php
/* pixel-dusche.de */
global $hl,$uniqueID, $design, $itext, $startDIV, $anker, $h1, $bgIMG;
global $fileID, $lastUsedTemplateID, $tabstand, $tabstand_bottom, $anker, $anzahlOffenerDIV, $templateIsClosed, $parallaxText;
global $video, $accordion, $klasse, $imgGal, $ausrichtung;
global $class_inner, $farbe_inner, $tclass, $farbe, $position, $date_box, $class, $left_content, $aArray;

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





if($tref == 1 || !$tref) $template = '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="obsthof '.$klasse.' '.($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($class ? $class.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : $video_special).'>

'.$date_box.'

  <div class="'.$edit_mode_class.'container '.($class_inner ? $class_inner.' col-inner ' : '').($tabstand ? ' pt0 ' : '').''.($tabstand_bottom? ' pb0 ' : '').'"'.($bgIMG ? ' style="background:url('.$bgIMG.') no-repeat fixed; background-size: cover; padding-top:2em; padding-bottom:2em;"' : '').'>
        <div class="row">
            <div class="col-12'.($linkbox ? ' linkbox' : '').'"'.($linkbox ? ' id="lb'.$content_id.'" ref="'.$linkbox.'"'.($extLink ? ' data-extern="1"' : '') : '').($farbe_inner ? ' style="background:#'.$farbe_inner.'"' : '').'>
<div id="'.$uniqueID.'" class="directeditmode">
'.($date_box ? '#HL#' : '').'
#cont#
</div>
            </div>
        </div>
		'.edit_bar($content_id,"edit_class").'
    </div>
</section>
';
elseif($tref == 2) $template = '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="obsthof '.$klasse.' '.($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($class ? $class.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : $video_special).'>
	<div class="'.$edit_mode_class.'container '.($tabstand ? ' pt0 ' : '').''.($tabstand_bottom? ' pb0 ' : '').'"'.($bgIMG ? ' style="background:url('.$bgIMG.') no-repeat fixed; background-size: cover; padding-top:2em; padding-bottom:2em;"' : '').'>
        <div class="row">
            <div class="col-12 text-center'.($linkbox ? ' linkbox' : '').'"'.($linkbox ? ' id="lb'.$content_id.'" ref="'.$linkbox.'"'.($extLink ? ' data-extern="1"' : '') : '').($farbe_inner ? ' style="background:#'.$farbe_inner.'"' : '').'>
<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>
	        </div>
        </div>
	'.edit_bar($content_id,"edit_class").'
    </div>
</section>
';
elseif($tref == 3) $template = '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="obsthof balken text-center '.$klasse.' '.($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($class ? $class.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : $video_special).'>
	<div class="'.$edit_mode_class.'container-fluid fs '.($tabstand ? ' pt0 ' : '').''.($tabstand_bottom? ' pb0 ' : '').'"'.($bgIMG ? ' style="background:url('.$bgIMG.') no-repeat; background-size: cover; padding-top:2em; padding-bottom:2em;"' : '').'>
        <div class="row">
            <div class="col-12'.($linkbox ? ' linkbox' : '').'"'.($linkbox ? ' id="lb'.$content_id.'" ref="'.$linkbox.'"'.($extLink ? ' data-extern="1"' : '') : '').($farbe_inner ? ' style="background:#'.$farbe_inner.'"' : '').'>
<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>
            </div>
        </div>
	'.edit_bar($content_id,"edit_class").'
    </div>
</section>
';
elseif($tref == 4) $template = '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="bg-image d-flex '.$klasse.' '.($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($class ? $class.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : '').'>
	<div class="'.$edit_mode_class.'container-fluid '.($tabstand ? ' pt0 ' : '').''.($tabstand_bottom? ' pb0 ' : '').'"'.($bgIMG ? ' style="background:url('.$bgIMG.') no-repeat; background-size: cover; '.($ausrichtung > 1 ? 'background-position:'.$aArray[$ausrichtung].';' : '').'padding-top:6em; padding-bottom:6em;"' : '').'>
        <div class="container h-100">
        	<div class="row h-100 align-items-center">
        		<div class="col-12 col-lg-6 col-md-4">'.$left_content.'</div>
            	<div class="col-12 col-lg-6 col-md-8 bg-text shdow '.($linkbox ? ' linkbox' : '').'"'.($linkbox ? ' id="lb'.$content_id.'" ref="'.$linkbox.'"'.($extLink ? ' data-extern="1"' : '') : '').'>
	<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>
            	</div>
        	</div>
        </div>
	'.edit_bar($content_id,"edit_class").'
    </div>
</section>
';
elseif($tref == 5) $template = '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="bg-image d-flex '.$klasse.' '.($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($class ? $class.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : '').'>
	<div class="'.$edit_mode_class.'container-fluid '.($tabstand ? ' pt0 ' : '').''.($tabstand_bottom? ' pb0 ' : '').'"'.($bgIMG ? ' style="background:url('.$bgIMG.') no-repeat; background-size: cover; padding-top:6em; padding-bottom:6em;"' : '').'>
		<div class="container">
			<div class="row">
				<div class="col-12">
	<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>
				</div>
			</div>
		</div>
	'.edit_bar($content_id,"edit_class").'
	</div>
</section>
';
elseif($tref == 8) $template = '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="'.$klasse.' '.($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($class ? $class.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : '').'>
	<div class="'.$edit_mode_class.'container-fluid '.($tabstand ? ' pt0 ' : '').''.($tabstand_bottom? ' pb0 ' : '').'"'.($bgIMG ? ' style="background:url('.$bgIMG.') no-repeat fixed; background-size: cover; padding-top:6em; padding-bottom:6em;"' : '').'>
        <div class="container">
        	<div class="row">
            	<div class="col-12 col-lg-6 col-md-8 bg-text'.($linkbox ? ' linkbox' : '').'"'.($linkbox ? ' id="lb'.$content_id.'" ref="'.$linkbox.'"'.($extLink ? ' data-extern="1"' : '') : '').'>
	<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>
            	</div>
        	</div>
        </div>
	'.edit_bar($content_id,"edit_class").'
    </div>
</section>
';
elseif($tref == 6) $template = '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="obsthof team '.$klasse.' '.($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($class ? $class.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : $video_special).'>
	<div class="'.$edit_mode_class.'container '.($tabstand ? ' pt0 ' : '').''.($tabstand_bottom? ' pb0 ' : '').'"'.($bgIMG ? ' style="background:url('.$bgIMG.') no-repeat fixed; background-size: cover; padding-top:2em; padding-bottom:2em;"' : '').'>
		<div class="row">
			<div class="col-12 '.($linkbox ? ' linkbox' : '').'"'.($linkbox ? ' id="lb'.$content_id.'" ref="'.$linkbox.'"'.($extLink ? ' data-extern="1"' : '') : '').($farbe_inner ? ' style="background:#'.$farbe_inner.'"' : '').'>
				<div id="'.$uniqueID.'" class="directeditmode">
				<div class="row">#cont#</div>
				</div>
			</div>
		</div>
	'.edit_bar($content_id,"edit_class").'
	</div>
</section>
';
else if($tref == 7) $template = '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="obsthof '.$klasse.' '.($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($tclass ? $tclass.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : $video_special).'>
<div class="'.$edit_mode_class.'container '.($class_inner ? $class_inner.' col-inner ' : '').($tabstand ? ' pt0 ' : '').''.($tabstand_bottom? ' pb0 ' : '').'"'.($bgIMG ? ' style="background:url('.$bgIMG.') no-repeat fixed; background-size: cover; padding-top:2em; padding-bottom:2em;"' : '').'>
        <div class="row" style="position:relative">
        <div class="rahmen-team">
            <div class="col-12 pad-team'.($linkbox ? ' linkbox' : '').'"'.($linkbox ? ' id="lb'.$content_id.'" ref="'.$linkbox.'"'.($extLink ? ' data-extern="1"' : '') : '').'>
<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>
			</div>
            <div class="col-12 flex space-between">
			'.$imgGal.'
            </div>
        </div>
        </div>
	'.edit_bar($content_id,"edit_class").'
    </div>
</section>
';

$anzahlOffenerDIV=0;


$hl = '';
$farbe = '';
$tclass = '';
$class='';
$bgIMG = '';
$itext = '';
$parallaxText = '';
$parallax = '';
$tabstand = '';
$tabstand_bottom = '';
$accordion = '';
$imgGal = '';
$linkbox = '';
$extLink = 0;
$left_content='';
$class_inner = '';
$farbe_inner = '';