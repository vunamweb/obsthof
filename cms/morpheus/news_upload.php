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
$myauth = 14;
include("cms_include.inc");


$nid 	 = $_REQUEST["nid"];
$ngid 	 = $_REQUEST["ngid"];
$imgid 	 = $_REQUEST["imgid"];
$news	 = $_REQUEST["news"];

$ordner = "images/news";
$db		= "news";
# print_r($_REQUEST);
# echo $db;

if($_FILES) {
	$tmp  = $_FILES['image']['tmp_name'][0];
	$img  = strtolower($_FILES['image']['name'][0]);

	if ($polar) $img = "aktuell".$polar.".jpg";

	if (!move_uploaded_file($tmp, "../$ordner/".$img)) die("upload fehlgeschlagen!");

	chmod("../$ordner/".$img, 0777);

	if ($db == "news") 	$query = "update $db set $imgid='$img' where nid='$nid'";
	elseif ($db)		$query = "update $db set djimg='$img' where djid='$djid'";

	if ($db && !$polar) {
		$result = safe_query($query);
		safe_query($query);
		unlink($tmp);
	}

	if ($polar) 				die("<script language=\"JavaScript\">document.location='polaroid.php';</script>");
	elseif ($news) 				die("<script language=\"JavaScript\">document.location='news.php?edit=$nid&ngid=$ngid';</script>");
	elseif ($db == "news")		die("<script language=\"JavaScript\">document.location='programm.php?edit=$nid&ngid=$ngid';</script>");
	else	 					die("<script language=\"JavaScript\">document.location='dj.php?edit=$djid';</script>");
}
else {
	echo "<div id=content_big class=text>\n<p><b>Bild Upload</b></p>
		<form action=\"news_upload.php\" method=post enctype=\"multipart/form-data\">\n\n";

	echo '	<input name="image[]" type="file" style="width:500px"><br>
			<input name=ngid type=hidden value='.$ngid.'>
			<input name=djid type=hidden value='.$djid.'>
			<input name=nid type=hidden value='.$nid.'>
			<input name=polaroid type=hidden value='.$polar.'>
			<input name=news type=hidden value='.$news.'>
			<input name=imgid type=hidden value='.$imgid.'>
			<p><input type=submit style="background-color:#7B1B1B;color:#FFFFFF;font-weight:bold;width:100px;" value="upload starten" style="width:100px;background-color:#BBBBBB"></p>
	</form>
	';
}

echo '<p><a href="javascript:history.back();">' .backlink() .' zurück</a></p>';
?>

<?
include("footer.php");
?>
