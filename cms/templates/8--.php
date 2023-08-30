<?php
/* pixel-dusche.de */

global $uniqueID, $dir, $anker;
global $fileID, $lastUsedTemplateID;
global $headline, $grIMG;

$fileID = basename(__FILE__, '.php');
$lastUsedTemplateID = $fileID;


$template = '
	<section class="section_projects"'.($anker ? ' id="'.$anker.'"' : '').'>
		<div class="container-fluid">
	    	<div class="row">
                <div class="col-12 col-lg-12">
                    <h2>'.$headline.'</h2>

                    <div class="main-carousel">
'.$grIMG.'

		  			</div>
	  			</div>
			</div>			
		</div>
	</section>
';

$headline = '';