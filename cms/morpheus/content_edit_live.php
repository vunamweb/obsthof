<?php
# print_r($_SESSION);
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                             #
# start 12/2003                                     #
# edit 27.11.2006                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #
session_start();
$box=1;
$live=1;
$myauth = 10;
$redaktion_in = 'in';
$nav_active = ' class="active"';

//error_reporting(0);
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # sprache ermitteln
# # # # # # # # # # # # # # # # # # # # # # # # # # #
global $navarray, $sprache, $morpheus;
# print_r($_REQUEST);


# # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET["muuri"])) {
	$muuri = $_GET["muuri"] == "off" ? 0 : 1;
	$_SESSION["muuri"] = $muuri;
}
$muuri = $_SESSION["muuri"];
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # #


if (!isset($_SESSION["sprache"])) $_SESSION["sprache"] = 1;
$sprache = $_SESSION["sprache"];

$comeFrom = isset($_SESSION["comeFrom"]) ? $_SESSION["comeFrom"] : 'template.php';


$img_pfad = "../images/userfiles/image/";

# # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # _sprache
# # # # # # # # # # # # # # # # # # # # # # # # # # #

if (isset($_REQUEST["back"])) {
	$back = $_REQUEST["back"];
	$_SESSION["back"] = $back;
}
if (isset($_SESSION["back"])) 	$back = $_SESSION["back"];
if (isset($_SESSION["templ"])) 	$templ = $_SESSION["templ"];

# # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # _sprache
# # # # # # # # # # # # # # # # # # # # # # # # # # #

# # # # # # # # # # # # # # # # # # # # # # # # # # #
# formular pull down # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # #
function pulldownContent ($tp, $db, $wname, $wid, $mf=0) {
	if ($db == "termin_liste")
		$query = "SELECT * FROM $db tl, termin_abt ta WHERE ta.taid=tl.taid ORDER BY ta.abt, $wname";
	elseif ($db == "morp_cms_pdf" && $mf)
		$query = "SELECT * FROM $db p, `morp_cms_pdf_group` pg WHERE p.pgid=pg.pgid AND pg.pgid=$mf ORDER BY pg.pgname, $wname";
	elseif ($mf == 2)
		$query = "SELECT * FROM $db p, `morp_cms_pdf_group` g WHERE p.pgid=g.pgid AND p.pgid=$mf ORDER BY pname";
	elseif ($db == "morp_cms_pdf")
		$query = "SELECT * FROM $db p, `morp_cms_pdf_group` pg WHERE p.pgid=pg.pgid AND pgart=1 ORDER BY pg.pgname, $wname";
	elseif ($db == "morp_cms_product")
		$query = "SELECT * FROM $db p, productkat pk, productimg pi, productwg wg WHERE wg.prokid=pk.prokid AND p.proid=pi.proid AND p.proid=wg.proid GROUP by p.proid ORDER BY pk.prokbezde, $wname";
	elseif ($mf)
		$query = "SELECT * FROM $db WHERE 1 ORDER BY ngid, nerstellt DESC, $wname";
	elseif ($db == "morp_cms_nav")
		$query = "SELECT * FROM $db WHERE 1 ORDER BY parent, $wname";
	else
		$query = "SELECT * FROM $db ORDER BY $wname";

	$result = safe_query($query);

	if ($db == "morp_shop_wg") $pd .= '<option value="0">alle</option>';

	while ($row = mysqli_fetch_object($result)) {
		if ($row->$wid == $tp) $sel = "selected";
		else $sel = "";

		$nm = $row->$wname;

		if ($db == "termin_liste") 	$nm = $row->abt ." - $nm";
		elseif ($db == "morp_cms_pdf") 		$nm = $row->pgname ." - $nm";
		elseif ($db == "morp_referenzen")		$nm = $row->kunde ." - ".$row->name;
		elseif ($db == "morp_mitarbeiter")	$nm = "$nm, ".$row->vorname;

		elseif ($db == "morp_cms_nav")	{
			$sql = "SELECT name FROM `morp_cms_nav` WHERE navid=".$row->parent;
			$res = safe_query($sql);
			$rw = mysqli_fetch_object($res);
			$nm = $rw->name ." - $nm";
		}

		$pd .= "<option value=\"" .$row->$wid ."\" $sel > $nm</option>\n";
	}
	return $pd;
}

function vorlage ($id) {
	$query = "SELECT vorl_name, cid FROM `morp_cms_content` WHERE vorlage=1 ORDER BY vorl_name";
	$result = safe_query($query);
	$pd = '<option value="0">bitte wählen</option>';

	while ($row = mysqli_fetch_object($result)) {
		if ($row->cid == $id) $sel = "selected";
		else $sel = "";

		$nm = $row->vorl_name;
		$pd .= "<option value=\"" .$row->cid ."\" $sel>$nm</option>\n";
	}
	return $pd;
}

function get_pdf($id) {
	// dbconnect_live();
	$query = "SELECT * FROM `morp_cms_pdf` WHERE pid=$id";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
	return $row->pname;
	dbconnect();
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_REQUEST["fckedit"])) $fckedit= $_REQUEST["fckedit"]; else $fckedit = '';
if ($fckedit) $box = 1;

include("cms_include.inc");
// include("editor_css_edit.php");
// include("editor_edit.php");
include("_tinymce.php");
include("../nogo/navarray_".$morpheus["lan_arr"][$sprache].".php");

# # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

echo '
<link rel="stylesheet" href="css/style_light_edit.css" type="text/css">
<form name="check">
	<input type="hidden" name="check" value="">
</form>

<form method=post action="content_edit_live.php" name="content_edit"  class="form-inline" id="content_edit">

<div>

';

// print_r($_POST);

# print_r($_REQUEST);

$hoehe 		= $_SESSION["hoehe"];
if (!$hoehe) $hoehe = 4;

if ($_GET["navid"]) {
	$navid = $_GET["navid"];
	$_SESSION["navid"] = $navid;
}
else $navid = $_SESSION["navid"];

//print_r($_SESSION);

$cont 	= "content";
$db 	= $_REQUEST["db"];	# als $table definieren
$del	= $_GET["del"];
$edit	= $_REQUEST["edit"];
$change	= $_REQUEST["change"];
$split	= $_REQUEST["split"];
$save	= $_REQUEST["save"];
$sort	= $_REQUEST["sort"];
$stelle = $_REQUEST["stelle"];
$dupl	= $_REQUEST["duplizieren"];
$brick  = $_REQUEST["brickname"];
$vorlage= $_REQUEST["vorlage"];


if ($_POST["hoehe"]) {
	$hoehe 				= $_POST["hoehe"];
	$save				=1;
	$_SESSION["hoehe"] 	= $hoehe;
}

$warn = 0;
# # # # # # auswertung tabelle
$tab	= $_REQUEST["tab"];

# # # # # # linkwahl
$link	= $_REQUEST["link"];
$pos	= $_REQUEST["pos"];
$cid 	= $_REQUEST["cid"];
$p2 	= $_REQUEST["p2"];
$p3 	= $_REQUEST["p3"];
$p4 	= $_REQUEST["p4"];
$p5 	= $_REQUEST["p5"];
# # # # # # # # # # # # # # #

if ($db == "newsletter" || $_GET["target"] == "newsletter") {
	$getid 	 = "nlid";
	$link  	 = "newsletter.php";
	$cont 	 = "text";
	$db 	 = "newsletter";
}
elseif ($db == "template") {
	$getid 	 = "tid";
	$link  	 = "template.php";
}
elseif (!$db || $db == "morp_cms_content") {
	$db		 = "morp_cms_content";
	$getid 	 = "cid";
	if ($vorlage || $templ || $comeFrom == 'template.php') 	$link = "template.php";
	else 			$link = "content.php?edit=$navid";
}
elseif ($db == "morp_cms_news") {
	$getid 	 = "nid";
	$link  	 = "news.php?edit=$edit&navid=$navid&ngid=".$_REQUEST["ngid"];
	$cont 	 = "ntext";
	$ngid	 = $_REQUEST["ngid"];
}
elseif ($db == "productkat") {
	$getid 	 = "prokid";
	$cont 	 = "c_". ($sprache == 1 ? "de" : "en");
	$link  	 = "shop_wg.php";
}

### diese variable ist gefuellt, wenn der link von image_list kommt, also ein image eingefuegt wird
$imgid 	= $_REQUEST["imgid"];
$imglnk = $_REQUEST["imglnk"];
$pid 	= $_REQUEST["pid"];
$art	= $_REQUEST["art"];

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # !!!!!!!!!!! navigationsname einsetzen
if ($db == "morp_cms_content") {
	$query = "SELECT * FROM `morp_cms_nav` WHERE navid='$navid'";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
	$ort = $row->name;
}
# # # # # # # !!!!!!!!!!! # # # # # # # !!!!!!!!!!!
# # # # # # # !!!!!!!!!!! immer ueberpruefen. kann von kunde zu kunde variieren
$query 	= "SELECT tid FROM `morp_cms_content` WHERE cid='$edit'";
$result = safe_query($query);
$row 	= mysqli_fetch_object($result);
$tid 	= $row->tid;
# # # # # # # !!!!!!!!!!! # # # # # # # !!!!!!!!!!! # # # # # # # !!!!!!!!!!!
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

if ($link) {
	if ($_GET["galery"]) $setlink = "galery=$cid";
	else {
		$setlink = "$cid";
/*
		$setlink = "cid=$cid&p2=$p2";
		if ($p3) $setlink .= "&p3=$p3";
		if ($p4) $setlink .= "&p4=$p4";
		if ($p5) $setlink .= "&p5=$p5";
		#$edit = $link;
*/
	}
}

