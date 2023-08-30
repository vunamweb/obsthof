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

include("cms_header.php");
include("db.php");
include_once("db_live.php");
dbconnect();

include("login.php");
include("funktion.php");
include("cms_navigation.php");

$db 	 = $_REQUEST["db"]; if (!$db) $db = "morp_cms_content";

include("vero_fkt.php"); # array $arr wird in bezug auf db geladen

echo '<div id=content_big class=text><p>Geänderte Inhalte auf dem <b>Liveserver veröffentlichen</b></p>';

echo "<form name='set_db' method='post'><select name='db' onChange=\"document.set_db.submit()\" size=7 style=\"width:300px;\">
		<option value='content' $co_s>seiten-inhalte (content) live stellen</option>
		<option value='nav' $na_s>navigation, schlüsselwörter, seiten-beschreibung</option>
		<option value='news' $ne_s>news aktualisieren</option>
		<option value='kurs_art' $ku_s>kurse</option>
		<option value='seminar' $se_s>seminare</option>
		<option value='form' $fm_s>formulare aktualisieren</option>
		<option value='delete' $de_s>gelöschte seiten auf liveserver löschen</option>
	</select></form>";

$live = '<form method=post action="veroeffentlichen.php" name=live><input type="submit" name="live setzen" value="ver&ouml;ffentlichen"><input type=hidden name=db value="' .$db .'"><br>&nbsp;';

#
# folgende db's werden erst offline behandelt und dann veroeffentlicht
#
# content
# nav
# kurs_art
# delete (navigation delete)
# seminar
#

if ($db == "seminar" || $db == "kurs_art") $query = "SELECT * FROM $db where edit=1";
elseif ($db != "delete") $query = "SELECT * FROM $db c, morp_cms_protokoll z, morp_cms_user u WHERE
									c.$arr[1]=z.id AND
									u.uid=z.uid AND
									z.db='$db' AND
									c.edit=1
									order by z.prid DESC
									";
else $query = "SELECT * FROM `$db`";
# echo $query;
$res   = safe_query($query);
if (mysqli_num_rows($res) > 0) echo $live;

$col = array("#EFECEC", "#FFFFFF");
$ct  = 0;

echo "<table cellspacing=2 cellpadding=2>";

$tmp_arr = array();

while ($row = mysqli_fetch_object($res))	{
	#$tmp = "set ";
	#foreach($na_arr as $val) $tmp .= $val ."='" .$row->$val ."', ";
	$anzeige = $row->$arr[0];
	$id		 = $row->$arr[1];
	$chk	 = $id;

	if (!in_array($id, $tmp_arr)) {
		$tmp_arr[] = $id;
		if ($db == "morp_cms_content" || $db == "nav") {
			$que = "SELECT * FROM `morp_cms_nav` where navid=$id";
			$re  = safe_query($que);
			$ro = mysqli_fetch_object($re);
			$par = $ro->parent;
			$anzeige = $ro->name;
			$pfad = $ro->navid;
			if ($par) {
				for ($n=0; $n<4;$n++) {
					if ($par) {
						$que = "SELECT * FROM `morp_cms_nav` where navid=$par";
						$re  = safe_query($que);
						$ro = mysqli_fetch_object($re);
						$par = $ro->parent;
						$anzeige = $ro->name .'|'.$anzeige;
						$pfad = $ro->navid.'_'.$pfad ;
					}
				}
			}
		}

		if ($chk) $ch = explode("_", $chk);
		$c = 1;
		unset($vors);

		foreach($ch as $n) {
			$c++;
			$vors .= "p".$c."=".$n."&";
		}
		$vors .= "cid=$n";

		if ($db == "morp_cms_content") $anzeige = $anzeige.'</a></td><td width=80><!-- <a href="../frameset.php?'.$vors.'" target="_blank">vorschau</a> --></td><td><a href="veroeffentlichen.php?rebuild='.$id.'">wiederherstellen</a>';

		elseif ($db == "termine") {
			$que = "SELECT * FROM termine t, termin_abt ta, termin_liste tl where t.tid=$id AND t.tlid=tl.tlid AND tl.taid=ta.taid";
			$re  = safe_query($que);
			$ro = mysqli_fetch_object($re);
			$abt = $ro->abt;
			$so = $ro->sort;
			$tn = $ro->tname;
			$anzeige = "$abt - $tn - $so";
		}

		#else unset($neu);
		#if ($neu) $neu = "NEU!";
		#echo "<input type=checkbox name=\"x_$id\" value=\"$id\">$anzeige &nbsp; &nbsp; <b>$neu</b><br>";

		echo "<tr bgcolor=$col[$ct]><td width=20 valign=top><input type=checkbox name=\"x_$id\" value=\"$id\" style=\"border: 0; height:16px;\" checked></td><td width=260 valign=top>$anzeige</td><td width=20>&nbsp;".$row->uname."<!-- ".$row->prid." --></td></tr>";

		if ($ct == 0) $ct = 1;		//farbendefenition
		else $ct = 0;
	}
}

echo "</table><p><a href=\"index.php\">" .backlink() ." zur&uuml;ck</a>";

?>

</form>
<?
include("footer.php");
?>