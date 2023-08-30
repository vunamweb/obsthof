<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bjoern t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

//print_r($_POST);

include("cms_include.inc");
// include("editor.php");

$datei = $_GET["datei"];

$save = $_POST["save"];
$data = ($_POST["data"]);

if($save && $data) {
	save_data($save,$data,"w");
}

$txt = read_data($datei);

?>
	<h2>Edit Config Files</h2>

	<ul>
		<li><a href="?datei=../nogo/config.php">config</a></li>
		<li><a href="?datei=../nogo/config_morpheus.inc">morpheus config</a></li>
		<li><a href="?datei=../.htaccess">htaccess</a></li>
	</ul>

	<form method="post">
		<input type="hidden" name="save" value="<?php echo $datei; ?>">
		<textarea name="data" id="data" class="form-control" style="min-height:500px;"><?php echo $txt; ?></textarea>
		<input type="submit" value="speichern" class="button" />
	</form>

<?php

?>

<?php
include("footer.php");
?>