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
$myauth = 10;
include("cms_include.inc");

$cust 	 = $_REQUEST["cust"];
$mid 	 = $_REQUEST["mid"];
$nid 	 = $_REQUEST["nid"];
$ngid 	 = $_REQUEST["ngid"];
$pgid 	 = $_REQUEST["pgid"];
$imgid 	 = $_REQUEST["imgid"];
$news	 = $_REQUEST["news"];

$cedit	= $_REQUEST["cedit"];
$navid	= $_REQUEST["navid"];

$folder	 = $_REQUEST["folder"];
$id 	 = $_REQUEST["id"];
$from 	 = $_REQUEST["from"];
$setid 	 = $_REQUEST["setid"];
$tbl 	 = $_REQUEST["tbl"];

$nltid 	 = $_REQUEST["nltid"];
$nl		= $_REQUEST["nl"];
$liste	= $_REQUEST["liste"];

$download 	 = $_REQUEST["download"];
$tab 	 = "foto";
$mformid = $_REQUEST["mformid"];
$fileNameExtend = $_REQUEST["zusatz"];

// id='.$edit.'&imgid='.$arr[0].'&folder='.$imgfolder.'&from='.$scriptname.'&setid='.$id.'

# ziel-ordner und db bestimmen
if ($folder) {
	$ordner = "images/".$folder;
	$db		= $tbl;
	$cedit	= $id;
	$jumpback = $from.'.php';
	$tab = $imgid;
}
elseif ($navid) {
	$ordner = "images/backg";
	$db		= "morp_cms_content";
	$setid  = "cid";
	$tab 	= "timage";
}
elseif ($download) {
	$ordner = "images/download";
	$db		= "morp_cms_pdf_group";
	$setid  = "pgid";
	$cedit	= $download;
	$jumpback = 'pdf_group.php';
	$tab = $imgid;
}
elseif ($news) 	{
	$ordner = "images/news";
	$db		= "morp_cms_news";
	$setid  = "nid";
	$cedit	= $nid;
	$tab = $imgid;
}
elseif ($cust) 	{
	$ordner = "secure/dfiles/HgtFGDkjg/";
	$db		= "morp_download";
	$setid  = "benutzer";
	$imgid 	= "datei";
	$jumpback = "customer_kat.php";
}
elseif ($mid) 	{
	$ordner = "images/team";
	$db		= "morp_mitarbeiter";
	$setid  = "mid";
	$cedit	= $mid;
	$jumpback = "morp_mitarbeiter.php";
}
elseif ($nl) 	{
	$ordner = "img/";
	$db		= "morp_newsletter_cont";
	$setid  = "nlcid";
	$cedit	= $_REQUEST["nlcid"];
	$jumpback = "newsletter_edit.php";
	$tab 	 = $_REQUEST["imgid"];
	$zusatz  = '&liste='.$liste;
}
elseif ($nltid) 	{
	$ordner = "images/";
	$db		= "morp_newsletter_template";
	$setid  = "nltid";
	$cedit	= $_REQUEST["nltid"];
	$jumpback = "newsletter_template.php";
	$tab 	 = $_REQUEST["imgid"];
	$zusatz  = '';
}
else 	{
	$ordner = "images/news";
	$db		= "news";
}

echo "<div>\n\n";
// echo $tab.' / '.$ordner;
#die();


if($_FILES) {
	$tmp  = $_FILES['image']['tmp_name'];
	$img  = strtolower(($_FILES['image']['name']));

	$img = explode(".", $img);
	$position = count($img)-1;
	$fileType = $img[$position];
	$fileName = ($fileNameExtend ? $fileNameExtend.'-' : '').eliminiere($img[0]).'.'.$fileType;

	if (!move_uploaded_file($tmp, "../$ordner/".$fileName)) die("upload fehlgeschlagen!");
	chmod("../$ordner/".$fileName, 0777);

	// echo "../$ordner/".$fileName;

	if ($cust)		$query = "INSERT $db set $imgid='$fileName', benutzer='$cust'";
	elseif ($db)	$query = "UPDATE $db SET $tab='$fileName' WHERE $setid='$cedit'";

	// echo $query;
	// die();

	if ($query) {
		//$result = safe_query($query);
		safe_query($query);
		#unlink($tmp);
	}

	# rueckspruenge zu den ausgangs-tools
	if ($news) 				die("<script language=\"JavaScript\">document.location='news.php?edit=$nid&ngid=$ngid';</script>");
	elseif ($navid)			die("<script language=\"JavaScript\">document.location='content_template.php?edit=$cedit&navid=$navid';</script>");
	else					die("<script language=\"JavaScript\">document.location='".$jumpback."?edit=$cedit".($pgid ? '&pgid='.$pgid : '').($liste ? '&liste='.$liste : '')."';</script>");
}

else {
	if($nl) {
		echo '
		<p>&nbsp;</p>
		<p><a href="newsletter_image.php?liste='.$liste.'&edit='.$cedit.'&imgid='.$tab.'&nl='.$nl.'">&raquo; Wähle Bild aus dem Bildarchiv</a></p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>	
		';
	}
	else {
		echo "<h2>Bild Upload</h2><br>
			<form method=post enctype=\"multipart/form-data\">\n\n";
	
		echo '	<input name="image" type="file" style="width:500px"><br>
				<input name=ngid type=hidden value='.$ngid.'>
				<input name=cedit type=hidden value='.$cedit.'>
				<input name=cust type=hidden value='.$cust.'>
				<input name=nid type=hidden value='.$nid.'>
				<input name=navid type=hidden value='.$navid.'>
				<input name=news type=hidden value='.$news.'>
				<input name=tn type=hidden value='.$tn.'>
				<input name=full type=hidden value='.$full.'>
				<input name=imgid type=hidden value='.$imgid.'>
				<input name=nltid type=hidden value='.$nltid.'>
				<input name=liste type=hidden value='.$liste.'>
				<input name=csv type=hidden value='.$csv.'>
				<input name=zusatz type=hidden value='.$fileNameExtend.'>
				<p><input type="submit" value="upload starten"></p>
		</form>';
		
	}
}

#echo '<p><a href="javascript:history.back();">' .backlink() .' zur&uuml;ck</a></p>';
?>

<?php
include("footer.php");
