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
$myauth = 40;
$config_in = 'in';
$conf4_active = ' class="active"';

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
		<li><a href="?datei=../css/css.css">CSS Individual</a></li>
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