if ($cid && ($pos == "all" || $pos == "de") && $db == "morp_cms_content") {
	# echo "import!!!! $cid $edit";
	$query 	= "SELECT * FROM $db WHERE $getid='$cid'";
	$result = safe_query($query);
	$row 	= mysqli_fetch_object($result);

	// if ($pos == "all") 	$lang_wahl = "c_".$lang;
	// else				$lang_wahl = "c_de";

	$lang_wahl = "content";

	$text 	= addslashes($row->$lang_wahl);
	if ($pos == "all") 	$query = "UPDATE $db SET $lang_wahl='$text', edit=1 WHERE $getid='$edit'";
	else				$query = "UPDATE $db SET c_en='$text', edit=1 WHERE $getid='$edit'";
	$result = safe_query($query);
}

elseif ($cid && $pos == "all" && $db == "newsletter") {
	# echo "import!!!! $cid $edit";
	$query 	= "SELECT * FROM `morp_cms_content` WHERE cid='$cid'";
	$result = safe_query($query);
	$row 	= mysqli_fetch_object($result);
	$text 	= addslashes($row->content);
	$query 	= "UPDATE `morp_cms_newsletter` set text='$text' WHERE $getid='$edit'";
	$result = safe_query($query);
}

if ($sort) {
	$query 	= "SELECT * FROM $db WHERE $getid='$edit'";
	$result = safe_query($query);
	$row 	= mysqli_fetch_object($result);
	$text 	= $row->$cont;
	$text 	= explode("##", $text);

	$bA = $text[($stelle-1)]; 	// brick A an position stelle-1 auslesen - weil array bei 0 beginnt
	$bB = $text[($sort-1)]; 	// brick B an position sort-1 auslesen
	$text[($stelle-1)] = $bB;	// brick B an neuer pos einsetzen
	$text[($sort-1)] = $bA;		// brick A an neuer pos einsetzen

	if ($val) $text = implode("##", $text);

	$set 	= "set $cont='".addslashes($text)."', edit=1 ";
	$query 	= "update $db " .$set ."where $getid=$edit";
	$result = safe_query($query);
}

elseif ($change) {
	$query 	= "SELECT * FROM $db WHERE $getid='$change'";
	$result = safe_query($query);
	$row 	= mysqli_fetch_object($result);
	$text 	= $row->$cont;
	$text 	= explode("##", $text);
	$br_  	= explode(".", $brick);

	$bA 	= $text[$stelle-1]; 		// brick stelle auslesen
	$bA 	= explode("@@", $bA);
	$bA[0] 	= $br_[0];
	$bA 	= implode("@@", $bA);

	$text[$stelle-1] = $bA;	// brick B an neuer pos einsetzen

	$text 	= implode("##", $text);

	$set 	= "SET ".$cont."='".addslashes($text)."', edit=1 ";
	$query 	= "UPDATE $db " .$set ."WHERE $getid=$change";
	$result = safe_query($query);
}

