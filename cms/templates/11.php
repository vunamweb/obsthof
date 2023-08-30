<?php
/* pixel-dusche.de */

global $emotional, $headerImg;
global $uniqueID, $fileID, $lastUsedTemplateID, $anzahlOffenerDIV, $templateIsClosed;

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
#cont#


		


            <section class="'.$edit_mode_class.'sectionbanner">
                <ul>
					'.$headerImg.'                    
                </ul>
			'.edit_bar($content_id,"edit_class").'
            </section>
';

