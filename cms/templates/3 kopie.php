<?php
/* pixel-dusche.de */

global $hl, $button, $imgSize, $imgClass;
global $fileID, $lastUsedTemplateID, $anker, $ctHL, $subline;

$fileID = basename(__FILE__, '.php');
$lastUsedTemplateID = $fileID;

$indic = '';
for($i=1; $i<=$ctHL; $i++) {
	$indic .= '<button type="button" data-bs-target="#sturmSlider" data-bs-slide-to="'.($i-1).'"'.($i==1 ? ' class="active"' : '').' aria-current="true" aria-label="Slide '.$i.'"></button>';
}

$template = '
	<section class="bg-gray pb0">
    	<div class="container">
        	<div class="row">
            	<div class="col-12">
					'.$hl.'
				</div>
				
            	<div class="col-12 col-lg-10 offset-lg-1">
					<div id="sturmSlider" class="carousel slide" data-bs-ride="carousel">
		  				<div class="carousel-inner text-center">
#cont#				
  						</div>
						<div class="carousel-indicators">
'.$indic.'
						</div>
					</div>
				</div>
			</div>				
		</div>
	</section>
	<section class="bg-blue slider">
    	<div class="container">
        	<div class="row">
            	<div class="col-2">
					<button class="carousel-control-prev" type="button" data-bs-target="#sturmSlider" data-bs-slide="prev">
						<span class="fa fa-chevron-left" aria-hidden="true"></span>
						<span class="c-text prev">zurück</span>
					</button>
				</div>
            	<div class="col-8 text-center">
					'.setLink($subline, '', 'underline').'
				</div>
            	<div class="col-2 text-right">
  					<button class="carousel-control-next" type="button" data-bs-target="#sturmSlider" data-bs-slide="next">
    					<span class="c-text next">weiter</span>
    					<span class="fa fa-chevron-right" aria-hidden="true"></span>
  					</button>
				</div>
        	</div>
    	</div>
	</section>	
';

$hl = '';
$button = '';
$subline = '';
$ctHL = 0;