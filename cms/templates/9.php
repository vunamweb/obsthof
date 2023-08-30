<?php
/* pixel-dusche.de */

global $uniqueID, $hl,$uniqueID, $anker, $headerImg;
global $fileID, $lastUsedTemplateID, $clink;

$fileID = basename(__FILE__, '.php');
$lastUsedTemplateID = $fileID;

$template = '
            <section'.($anker ? ' id="'.$anker.'"' : '').'>
                '.$headerImg.'
                <div class="text-top-home animate__animated animate__zoomIn">
<div id="'.$uniqueID.'" class="directeditmode">#cont#</div>
                </div>
                <div class="bottom-home">
                    <div class="container text-bottom-home">
                        <div class="row align-items-center">
                            '.$clink.'   
                        </div>
                    </div>
                </div>
            </section>';

