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


function letsgo($id_arr, $db, $arr, $rebuild) {
	global $is_tabelle;
	$is_tabelle = array();

	if ($rebuild) dbconnect_live();
	else dbconnect();
	$cont_arr = array();

	ob_end_flush();
	echo "<b>starte auslesen der datenbank.....</b><p>";
	flush();

	foreach($id_arr as $val) {
		$query = "SELECT * FROM `$db` where $arr[1]='$val'";
		$res   = safe_query($query);
		$x = 0;
		$ct = count($arr);
		$row = mysqli_fetch_object($res);

		#echo "$val wird gelesen<br>";
		#flush();

		$tmp = "set ";
		if ($db != "delete") {
			foreach($arr as $val_) {
				$x++;
				$inp = $row->$val_;
				$inp = repl("'", "&rsquo;", $inp);
				$tmp .= $val_ ."='" .$inp ."'";
				if ($x < $ct) $tmp .= ", ";
			}
		}
		else $tmp = $row->query;

		# sind tabellen eingefuegt? diese muessen extra aktualisiert werden
		if ($db == "morp_cms_content" || $db == "coach") {
			if (isin("tabelle@@", $row->text)) {
				$tab = explode("##", $row->text);
				foreach($tab as $brick) {
					if (isin("tabelle@@", $brick)) {
						$brick = explode("@@", $brick);
						#echo "YES!$brick[1]<br>";
						$is_tabelle[] = $brick[1];
						#print_r($is_tabelle);
					}
				}
			}
		}
		# _tabellen
		# echo $tmp;
		$cont_arr[] = $tmp;

		####!!!!!!!! hier edit und neu auf 0 setzen !!!!!!!!
		if ($db != "delete") $query = "update $db set edit=0 where $arr[1]='$val'";
		else $query = "delete from `$db` where $arr[1]='$val'";
		#echo $query;
		$res   = safe_query($query);
	}

	echo "<p><b>Datenbank wurde erfolgreich ausgelesen</b></p>";
	flush();

	 # # # # # # # # #  # # # # # # # # #  # # # # # # # # #  # # # # # # # # #
	 # # # # # # # # #  # # # # # # # # #  # # # # # # # # #  # # # # # # # # #
	 # # # # # # # # #  # # # # # # # # #  # # # # # # # # #  # # # # # # # # #

	if ($rebuild) dbconnect();
	else dbconnect_live();

	$x = 0;
	foreach($id_arr as $val) {
		if ($db != "delete") {
			$query = "SELECT * FROM $db where $arr[1]='$val'";
			$res   = safe_query($query);
			# echo "<br>anzahl: ".mysqli_num_rows($res)."<br>";
			if (mysqli_num_rows($res) > 0) $upd = "update";
			else $upd = "insert";

			$query = $upd ." `$db` " .$cont_arr[$x];
			if ($rebuild) $query .= ", edit=0";
			if ($upd == "update") $query .= " where $arr[1]='$val'";
			# echo $query;
		}
		else $query = $cont_arr[$x];
		$result = safe_query($query);

		#echo "$val wird geschrieben<br>";
		#flush();

		$x++;
	}
}

include("cms_header.php");
include("funktion.php");
include("cms_navigation.php");
include_once("db.php");
include_once("db_live.php");

global $is_tabelle;

echo '<div id=content_big class=text>';

$id_arr = array();

$rebuild = $_REQUEST["rebuild"];
if ($rebuild) {
	$db = "morp_cms_content";
	$id_arr[] = $rebuild;
}

else {
	foreach($_POST as $key=>$val) {
		if ($key == "db") $db = $val;
		elseif ($key[0] == "x") $id_arr[] = $val;
	}
}

include("vero_fkt.php"); # array $arr wird in bezug auf db geladen

letsgo($id_arr, $db, $arr, $rebuild);

// tabellen werden in einer eigenen tabelle verwaltet.
// diese muessen aber mit dem content ebenfalls hochgeladen werden

if (count($is_tabelle)>0) {
	echo "start<br>";
	# $id_arrN = array();
	if ($rebuild) dbconnect_live();
	else dbconnect();

	foreach($is_tabelle as $val) {
		$query = "SELECT * FROM `tabelle` where tabid='$val'";
		$res   = safe_query($query);
		$x = mysqli_num_rows($res);
		if ($x > 0) {
			while ($row = mysqli_fetch_object($res)) {
				$id_arrN[] = $row->tabid;
			}
		}
		# print_r($id_arrN);
	}

	$arr = array("tabtext", "tabid", "spalte", "reihe", "cid");
	letsgo($is_tabelle, "tabelle", $arr, $rebuild);
}
// _tabelle updaten
//

if ($db != "delete") echo "<p><b>Datenbank wurde erfolgreich auf dem Liveserver veröffentlicht</b></p>";
else echo "<p><b>Daten wurden erfolgreich auf dem Liveserver gelöscht</b></p>";
echo "<p><a href=\"veroeffentlichen_liste.php?db=$db\">" .backlink() ." zur&uuml;ck</a>";

?>

<?
include("footer.php");
?>