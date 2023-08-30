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


$pgid 	 = $_REQUEST["pgid"];
$pid 	 = $_REQUEST["pid"];

if($pid) {
	$ordner = "media";
	$db 	= "morp_produkt";
	$id 	= "pid";
	$wayback = "morp_shop_produkt.php?edit=".$pid;
	$col 	 = $_REQUEST["col"];
	$edit	= $pid;
}
else {
	$ordner = "pdf";
	$db		= "pdf";
	$id 	= "pid";
	$wayback = "pdf.php?pgid=".$pgid;
	$col 	= "pname";
}

# print_r($_REQUEST);
# echo $db;

if($_FILES) {
	$tmp  = $_FILES['image']['tmp_name'][0];
	$target = "../$ordner/";
	$file  = strtolower($_FILES['image']['name'][0]);

	$pruefe = explode(".", $file);
	$ct = count($pruefe);
	$pruefe = strtolower($pruefe[$ct-1]);

	if($pruefe == "zip") {
		if (!move_uploaded_file($tmp, $target.$file)) die("upload fehlgeschlagen!");

		chmod($target.$file, 0777);

		// assuming file.zip is in the same directory as the executing script.
		// get the absolute path to $file
		$path = pathinfo(realpath($target.$file), PATHINFO_DIRNAME);

		$zip = new ZipArchive;
		$res = $zip->open($target.$file);

		if ($res === TRUE) {
			// extract it to the path we determined above
			$zip->extractTo($path);
			$zip->close();
			//echo "WOOT! $file extracted to $path";

			if($db == "pdf") {
				$sql = "SELECT pid FROM $db WHERE pname='$file'";
				$res = safe_query($sql);

				if(mysqli_num_rows($res)) {
					$row = mysqli_fetch_object($res);
					$sql = "UPDATE $db SET $col='$file', pgid='$pgid', pdate='".date("Y-m-d")."' WHERE $id='".$row->pid."'";
				}
				else $sql = "INSERT $db SET $col='$file', pgid='$pgid', pdate='".date("Y-m-d")."'";
			}
			else {
				$sql = "UPDATE $db SET $col='$file' WHERE $id='".$edit."'";
			}

			// echo $sql;

			safe_query($sql);

			echo "OPENED $file.  <br><br><a href=\"$wayback\"><i class=\"fa fa-chevron-left\"></i> zurück</a>";

			//die("<script language=\"JavaScript\">document.location='$wayback';</script>");
		} else {
			echo "Doh! I couldn't open $file.  <br><br><a href=\"pdf.php?pgid=$pgid\"><i class=\"fa fa-chevron-left\"></i> zurück</a>";
		}

	}

}
else {
	echo "<div id=content_big class=text>\n<p><b>ZIP Upload</b></p>
		<form method=\"post\" enctype=\"multipart/form-data\">\n\n";

	echo '	<input name="image[]" type="file" style="width:500px"><br>
			<input name=pgid type=hidden value='.$pgid.'>
			<p><input type=submit style="background-color:#7B1B1B;color:#FFFFFF;font-weight:bold;" value="zip upload starten" style="width:100px;background-color:#BBBBBB"></p>
	</form>
	';
	echo '<p><a href="javascript:history.back();">' .backlink() .' zurück</a></p>';
}

?>

<?php
include("footer.php");
?>
