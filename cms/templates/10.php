<?php
/* pixel-dusche.de */

global $dir, $anker;
global $fileID, $lastUsedTemplateID,$anzahlOffenerDIV, $OffeneSection;
global $hl, $hl2, $date_box;

$fileID = basename(__FILE__, '.php');
$lastUsedTemplateID = $fileID;

if($OffeneSection) $template .= '
			</section>
';
$OffeneSection = 0;

// '.$date_box.'

$template = '

#cont#

';

$templateIsClosed = 1;
$anzahlOffenerDIV = 0;
$date_box ='';
