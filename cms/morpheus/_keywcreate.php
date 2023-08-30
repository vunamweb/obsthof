<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

include("cms_include.inc");
include("stopwords_de.php");
include("stopwords_en.php");
ob_start();

// setlocale(LC_CTYPE, 'de_DE@euro', 'de_DE', 'de', 'ge');

/*
$sql = "REPAIR TABLE `morp_cms_content` , `k_desc`, `morp_suche_count`, `k_title`, `morp_cms_nav`, `morp_cms_news`, `morp_cms_pdf`, `pdf_group`, `product`, `productkat`";
$res = safe_query($sql);

$sql = "OPTIMIZE TABLE `morp_cms_content` , `k_desc`, `morp_suche_count`, `k_title`, `morp_cms_nav`, `morp_cms_news`, `morp_cms_pdf`, `pdf_group`, `product`, `productkat`";
$res = safe_query($sql);
*/

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# jetzt geht es los!!!!!!!!!
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

# ersetzungstabelle f&uuml;r sonderzeichen etc
global $locSearch, $locReplace, $replace;

$locSearch[] = "=ó=";
$locSearch[] = "=“=";
$locSearch[] = "=„=";
#$locSearch[] = "=ü=";
$locSearch[] = "=&uuml;=";
#$locSearch[] = "=ß=";
// $locSearch[] = "=([0-9/.,+-]*\s)=";
$locSearch[] = "=([^A-Za-z0-9äöüÄÖÜß])=";
$locSearch[] = "= +=";
$locSearch[] = "=/.=";

$locReplace[] = "o";
$locReplace[] = "";
$locReplace[] = "";
#$locReplace[] = "ue";
$locReplace[] = "ue";
#$locReplace[] = "ss";
#$locReplace[] = " ";
$locReplace[] = " ";
$locReplace[] = " ";
$locReplace[] = " ";

# stopwoerter in de und en
$search_de[] = "=(\s[A-Za-z0-9]{1,3})\s=";
$search_de[] = "= " . implode(" | ", $stopwords["de"]) . " =i";
$search_de[] = "= +=";

$search_en[] = "=(\s[A-Za-z0-9]{1,3})\s=";
$search_en[] = "= " . implode(" | ", $stopwords["en"]) . " =i";
$search_en[] = "= +=";

$replace[] = " ";
$replace[] = " ";
$replace[] = " ";


$que = "DELETE FROM `morp_suche_keyw` WHERE 1";
safe_query($que);
$que = "DELETE FROM `morp_suche_count` WHERE 1";
safe_query($que);

//$STARTZEIT = microtime_float();

echo '<div id="content_big">';
ob_end_flush();
echo "<p><b>starte auslesen der datenbank.....</b></p>";

flush();

function konvertiere ($text, $search) {
	global $locSearch, $locReplace, $replace;

	$string = trim((stripslashes(strip_tags($text))));
	$string = preg_replace($locSearch, $locReplace, $string);
	$string = " " . str_replace(" ", "  ", $string) . " ";
	$string = trim(preg_replace($search, $replace, $string));

	return $string;
}

function set_keyw ($string, $db, $primary, $lang, $art, $navid, $stid="", $ngid="") {
	# ist das keyword vorhanden?
	global $mylink;

	$string = trim($string);
	$sql	= "SELECT kid FROM `morp_suche_keyw` WHERE keyw = '$string'";
	$res	= safe_query($sql);
	$x		= mysqli_num_rows($res);

	if ($x > 0) {
		# wenn ja, sprache und art kennzeichnen
		$row = mysqli_fetch_object($res);
		$kid = $row->kid;
		$que = "update `morp_suche_keyw` set $lang=1 WHERE kid=$kid";
		safe_query($que);
	}
	else {
		# wenn nein, sprache und art kennzeichnen und keyword einsetzen
		$que = "insert `morp_suche_keyw` set `keyw`='$string', $lang='1'";
		safe_query($que);
		$kid = mysqli_insert_id($mylink);
	}

	#echo $que; echo "<br>";


	# ist dieses keyword im zusammenhang mit der navID schon vorhanden? wenn ja, zaehler hochsetzen => erhoehung der gewichtung
	$anz	= "anz".$lang;
	$sql	= "SELECT ".$anz.", sid FROM `morp_suche_count` WHERE kid='$kid' AND navid='$navid' AND art=".$art."";
	$res	= safe_query($sql);
	$x		= mysqli_num_rows($res);

	if ($stid) $set = ", stid=".$stid;
	if ($ngid) $set = ", nid=".$navid.", ngid=".$ngid;
	if ($x > 0) {
		# wenn ja, zaehler erhoehen
		$row = mysqli_fetch_object($res);
		$id  = $row->sid;
		$an  = $row->$anz;
		$an++;
		$que = "update `morp_suche_count` set ".$anz."=".$an.$set." WHERE sid=".$id;
		safe_query($que);
	}
	else	{
		$que = "insert `morp_suche_count` set ".$anz."=1, navid='$navid', kid='$kid', art=$art".$set;
		safe_query($que);
	}

	// if($art==2) echo $que; echo "<br>".$navid."<br>";
}

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

