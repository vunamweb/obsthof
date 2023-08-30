<?php
/* pixel-dusche.de */

global $uniqueID, $hl,$uniqueID, $button, $imgSize, $imgClass;
global $fileID, $lastUsedTemplateID, $anker, $templateIsClosed, $anzahlOffenerDIV;

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

$template = '
<section class="section teaser1">
  	<div class="'.$edit_mode_class.' container">
		<div class="row directeditmode" id="'.$uniqueID.'">
			#cont#
		</div>
		'.edit_bar($content_id,"edit_class").'
	</div>
</section>
';

$templateIsClosed = 1;