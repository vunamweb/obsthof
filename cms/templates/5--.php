<?php
/* pixel-dusche.de */

global $hl,$uniqueID, $button, $imgSize, $imgClass, $template5count, $templateTotal;
global $fileID, $lastUsedTemplateID, $anker, $content_right, $bgIMG, $tippsDIV, $buch;

$fileID = basename(__FILE__, '.php');
$lastUsedTemplateID = $fileID;

$edit_mode_class = 'container_edit ';

if(!$tippsDIV) $tippsDIV = 1;
else $tippsDIV++;

if(!$template5count || $template5count < 1) {
	$sql = "SELECT cid FROM morp_cms_content WHERE tid=$fileID AND navid=$cid AND ton=1 ORDER by tpos";
	$res = safe_query($sql);
	$templateTotal = mysqli_num_rows($res);
	$template5count = 1;
}
else $template5count++;

$template = '
	    '.($tippsDIV < 2 ? '<section style="background:url('.$bgIMG.');background-repeat:no-repeat;">' : '').'
            <div class="'.$edit_mode_class.'container"'.($anker ? ' id="'.$anker.'"' : '').'>
                <div class="row bg-text shdow tipps">
                    <div><hr></div>
					<div class="col-12 col-md-4 col-lg-3">
						'.$buch.'
                    </div>
					<div class="col-12 col-md-8 col-lg-9 download-rel">
						#cont#
                    </div>
                </div>
            '.edit_bar($content_id,"edit_class").'
            </div>';
 
if(($template5count == $templateTotal || $tende) && !$templateIsClosed) {
	 $template .= '			 
		</section>
 ';
	 $template5count = 0;
	 $templateTotal = 0;
	 $templateIsClosed = 1;
}
  
$hl = '';
$button = '';
$content_right = '';
