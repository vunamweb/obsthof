<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # # 
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # # 

include("../nogo/funktion.inc");

$dir = "http://localhost/chung-shi/images/userfiles/image/";
$id 	= $_GET["imgid"];
$nm 	= $_GET["nm"];
$format = $_GET["format"];

$nm		= eliminiere($nm);
if ($format == "gif") 		$src = @ImageCreateFromGIF("../blob.php?imgid=$id&type=gif");
elseif ($format == "png") 	$src = ImageCreateFromPNG("../blob.php?imgid=$id&type=png");
else						$src = @ImageCreateFromJPEG("../blob.php?imgid=$id&type=jpeg");

$w 		= @ImageSx($src);
$h 		= @ImageSy($src);
$x 		= $w;
$y 		= $h;

$dst = @ImageCreateTrueColor($x,$y);
if ($format == "gif") imagecolortransparent($dst, 0);
@ImageCopyResampled($dst, $src, 0, 0, 0, 0, $x, $y, $w, $h);


if ($format == "gif") 		@imageGIF($dst,$nm.".".$format);
elseif ($format == "png") 	@imagePng($dst,$nm.".".$format);
else						@imageJPEG($dst,$nm.".".$format,100);

?>