elseif ($save) {
	$x 		= 0;
	$brick_arr = array();

	foreach($_POST as $key=>$val) {

		$key = explode("#", $key);

		# if ($key[1]) $show .= "$key[0] - $key[1] - $key[2] - $val<br>";
		# if (isin("bild", $key[1])) $show .= "$key[0] - $key[1] - $key[2] - $val<br>";
		if ($key[0] == "brick") {
			if ((preg_match("/link_/", $key[1]) || preg_match("/bild/", $key[1]) || preg_match("/image_popup/", $key[1]) || preg_match("/anker_link/", $key[1]) || preg_match("/gleicher/", $key[1])) &&
					!preg_match("/linksbuendig/", $key[1]) && !preg_match("/download/", $key[1]) && !preg_match("/TOP/", $key[1]) && !preg_match("/galerie/", $key[1]) && !$linktext
				)
				$linktext = $val ."|";

			else {
				if ($linktext) {
					$linktext .= $val;
					$tmp = explode("_", $key[2]);
					$key[2] = $tmp[0];
					$val = addslashes($linktext);
				}
				$x++;

				// echo "$brick -- $x - $stelle: $key[0] - $key[1] - $key[2] - $val ::: $dupl<br>";

				if ($dupl == $x)  {
					$tb = explode(".", $brick);
					$brick_arr[$key[2]] =  $key[1] ."@@" .($val) ."##". $key[1] ."@@" .($val) ."##";  // neuer datensatz wird eingefuegt
					// echo "<p>datensatz wird dupliziert</p>";
				}
				elseif ($brick && $stelle == $x)  {
					$tb = explode(".", $brick);
					$brick_arr[$key[2]] = $tb[0] ."@@" ."##" .$key[1] ."@@" .($val) ."##";  // neuer datensatz wird eingefuegt
					# echo "<p>neuer datensatz wird eingefuegt</p>";
				}
				else	{
					# $show .= "$key[0] - $key[1] - $key[2] - $val<br>";
					$brick_arr[$key[2]] = $key[1] ."@@" .($val) ."##";		  //
				}

				if ($linktext) unset($linktext);
			}
		}
	}
	// wenn ein brick hinten angefuegt wird
	# print_r($brick_arr);
	if ($brick && $stelle > $x) {
		$tb = explode(".", $brick);
		$brick_arr[] = $tb[0] ."@@";
	}

	if (count($brick_arr) < 1 && $brick) $brick_arr[] = $brick ."@@";

	foreach($brick_arr as $key=>$val) {
		if ($val) $text .= $val;
	}

	$text = $text;

	if (!$layout = $_POST["layout"]) $layout = 1;
	if ($db != "morp_cms_news" && $db != "productkat") 	$set = "SET ".$cont."='$text', edit=1, layout='$layout' ";
	else										$set = "SET ".$cont."='$text', edit=1 ";

	if ($edit) 	{
		$query = "UPDATE $db " .$set ."where $getid=$edit";
		$result = safe_query($query);

		// BACKUP WIRD ERZEUGT *******************************************************************
		$sql = "SELECT content FROM `morp_cms_content_history` WHERE $getid=$edit";
		$res = safe_query($sql);
		$rw = mysqli_fetch_object($res);
		$pruefe = $rw->content;

		if($pruefe != $text) {
			$sql = "INSERT `morp_cms_content_history` " .$set .", $getid=$edit , navid=$navid";
			safe_query($sql);
		}
	}
	else {
		$query = "INSERT $db " .$set;
		$result = safe_query($query);
	}

	// print_r($_POST);
	// echo "$query<br>";


	$c = mysqli_insert_id($mylink);

	if ($edit) 	protokoll($uid, $db, $edit, "edit");
	else 		protokoll($uid, $db, $c, "neu");


	// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	// SET change datum in db / table nav // EDIT: 2019-08-12

	$sql = "SELECT navid FROM $db WHERE $getid=$edit";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
	$navid = $row->navid;

	$updated_dat = date("Y-m-d").'T'.date("H:i:s").'+00:00';
	$sql  = "UPDATE morp_cms_nav SET updated_dat='$updated_dat' WHERE navid=$navid";
	safe_query($sql);
	// _______________________________________________________________________________
	// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *


	if (!$edit) {
		$query 	= "SELECT * FROM $db WHERE descr='$descr'";
		$result = safe_query($query);
		$row 	= mysqli_fetch_object($result);
		$edit 	= $row->edit;
	}
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

if ($split) {
	$query 		= "SELECT * FROM $db WHERE $getid='$edit'";
	$result 	= safe_query($query);
	$row 		= mysqli_fetch_object($result);
	$text 		= $row->$cont;
	$text_brick = "t".$tid."__fliesstext";

	if (preg_match("/<s>/", $text)) 	$text = explode("<s>", $text);
	else 								$text = explode("&lt;s&gt;", $text);

	$n			= count($text);
	$x			= 0;

	foreach ($text as $val) {
		$x++;
		$text_				.= addslashes($val);
		if ($x < $n) $text_ .= "##$text_brick@@";
	}

	$set 	= "set $cont='$text_', edit=1 ";
	$query 	= "update $db " .$set ."where $getid=$edit";
	$result = safe_query($query);
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if ($edit || $del) {
	if ($db != "morp_cms_content") $query  = "SELECT * FROM $db WHERE $getid=$edit";
	else $query  = "SELECT * FROM `morp_cms_content` LEFT JOIN morp_cms_protokoll On morp_cms_content.cid=morp_cms_protokoll.id
					WHERE
						morp_cms_content.cid=$edit
					ORDER BY
						morp_cms_protokoll.prid DESC
						";
	# $query  = "SELECT * FROM $db WHERE $getid=$edit";
	$result = safe_query($query);
	$row 	= mysqli_fetch_object($result);
	$text 	= $row->$cont;
	$descr 	= $row->descr;
	$user	= $row->uid;
	$tid 	= $row->tid;
	if ($db == "template") $tid = "99";

# # ________________________________________________________________________________________________________________________________
# # diese komplexen abfragen muessen nochmal ueberdacht und ueberarbeitet werden - diese logiken sind sehr alt...
	# user auslesen
	if ($user) {
		$que  	= "SELECT * FROM morp_cms_user WHERE uid=$user";
		$res 	= safe_query($que);
		$reihe 	= mysqli_fetch_object($res);
		$unm	= $reihe->uname;
	}

	# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	# # es werden mehrere textlayouts unterstuetzt
/*	$layout = $row->layout;

	$layout_bez = $morpheus["layout"];

	foreach($layout_bez as $i=>$val) {
		$radio .= $val.' <input type="radio" name="layout" style="background-color:#dddddd;" border=0 value="'.$i.'"';
		if ($layout == $i) $radio .= ' checked';
		$radio .= '> &nbsp; &nbsp; &nbsp;';
	}
*/	# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

# # ________________________________________________________________________________________________________________________________
# # diese komplexen abfragen muessen nochmal ueberdacht und ueberarbeitet werden - diese logiken sind sehr alt...
	# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	# # textboxen zusammenstellen und anzahl feststellen
	$text_ 	 = explode("##", $text);
	$counter = 0;
	$arr_ct	 = (count($text_) -1);
	$zuruck  = "?edit=$edit&navid=$navid&db=$db&back=$back&sprache=$sprache";

	foreach ($text_ as $val) {
		if ($val) {
			$counter++;

			if ($counter) $textbox .= "
					<!--<a name=".($counter)."></a>-->

			";

			if ($counter == $stelle && $del)
				$textbox .= "<p style=\"background-color:#ff0000; height: 38px; line-height: 37px; margin: 0 0px 30px 0px;\"><font> &nbsp; &nbsp; <a href=\"$zuruck\">< abbrechen</a>  &nbsp; ".$MORPTEXT["CONE-LOSCHEN"]."</font>&nbsp; &nbsp;
					<input type=\"hidden\" name=\"stelle\" value=\"$counter\" class=\"form-control\"><input type=\"submit\"  class=\"btn btn-default sve\" name=\"erstellen\" value=\"speichern $counter\"></p>";

			elseif (($counter == $stelle && $imgid) || ($counter == $stelle && $imglnk))    # nachdem ein bild ausgewaehlt wurde, dieses mit speichern best&auml;tigen
				{
					#if (!$imglnk) $tmp = $art."#" .$counter ."\"";
					#else {
						$val = explode("@@", $val);
						# print_r($val);
						$tmp = $val[0]."#" .$counter ."_a\"";;

						$txt   	= $val[1];
						$txt	= explode("|", $txt);
						if (!$pid) $txt	= $txt[1];
						else $imgid		= $txt[0];
					#}

					$imgname = get_img($imgid);
					$textbox .= '<div class="item" style="height:200px;">'."<img src=\"../mthumb.php?h=100&amp;src=images/userfiles/image/".urlencode($imgname)."\" >
						<input type=\"hidden\" name=\"brick#".$tmp;
					$textbox .= " value=\"$imgid\">
						<p style=\"background-color:#ff0000; height: 38px; line-height: 37px; margin: 0px 0px 30px 0px; color: #ffffff;\"> &nbsp; ".$MORPTEXT["CONE-BILDUP"]."</font> &nbsp; &nbsp;
							<input type=\"hidden\" name=\"stelle\" value=\"$counter\"><input type=\"submit\"  class=\"btn btn-default sve\" name=\"erstellen\" value=\"".$MORPTEXT["GLOB-SPEICHERN"]." $counter\">
							<input type=\"hidden\" name=\"brick#".$val[0]."#" .$counter ."_b\" value=\"$txt\">
						</p>
						</div>
						";

/*
					if ($imglnk) {
						if (!$pid) $txt = $txt;
						else $txt = $pid;
						$textbox .= "<input type=hidden name=\"brick#".$val[0]."#" .$counter ."_b\" value=\"$txt\">";
					}
*/
				}

			elseif ($counter == $stelle && $pid)    {  # nachdem ein pdf ausgewaehlt wurde, dieses mit speichern best&auml;tigen
				$val = explode("@@", $val);
				$brick = $val[0];
				$brick = explode(".", $brick);
				$textbox .= "\n\n<p><input type=hidden name=\"brick#".$brick[0]."#" .$counter ."\" value=\"$pid\"><b>".get_pdf($pid)."</b> <font color=#ff0000 size=-1 face=Tahoma> &nbsp;".$MORPTEXT["CONE-DOWNLOAD"]."</font> &nbsp; &nbsp;
					<input type=\"submit\" name=\"erstellen\" value=\"speichern\" class=\"btn btn-default sve\"></p>";
			}
			else {
				$val 		= explode("@@", $val);
				$brickname 	= $val[0];
				$txt   		= $val[1];

				$brick 		= explode(".", $brickname);
				unset($image);

				$b_chk = explode("_", $brick[0]);
				unset($tmp);
				for($n=1; $n < count($b_chk); $n++) {
					$tmp .= $b_chk[$n]." ";
				}
				$b_chk = $tmp;
				trim($b_chk);
				###########################################################

# # ________________________________________________________________________________________________________________________________
# # per b_chk wird die art der textbox erkannt und zugewiesen
# # $b_chk enthaelt den namen des brick = name der php-vorlage aus dem ordner bricks

				$colour = "#fff";
				$bgcolor = "";

				// echo '#############'.$b_chk;

				if (preg_match("/galerie/", $b_chk)) 									$galerie = 1;
				elseif (preg_match("/texteditor/", $b_chk)) 							$fck = 1;
				elseif (preg_match("/formular/", $b_chk)) 								$insert_form = 1;
				elseif (preg_match("/umbruch/", $b_chk) || preg_match("/sitemap/", $b_chk) ||
					preg_match("/^kunden/", $b_chk) || preg_match("/^spalte/", $b_chk) ||
					preg_match("/stellenangebote/", $b_chk) ||
					preg_match("/pfeil/", $b_chk) ||
					//preg_match("/veranstaltungen/", $b_chk) ||
					preg_match("/^filialen/", $b_chk) ||
					preg_match("/textfluss/", $b_chk) ||
					preg_match("/linie/", $b_chk) ||
					preg_match("/ende/", $b_chk) ||
					preg_match("/end/", $b_chk) ||
					preg_match("/^start/", $b_chk) ||
					preg_match("/ start/", $b_chk) ||
					preg_match("/ end/", $b_chk) ||
					preg_match("/trenner/", $b_chk)
				)		 							{ $umbruch = 1; $bgcolor = "#777777"; }

				elseif (preg_match("/bild/", $b_chk) || preg_match("/grafik/", $b_chk)) 		$image = 1;
#				elseif (preg_match("/menu/", $b_chk)) 									$insert_menu = 1;
				elseif (preg_match("/anwendung/", $b_chk)) 								$insert_anwendung = 1;
				elseif (preg_match("/veranstaltungen/", $b_chk)) 						$insert_buchung = 1;
				elseif (preg_match("/^ icon/", $b_chk)) 								$insert_icon = 1;
				elseif (preg_match("/^ colour/", $b_chk)) 								$insert_colour = 1;
				elseif (preg_match("/Leistungen/", $b_chk)) 							$insert_leistungen = 1;
#				elseif (preg_match("/produkt/", $b_chk)) 								$insert_shop = 1;
				elseif (preg_match("/image link/", $b_chk)) 							$insert_imagelink = 1;
				elseif (preg_match("/image popup/", $b_chk)) 							$insert_imagelink = 2;
#				elseif (preg_match("/presse/", $b_chk)) 								$insert_presse = 1;
				elseif (preg_match("/link/", $b_chk) || preg_match("/gleicher/", $b_chk)) 	$insert_link = 1;
				elseif (preg_match("/download gruppe/", $b_chk))						$insert_pdf_group = 2;
				elseif (preg_match("/download vcard/", $b_chk))							$insert_pdf_vcard = 2;
				elseif (preg_match("/download/", $b_chk) || preg_match("/^uploaded/", $b_chk))	$insert_pdf = 1;
				elseif (preg_match("/kompetenz/", $b_chk)) 								$insert_loesung = 1;
				elseif (preg_match("/kunde/", $b_chk)) 									$insert_kunde = 1;
				elseif (preg_match("/referenz/", $b_chk)) 								$insert_referenz = 1;
				elseif (preg_match("/mitarbeiter/", $b_chk)) 							$insert_mitarbeiter = 1;
				elseif (preg_match("/farbe/", $b_chk)) 									$insert_farbe = 1;
				elseif (preg_match("/klasse/", $b_chk)) 								$insert_klasse = 1;
				elseif (preg_match("/branche/", $b_chk)) 								$insert_branche = 1;
				elseif (preg_match("/mindflash/", $b_chk)) 								$insert_mindflash = 1;
				elseif (preg_match("/video/", $b_chk)) 									$insert_video = 1;
				elseif (preg_match("/stimmen land/", $b_chk)) 									$insert_land = 1;
//				elseif (preg_match("/termin/", $b_chk)) 								$insert_termin = 1;
				elseif (preg_match("/objekt/", $b_chk)) 								$insert_objekt = 1;
				elseif (preg_match("/buchungsmodul/", $b_chk)) 							$insert_buchung = 1;
				elseif (preg_match("/template/", $b_chk)) 								$insert_templ = 1;
				elseif (preg_match("/tabelle/", $b_chk)) 								$insert_tab = 1;
				elseif (preg_match("/news/", $b_chk) ) 	$insert_news = 1;

				elseif (preg_match("/vorlage/", $b_chk))								$insert_vorlage = 1;
				elseif (preg_match("/^vorlage/", $b_chk) || preg_match("/^headline/", $b_chk))$row = 2.5;
				elseif (preg_match("/^abstand/", $b_chk)) 								$insert_text = 1;
				elseif (preg_match("/html/", $b_chk) || preg_match("/termine tab/", $b_chk) || preg_match("/map/", $b_chk) || preg_match("/themenliste/", $b_chk)) 								$row = 10;
				elseif (preg_match("/box/", $b_chk)) 								$row = 6;
				elseif (preg_match("/text/", $b_chk) || preg_match("/aufzaehlung/", $b_chk)) 	$row = $hoehe;
				else $row = 1.5;

				# # headline und subheadline werden mit groesseren typo size dargestellt
				if (preg_match("/^headline/", $b_chk))			$colour = "#ffffff; font-size: 13px; font-weight: bold;";
				elseif (preg_match("/^sub/", $b_chk))			$colour = "#ffffff; font-size: 11px; font-weight: bold;";
				elseif (preg_match("/modul/", $b_chk))			$bgcolor = "#999999";

# # ________________________________________________________________________________________________________________________________
# # den abschluss jeder formatvorlage / brick definieren - loeschen, verschieben, etc.

				unset($textbox_abschluss);

				$textbox_abschluss .= "<td valign=\"top\" align=\"center\" style=\"padding-left:20px;\" nowrap><a href=\"javascript:setdupl('".($counter)."');\" class=\"button2\"><i class=\"fa fa-copy\"></i></a>&nbsp;";

				if ($counter > 1)
					$textbox_abschluss .= " <a href=\"javascript:check('content_edit_live.php?db=$db&edit=$edit&navid=$navid&sort=" .($counter-1) ."&stelle=$counter&back=$back');\" name=\"".$MORPTEXT["GLOB-POSO"]."\" title=\"".$MORPTEXT["GLOB-POSO"]."\" class=\"button2\"><i class=\"fa fa-sort-up\"></i></a>";

				$textbox_abschluss .= "<a href=\"javascript:check('content_edit_live.php?db=$db&edit=$edit&navid=$navid&sort=" .($counter+1) ."&stelle=$counter&back=$back');\" name=\"".$MORPTEXT["GLOB-POSU"]."\" title=\"".$MORPTEXT["GLOB-POSU"]."\" class=\"button2\"><i class=\"fa fa-sort-desc\"></i></a> &nbsp; ";


				unset($textbox_abschluss2);

				$textbox_abschluss2 .= "<td class=\"padRL20\"><a href=\"javascript:setdupl('".($counter)."');\" class=\"button2\"><i class=\"fa fa-copy\"></i></a></td>";
				if ($counter > 1)	$textbox_abschluss2 .= "<td><a href=\"javascript:check('content_edit_live.php?db=$db&edit=$edit&navid=$navid&sort=" .($counter-1) ."&stelle=$counter&back=$back');\" name=\"".$MORPTEXT["GLOB-POSO"]."\" title=\"".$MORPTEXT["GLOB-POSO"]."\" class=\"button2\"><i class=\"fa fa-sort-up\"></i></a></td>";
				$textbox_abschluss2 .= "<td><a href=\"javascript:check('content_edit_live.php?db=$db&edit=$edit&navid=$navid&sort=" .($counter+1) ."&stelle=$counter&back=$back');\" name=\"".$MORPTEXT["GLOB-POSU"]."\" title=\"".$MORPTEXT["GLOB-POSU"]."\" class=\"button2\"><i class=\"fa fa-sort-desc\"></i></a></td>";


				if (!$sort && $counter == $stelle) 		$divend = 1;
				elseif ($sort && $counter == $sort) 	$divend = 1;
				else 									unset($divend);

				if ($divend) $textbox .= '
';
# # ________________________________________________________________________________________________________________________________

				if ($image) {
					$txt 	 = explode("|", $txt);
					$imageid = $txt[0];
					$ausrichtung = $txt[1];

					if (!$imageid) $imageid = 1;

					$imgname = get_img($imageid);
					$isSVG = isin(".svg", $imgname) ? 1 : 0;
					$size 	 = getimagesize($img_pfad.($imgname));
					$w 		 = $size[1];

					if($w < 200) 	$blob 	 = "<img src=\"".$img_pfad.($imgname).'"'.($isSVG ? ' style="height:200px; width:200px; margin: 0 30px;"' : '').'>';
					else 			$blob 	 = "<img src=\"../mthumb.php?src=images/userfiles/image/".urlencode($imgname)."&h=200\" class=\"img-responsive\" border=1>";

					# $hinweis = " image auswechseln - [".$b_chk ."]";
					$hinweis = $b_chk ;
					$chg_img = "<a href=\"javascript:check('image.php?box=1&live_edit=1&gid=1&edit=$edit&navid=$navid&vorlage=$vorlage&stelle=$counter&back=$back&db=$db&art=".$brick[0]."');\">";

					$textbox .= '

					<table id="t'.$counter.'" class="item bgw" style="min-height:100px;">'."
						<tr>
							<td>&nbsp; " .$counter .".&nbsp; <input type=\"hidden\" name=\"brick#" .$brick[0] ."#" .$counter ."\" value=\"$imageid\" class=\"getVal\"> ".$hinweis." &nbsp; </td>
							<td align=left>$chg_img";

					$textbox .= $blob."</a>
							<td>".'
								<input type="hidden" name="brick#'.$brick[0].'#'.$counter.'_b" id="ar'.$counter.'" value="'.$ausrichtung.'" />
								<button class="btn btn-'.($ausrichtung == 1 ? 'primary' : 'info').' setImageAusrichtung ar'.$counter.'" value="1" ref="ar'.$counter.'" /> <i class="fa fa-circle-thin"></i>&nbsp;</button>
								<button class="btn btn-'.($ausrichtung == 2 ? 'primary' : 'info').' setImageAusrichtung ar'.$counter.'" value="2" ref="ar'.$counter.'" /><i class="fa fa-chevron-left"></i>&nbsp;</button>
								<button class="btn btn-'.($ausrichtung == 3 ? 'primary' : 'info').' setImageAusrichtung ar'.$counter.'" value="3" ref="ar'.$counter.'" />center</button>
								<button class="btn btn-'.($ausrichtung == 4 ? 'primary' : 'info').' setImageAusrichtung ar'.$counter.'" value="4" ref="ar'.$counter.'" /><i class="fa fa-chevron-right"></i>&nbsp;</button>
							</td>'."

							$textbox_abschluss
								<span class=\"btn btn-info\"><i class=\"fa fa-trash-o del\" ref=\"t".$counter."\"></i></span>
							</td>
						</tr>
					</table>
";
					unset($blob);
				}

				elseif ($fck) {
					$textbox .= "

<table bgcolor=\"$bgcolor\" id=\"t".$counter."\" class=\"item\">
	<tr>";
					$textbox .= '

		<td class="c150" valign="top"> &nbsp; '.$counter.'.  <a href="content_fckedit.php?cid='.$edit.'&navid='.$navid.'&back='.$back.'&stelle='.$counter.'&db='.$db.'" class="textedit">' .str_replace("_", " ", $b_chk) .' &nbsp; <i class="fa fa-pencil-square-o large"></i></a>
		</font></td>
		<td></td>
			'.$textbox_abschluss.'
		<td>	
			<span class="button2"><i class="fa fa-trash-o del" ref="t'.$counter.'"></i></span>
		</td>
		</td>
		</tr>
		<tr>';

					//if ($fckedit && $counter == $stelle) $txt = ($_POST["FCKeditor1"]);

					$textbox .= "<td colspan=\"4\"><textarea class=\"summernote\" name=\"brick#" .$brick[0] ."#" .$counter ."\" readonly style=\"width:510px; background-color:#f2f2f2; border: solid 4px $colour;\" onchange=\"setchange('1');\">". stripslashes($txt) ."</textarea>
					</td>
	
	
					</tr></table>
										";

					unset($fck);
				}

				elseif ($insert_vid) {
					if (!$txt) $txt = 0;

					if ($insert_pdf) $hinweis = " ".$MORPTEXT["CONE-DOCWAHL"];
					else $hinweis = " ".$MORPTEXT["CONE-VIDWAHL"];

					$textbox .= "

					<table bgcolor=\"$bgcolor\" id=\"t".$counter."\" class=\"item\">
						<tr>
							<td class=\"c520\" class='text_verw'> &nbsp; " .$counter .".&nbsp; <input type=\"hidden\" name=\"brick#" .$brick[0] ."#" .$counter ."\" value=\"$txt\" class=\"getVal\"> <a href=\"javascript:check('pdf_select.php?cid=$edit&navid=$navid&stelle=$counter&back=$back');\">" .ilink() .$hinweis ."</a></font></td><td width=100><i class=\"fa fa-trash-o del\" ref=\"t".$counter."\"></i></a></td><td width=100>";

					$nm_pdf = get_pdf($txt);

					$textbox .= $textbox_abschluss;
					$textbox .= "<p style=\"border:solid 1;width:300px;margin:-0 0 0 0;color:gray;\"><b><a name=\"$nm_pdf\" href=\"../pdf/$nm_pdf\" target=\"_blank\" title=\"$nm_pdf\">".$nm_pdf."</a></b></p></td></tr></table>";
					unset($insert_pdf);
					unset($insert_video);
				}


				# # # # # # # # # # # links erstellen
				elseif ($umbruch) {
					unset($umbruch);
					$textbox .= "

					<table bgcolor=\"$bgcolor\" id=\"t".$counter."\" class=\"item\"><tr>";

					$textbox .= "<td> &nbsp; $counter. " .str_replace("_", " ", $b_chk) ."</td>";
					$textbox .= '<td><table class="tbl0"><tr>';
					$textbox .= $textbox_abschluss2;
					$textbox .= '<td class="padRL20"><span class="button"">'."<i class=\"fa fa-trash-o del\" ref=\"t".$counter."\"></i></span>
								<input type=\"hidden\" name=\"brick#" .$brick[0] ."#" .$counter ."\" value=\"umbruch\"  class=\"getVal\"></td>";
					$textbox .= '</tr></table>';
					$textbox .= "</td></tr></table>\n\n";
				}


				# # # # # # # # # # # links erstellen
				elseif ($insert_link) {
					unset($insert_link);
					$txt = explode("|", $txt);
					$href = $txt[0];
					$txt  = $txt[1];
					$hidden = "hidden";

					# echo $brick[0];

					if ($link && $counter==$pos) $href = $setlink;
					elseif(preg_match("/anker/", $brick[0]) || preg_match("/gleicher/", $brick[0])) $hidden = "text";
					elseif(preg_match("/extern/", $brick[0]) || preg_match("/mail/", $brick[0])) {}
					elseif(!$href) $href = $MORPTEXT["CONE-ZIELWAHL"];

					if(  preg_match("/intern/", $brick[0]) || preg_match("/footer/", $brick[0]) || preg_match("/start/", $brick[0])  )
						$holelink = "<a href=\"javascript:check('link.php?edit=$edit&navid=$navid&back=$back&db=$db&pos=$counter');\" class=\"btn btn-info\">$href</a> &nbsp; ";
					else unset($holelink);

					$textbox .= "
					<table bgcolor=\"$bgcolor\" id=\"t".$counter."\" class=\"item\"><tr>";

					$textbox .= "<td style=\"padding-right:20px;\">&nbsp; $counter. " .str_replace("_", " ", $b_chk) ."</td>
						<td>";

					if (preg_match("/TOP/", $brick[0])) $textbox .= "<input type=\"Hidden\" name=\"brick#" .$brick[0] ."#" .$counter ."\" value=\"anker\"  class=\"getVal\"><input type='text' class=\"form-control\" name=\"brick#" .$brick[0] ."#" .$counter ."\" style=\"width:50px\" value=\"$href\">\n";
					else $textbox .= "<font class='text'>
							Link: $holelink</td><td>
							<input type='$hidden' name=\"brick#" .$brick[0] ."#" .$counter ."_a\" style=\"width:200px\" value=\"$href\"  class=\"getVal\">
							<input type='text'  class=\"form-control\" name=\"brick#" .$brick[0] ."#" .$counter ."_a\" style=\"width:150px\" value=\"$href\"> &nbsp;
							Text: <input type='Text'  class=\"form-control\" name=\"brick#" .$brick[0] ."#" .$counter ."_b\" style=\"width:200px\" value=\"$txt\" onchange=\"setchange('1');\">
						</td>
						<td>
						".'<table class="tbl0">
							<tr>
								'.$textbox_abschluss2.'
								<td class="padRL20"><span class="button2"><i class="fa fa-trash-o del" ref="t'.$counter.'"></i></span></td>
							</tr>
						</table>
					</td></tr></table>';
				}



				# # # # # # # # # # # link zu einem Produkt im Shop erstellen # muss nochmal ueberdacht werden
				elseif ($insert_sho) {
					unset($insert_shop);

					$textbox .= "
					<table bgcolor=\"gray\" id=\"t".$counter."\" class=\"item\"><tr>";

					$textbox .= "<td class=\"c150\" colspan=2> &nbsp; $counter. " .str_replace("_", " ", $b_chk) ." &nbsp; </td>
					<td width=190><a href=\"javascript:check('shop_produkt_auswahl.php?navid=$edit&navid=$navid&stelle=$counter&back=$back&db=$db&gb=1')\" rel=\"gb_page_center[640]\">".$MORPTEXT["CONE-ARTWAHL"]."</a></td>";
					$textbox .= "<td width=90><input type=\"Hidden\" name=\"brick#" .$brick[0] ."#" .$counter ."\" onchange=\"setchange('1');\" value=\"$txt\"  class=\"getVal\"> &nbsp; &nbsp; &nbsp; <i class=\"fa fa-trash-o del\" ref=\"t".$counter."\"></i></a></td>";

					$textbox .= $textbox_abschluss;
					$textbox .= "
					</td></tr></table>";
				}


				# # # # # # # # # # # links erstellen
				elseif ($insert_imagelink) {
					$popup = $insert_imagelink;
					unset($insert_imagelink);
					$txt 	 = explode("|", $txt);
					$imageid = $txt[0];
					$href  	 = $txt[1];
					$hidden  = "hidden";

					if ($link && $counter==$pos) $href = $setlink;
					elseif(preg_match("/extern/", $brick[0])) $hidden = "text";
					elseif(!$href && !$popup) $href = $MORPTEXT["CONE-ZIELWAHL"];

					if(preg_match("/intern/", $brick[0])) $holelink = "<a href=\"javascript:check('link.php?edit=$edit&navid=$navid&back=$back&db=$db&pos=$counter');\" class=\"btn-btn-info\">".$MORPTEXT["CONE-LINKSETZEN"]."</a>";
					elseif(preg_match("/pdf/", $brick[0])) {
						if (preg_match("/bitte/", $href)) $href = 1;
						$nm_pdf = get_pdf($href);
						$holelink = "<a href=\"javascript:check('pdf_select.php?cid=$edit&navid=$navid&stelle=$counter&back=$back&imglnk=1');\" class=\"btn-btn-info\">" .ilink() ." PDF: " .$hinweis ." $nm_pdf</a>";
					}
					else unset($holelink);

					if($imageid) $imgname = get_img($imageid);

					$textbox .= "
					<table id=\"t".$counter."\" class=\"item\" style=\"min-height:100px;\"><tr>";

					$isSVG = isin(".svg", $imgname) ? 1 : 0;

					$textbox .= "
					<td class=\"c160\" valign=top>
						&nbsp; $counter. " .str_replace("_", " ", $b_chk) ."
					</td>
					<td valign=top>
						<a href=\"javascript:check('image.php?gid=1&edit=$edit&navid=$navid&vorlage=$vorlage&stelle=$counter&back=$back&db=$db&imglnk=1');\" class=\"btn-btn-info\">
							<img src=\"".($isSVG ? '../images/userfiles/image/'.$imgname : "../mthumb.php?h=90&amp;zc=2&amp;src=images/userfiles/image/".urlencode($imgname))."\" style=\"".($isSVG ? 'height:60px;' : '')."padding:0 20px;\"  class=\"img-responsive\" >
						</a>
						<input type='hidden' name=\"brick#" .$brick[0] ."#" .$counter ."_a\" value=\"$imageid\"  class=\"getVal\">
					</td>
					<td valign=top>&nbsp; ". ($holelink  ? "Link: $holelink" : 'Text: ') ."
						<input type='text' class=\"form-control\" name=\"brick#" .$brick[0] ."#" .$counter ."_b\" value=\"$href\">
					</td>";

					$textbox .= $textbox_abschluss;
					$textbox .= "<span class=\"btn btn-danger\"><i class=\"fa fa-trash-o del\" ref=\"t".$counter."\"></i>
					</td>\n</tr></table>\n";
					unset($imageid);
					unset($href);
					$popup = '';
				}


				# # # # # # # # # # # ohne image - nur text
				elseif ($insert_form || $insert_templ || $insert_termin || $insert_news || $insert_loesung || $galerie || $insert_branche || $insert_pdf || $insert_shop || $insert_anwendung || $insert_mindflash || $insert_kunde || $insert_mitarbeiter || $insert_objekt || $insert_presse || $insert_video || $insert_menu || $insert_vorlage || $insert_warengruppe || $insert_land || $insert_farbe || $insert_icon || $insert_colour || $insert_klasse || $insert_pdf_group || $insert_buchung || $insert_referenz || $insert_pdf_vcard || $insert_leistungen ) {

					$icon = '';
					if($insert_icon) {
						$newIcon = isset($_GET["newIcon"]) ? $_GET["newIcon"] : 0;
						$newIconPos = isset($_GET["newIconPos"]) ? $_GET["newIconPos"] : 0;

						if($newIcon && $newIconPos == $counter) {
							$txt = $newIcon;
							$warn = 1;
						}
						$sql  = "SELECT fa FROM morp_fa WHERE faid=".$txt;
						$rs = safe_query($sql);
						$rw = mysqli_fetch_object($rs);

						$icon = '<a href="morp_icons_select.php?edit='.$edit.'&navid='.$navid.'&pos='.$counter.'"><i class="getIcon fa '.$rw->fa.' lgIcon" style="border:solid 1px #1997c6;padding:10px;font-size:30px; text-align:center; margin-top:5px; margin-right:20px;"></i></a>';
					}

					if ($insert_form) {
						unset($insert_form);
						$pdf_pd = pulldownContent ($txt, "morp_cms_form", "fname", "fid");
					}
					elseif ($insert_objekt) {
						unset($insert_objekt);
						$pdf_pd = pulldownContent ($txt, "morp_immo_objekt", "objekt", "oid");
					}
					elseif ($insert_leistungen) {
						unset($insert_leistungen);
						$pdf_pd = pulldownContent ($txt, "morp_faq_category", "category", "catId");
					}
					elseif ($insert_colour) {
						unset($insert_colour);
						$pdf_pd = pulldownContent ($txt, "morp_color", "colname", "colid");
					}
					elseif ($insert_buchung) {
						unset($insert_buchung);
						$pdf_pd = pulldownContent ($txt, "morp_event_kategorie", "bezeichnung", "kid");
					}
					elseif ($insert_icon) {
						unset($insert_icon);
						$pdf_pd = pulldownContent ($txt, "morp_fa", "beschreibung", "faid");
					}
					elseif ($insert_referenz) {
						unset($insert_referenz);
						$pdf_pd = pulldownContent ($txt, "morp_referenzen", "kunde", "refid");
					}
					elseif ($insert_klasse) {
						unset($insert_klasse);
						$pdf_pd = pulldownContent ($txt, "morp_class", "class", "clid");
					}
					elseif ($insert_land) {
						unset($insert_land);
						$pdf_pd = pulldownContent ($txt, "morp_stimmen_land", "land", "id");
					}
					elseif ($insert_farbe) {
						unset($insert_farbe);
						$pdf_pd = pulldownContent ($txt, "morp_color", "colname", "colid");
					}
					elseif ($insert_vorlage) {
						unset($insert_vorlage);
						$pdf_pd = vorlage($txt);
					}
					elseif ($insert_warengruppe) {
						unset($insert_warengruppe);
						$pdf_pd = pulldownContent ($txt, "morp_shop_wg", "gruppe", "wid");
					}
					elseif ($insert_menu) {
						unset($insert_menu);
						$pdf_pd = pulldownContent ($txt, "morp_cms_nav", "name", "navid");
					}
					elseif ($insert_shop) {
						unset($insert_shop);
						$pdf_pd = pulldownContent ($txt, "morp_shop_prod", "name", "pid");
					}
					elseif ($insert_presse) {
						unset($insert_presse);
						$pdf_pd = pulldownContent ($txt, "morp_cms_pdf_group", "pgname", "pgid");
					}
					elseif ($insert_kunde) {
						unset($insert_kunde);
						$pdf_pd = pulldownContent ($txt, "morp_kunde", "beschr", "kid");
					}
					elseif ($insert_mitarbeiter) {
						unset($insert_mitarbeiter);
						$pdf_pd = pulldownContent ($txt, "morp_mitarbeiter", "name", "mid");
					}
					elseif ($insert_mindflash) {
						unset($insert_mindflash);
						$pdf_pd = pulldownContent ($txt, "morp_cms_news", "ntitle", "nid", 2);
					}
					elseif ($insert_video) {
						unset($insert_video);
						$pdf_pd = pulldownContent ($txt, "morp_cms_pdf", "pname", "pid", "1");
					}
					elseif ($insert_anwendung) {
						unset($insert_anwendung);
						$pdf_pd = pulldownContent ($txt, "product_eigenschaft", "pe_de", "peid");
					}
					elseif ($insert_news) {
						unset($insert_news);
						$pdf_pd = pulldownContent ($txt, "morp_cms_news_group", "ngname", "ngid");
					}
					elseif ($insert_pdf_group) {
						unset($insert_pdf_group);
						$pdf_pd = pulldownContent ($txt, "morp_cms_pdf_group", "pgname", "pgid");
					}
					elseif ($insert_pdf_vcard) {
						unset($insert_pdf_vcard);
						$pdf_pd = pulldownContent ($txt, "morp_cms_pdf", "pname", "pid", 3);
					}
					elseif ($insert_pdf) {
						unset($insert_pdf);
						$pdf_pd = pulldownContent ($txt, "morp_cms_pdf", "pname", "pid");
					}
					elseif ($insert_templ) {
						unset($insert_templ);
						$pdf_pd = pulldownContent ($txt, "template", "tname", "tid");
					}
					elseif ($galerie) {
						unset($galerie);
						$pdf_pd = pulldownContent ($txt, "morp_cms_galerie_name", "gnname", "gnid");
					}


					$textbox .= "

					<table id=\"t".$counter."\" bgcolor=\"#B2B2B2\" class=\"item\"><tr>";

					$textbox .= "<td> &nbsp; $counter. $insert_icon <span class=\"textedit\">" .str_replace("_", " ", $b_chk) ."</span></td>
					<td>$icon<select name=\"brick#" .$brick[0] ."#$counter\" class=\"form-control select\"".($icon ? ' style="width:50%;"' : '').">$pdf_pd</select></td>".'<td><table class="tbl0"><tr>';

					$textbox .= $textbox_abschluss2;
					$textbox .= '<td class="padRL20"><span class="button2"><i class="fa fa-trash-o del"  ref="t'.$counter.'"></i></span></td></tr></table></td></tr></table>';
				}


				elseif ($insert_tab) {
					unset($insert_tab);
					if ($tab && $pos == $counter) {
						$lk = "edit=$tab";
						$txt = $tab;
					}
					elseif (!$txt) $lk = "neu=1";
					else $lk = "edit=$txt";

					$textbox .= "
					<table  id=\"t".$counter."\" style=\"background-color:#a4c3e0;\" class=\"item\"><tr>";

					$textbox .= "<td width=140> &nbsp; $counter. " .str_replace("_", " ", $b_chk) ."";
					$textbox .= "<p>&nbsp; <a href=\"javascript:check('tabelle.php?$lk&pos=$counter&cid=$edit&navid=$navid&db=$db&back=$back');\" class=nav>". ilink() ." ".$MORPTEXT["CONE-TABBEARBEIT"]."</a></p></td>";
					$textbox .= $textbox_abschluss;
					$textbox .= "<p><i class=\"fa fa-trash-o del\" ref=\"t".$counter."\"></i></a></p>
<input type=\"hidden\" name=\"brick#" .$brick[0] ."#$counter\" value=\"$txt\"  class=\"getVal\">";
					$textbox .= "</td></tr></table>";
				}


				elseif ($insert_text) {
					unset($insert_text);
					$textbox .= "
					<table id=\"t".$counter."\" bgcolor=\"gray\" class=\"item\"><tr>";
					$textbox .= "<th class=\"c150\" colspan=2> &nbsp; $counter. " .str_replace("_", " ", $b_chk) ." &nbsp; <a href=\"javascript:check('brick_change.php?cid=$edit&navid=$navid&back=$back&pos=$counter&db=$db');\"><i class=\"fa fa-text-width\"></i></a></th>";
					$textbox .= " </th></tr><tr><th width=90><input type=\"Text\" class=\"form-control\" name=\"brick#" .$brick[0] ."#" .$counter ."\" style=\"width:50px; border: solid 1px gray;\" onchange=\"setchange('1');\" value=\"".stripslashes($txt)."\"> &nbsp; &nbsp; &nbsp; <i class=\"fa fa-trash-o del\" ref=\"t".$counter."\"></i></a></th>";

# content_edit.php?db=$db&edit=$edit&stelle=$counter&split=$counter&save=1

					$textbox .= $textbox_abschluss;
					$textbox .= "
					</th></tr></table>";
				}


				else {

					$special = isin("anmerkungen", $b_chk) ? 1 : 0;
					$textbox .= "

					<table id=\"t".$counter."\" bgcolor=\"$bgcolor\" class=\"item\">
						<tr>
							<th".($special ? ' style="background:#222; color:#fff;"' : '').">".($special ? ' &nbsp; '.str_replace("_", " ", $b_chk) : " &nbsp; $counter. <a href=\"javascript:check('brick_change.php?cid=$edit&navid=$navid&back=$back&pos=$counter&db=$db');\" class=\"textedit\">" .str_replace("_", " ", $b_chk) ."</a>".' &nbsp; &nbsp; <i class="fa fa-file-text selall" ref="s'.$counter.'"></i> <span class="rahmen"><i class="fa fa-anchor structureOn" ref="s'.$counter.'"></i> <i class="fa fa-external-link externalLink" ref="s'.$counter.'"></i> <i class="fa fa-at mailLink" ref="s'.$counter.'"></i> <i class="fa fa-eraser delAllLinks" ref="s'.$counter.'"></i></span><i class="bold__text btn" ref="s'.$counter.'"><strong>B</strong></i>  <i class="italic__text btn" ref="s'.$counter.'"><strong>I</strong></i>  <i class="underline__text btn" ref="s'.$counter.'"><strong><u>U</u></strong></i></span>').'

								<table class="tbl0">'."
									<tr>
										<td class=\"padRL20\">
											".'<span class="buttonSave SAVE"><i class="fa fa-save"></i></a></span>
										</td>
										<td class="padRL20">';

										if (preg_match("/text/", $brick[0])) $textbox .= "<button type=\"submit\" name=\"split\" class=\"split btn btn-info mobileOff\" value=\"1\">Text Splitten <s></button></td>";

					$textbox .= "

								".$textbox_abschluss2."
										<td class=\"padRL20\"><span class=\"button2\"><i class=\"fa fa-trash-o del\" ref=\"t".$counter."\"></i></span></td>
									</tr>
								</table>
							</th>
						</tr>
						<tr>";

					$textbox .= "
							<td>
								<textarea id=\"s".$counter."\" name=\"brick#" .$brick[0] ."#" .$counter ."\" style=\"".($special ? 'background:#ddd;' : '')."height:" .($row*30) ."px;\" class=\"form-control ta gridcheck getVal \" onchange=\"setchange('1');\">".stripslashes($txt)."</textarea>
							</td>
						</tr>
					</table>
					";
				}
			}
		}
		#if ($counter % 4 == 0) $textbox .="\n<!-- direct -->\n";

#		if ($divend) $textbox .= '</div>			';
	}
	# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	$counter++;

	// _textboxen zusammenstellen
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
//

// stelle select mit bricks zusammen
$dir = opendir("../bricks");
$select_brick = '<select name="brickname" class="form-control">
	<option value="">'.$MORPTEXT["CONE-FORMATIERUNGWAHL"].'</option>
	<option value="">------------------------</option>
';
$dir_arr = array();

//////////////////////////////////////////////////////////////////////
// NEU !!!!!!!!!!!!!!!!!!!!
// --- Template Art checken
# $link_arr = array(3, 4);
//////////////////////////////////////////////////////////////////////
if ($db == "morp_cms_news") $anz = "news_";
else $anz = "t".$tid."_";
while ($name = readdir($dir)) {
	if (!$link_aktiv) {
		if (!is_dir($name)) {
			# echo $name."<br>";
			$suche_nach = '/^'.$anz.'/';
			if (preg_match($suche_nach, $name)) { $dir_arr[] = $name; }
			elseif (preg_match("/^all/", $name)) $dir_arr[] = $name;
		}
		elseif (!preg_match("/Box/", $name)) $dir_arr[] = $name;
	}
}
sort ($dir_arr);

$eb1 = '';
$eb2 = '';
$eb3 = '';
$eb4 = '';
$eb5 = '';
$eb6 = '';

foreach($dir_arr as $name) {
	$name_ = explode(".", $name);
	$name_ = repl("_", " ", $name_[0]);
	$name_ = substr($name_,4);

	if ($counter > 1 && $name_ == "link") 	{}
	elseif ($name_[0] == "-") 				$select_brick .= "<option value=''>$name_</option>\n";
	elseif ($name_)							{
		$option = "<option value='$name'>$name_</option>\n";

		if(preg_match("/bild/", $name_)) 		$eb1 .= $option;
		elseif(preg_match("/link/", $name_)) 	$eb4 .= $option;
		elseif(preg_match("/headl/", $name_)) 	$eb2 .= $option;
		elseif(preg_match("/flies/", $name_)) 	$eb3 .= $option;
		elseif(preg_match("/umbru/", $name_)) 	$eb6 .= $option;
		else									$eb5 .= $option;

	}
}

$ebleer = "<option value=''>------------------</option>\n";
$select_brick .= $eb1 ? $eb1.$ebleer : '';
$select_brick .= $eb2 ? $eb2.$ebleer : '';
$select_brick .= $eb3 ? $eb3.$ebleer : '';
$select_brick .= $eb4 ? $eb4.$ebleer : '';
$select_brick .= $eb5 ? $eb5.$ebleer : '';
$select_brick .= $eb6 ? $eb6.$ebleer : '';
$select_brick .= "</select>\n";
// _select
//

//
// stelle select mit counter zusammen
if (!$counter) $counter = 1;
$select_stelle = '<select name="stelle" class="form-control">
	<option value="'.$counter.'">'.$counter.'</option>
';

if ($counter > 1) {
	for ($i=1; $i < $counter; $i++) {
		$select_stelle .= "<option value='$i'>$i</option>\n";
	}
}
$select_stelle .= "</select>\n";
// _select
//

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

$direct_link = '<div class="link_container"><span style="float:right; width: 40px;"><a href="#top">'.$MORPTEXT["CONE-TOP"].'</a>&nbsp; </span>';

for ($n=($counter-1); $n>1; $n--) {
	$direct_link .= '<span style="float:right; padding-right: 8px;"><a href="#'.$n.'">'.$n.'|</a></span>';
}

$direct_link .= '</div>
';


echo "\n\n
	<input type=hidden name=save value=1>
	<input type=hidden name=db value=$db>
	<input type=hidden name=ngid value='$ngid'>
	<input type=hidden name=back value='$back'>
	<input type=hidden name=duplizieren value=\"\">
	<input type=hidden name=edit value=$edit>
	<input type=hidden name=navid value=$navid>
	<input type=hidden name=vorlage value='$vorlage'>

	<!-- textboxes -->\n\n";


echo '		<h2 class="content_edit_titel"></h2>

<div class="panel panel-default fix-Top panel-edit">
<!--
  <div class="panel-heading">
	<ul class="navTopTop">
		<li><b class="panel-title">'.$ort.'</b></li>
		<li class="mobileOff"><img src="images/'.$morpheus["lan_arr"][$sprache].'.gif"></li>
		<li class="mobileOff"><a href="../index.php?vs=1&cid='.$navid.'&navid='.$navid.'&lan='.$sprache.'" data-title="Vorschau" data-width="1200" data-toggle="lightbox" data-gallery="remoteload"><i class="fa fa-television"></i> '.$MORPTEXT["GLOB-VORSCHAU"].'</a></li>
		<li class="mobileOff">'.$MORPTEXT["CONE-LETZTEBEAR"].': '.$unm.'</li>
		<li class="mobileOff"><a href="content_history.php?id='.$edit.'" data-title="Historie" data-width="1200" data-height="500" data-toggle="lightbox" data-gallery="remoteload"><i class="fa fa-tasks"></i>Historie</a></li>
		'.($muuri ? '
		<li class="muuri"><a href="?edit='.$edit.'&navid='.$navid.'&muuri=off"><i class="fa fa-arrows"></i></a></li>' : '
		<li class="muuri"><a href="?edit='.$edit.'&navid='.$navid.'&muuri=on"><i class="fa fa-exchange"></i></a></li>').'
	</ul>
  </div>
-->
  <div class="panel-body pt1">
	<ul class="navTop">
		<li>'.$select_brick ." " .$select_stelle .'</li>'.'
		<li><input type="submit" name="erstellen" value="einf&uuml;gen" class="btn btn-default"></li>
		<li><button type="button" class="svebut sve" name="erstellen" value="speichern" style="margin: 0em 0; padding: 2px 10px;"><i class="fa fa-save"></i></button></li>
		<li class="internalLink"><i class="fa fa-anchor"></i></li>
	</ul>
  </div>
</div>

';



if ($db == "morp_cms_content") {
	#	if ($sprache > 1) echo "<a name=\"import\" href=\"content_edit.php?edit=$edit&cid=$edit&back=$back&db=content&pos=de&lang=$lang\" title=\"import\">" .ilink() ." de content importieren</a> &nbsp; &nbsp; ";
	#	echo "<!-- [Daten werden in diese Seite kopiert. Originalseite bleibt bestehen, aber vorhandene Daten auf dieser Seite gehen verloren.] --></p>";
}

elseif ($db == "newsletter") {
	echo "<a href=\"newsletter/vorschau.php?edit=$edit&navid=$navid\" rel=\"gb_page_center[640]\" target=_blank> ".$MORPTEXT["GLOB-VORSCHAU"]." " .ilink() ."</a></p><div style=\"margin: -12px 0px 0px 310px; position: relative; height: 30px;\"><a name=\"import\" href=\"javascript:check('link.php?edit=$edit&navid=$navid&db=content&pos=all&lang=$lang&target=newsletter');\" title=\"import\">" .ilink() ." importieren</a> &nbsp;
[".$MORPTEXT["CONE-DATENSEITE"]."<img src=\"images/leer.gif\" width=\"74\" height=\"1\" alt=\"\">".$MORPTEXT["CONE-DATENSEITEVERLOREN"]."]</div>";
}

elseif ($db == "morp_cms_news") {
	# echo "<a href=\"../index.php?vs=1&cid=$edit&lang=$lang&nid=$edit\"  rel=\"gb_page_center[1024]\" target=\"_blank\">vorschau " .ilink() ."</a></p>";
}


echo '

</div>

	<div class="abstand-Content"></div>
	<div class="alert alert-success popup" style="background:#fff;" role="alert"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
<span class="sr-only">Loading...</span></div>

	<div id="mycontent" class="content_edit columns_all">



<span class="copied">
	Copy to clipboard
</span>

<input type="hidden" id="lnk1" value="<a:https://www.url.de:>">
<input type="hidden" id="lnk2" value="</a>">
<input type="hidden" id="ziel" value="">

<div class="exturl">
	<li class="closeExtLinkHeader"><i class="fa fa-close"></i></li>
	<input type="text" id="lnkext" value="https://www.url.de" style="width:80%;"><br/><span class="btn btn-info setexturl">einfügen</span>
</div>

<div class="mailadr">
	<li class="closemailadrHeader"><i class="fa fa-close"></i></li>
	<input type="text" id="mailadr" value="name@url.de" style="width:80%;"><br/><span class="btn btn-info setmailadr">einfügen</span>
</div>



';
if($sprache == 1){
	include '../page/structure_de.inc';
	include '../page/structure_edit_mode_de.inc';
}
else {
	include '../page/structure_en.inc';
	include '../page/structure_edit_mode_en.inc';
}
?>


<?php
echo $structure;
echo '</ul>';
echo $structure_edit_mode;
echo '</ul>

<div class="grid">
';

	echo repl("<!-- direct -->", $direct_link, $textbox);

	echo '</div>


		<!--	<p><button type="submit" class="svebut sve" name="erstellen" value="'.$MORPTEXT["GLOB-SPEICHERN"].'"><i class="fa fa-save"></i></button></p>-->
	</form>
';
//	echo "<br>$show";

if ($tab) echo '<script language="JavaScript" type="text/javascript">
	document.content_edit.submit();
</script>
</div>


';

if ($fckedit) echo '
<script type="text/javascript">
<!--
	document.content_edit.submit();

	setInterval(function(){
		document.location.href="content_edit_live.php?db='.$db.'&edit='.$edit.'&navid='.$navid.'&back='.$back.'";
		parent.parent.GB_hide();
	},1000);
 -->
</script>
';


include("footer.php");
?>

<style>
.ui-sortable-placeholder {
  border: 3px dashed #aaa;
  height: 450px;
  width: 344px;
  background: red;
}
.sitemap, .sitemap_edit {
	position: fixed; width: 300px; max-height: 80%; overflow: auto; top:100px; left: 50%; margin-left: -150px; border: solid 1px #000; z-index: 99999; background: #666; display: none; -webkit-box-shadow: 6px 6px 19px -2px rgba(0,0,0,0.57);-moz-box-shadow: 6px 6px 19px -2px rgba(0,0,0,0.57);box-shadow: 6px 6px 19px -2px rgba(0,0,0,0.57); padding: 4em;
}
.sitemap a, .sitemap li, .sitemap_edit a, .sitemap_edit li {
	color: #fff; line-height: 2em;
}
.closeHeader, .closeExtLinkHeader, .closemailadrHeader { background: #222; list-style: none; height: 38px; text-align: right; border: solid 1px #1997c6; width: 50px; padding: 6px 10px 5px; display: block; position: absolute; right: 0; top: 0;
}
.closeExtLinkHeader, .closemailadrHeader { background: transparent; margin-top: -50px; position: absolute; right: 5%; top: 11%; }
.closeHeader .fa {
	color: #1997c6;
}
.copied {
	position: fixed; top 140px; left: 50%; width: 300px; margin-left: -150px; background: yellow; text-align: center; font-size: 1em; z-index: 99999; padding: 50px 0; opacity: .5; display: none;
}

.exturl, .mailadr{
	position: fixed;
	top:10%;
	left: 0;
	width: 90%;
	margin-left: 5%;
	background: #fff;
	text-align: center;
	font-size: 1em;
	z-index: 99999;
	padding: 50px 0;
	height: 80%;
	padding-top: 20%;
	opacity: .9; display: none;
}
.rahmen { border: none; margin-left: 30px; padding: 2px 10px; border-radius: 0; margin-top: 2px; display: inline-block; background: transparent }
.rahmen i { margin: 0 8px; }
.rahmen2 { border: none; padding: 0 10px; border-radius: 0; margin-top: 2px; }
.delAllLinks { }
</style>


<script>

<?php if($muuri) { ?>
	var grid = new Muuri('.grid', {
		dragEnabled: true,
		dragAxis: 'y',
		threshold: 10,
		action: 'swap',
		distance: 0,
		delay: 100,
		layoutOnResize: true,
		setWidth: true,
		setHeight: true,
		layout: {
			fillGaps: true,
			rounding: false
		},
		sortData: {
			foo: function (item, element) {
				//console.log(item);
			},
			bar: function (item, element) {
				//console.log(77);
			}
		  }
	});


	$( ".gridcheck" ).change(function() {
		grid.refreshItems();
		console.log(99);
	});

	grid.on('synchronize', function () {
		console.log('Synced!');
	});

	grid.on('dragEnd', function (item) {
		$('.sve, .SAVE').css({"background":"red"});
	});
	// grid.refreshSortData();

<?php } else { ?>

	$(".grid table").removeClass("item");

<?php } ?>

	$(document).ready(function() {
		 setFormWidth();
    });
	$( window ).resize(function() {
		setFormWidth();
	});

	function setFormWidth() {
		wW = $(window).width();
		newWidth = wW - 300;

		if(wW > 1000)newSelectWidth = 300;
		else newSelectWidth = 200;

		// pos = $(".navbar-brand").position();

		$("#mycontent textarea.form-control").css({"min-width":newWidth+"px"});
		$("#mycontent .form-control.select").css({"min-width":newSelectWidth+"px"});

	 }


	  $( function() {
/*		$( "#mycontent" ).sortable({
			start: function(e, ui) {
				// puts the old positions into array before sorting
				// var old_position = ui.item.index();
				// console.log(old_position);
			},
			update: function(event, ui) {
				var data = $(this).sortable('serialize');
				// grabs the new positions now that we've finished sorting
				var new_position = ui.item.index();
		//	    console.log(data);
				$('.sve, .SAVE').css({"background":"red"});
		/*
				request = $.ajax({
					url: "UpdatePosContent.php",
					type: "post",
					data: "data="+data+"&id=<?php echo $edit; ?>"
				});
		*/
/*			}
		});
*/
//		$( "#mycontent" ).disableSelection();


/*
grid.hide(6, {onFinish: function (items) {

  console.log('items hidden!');
}});
*/


		$( ".del" ).click(function() {
			ref = $(this).attr("ref");

<?php if($muuri) { ?>
			var order = grid.getItems().map(item => item.getElement().getAttribute('id'));
			MuuriPosition = ($.inArray(ref, order));
			grid.remove(MuuriPosition);
<?php } ?>

			$('.sve, .SAVE').css({"background":"red"});
			$('#'+ref).remove();
		});

	});

/*
	$( "#mycontent" ).sortable({
	  opacity: 0.5
	});
*/

	/*
	$( "#content" ).sortable({
	  revert: true
	});
	*/

	// getData();

$( document ).ready(function() {
	var clipboard = new ClipboardJS('.intLink');

	clipboard.on('success', function(e) {
		 // console.log(e);
		$('.sitemap, .sitemap_edit').hide(250);
		$('.copied').fadeIn(250).delay(500).fadeOut(250);

		res = e["text"].split("Link_Name");
		 // console.log('res: '+res[0]);

		l1 = res[0];
		l2 = res[1];

		$("#lnk1").val(l1);
		$("#lnk2").val(l2);
		ziel = $('#ziel').val();
		 // console.log("ziel: "+ziel);
		if(ziel) insert(l1, l2, ziel);
	});

	clipboard.on('error', function(e) {
		// console.log(e);
		$('.sitemap').hide(250);
	});

	$( ".structureOn" ).click(function() {
		$('.sitemap').fadeIn(500);
		ziel = $(this).attr("ref");
		$("#ziel").val(ziel);
	});

	$( ".internalLink" ).click(function() {
		$('.sitemap_edit').fadeIn(500);
		//ziel = $(this).attr("ref");
		//$("#ziel").val(ziel);
	});


	$( ".externalLink" ).click(function() {
		$('.exturl').fadeIn(500);
		ziel = $(this).attr("ref");
		// console.log(ziel);
		$("#ziel").val(ziel);
	});
	$( ".setexturl" ).click(function() {
		$('.exturl').hide(500);
		url = $('#lnkext').val();
		ziel = $("#ziel").val();
		insert('<a:'+url+':>', '</a>', ziel);
	});

	$( ".bold__text" ).click(function() {
		// $('.pdfmap').fadeIn(500);
		ziel = $(this).attr("ref");
		$("#ziel").val(ziel);
		insert('<strong>', '</strong>', ziel);
	});

	$( ".italic__text" ).click(function() {
		// $('.pdfmap').fadeIn(500);
		ziel = $(this).attr("ref");
		$("#ziel").val(ziel);
		insert('<i>', '</i>', ziel);
	});

	$( ".underline__text" ).click(function() {
		// $('.pdfmap').fadeIn(500);
		ziel = $(this).attr("ref");
		$("#ziel").val(ziel);
		insert('<u>', '</u>', ziel);
	});
	
	$( ".mailLink" ).click(function() {
		$('.mailadr').fadeIn(500);
		ziel = $(this).attr("ref");
		$("#ziel").val(ziel);
	});
	$( ".setmailadr" ).click(function() {
		$('.mailadr').hide(500);
		url = $('#mailadr').val();
		ziel = $("#ziel").val();
		insert('<a:'+url+':>', '</a>', ziel);
	});



	$( ".closeHeader" ).click(function() {
		$('.sitemap').hide(500);
	});
	$( ".closeExtLinkHeader" ).click(function() {
		$('.exturl').hide(500);
	});
	$( ".closemailadrHeader" ).click(function() {
		$('.mailadr').hide(500);
	});

	$('.delAllLinks').click(function () {
		ziel = $(this).attr("ref");
		nm = $('#'+ziel).attr("name");
		data = $('#'+ziel).serialize();
		 console.log(nm);
		// UpdateHtmlDelAnchor.php

		request = $.ajax({
			url: "UpdateHtmlDelAnchor.php",
			type: "post",
			dataType : 'html',
			data: 'data='+data+'&nm='+nm,
			success: function(msg) {
				// console.log(msg);
				$('#'+ziel).val(msg);
				$('.sve, .SAVE').css({"background":"red"});
			}
		});

	});


<?php if($warn) { ?>	$('.sve, .SAVE').css({"background":"red"}); <?php } ?>


	function displayWarn() {
		$('.popup').fadeIn(250).delay(500).fadeOut(250);
		$('.sve, .SAVE').css({"background":"#1997c6"});
	}

	$('.selall').click(function () {
	//    this.select();
		sid = $(this).attr("ref");
		$('#'+sid).select();
		//console.log(sid);
	});

	$( ".fa-save" ).click(function() {
		// console.log("save");
<?php if($muuri) { ?>
		grid.synchronize();
<?php } ?>		displayWarn();
		getData();
	});

	function getData() {
		tinyMCE.triggerSave();
        jQuery(".resume_builder_input").trigger("change");

		var object = {};
		var array = $('#content_edit').serializeArray();
		var data = '';
		var x = 0;

		$.each(array, function(index, item) {
			var itnm = item.name;
			itnm = itnm.split("#");
			itnm = itnm[0];
			if(itnm == "brick") {
				data += '&'+item.name+'='+encodeURIComponent(item.value);
				object[item.name] = item.value;
			}
		});

		// console.log(data);

		request = $.ajax({
			url: "UpdateContent.php",
			type: "post",
			data: 'edit=<?php echo $edit; ?>'+data
		});
	}

//
});
</script>

<script>

	$( ".einfuegen" ).click(function() {
		var lnk1 = $("#lnk1").val();
		var lnk2 = $("#lnk2").val();
		// console.log("paste"+lnk1);

		ziel = $(this).attr("ref");
		// console.log(ziel);

		insert(lnk1, lnk2, ziel);
	});


	$( ".setImageAusrichtung" ).click(function(event) {
		var ausrichtung = $(this).val();
		var ref = $(this).attr("ref");

		 console.log("ausrichtung: "+ausrichtung+" -- "+ref);
		 event.preventDefault();
		// ziel = $(this).attr("ref");
		// console.log(ziel);
		$('#'+ref).val(ausrichtung);
		$('.'+ref).removeClass('btn-primary');
		$('.'+ref).removeClass('btn-danger');
		$('.'+ref).addClass('btn-info');
		$(this).removeClass('btn-info');
		$(this).addClass('btn-danger');
		$('.sve, .SAVE').css({"background":"red"});
		// btn-info

	});



	function insert(aTag, eTag, ziel) {

		var input = document.forms['content_edit'].elements['eingabe'];
		var input = document.getElementById(ziel);

		// console.log(name_element);
		input.focus();
		/* für Internet Explorer */
		if (typeof document.selection != 'undefined') {
			/* Einfügen des Formatierungscodes */
			var range = document.selection.createRange();
			var insText = range.text;
			range.text = aTag + insText + eTag;
			/* Anpassen der Cursorposition */
			range = document.selection.createRange();
			if (insText.length == 0) {
				range.move('character', -eTag.length);
			} else {
				range.moveStart('character', aTag.length + insText.length + eTag.length);
			}
			range.select();
		}
		/* für neuere auf Gecko basierende Browser */
		else if (typeof input.selectionStart != 'undefined') {
			/* Einfügen des Formatierungscodes */
			var start = input.selectionStart;
			var end = input.selectionEnd;
			var insText = input.value.substring(start, end);
			input.value = input.value.substr(0, start) + aTag + insText + eTag + input.value
				.substr(end);
			/* Anpassen der Cursorposition */
			var pos;
			if (insText.length == 0) {
				pos = start + aTag.length;
			} else {
				pos = start + aTag.length + insText.length + eTag.length;
			}
			input.selectionStart = pos;
			input.selectionEnd = pos;
		}
		/* für die übrigen Browser */
		else {
			/* Abfrage der Einfügeposition */
			var pos;
			var re = new RegExp('^[0-9]{0,3}$');
			while (!re.test(pos)) {
				pos = prompt("Einfügen an Position (0.." + input.value.length + "):", "0");
			}
			if (pos > input.value.length) {
				pos = input.value.length;
			}
			/* Einfügen des Formatierungscodes */
			var insText = prompt("Bitte geben Sie den zu formatierenden Text ein:");
			input.value = input.value.substr(0, pos) + aTag + insText + eTag + input.value
				.substr(pos);
		}
	}

<?php if($stelle) { ?>
$(document).ready(function() {
	target =  <?php echo "z_".$stelle; ?>

	// console.log(target);
	if(target) {
		$("html, body").animate({scrollTop: 0}, 0);
		$('html, body').stop().animate({
			scrollTop: $(target).offset().top - 100
		}, 500, 'easeInOutExpo');
	}
  });
<?php } ?>

</script>