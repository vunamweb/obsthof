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

$myauth = 14;

$redaktion_in = 'in';
$news_active = ' class="active"';


function pulldownNews ($tp, $db, $wname, $wid) {
	if ($db == "termin_liste")
		$query = "SELECT * FROM $db tl, termin_abt ta WHERE ta.taid=tl.taid ORDER BY ta.abt, $wname";
	elseif ($db == "morp_cms_pdf")
		$query = "SELECT * FROM $db p,  `morp_cms_pdf_group` pg WHERE p.pgid=pg.pgid ORDER BY pg.pgname, $wname";
	else
		$query = "SELECT * FROM $db ORDER BY $wname";

	$result = safe_query($query);

	while ($row = mysqli_fetch_object($result)) {
		if ($row->$wid == $tp) $sel = "selected";
		else $sel = "";

		$nm = $row->$wname;

		if ($db == "termin_liste") 	$nm = $row->abt ." - $nm";
		elseif ($db == "morp_cms_pdf") 		$nm = $row->pgname ." - $nm";
		$pd .= "<option value=\"" .$row->$wid ."\" $sel>$nm</option>\n";
	}
	return $pd;
}

global $navarray;

if(isset($_REQUEST["sprache"])) $sprache = $_REQUEST["sprache"];
elseif (isset($_SESSION["sprache"])) $sprache = $_SESSION["sprache"];
else $sprache = 1;

$_SESSION["sprache"] = $sprache;

/*
if ($_REQUEST["sprache"]) {
	$sprache = $_REQUEST["sprache"];
	$_SESSION["sprache"] = $sprache;
}
*/
include("cms_include.inc");
// include("editor_css_edit.php");
// include("editor_edit.php");

$ngid = $_REQUEST["ngid"];
if (!$ngid) $db   = "morp_cms_news_group";
else 		$db   = "morp_cms_news";

$edit 	= $_REQUEST["edit"];
$save 	= $_REQUEST["save"];
$neu  	= $_REQUEST["neu"];
$del  	= $_REQUEST["del"];
$del_ 	= $_REQUEST["del_"];

$delpdf = $_REQUEST["delpdf"];
$delimg = $_REQUEST["delimg"];
$dellnk = $_REQUEST["dellnk"];
$vis	= $_REQUEST["vis"];
$thid  	= $_REQUEST["thid"];

$formatSelect	= $_REQUEST["formatSelect"];

if(!$formatSelect) {
	$formatSelect =	$_SESSION["formatSelect"];
}
else $_SESSION["formatSelect"] = $formatSelect;

echo "<div>";

# print_r($_REQUEST);

/////////////////////////////////////////////////////////////////////////////////////////////////////
#$sql = "ALTER TABLE  morp_cms_news ADD  `monat` INT( 11 ) NOT NULL";
#safe_query($sql);
/////////////////////////////////////////////////////////////////////////////////////////////////////

if ($del) {
	echo '<p>&nbsp;</p><p><font color=#ff0000><b>Sind Sie sich sicher, dass sie den Datensatz l&ouml;schen wollen?</b></font></p>
		<p>&nbsp; &nbsp; &nbsp; <a href="news.php?del_=' .$del .'">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="news.php">Nein</a></p></body></html>';
	die();
}

elseif ($thid) {
	$query 	 = "UPDATE `morp_cms_news` SET sichtbar=$vis WHERE nid='$thid'";
	safe_query($query);
}

elseif ($del_) {
	$query = "SELECT * FROM `morp_cms_news` WHERE nid=$del_";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
 	$ti = $row->ntitle;

	$query = "delete FROM `morp_cms_news` WHERE nid=$del_";
	$result = safe_query($query);

	// $query_ = "INSERT `morp_cms_delete` SET descr='News \"$ti\" l&ouml;schen <br>benutzer: $user_name', `query`='$query'";
	// safe_query($query_);
}

elseif ($delimg) {
	$todel = "img".$delimg;
	$query = "UPDATE `morp_cms_news` SET $todel='', edit=1 WHERE nid=$edit";
	$result = safe_query($query);
}

elseif ($delpdf) {
	$query = "UPDATE `morp_cms_news` SET pid='', edit=1 WHERE nid=$delpdf";
	$result = safe_query($query);
}

