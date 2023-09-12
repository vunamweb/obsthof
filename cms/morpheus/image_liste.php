<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                             #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

$stelle = isset($_REQUEST["stelle"]) ? $_REQUEST["stelle"] : 0;

$myauth = 10;
$box = $_REQUEST["box"];
$ekko = 1;
$images_in = 'in';
include("cms_include.inc");

function morp_cms_img_group ($imgid) {
	$query  = "SELECT * FROM morp_cms_img_group order by name";
	$result = safe_query($query);
	while ($row = mysqli_fetch_object($result)) {
	 	$id = $row->gid;
		$nm = $row->name;
		if ($imgid == $id) $sel = "selected";
		else unset ($sel);
		$tmp .= "<option value=\"$id\" $sel>$nm</option>\n";
	}
	return $tmp;
}

$del 	 = $_REQUEST["del"];
$delete	 = $_REQUEST["delete"];
$save	 = $_REQUEST["save"];
$gid	 = $_REQUEST["gid"];

# beschreibenden text verwalten
$txtedit = $_REQUEST["txt"];
$newtext = $_POST["newtext"];
$ltext 	 = $_POST["longtext"];
$english_alt = $_POST["english_alt"];

# wenn bild in content eingesetzt wird
$stelle = $_REQUEST["stelle"];
$imglnk = $_REQUEST["imglnk"];
$navid  = $_REQUEST["navid"];
$edit   = $_REQUEST["edit"];
$cedit  = $_REQUEST["cedit"];
$db		= $_REQUEST["db"];
$art	= $_REQUEST["art"];
$vorlage= $_REQUEST["vorlage"];
$live_edit = $_GET["live_edit"];

$filenameORG = $_POST["filenameORG"];
$filename = $_POST["filename"];
$wgID = $_POST["wgID"] ? $_POST["wgID"] : 0;
$pcID = $_POST["pcID"] ? $_POST["pcID"] : 0;

$newsletter = $_REQUEST["newsletter"];
if ($_GET["db"] == "ec_kurs_art") $kurs = 1;

if ($cedit)  							$temp_lnk = "content_template.php?navid=$navid&edit=$cedit&vorlage=$vorlage";
elseif ($live_edit)  					$incl_lnk = "content_edit_live.php?navbar=0&db=$db&stelle=$stelle&navid=$navid&edit=$edit&art=$art&vorlage=$vorlage";
elseif ($navid || $kurs || $vorlage)  	$incl_lnk = "content_edit.php?box=$box&db=$db&stelle=$stelle&navid=$navid&edit=$edit&art=$art&vorlage=$vorlage";

# wenn bild in news eingesetzt wird
$nid	= $_REQUEST["nid"];
$ngid	= $_REQUEST["ngid"];

# deko bilder bestimmen
$inr 	= $_REQUEST["inr"];
$cid	= $_REQUEST["cid"];
$back	= $_REQUEST["back"];

# print_r($_REQUEST);

