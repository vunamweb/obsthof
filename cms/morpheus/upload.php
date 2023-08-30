<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # # 
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # # 

include("cms_include.inc");

echo '<div id="content" class="text"><b>W&auml;hlen Sie das PDF zum Upload</b><br>

<form action="upload-ende.php" method="post" enctype="multipart/form-data">';

	$id 	 = $_GET["id"];
	$bereich = $_GET["bereich"];
  	echo "<input type=hidden name=id value='$id'>
  		<input type=hidden name=bereich value='$bereich'>";
?>
  <input type="File" name="userfile" size="60" class="text"><p>
  <input type="submit" value="PDF uploaden" class="text">
</form>

<?
include("footer.php");
?>