elseif ($dellnk) {
	$query = "UPDATE `morp_cms_news` SET nlink='', edit=1 WHERE nid=$dellnk";
	$result = safe_query($query);
}

elseif ($save && $ngid) {
	$ntitle 	= addslashes($_POST["ntitle"]);
	$nsubtitle 	= addslashes($_POST["nsubtitle"]);
	$nMetaTitle = addslashes($_POST["nMetaTitle"]);
	$nMetaDesc 	= addslashes($_POST["nMetaDesc"]);
	$nlink		= $_POST["nlink"];
	$pid		= $_POST["pid"];
	$ntext 		= addslashes($_POST["ntext"]);
	$format		= $_POST["format"];
	$naut 		= $_POST["nautor"];
	$nabstr		= addslashes($_POST["nabstr"]);
	if (!$style	= $_POST["style"]) $style = 1;
	$nerstellt 	= us_dat($_POST["nerstellt"]);
	$nvon 		= us_dat($_POST["nvon"]);
	$nbis 		= us_dat($_POST["nbis"]);
	$hid 		= $_POST["hid"];
	$icon 		= $_POST["icon"];
	$sichtbar	= $_POST["sichtbar"];
	$nmovie 	= $_POST["nmovie"];
	
	$kat1	= $_POST["kat1"] ? $_POST["kat1"] : 0;
	$kat2	= $_POST["kat2"] ? $_POST["kat2"] : 0;
	$kat3	= $_POST["kat3"] ? $_POST["kat3"] : 0;
	
	if ($sichtbar) $sichtbar = 1;
	else $sichtbar = 0;

	if ($nabstr == "") {
		$na = explode(" ", strip_tags($ntext));
		for($i=0; $i<25;$i++) {
			$nabstr .= $na[$i]." ";
		}
	}
	
	$datum = explode("-", $nerstellt);

	// new databse - does not accept INT empty
	if(!$hid) $hid=0;
	if(!$pid) $pid=0;
	
	$set = "ntitle='$ntitle', nvon='$nvon', nbis='$nbis', icon='$icon', nmovie='$nmovie', hid='$hid', nlink='$nlink', nsubtitle='$nsubtitle', nabstr='$nabstr', nerstellt='$nerstellt', sichtbar=$sichtbar, ngid=$ngid, pid='$pid', nautor='$naut', style='$style', nMetaTitle='$nMetaTitle', nMetaDesc='$nMetaDesc', kat1=$kat1, kat2=$kat2, kat3=$kat3, jahr=".$datum[0].", monat=".$datum[1];
	# $set = "ntitle='$ntitle', nlink='$nlink', nsubtitle='$nsubtitle', nabstr='$nabstr', nerstellt='$nerstellt', aktuell=$aktuell, ngid=$ngid, pid='$pid', nautor='$naut', style='$style'";
	if ($format <= 3) $set .= ", ntext='$ntext'";

	if (!$neu) 	$query = "UPDATE `morp_cms_news` SET $set, edit=1 WHERE `nid`=$edit";
	else  		$query = "INSERT `morp_cms_news` SET $set";

	#echo "<br><br>";
	#echo $query;
	$result = safe_query($query);

	if (!$neu) {
		protokoll($uid, "news", $edit, "edit");
//		unset($edit);
	}
	else {
		$c = mysqli_insert_id($mylink);
		protokoll($uid, "news", $c, "neu");
		$edit = $c;
		unset($neu);
	}

	$scriptname = basename(__FILE__);

	if($_POST["back"]) {
?>
	<script>
		location.href='<?php echo $scriptname; ?>?ngid=<?php echo $ngid; ?>';
	</script>
<?php
	}

}

elseif ($save) {
	$ngname = $_POST["ngname"];
	$format	= $_POST["format"] ? $_POST["format"] : 1;
	$nlang	= $_POST["nlang"] ? $_POST["nlang"] : 1;
	$target	= $_POST["target"] ? $_POST["target"] : 0;
	$confirm= isset($_POST["confirm"]) ? $_POST["confirm"] : 0;

	$set 	= "ngname='$ngname', format='$format', nlang='$nlang', targetID='$target', confirm='$confirm'";

	if (!$neu) $query = "UPDATE `morp_cms_news_group` SET $set, edit=1 WHERE `ngid`=$edit";
	else {
		$query = "INSERT `morp_cms_news_group` SET $set";
		unset($neu);
	}
	
	// echo $query;
	
	$result = safe_query($query);
	unset($edit);
}