if ($save) {
	foreach ($_POST as $key=>$val) {
 		if (preg_match("/^gid/", $key)) {
			$tmp = explode ("_", $key);
			if ($val != $gid) {
				$que = "update `morp_cms_image` set gid=$val where imgid=$tmp[1]";
				safe_query($que);
			}
		}
	}

	create_img_liste();
}
elseif ($del) {
	$warnung = "<p><font color=#ff0000><b>Wollen Sie das Bild wirklich l&ouml;schen?</b></font></p>
				<p>Sind Sie sicher, dass dieses Bild auf keiner Ihrer Seiten mehr verwendet wird?<br>&nbsp;</p>
				<a href=\"image_liste.php?delete=$del&gid=$gid\" title=\"Bild l&ouml;schen!\">".'<i class="fa fa-trash"></i>'." Bild l&ouml;schen</a><p>
				<a href=\"image_liste.php?gid=$gid\" title=\"abbrechen\">" .backlink() ." abbrechen</a>";
}
# das bild wird endg&uuml;ltig gel&ouml;scht
elseif ($delete) {
	$query = "SELECT imgname FROM `morp_cms_image` WHERE imgid=$delete";
	$res = safe_query($query);
	$row = mysqli_fetch_object($res);
	$tmp = "../images/userfiles/image/".$row->imgname;
	@unlink($tmp);

	$query = "delete FROM `morp_cms_image` where imgid=$delete";
	safe_query($query);
	create_img_liste();
}
elseif($txtedit) {
	$query  = "SELECT * FROM `morp_cms_image` WHERE imgid=$txtedit";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
	$tx = $row->itext;
	$lt = $row->longtext;
	$english_alt = $row->english_alt;
	$inm 	= $row->imgname;
	$wgID = $row->wgID;
	$pcID = $row->pcID;

	
//	if (!$tx) $tx = "Bitte hier den beschreibenden Text einf&uuml;gen";
	$warnung = '<form action="image_liste.php" method="post"  class="form-inline">'."
		<input type=\"hidden\" value=\"$txtedit\" name=\"stelle\">
		<input type=\"hidden\" value=\"$txtedit\" name=\"inr\">
		<input type=\"text\" value=$gid name=\"gid\" class=\"form-control\"><br>
		<b>Externer Link (inkl. https:// angeben!)</b><br>
		<input type=\"text\" name=\"newtext\" size=\"100\" maxlength=\"255\" value='$tx' class=\"form-control\"><p>
		<p>&nbsp;</p>
		<b>ALT Text</b><br>
		<input type=\"text\" name=\"longtext\" size=\"100\" maxlength=\"255\" value='$lt' class=\"form-control\"><p>
		<div class=\"mt2 mb2\">$produktListe</div>
		<!--<input type=\"text\" name=\"english_alt\" size=\"100\" maxlength=\"255\" value='$english_alt' class=\"form-control\"><p>-->
		<p><b>Dateiname</b><br>
		<input type=\"text\" name=\"filename\" size=\"100\" maxlength=\"255\" value=\"$inm\" class=\"form-control\">
		<input type=\"hidden\" name=\"filenameORG\" value=\"$inm\"><br><br>
		<input type=\"submit\" name=\"speichern\" value=\"speichern\">
		<p>&nbsp;</p><p>".$inm."</p><p>Gruppen-Zugeh&ouml;rigkeit</p><p></p></form>

		<img src=\"../images/userfiles/image/".$inm."\" border=0 vspace=6>";
	#<select name=\"gid\">" .morp_cms_img_group($txtedit) ."</select>
}
elseif ($newtext || $ltext || $pcID || $wgID || $filename != $filenameORG) {
	if($filename != $filenameORG) {
		if(rename("../images/userfiles/image/".$filenameORG, "../images/userfiles/image/".$filename)) {
			$query = "UPDATE `morp_cms_image` SET itext='$newtext', `longtext`='$ltext', imgname='$filename' WHERE imgid=$inr";
			safe_query($query);
		}
	}
	else {
		$query = "UPDATE `morp_cms_image` SET itext='$newtext', `longtext`='$ltext' WHERE imgid=$inr";
		safe_query($query);
	}

	#$gid = $inr;
	create_img_liste();
	 // die();
}

echo '<div id="content" class="'.($box ? ' pad' : '').'">';

if (!$cid && !$nid) echo "<h2>Bildarchiv</h2><br>";
if ($warnung) die ($warnung ."</div></body></html>");
if ($navid || $nid || $kurs) echo "<p><a href='javascript:history.back();'>" .'<i class="fa fa-arrow-circle-left"></i>' ." zur&uuml;ck</a></p>\n";
elseif ($cid) echo "<p><a href='content_foto.php?back=$back&edit=$cid'>" .'<i class="fa fa-arrow-circle-left"></i>' ." zur&uuml;ck</a></p>\n";
elseif ($newsletter) echo "<p><a href='newsletter.php'>" .'<i class="fa fa-arrow-circle-left"></i>' ." zur&uuml;ck</a></p>\n";

if (!$navid && !$cid && !$nid && !$kurs) echo "
		<p><a href=\"image.php\" title=\"zur&uuml;ck\" class=\"button\">" .'<i class="fa fa-arrow-circle-left"></i>' ." zur&uuml;ck</a> &nbsp; 	".'
';


if($navid) echo '		<a href="img_upload.php?stelle='.$stelle.'&navid='.$navid.'&edit='.$edit.'&cedit='.$cedit.'&vorlage='.$vorlage.'&gid='.$gid.'&back='.$back.'&db='.$db.'&imglnk='.$imglnk.'&art='.$art.'" data-title="" data-width="500" data-toggle="lightbox" data-gallery="remoteload"  class="button"><i class="fa fa-upload"></i> Neue Bilder hochladen</a></p>
';

else echo '		<a href="img_upload.php?gid='.$gid.'" data-title="" data-width="500" data-toggle="lightbox" data-gallery="remoteload"  class="button"><i class="fa fa-upload"></i> Neue Bilder hochladen</a></p> ';

#
# query
# query
// THUMB ? --------
$query  = "SELECT * FROM morp_cms_img_group WHERE gid=$gid";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$thumb = $row->art;


$query  = "SELECT * FROM `morp_cms_image` where gid=$gid ORDER BY imgid DESC";
$result = safe_query($query);

$t = 0;
$x = 0;

if (!$navid && !$nid && !$inr) echo '<form method="post">

	<input type="submit" class="button" name="save" value="Bilder im neuen Ordner speichern"><br><br>

		<div class="row">
';

$imgdir = "../images/userfiles/image/";

while ($row = mysqli_fetch_object($result)) {
	$id = $row->imgid;
	$nm = $row->imgname;
	$ty = $row->type;
	$tx = nl2br($row->longtext);
	$altText = $row->itext;

	$hires = $thumb == 2 ? 1 : 0;

	#	$sql = "UPDATE `morp_cms_image` SET `english_alt`='$tx' WHERE imgid=$id";
	#	safe_query($sql);

	if ($tx) $tx = "<p class=bild style=\"background-color: silver; padding: 5px;\">$tx</p>";

	if ($nm) {
		$t++;
		$x++;

		echo '<div class="col-md-4 col-lg-3 col-xs-6 rahmen">';

#  create_image($id, $nm, $ty);
	  // $th_img = '		<img src="'.($hires ? '../mthumb.php?src=images/userfiles/image/'.urlencode($nm).'&amp;w=360&amp;h=300&amp;zc=2' : $imgdir.$nm).'" class="img-responsive" alt="'.$itext.'" title="'.$itext.'">';

	  if(isin(".svg$", $nm)) {
	  		$th_img = '
	<img src="../images/userfiles/image/'.($nm).'" alt="SVG mit img Tag laden" width="250"  >
	  		';
	  }
	  else $th_img = '	<img src="'.($hires ? '../mthumb.php?src=images/userfiles/image/'.urlencode($nm).'&amp;w=360&amp;h=300&amp;zc=2' : $imgdir.$nm).'" class="img-responsive" alt="'.$itext.'" title="'.$itext.'">';


	  $file_size = round(filesize("../images/userfiles/image/$nm")  / 1024);
	  $img_size = getimagesize("../images/userfiles/image/$nm");
	  $img_w = $img_size[0];
	  $img_h = $img_size[1];

/*
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="hovereffect">
        <img class="img-responsive" src="http://placehold.it/350x200" alt="">
        <div class="overlay">
           <h2>Hover effect 1v2</h2>
           <a class="info" href="#">link here</a>
        </div>
    </div>
</div>

*/

  		#$th_img = "<img src=\"blob.php?imgid=$id\" border=0 vspace=6><p>";

		if ($incl_lnk) 		echo "<a href=\"" .$incl_lnk ."&imgid=$id&back=$back&db=$db&imglnk=$imglnk\" name=\"$id\">$th_img";
		elseif ($temp_lnk) 	echo "<a href=\"" .$temp_lnk ."&imgsav=$nm\" name=\"$id\">$th_img";
		elseif ($back) 		echo "<a href=\"content_foto.php?edit=$cid&inr=$inr&back=$back&imgid=$id\" name=\"$id\">$th_img";
		elseif ($nid) 		echo "<a href=\"news.php?edit=$nid&ngid=$ngid&gid=$id\" title=\"image w&auml;hlen\" name=\"$id\">$th_img";
		elseif ($newsletter) 		echo "<a href=\"newsletter.php?&update=$newsletter&img=$nm\" title=\"image w&auml;hlen\" name=\"$id\">$th_img";
		elseif ($id >= 1) 	echo $th_img.'
			<div class="row">
				<div class="col-xs-2">
					<a href="image_liste.php?gid='.$gid.'&txt='.$id.'"><i class="fa fa-pencil-square-o "></i> </a>
					<a href="image_liste.php?del='.$id.'&gid='.$gid.'" title="image l&ouml;schen" name="'.$id.'"><i class="fa fa-trash-o"></i></a>
					<a href="image_copy.php?copy='.$id.'&gid='.$gid.'" title="image Duplizieren" name="'.$id.'"><i class="fa fa-copy"></i></a>
				</div>
			';

		if (!$back && !$nid && !$navid && !$newsletter && !$kurs) echo '</a>
				<div class="col-xs-5">
					<a href="image_liste.php?gid='.$gid.'&txt='.$id.'">'.$nm.'</a>
					Breite: '.$img_w.' px<br/>Höhe: '.$img_h.' px<br/>'.$file_size.' kb
				</div>
				<div class="col-xs-5">
					<select name="gid_'.$id.'" class="form-control">'.morp_cms_img_group($gid) .'</select></p>
				
					<p>Alternativ Text: '.$altText.'</p>
					<p>'.$tx.'</p>
				</div>
			</div>

';
		else echo "<br>\n<b>$nm</b></a>";

		echo "</div>";
		# # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	}
}

if (!$navid && !$nid && !$inr && !$kurs) echo '</form>
</div>
';

if (!$navid && !$nid && !$inr && !$back && !$kurs) echo "<div style=\"clear:left;\"><p>&nbsp;</p><p><a href=\"img_upload.php?gid=$gid\" class=\"button\">".'<i class="fa fa-upload"></i> Neue Bilder hochladen</a></p></div>';
?>
</div>

<?php
include("footer.php");
?>