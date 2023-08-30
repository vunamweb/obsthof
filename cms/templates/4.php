<?php
/* pixel-dusche.de */

global $hl,$uniqueID, $button, $imgSize, $imgClass, $template4count, $templateTotal;
global $fileID, $lastUsedTemplateID, $anker, $content_right, $bgIMG, $personenDIV;

$fileID = basename(__FILE__, '.php');
$lastUsedTemplateID = $fileID;

$edit_mode_class = 'container_edit ';

if(!$personenDIV) $personenDIV = 1;
else $personenDIV++;

if(!$template4count || $template4count < 1) {
	$sql = "SELECT cid FROM morp_cms_content WHERE tid=$fileID AND navid=$cid AND ton=1 ORDER by tpos";
	$res = safe_query($sql);
	$templateTotal = mysqli_num_rows($res);
	$template4count = 1;
}
else $template4count++;

$template = '
	    '.($personenDIV < 2 ? '<section style="background:url('.$bgIMG.');background-repeat:no-repeat;">' : '').'
            <div class="'.$edit_mode_class.'container"'.($anker ? ' id="'.$anker.'"' : '').'>
                <div class="row bg-text shdow personen">
                    <div><hr></div>
					<div class="col-12 col-lg-6">
						#cont#
                    </div>
					<div class="col-12 col-lg-6">
						'.$content_right.'
                    </div>
                </div>
            '.edit_bar($content_id,"edit_class").'
            </div>';
 
if(($template4count == $templateTotal || $tende) && !$templateIsClosed) {
	 $template .= '			 
		</section>
 ';
	 $template4count = 0;
	 $templateTotal = 0;
	 $templateIsClosed = 1;
}
  
$hl = '';
$button = '';
$content_right = '';