# # # News wird erstellt oder editiert, nachdem Gruppe ausgewaehlt wurde
# # # es werden unterschiedliche Zusammenstellungen unterstuetzt. Bsp.: Mit Image (bis zu 4), interne/externe Links, Abstract
# # # UND NEU mit Pflege ueber das Content_Edit Modul (z.Zt. Format = 3)
if (($edit || $neu) && $ngid) {

	// SELECTIZE * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
// 		$sql = "SELECT tagID FROM `morp_tags_list` WHERE art='news' AND targetID=$edit";
// 		$rs = safe_query($sql);
// 		$tagList = array();
// 
// 		$setOptions .= '
// 					 var control = $select[0].selectize;
// 					 control.addOption({
// 					 	value: data,
// 					 	title: value
// 					 });
// ';
// 
// 		while ($rw = mysqli_fetch_object($rs)) {
// 			$tagList[]=$rw->tagID;
// 		}
// 
// 		$tagCount = count($tagList);
// 		$tagList = implode(",", $tagList);

		// $tagSelect = '<tr><td># Hashtags</td><td><select multiple id="tags'.$edit.'" name="tags[]" class="tags form-control" ref="'.$edit.'" placeholder="Tags / Kategorien"></select></td></tr>';

		$sql = "SELECT * FROM morp_tags WHERE 1 ORDER BY tag";
		$res = safe_query($sql);
		$tag_list = '';
		while ($row = mysqli_fetch_object($res)) {
			$tag_list .= '{value:'.$row->tagID.' , title: "'.$row->tag_long.'" },
		';
		}

		$jsList = '
<script>

	function setOptions(data, value) {
		'.$setOptions.'
	}

	var options = [
	 {id:\'\' , title: \'All\' },
'.$tag_list.'
	];

	var $select = $(\'#tags'.$edit.'\').selectize({
		plugins: [\'remove_button\'],
	    labelField: \'title\',
	    searchField: \'title\',
	    sortField: \'title\',
		options: options,
	    create:  function(value, callback) {
			 console.log(value);
		    request = $.ajax({
		        url: "UpdateTag.php",
		        type: "post",
		        data: "add=1&table=morp_cms_news&art=news&data="+value+"&id='.$edit.'&feld=nid",
		        success: function(data) {
					 console.log(data);
					 callback({ value: data, title: value });
					 setOptions(data, value);

				}
		    });
	    },
	    onChange: function(value) {
	        if (!value.length) return;
		    request = $.ajax({
		        url: "UpdateTag.php",
		        type: "post",
		        data: "table=morp_cms_news&art=news&data="+value+"&id='.$edit.'&feld=nid",
		        success: function(data) {
					 console.log(data);
				}
		    });
	    }
	});


	'.( $tagCount > 0 ? ' $select[0].selectize.setValue(['.$tagList.']);' : '').'



</script>
';

	// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *


	echo "<a href='news.php?ngid=$ngid'><i class=\"fa fa-arrow-circle-left\"></i> zur&uuml;ck</a><p>";

	if (!$neu) {
		$query = "SELECT * FROM `morp_cms_news` n, `morp_cms_news_group` ng WHERE n.nid=$edit AND n.ngid=ng.ngid";
		$result = safe_query($query);
		$row = mysqli_fetch_object($result);
	}

	else {
		$query = "SELECT * FROM `morp_cms_news_group` WHERE ngid=$ngid";
		$result = safe_query($query);
		$row = mysqli_fetch_object($result);
	}

	echo '<form action="news.php?edit='.$edit.'&save=1" method="post"  class="form-inline">
			<input type="hidden" name="edit" value="' .$edit .'">
			<input type="hidden" name="ngid" value="' .$ngid .'">
			<input type="hidden" name="save" value="1">';

	if ($neu) {
		echo '<input type="hidden" name="neu" value="1">';
		$nerstellt = date("d.m.Y");
		// $nbis = date("d.m.Y");
		// $nvon = date("d.m.Y");
	}
	else {
		$nerstellt = euro_dat($row->nerstellt);
		$nbis = euro_dat($row->nbis);
		$nvon = euro_dat($row->nvon);
	}

	if ($row->sichtbar == "1") $sichtbar = "checked";
	else unset($sichtbar);
	// echo $sichtbar;

	$img1 	= $row->img1;
	$img2 	= $row->img2;
	$img3 	= $row->img3;
	$img4 	= $row->img4;

	# Darstellungs Format
	$format	= $row->format;
	echo '<input type="hidden" name=format value="'.$format.'">';

	$style 		= $row->style;
	$checked 	= "style_".$style;
	$$checked 	= 'checked';

	$imgid 	= $row->imgid;
	$pid 	= $row->pid;
	$nlink 	= $row->nlink;
	$icon 	= $row->icon;

	if (!$nlink) $bittew = "internen link w&auml;hlen";
	else {
		$bittew = "&nbsp;&raquo; internen link &auml;ndern";
		$dellink = ' &nbsp; &nbsp; &nbsp; <a href="news.php?dellnk='.$edit.'&edit='.$edit.'&ngid='.$ngid.'"><i class="fa fa-trash-o"></i>  Link l&ouml;schen</a>';
	}

	if ($link = $_GET["ebene"]) {
		include("../nogo/navarray_".$morpheus["lan_arr"][$row->nlang].".php");
		$nlink = $_GET["cid"];
		$save_warn = 1;
	}
	elseif ($_GET["gid"]) {
		$imgid = $_GET["gid"];
		$save_warn = 1;
	}
	elseif ($_GET["pid"]) {
		$pid = $_GET["pid"];
		$save_warn = 1;
	}

	echo '<table class="table news-size">
		<tr>
			<td colspan="2"><font>News Format:
				<strong>'.$morpheus["news_formate"][$format].'</strong></font>
				 &nbsp; &nbsp; <img src="images/'.$morpheus["lan_arr"][$sprache].'.gif" alt="" width="13" height="9" border="0"> &nbsp; &nbsp;
				 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				sichtbar:	<input type="checkbox" name="sichtbar" value="1" '.$sichtbar.'>
				<span style="width:100px;display:inline-block;"></span>
<!--				<span style="margin-right:20px; display:inline:block;">Mitwirkung:	<input type="checkbox" name="kat1" value="1" '.($row->kat1 ? ' checked' : '').'></span>
				<span>Zeitleiste:	<input type="checkbox" name="kat2" value="1" '.($row->kat2 ? ' checked' : '').'></span>-->
				
				</td>';
	echo '
		</tr>
		<tr>
			<td>Titel</td>
			<td><input type="text"  class="form-control" name="ntitle" value="' .htmlspecialchars($row->ntitle) .'"></td>
		</tr>
	';

	if ($format == 4) 	{

		$pdf_pd = pulldownNews ($icon, "morp_fa", "beschreibung", "fa");
		echo '
		<tr>
			<td>ICON</td>
			<td><select name="icon" class="form-control">'.$pdf_pd.'</select></td>
		</tr>
		';
	}

#	if ($format <= 2 || $format == 6) 	{
	if ($format <= 2) 	{
		echo '<tr>
			<td>';

		if ($format == 3) echo 'Termin';
		elseif ($format == 6) echo 'Subheadline';
		else echo '2. &uuml;berschrift (kein Pfilchtfeld)';

		echo '</td>
			<td><input type="text" name="nsubtitle"  class="form-control" value="' .htmlspecialchars($row->nsubtitle) .'" ></td>
		</tr>
		';
	}

	if ($format == 1 || $format == 3) 	echo '<tr>
			<td valign=top>'. ($format == 3 ? 'Abstract / Beschreibung' : 'Newstext') .'</td>
			<td><textarea row="10" style="height:140px" class="summernote form-control" name="ntext">' .$row->ntext .'</textarea></td>
		</tr>
		'. ($format == 3 ? '' : '<tr>
			<td valign=top></td>
			<td>&lt;b&gt;text&lt;/b&gt;  <strong>text</strong><br>
			&lt;i&gt;text&lt;/i&gt;  <em>text</em><br>
			&lt;u&gt;text&lt;/u&gt;  <u>text</u><br></td>
		</tr>
		');

	if ($format == 2 || $format == 1 ) echo '	<tr>
			<td valign=top>'. ($format == 3 ? 'Kurztext Job' : 'Abstract/Kurzfassung<br>
			<font size="-2"> (wenn Sie dieses Feld freilassen,<br>wird das Abstract automatisch erstellt)') .'</td>
			<td>
				<textarea  class="form-control" name="nabstr">' .($row->nabstr) .'</textarea>
				<!--<p>Startseite:</p>
				<textarea  class="form-control" name="nmovie">' .($row->nmovie) .'</textarea>-->
			</td>
		</tr>
<!-- 		<tr>
			<td>'.$autor_bez.'</td>
			<td><input type="text" name="nautor"  class="form-control" value="' .$row->nautor .'" ></td>
		</tr> -->
		';


		echo $tagSelect;

// IRRELEVANT FUER INTRANET
/*

	$nMetaTitle = $row->nMetaTitle;
	$nMetaDesc 	= $row->nMetaDesc;

		echo '
		<tr>
			<td>Meta Title</td>
			<td><input type="text" name="nMetaTitle"  class="form-control" value="' .$nMetaTitle .'"></td>
		</tr>
		<tr>
			<td>Meta Description</td>
			<td><input type="text" name="nMetaDesc"  class="form-control" value="' .$nMetaDesc .'"></td>
		</tr>
';
*/

	if ($format == 5 || $format == 6)	echo '<tr>
			<td valign=top></td>
			<td><p><a href="content_edit.php?db=news&ngid='.$ngid.'&edit='.$edit.'"><i class="fa small fa-plus"></i> editiere <strong>' .$row->ntitle .'</strong> Text</a></p></td>
		</tr>';

	if (!$neu && $format != 5) echo '<tr>
			<td>Link</td>
			<td><input type="text" name="nlink"  class="form-control" value="' .$nlink .'"> &nbsp; <a href="link.php?nid='.$edit.'&ngid='.$ngid.'">'  .$bittew .'</a>'.$dellink .'
			<p>Internen Link w&auml;hlen oder externen Link einsetzen</p>
			</td>
		</tr>
		';

	# im moment sind diese features fast alle ausgeblendet. koennen jederzeit aktiviert werden.
	# z.b. von bis fuer zeitliche steuerung der news. oder button, zum aktivieren der news auf
	# speziellen seiten > z.b. hot-news (kombi aus versch. news-gruppen) etc.


	# if ($format <= 2 || $format == 5 || $format == 6) 	{
	if ($format) 	{
		echo '
		<tr>
			<td>Erstellt</td>
			<td><input type="text" name="nerstellt"  class="form-control" value="' .$nerstellt .'"></td>
		</tr>
		<tr>
			<td>Online von</td>
			<td><input type="text" name="nvon"  class="form-control" value="' .$nvon .'"></td>
		</tr>
		<tr>
			<td>Online bis</td>
			<td><input type="text" name="nbis"  class="form-control" value="' .$nbis .'"></td>
		</tr>
		<tr>
			<td valign="top"><p>NEWS GRUPPE &nbsp; (Vorsicht!<br>es <u>k&ouml;nnen</u> Daten verloren gehen)</p></td>
			<td valign="top">';

		$pdf_pd = pulldownNews ($ngid, "morp_cms_news_group", "ngname", "ngid");
		echo "<p><select name=\"ngid\" class=\"form-control\">$pdf_pd</select><br>&nbsp;</p>";
	}
	else echo "<input type=\"Hidden\" name=\"ngid\" value=\"$ngid\">";




	# # # fotos
	# # # image einfuegen. images werden in einem folder in original groesse eingebunden
	if ($save_warn) echo '<p style="color:#FF0000;"><b>Bitte Speichern, sonst gehen die &auml;nderungen verloren!</b></p>';

	echo '<input type="submit"  class="button" value="speichern" name="speichern"></td>
		</tr>
		<tr>
			<td colspan="2"><table>';

	# # # # # # # # # # # # # # # # # # # # # # fotos anzeigen und link zum uploaden - keine veraenderung des foto. foto muss dem endformat entsprechen
	if ($edit && $format != 1 && $format != 2 && $format != 4 && $format != 6) 	{
		if ($format == 4) $n = 1;
		else $n = 1;

		$arr = array("", "Foto 1", "Foto 2", "Foto 3", "Foto 4");
		for ($i=1; $i<=$n; $i++) {
			$thimage = "img".$i;
			echo '<tr><td width="160">'.$arr[$i].'</td><td><input type=hidden name=img'.$i.' value="' .$$thimage .'"><a href="image_folder_upload.php?nid='.$edit.'&ngid='.$ngid.'&imgid='.$thimage.'&news=1">';

			# foto aus db loeschen. es bleibt aber auf der platte.
			if ($$thimage) echo '<img src="../images/news/'.$$thimage.'"></a> &nbsp; &nbsp; <a href="news.php?delimg='.$i.'&edit='.$edit.'&ngid='.$ngid.'&news=1" class="btn btn-danger"><i class="fa fa-trash-o"></i> </a>';
			else echo '<b>Foto</b>: bitte w&auml;hlen</a>';

			echo '</td></tr>';
		}
	}

	# # in dieser variante wird ein thumbnail und ein bild in einer vorgegebenen groesse erstellt.
	# # vielleicht sollte diese variante demnaechst ausschliesslich verwendet werden, damit kunden
	# # die bilder nciht vorbereiten muessen. die bild-groessen werden ueber die config.inc gesteuert
	# # $morpheus["img_size_news"]	;  $morpheus["img_size_news_tn"]
	elseif ($edit && ($format == 40 || $format == 1)) 	{
		$arr = array("", "Foto", "Foto 2", "Foto 3", "Foto 4");

		for ($i=1; $i<=1; $i++) {
			$thimage = "img".$i;

			echo '<tr><td width="160">'.$arr[$i].'</td><td><input type=hidden name=img'.$i.' value="' .$$thimage .'" ><a href="image_folder_upload.php?nid='.$edit.'&ngid='.$ngid.'&tn='.$morpheus["img_size_news_tn"].'&full='.$morpheus["img_size_news"].'&imgid='.$thimage.'&news=1">';

			# foto aus db loeschen. es bleibt aber auf der platte.
			if ($$thimage) echo '<img src="../mthumb.php?w=200&amp;src=images/news/'.urlencode($$thimage).'"></a> &nbsp; &nbsp; <a href="news.php?delimg='.$i.'&edit='.$edit.'&ngid='.$ngid.'&news=1" class="btn btn-danger"><i class="fa fa-trash-o"></i> </a>';
			else echo '<b>Foto</b>: bitte w&auml;hlen</a>';

			echo '</td></tr>';
		}
	}

	# http://localhost/peakom/morpheus/image_folder_upload.php?nid=1&ngid=7&tn=120&full=450&imgid=img1&news=1

	elseif ($edit && ($format == 6)) 	{
		$arr = array("", "Foto klein Thumb - Startseite - nur Buch", "Foto gross Mindflash - nur Buch");

		for ($i=1; $i<=2; $i++) {
			$thimage = "img".$i;

			echo '<tr><td width="160">'.$arr[$i].'</td><td><input type=hidden name=img'.$i.' value="' .$$thimage .'" ><a href="image_folder_upload.php?nid='.$edit.'&ngid='.$ngid.'&tn='.$morpheus["img_size_news_tn"].'&full='.$morpheus["img_size_news"].'&imgid='.$thimage.'&news=1">';

			# foto aus db loeschen. es bleibt aber auf der platte.
			if ($$thimage) echo '<img src="../images/news/'.$$thimage.'"></a> &nbsp; &nbsp; <a href="news.php?delimg='.$i.'&edit='.$edit.'&ngid='.$ngid.'&news=1"  class="btn btn-danger"><i class="fa fa-trash-o"></i> </a>';
			else echo '<b>Foto</b>: bitte w&auml;hlen</a>';

			echo '<p>&nbsp;</p></td></tr>';
		}
	}

	 # # # # # # # # # # # # # # # # # # # # # # fotos
	 # # # # # # # # # # # # # # # # # # # # # # fotos
	echo '</table>';

	echo '
	<input type="hidden" id="back" name="back" value="">
		<button type="submit" id="savebtn" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern / aktualisieren</button>
		<button type="submit" id="savebtn2" value="hier" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; '.$um_wen_gehts.' speichern und zurück</button>

	</td>
		</tr>';
###########################################################

	# # # # pdf download einfuegen
	if (!$neu && $format <= 2) echo '<tr bgcolor="#E2E2E2">
			<td>Download-Dok/PDF</td><td><input type=hidden name=pid value="' .$pid .'" ><a href="pdf_select.php?nid='.$edit.'&ngid='.$ngid.'" title=\"Neues Dokument einsetzen\">';

	if ($pid > 0 && !$neu && $format <=2 ) {
		$pnm = pdfname($pid);
		echo 'neues Dokument/PDF w&auml;hlen</a><p><a href="news.php?delpdf='.$edit.'&edit='.$edit.'&ngid='.$ngid.'"  class=\"button\"><i class="fa fa-trash-o"></i> <b>'.$pnm.'</b> l&ouml;schen</a></p>
		<p><a href="../pdf/'.$pnm.'" target="_blank"><i class="fa fa-file-o"></i> Dokument ansehen</a></p>';
	}
	elseif (!$neu && $format != 6) echo '<b>Download-Dok/PDF</b>: bitte w&auml;hlen</a>';


	echo '</td>
		</tr>
		</table></form>';
}

# # # # NEWS GRUPPE erstellen / editieren
elseif ($edit || $neu) {
	echo "<a href='news.php'><i class=\"fa fa-arrow-circle-left\"></i> zur&uuml;ck</a><p>";

	if (!$neu) {
		if(isset($_GET["cid"])) {
			$target = $_GET["cid"];
			$sql = "UPDATE `morp_cms_news_group` SET targetID='$target', edit=1 WHERE ngid=$edit";
			safe_query($sql);
		}

		$query 	= "SELECT * FROM `morp_cms_news_group` WHERE ngid=$edit";
		$result = safe_query($query);
		$row 	= mysqli_fetch_object($result);
	}

	echo '<form method="post" class="form-inline"><input type="hidden" name="edit" value=' .$edit .'><input type="hidden" name=save value=1>';
	if ($neu) {
		echo '<input type="hidden" name=neu value=1>';
	}

	echo '<table class="autocol p20">
		<tr>
			<td>News-Gruppe Name</td>
			<td><input type=text name=ngname value="' .$row->ngname .'"></td>
		</tr>
		<tr>
			<td>Text Format<p>&nbsp;</p></td>
			<td>';

	foreach ($morpheus["news_formate"] as $key=>$val) {
		if ($row->format == $key) $chk = " checked";
		else unset($chk);
		echo '<input type="radio" name="format" value="'.$key.'"'.$chk.'> &nbsp;'.$val.'&nbsp; &nbsp; &nbsp;';
	}

	$confirm = $row->confirm;

	echo '<p>&nbsp;</p></td>
		</tr>
		<tr>
			<td>Dokumente dieser Gruppe benötigen Lesebstätigung</td>
			<td><input type="checkbox" value="1" name="confirm"'.($confirm ? ' checked' : '').' /></td>
		</tr>
		<tr>
			<td>Sprache</td>
			<td>';

	$lan 	= $row->nlang;
	foreach ($morpheus["lan_arr"] as $key=>$val) {
		if ($key == $lan) 	$chk = " checked";
		else				unset($chk);
		echo '<input type="radio" name="nlang" value="'.$key.'"'.$chk.'> '.$morpheus["lan_nm_arr"][$val].' &nbsp; &nbsp; ';
	}

	echo '</td>
		</tr>
		<tr>
			<td>Zielseiten ID für Suche</td>
			<td><input type="text" class="" value="'.$row->targetID.'" name="target">
			<a class="btn btn-info" href="link.php?ngid='.$edit.'&ng=1">wählen</a>
			</td>
		</tr>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit"  class="button" value="speichern" name="speichern"></td>
		</tr>
		</table>';
}

# # # Liste der News nach Auswahl einer Gruppe
elseif ($ngid) {
	$query 	= "SELECT format FROM `morp_cms_news_group` WHERE ngid=$ngid";
	$result = safe_query($query);
	$row 	= mysqli_fetch_object($result);
	$format = $row->format;

	echo "\n<p class=text><b>News verwalten</b></p>
		<p><a href=\"news.php\"><i class=\"fa fa-arrow-circle-left\"></i> zur&uuml;ck</a>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='news.php?neu=1&ngid=$ngid' class=\"button\"><i class=\"fa small fa-plus\"></i> neue News erstellen</a>
		<table class=\"autocol p20\">
			<tr bgcolor=$bgcolor>
				<td width=200><b>Titel</b></td>
				<td width=280><b>Text</b></td>
				<td width=60><b>Erstellt</b></td>
				<td></td>
				<td width=100><b>Online sichtbar</b></td>
				<td width=50></td>
			</tr>
	";

	$query 	= "SELECT * FROM `morp_cms_news` WHERE ngid=$ngid ORDER BY nerstellt desc, nid desc";
	$result = safe_query($query);

	$bgcolor = "#EFECEC";  // tabellen-hintergrundfarbe soll wechseln. erster farbwert wird gesetzt

	###########################################################
	// formular wird jetzt zusammengestellt
	while ($row = mysqli_fetch_object($result))	{
		echo "<tr bgcolor=$bgcolor>
			<td><p>" .$row->ntitle ."</p></td>
			<td>" .substr(strip_tags($row->ntext), 0, 80) ." ...</p></td>
			<td><p>" .euro_dat($row->nerstellt) ."</p></td>
			<td align=\"center\">" .($row->sichtbar ? '<a href="?vis=0&thid='.$row->nid.'&ngid='.$row->ngid.'"><i class="fa  fa-eye"></i></a>' : '<a href="?vis=1&thid='.$row->nid.'&ngid='.$row->ngid.'"><i class="gray fa  fa-eye-slash"></i></a>') ."</td>
			<td valign=top><p>von: " .euro_dat($row->nvon) ."<br>bis: " .euro_dat($row->nbis) ."</p></td>
			<td valign=top align=center><a href='news.php?edit=" .$row->nid ."&ngid=".$row->ngid."' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i></a> &nbsp; &nbsp; &nbsp; <a href='news.php?del=" .$row->nid ."&ngid=".$row->ngid."'><i class=\"fa  fa-trash-o\"></i></a></td>
		</tr>
		";

		if ($bgcolor == '#EFECEC') $bgcolor = "#FFFFFF";
		else $bgcolor = '#EFECEC';
	}
	// formular ist fertig
	###########################################################

	echo "</table><br>
		<a href='news.php?neu=1&ngid=$ngid' class=\"button\"><i class=\"fa small fa-plus\"></i> neue news erstellen</a>";
}

# # # NEWS Start - Liste der News Gruppen
else {
	$where = " WHERE 1 ";

	if($formatSelect) {
		$where = " WHERE ( ";
		$arr = explode(",", $formatSelect);
		$fs = '';
		foreach($arr as $val) {
			$fs .= $fs ? ' OR ' : '';
			$fs .= " format=$val ";
		}
		$where .= $fs . " ) ";
	}

	$query 	= "SELECT * FROM $db $where ORDER BY ngname";
	$result = safe_query($query);
	$ct		= 1;

	echo "<p><b>News-Gruppen</b></p>
		<p>&nbsp;</p>
		<table class=\"autocol p20\">\n";

	while ($row = mysqli_fetch_object($result))	{
		$nm = $row->ngname;
		$id = $row->ngid;
		$nl = $row->nlang;
		$confirm = $row->confirm;


		echo '<tr bgcolor="'.$morpheus["col"][$ct].'">
		<td><p><a href="news.php?edit='.$id.'">'.$nm.'</a></p></td>
		<td align="center"><p><a href="news.php?edit='.$id.'" class="btn btn-info">'.($confirm ? '<i class="fa fa-check"></i> Lesebstätigung erforderlich' : '').'</a></p></td>
		<td align="center"><p><a href="news.php?edit='.$id.'" class="btn btn-info"><i class="fa fa-cogs"></i> </a></p></td>
		<td align="center"><p><a href="news.php?ngid='.$id.'&sprache='.$nl.'" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i> </a></p></td>
	</tr>';

		if ($ct == 0) $ct = 1;		//farbendefenition
		else $ct = 0;
	}

	echo "</table><p>&nbsp;</p><p><a href=\"news.php?neu=1\" class=\"button\"><i class=\"fa small fa-plus\"></i> NEUE NEWS GRUPPE</a></p>";
}

?>

</div>

<?php
include("footer.php");

echo $jsList;

?>

<script>
  $("#savebtn2, #savebtn3").on("click", function() {
	  	$("#back").val(1);
  });
</script>