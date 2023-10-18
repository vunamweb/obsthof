<?php
/* pixel-dusche.de */

global $uniqueID, $fileID, $lastUsedTemplateID, $anker, $class, $farbe, $tabstand, $anzahlOffenerDIV, $templateIsClosed, $text_rechts, $interner_link, $tabstand_bottom, $klasse, $HL, $linkButton;

$fileID = basename(__FILE__, '.php');
$lastUsedTemplateID = $fileID;
$templateIsClosed=1;

$template = '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="obsthof '.($tref == 3 ? 'pt0 pb0 ' : '').($tabstand ? ' pt0 ' : '').($tabstand_bottom? ' pb0 ' : '').($class ? $class.' bg-color' : '').'"'.($farbe ? ' style="background:#'.$farbe.'"' : '').'>
    <div class="container bildtext bildtext'.($klasse ? ' '.$klasse : '').'">
   		<div class="row vertical-align">
';


// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// TEMPLATE


if($tref == 1 || !$tref) $template .= '
            <div class="col-xs-12 col-sm-6 col-md-6 order-1 order-md-1 imgColL btn-abs col-md-push-6">
'.$grIMG.'<div class="btn-container">'.$linkButton.'</div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 order-2 order-md-2 contentPad col-md-pull-6">
<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>
            </div>
';

else if($tref == 2) $template .= '
            <div class="col-xs-12 col-sm-6 col-md-6 order-2 order-md-1 contentPad col-md-pull-6">
<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 order-1 order-md-2 imgColR btn-abs col-md-push-6">
'.$grIMG.'<div class="btn-container">'.$linkButton.'</div>
            </div>
';

else if($tref == 3) $template .= '
			<div class="col-xs-12 col-sm-6 col-md-6 order-2 order-md-1 contentPad">
<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 order-1 order-md-2 imgColR btn-abs">
'.$grIMG.'<div class="btn-container">'.$linkButton.'</div>
			</div>
';

else if($tref == 4) $template .= '
      	  		<div class="col-md-5 bild2 imgColL btn-abs">
'.$grIMG.'<div class="btn-container">'.$linkButton.'</div>
				</div>
      	  		<div class="col-xs-12 col-sm-6 col-md-6 offset-md-1 contentPad">
<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>					
				</div>
';


// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// END TEMPLATE

$template .= '
    	</div>
	</div>
</section>
';

$anzahlOffenerDIV = 0;

$class = '';
$farbe = '';
$grIMG = '';
$text_rechts = '';
$HL = '';
$linkButton = '';