// es geht los!

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// CONTENT

$ausnahme = " AND ( n.navid <> 8 AND n.navid <> 7 ) ";

$query 	= "SELECT * FROM `morp_cms_content` c, `morp_cms_nav` n WHERE c.navid=n.navid AND n.sichtbar=1 $ausnahme ORDER BY c.navid";
$result = safe_query($query);

echo "Anzahl Gesamt: ".mysqli_num_rows($result);

$query 	= "SELECT * FROM `morp_cms_content` c, `morp_cms_nav` n WHERE c.navid=n.navid AND n.sichtbar=1 $ausnahme ORDER BY c.navid";
#$query 	= "SELECT * FROM `morp_cms_content` c, nav n WHERE c.navid=n.navid AND n.sichtbar=1 ORDER BY c.navid LIMIT 0,2";

flush();
$result = safe_query($query);
$oldid = 0;


while ($row = mysqli_fetch_object($result)) {
	echo $de 	= get_raw_text(($row->content));
	$n_de 	= ($row->name);
#	$id 	= $row->cid;
	echo	$id 	= $row->navid;

echo " -- ";
flush();

	echo $string = konvertiere ($de, $search_de);
	$arr 	= explode(" ", $string);
	// print_r($arr);


	# # # # # # # # # # # # KEYWORDS
	if ($arr) {
		foreach ($arr as $word) {
			if(intval($word) > 0) { 
				//echo $word.'<br>';
			}
			else if(trim($word)) { echo $word.'<br>'; set_keyw (trim($word), "", "", $morpheus["lan_arr"][$row->lang], 1, $id); }
		}
	}

	if($id != $oldid) {
		$oldid = $id;
		# auswertung titel / artikel bezeichnung deutsch
		$string = konvertiere ($n_de, $search_de);
		$arr 	= explode(" ", $string);

		if ($arr) {
			foreach ($arr as $word) {
				if(intval($word) > 0) {
				//	echo $word.'<br>';
				}
				else if(trim($word)) { echo $word.'<br>'; set_keyw (trim($word), "", "", $morpheus["lan_arr"][$row->lang], 1, $id); }
			}
		}
	}

}

/*



echo "<h4>NEWS</h4>";

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// NEWS

$query 	= "SELECT ntitle, ntext, nid, n.ngid, nlang FROM `morp_cms_news_group` ng, `morp_cms_news` n WHERE ng.ngid=n.ngid AND n.sichtbar=1";
$result = safe_query($query);
$oldid = 0;

while ($row = mysqli_fetch_object($result)) {
	$de 	= strip_tags(($row->ntext));
	$n_de 	= strip_tags($row->ntitle);
#	$id 	= $row->cid;
	echo	$id 	= $row->nid;

	// echo " - $de $n_de - ";
	echo " -- ";
flush();

	$string = konvertiere ($de, $search_de);
	$arr 	= explode(" ", $string);
	// print_r($arr);

	# # # # # # # # # # # # KEYWORDS
	if ($arr) {
		foreach ($arr as $word) {
			if(trim($word)) set_keyw (trim($word), "", "", $morpheus["lan_arr"][$row->nlang], 2, $row->nid, 0, $row->ngid);
		}
	}

	if($id != $oldid) {
		$oldid = $id;
		# auswertung titel / artikel bezeichnung deutsch
		$string = konvertiere ($n_de, $search_de);
		$arr 	= explode(" ", $string);

		if ($arr) {
			foreach ($arr as $word) {
				if(trim($word)) set_keyw (trim($word), "", "", $morpheus["lan_arr"][$row->nlang], 2, $row->nid, 0, $row->ngid);
			}
		}
	}
}
*/








#@mail('post@pixel-dusche.de', 'KEYWORD CREATE ::: biodentis', date("d.m-Y H:i"));

#echo '<script type="text/javascript">document.location.href=\'?last='.$last.'\';</script>';
#echo "<p><a href=\"_keyw.php?next=1\">weiter</a></p>";
?>

<p><br><b>fertig!!!</b>

</body>
</html>
