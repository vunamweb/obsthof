<?php
global $morpheus, $dir, $navID;

$v = explode('|', $text);

$video = $v[0];
$w = $v[1];
$h = $v[2];

if (!$w) $w = 595;
if (!$h) $h = 361;

$output .= '
	<div class="video_wrapper" style="background-image: url( \'https://img.youtube.com/vi/'.trim($video).'/hqdefault.jpg\' );">
		<div class="video_trigger" data-source="'.trim($video).'" data-type="youtube">
			<p class="text-center">Mit dem Aufruf des Videos erklären sie sich einverstanden, dass ihre Daten an YouTube übermittelt werden und das sie die <a href="'.getUrl(10).'">Datenschutzerklärung</a> gelesen haben.</p>
			<input type="button" class="btn btn-info btn-play" value="YouTube Video abspielen" />
		</div>
	    <div class="video_layer"><iframe src="" border="0" data-scaling="true" data-format="16:9"></iframe></div>
	</div>
';
