<?php
/* pixel-dusche.de */

global $hl, $button, $imgSize, $imgClass;
global $fileID, $lastUsedTemplateID, $anker;

$fileID = basename(__FILE__, '.php');
$lastUsedTemplateID = $fileID;


$template = '
<section'.($anker ? ' id="'.$anker.'"' : '').' class="mt3 mb6">
  <div id="my3">
    <div class="swiper-wrapper">
#cont#
    </div>
    <!-- Add Pagination -->
    <div class="swiper-paginationX"></div>
  </div>
</section>

';

$hl = '';
$button = '';

?>