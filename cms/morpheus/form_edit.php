<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #
// print_r($_POST);
$form = 1;
include("cms_include.inc");

$db 	= "morp_cms_form";
$getid 	= "fid";

# get requests
$del	= $_GET["del"];
$edit	= $_REQUEST["edit"];
$save	= $_REQUEST["save"];
$sort	= $_REQUEST["sort"];
$stelle = $_REQUEST["stelle"];
$brick  = $_REQUEST["brickname"];

# # # # # # # !!!!!!!!!!! name einsetzen
$query = "SELECT * FROM morp_cms_form where fid='$edit'";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$ort = $row->fname;
# # # # # # # !!!!!!!!!!! # # # # # # # !!!!!!!!!!!

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

if ($sort) {
	$query = "SELECT * FROM $db where $getid='$edit'";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
	$text = $row->fform;
	$text = explode("##", $text);

	$bA = $text[($stelle-1)]; 	// brick A an position stelle-1 auslesen
	$bB = $text[($sort-1)]; 	// brick B an position sort-1 auslesen
	$text[($stelle-1)] = $bB;	// brick B an neuer pos einsetzen
	$text[($sort-1)] = $bA;		// brick A an neuer pos einsetzen

	foreach($text as $key=>$val) {
		if ($val) $text_ .= $val ."##";
	}
	$set = "set fform='$text_', edit=1 ";
	$query = "update $db " .$set ."where $getid=$edit";
	$result = safe_query($query);
}
elseif ($save) {
	$x = 0;
	$feld_arr = array();
	foreach($_POST as $key=>$val) {
		$key = explode("#", $key);
		$savethis = 1;

		if ($key[0] == "feld") {
			if (($key[1] == "Pulldown" || $key[1] == "Radiobutton") && !$pd) {
				$pd = $val;
				unset($savethis);
			}
			elseif (($key[1] == "Pulldown" || $key[1] == "Radiobutton") && $pd) {
				$n = $key[2];
				$n = explode("_", $n);
				$key[2] = $n[0];
				$val = $pd ."|" .$val;
				$x++;
				unset($pd);
			}
			else $x++;

			$post = $_POST[(repl(" ", "_", $val))];

			if ($stelle != $key[2] && $savethis)
				$form_arr[$key[2]] = $key[1] ."@@$post|" .$val ."##";		  //
			elseif ($brick && $stelle == $x && $savethis)
				$form_arr[$key[2]] = $brick ."@@" ."##" .$key[1] ."@@$post|" .$val ."##";
				// neuer datensatz wird eingefuegt
		}
	}
	// wenn ein brick hinten angefuegt wird
	if ($brick && $stelle > $x) $form_arr[] = $brick ."@@";

	if (count($form_arr) < 1 && $brick) $form_arr[] = $brick ."@@";

	foreach($form_arr as $key=>$val) {
		if ($val) $text .= $val;
	}
	$set = "set fform ='$text', edit=1 ";

	if ($edit) 	$query = "update $db " .$set ."where $getid=$edit";
	else 		$query = "insert $db " .$set;

	#echo "$query<br>";
	$result = safe_query($query);
	if ($edit) protokoll($uid, $db, $edit, "edit");
	else {
		$c = mysqli_insert_id($mylink);
		protokoll($uid, $db, $c, "neu");
	}

}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if ($edit || $del) {
	$query  = "SELECT * FROM $db where $getid=$edit";
	$result = safe_query($query);
	$row 	= mysqli_fetch_object($result);
	$text 	= $row->fform;
	$fname	= $row->fname;

	# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	# # textboxen zusammenstellen und anzahl feststellen
	$text_ = explode("##", $text);
	$counter = 0;

	# $help = "<a href='#' onMouseDown=\"show();\"><img src=\"images/help.gif\" alt=\"Hilfe anzeigen/ausblenden\" border=0></a>";

	foreach ($text_ as $val) {
		if ($val) {
			$counter++;
			if ($counter == $stelle && $del)
				$textbox .= "<p><font color=#ff0000 size=-1 face=Tahoma>Bitte l&ouml;schen durch speichern best&auml;tigen</font> &nbsp; &nbsp; <a href=\"form_edit.php?edit=$edit\">< zur&uuml;ck</a> &nbsp; &nbsp;
					<input type=submit style=\"background-color:#cccccc;color:#FFFFFF;font-weight:bold;width:100px;\" name=erstellen value=speichern style=\"width:70;background-color:#BBBBBB;\"></p>";

			else {
				$val 	= explode("@@", $val);
				$art 	= $val[0];
				$nm 	= $val[1];
				# $txt   	= $val[2];

				unset($tmp_textbox);
				if ($counter > 1) $tmp_textbox .= "&nbsp;<a href=\"form_edit.php?db=$db&edit=$edit&sort=" .($counter-1) ."&stelle=$counter&back=$back\" name=\"eine Position nach oben\" title=\"eine Position nach oben\"><img src=\"images/up.gif\" width=\"9\" height=\"9\" alt=\"\" border=0></a>";

				$tmp_textbox .= "&nbsp;<a href=\"form_edit.php?db=$db&edit=$edit&sort=" .($counter+1) ."&stelle=$counter&back=$back\" name=\"eine Position nach unten\" title=\"eine Position nach unten\"><img src=\"images/down.gif\" width=\"9\" height=\"9\" alt=\"\" border=0></a>&nbsp; position</td></tr></table>";


				if ($art == "Pulldown" || $art == "Radiobutton") {
					$textbox .= "<p>\n\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\" bgcolor=\"#cccccc\" width=500><tr>";
					$textbox .= "<td height=20 width=226><font class='text_verw'> &nbsp; $counter $art</font></td><td width=100><a href=\"form_edit.php?db=$db&edit=$edit&del=1&stelle=$counter\" class=del name=\"Dieses Feld l&ouml;schen\" title=\"Dieses Feld l&ouml;schen\"><img src=\"images/delete_w.gif\" alt=\"Dieses Feld l&ouml;schen\" border=\"0\"></a></td><td width=100>";

					$textbox .= $tmp_textbox;
					$txt = explode("|", $nm);
					$textbox .= "<table><tr>
							<td class='feld' width=80>$art: </td>
							<td><input type='text' name=\"feld#$art#" .$counter ."_a\" style=\"width:180px\" value=\"$txt[1]\"></td></tr>
						<tr>
							<td class='feld' valign=top>Auswahl: </td>
							<td><textarea cols=40 rows=8 name=\"feld#$art#" .$counter ."_b\">$txt[2]</textarea></td>
						</tr></table>";
				}

				else {
					$nm = explode("|", $nm);
					$pf = $nm[0];
					$nm = $nm[1];
					if ($pf) $sel = "checked";
					else $sel = "";

					$textbox .= "<p>\n\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\" bgcolor=\"#cccccc\" width=500><tr>";
					$textbox .= "<td height=20 width=226><font class='text_verw'> &nbsp; $counter $art</font></td><td width=100><a href=\"form_edit.php?db=$db&edit=$edit&del=1&stelle=$counter\" class=del name=\"Dieses Feld l&ouml;schen\" title=\"Dieses Feld l&ouml;schen\"><img src=\"images/delete_w.gif\" alt=\"Dieses Feld l&ouml;schen\" border=\"0\"></a></td><td width=100>";

					$textbox .= $tmp_textbox;

					$textbox .= "<font class='feld'></font><input type='text' name=\"feld#$art#$counter\" style=\"width:180px\" value=\"$nm\"> &nbsp; &nbsp; &nbsp;
					<input type=\"checkbox\" name=\"" .str_replace(" ", "_", $nm) ."\" id=\"" .str_replace(" ", "_", $nm) ."\" value=\"1\" $sel> Eingabe-Pflichtfeld";
				}


				}
			}
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
$select_brick = "\n<select name=brickname style=\"width:200;\">\n<option value=''>Bitte Formularfeld w&auml;hlen</option>";
$dir_arr = array("Eingabefeld", "Textfeld", "Pulldown", "Checkbox", "Radiobutton");

foreach($dir_arr as $name) {
	$select_brick .= "<option value='$name'>$name</option>\n";
}
$select_brick .= "</select>\n";
// _select
//

//
// stelle select mit counter zusammen
if (!$counter) $counter = 1;
$select_stelle = "\n<select name=stelle style=\"width:50;\">\n<option value='$counter'>$counter</option>\n";

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
echo "\n\n<div id=vorschau>\n<p class=text><a href=\"formular.php\" title=\"zur&uuml;ck\">" .backlink() ." zur&uuml;ck</a></p>";
echo "\n\n<p class=text><b><font color=#cccccc></font>Formulare erstellen und editieren</b></p>";

#if (!$descr) $descr = "Pflichtfeld";

echo "\n\n<form method=post action=\"form_edit.php\" name=content_edit>
	<input type=hidden name=save value=1>
	<input type=hidden name=edit value=$edit>\n\n

	<!-- textboxes -->\n\n";

	echo $select_brick ." " .$select_stelle ."<input type=submit style=\"background-color:#cccccc;color:#FFFFFF;font-weight:bold;width:100px;\" name=erstellen value=einf&uuml;gen style=\"width:60;background-color:#BBBBBB;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input type=submit style=\"background-color:#cccccc;color:#FFFFFF;font-weight:bold;width:100px;\" name=erstellen value=speichern style=\"width:70;background-color:#BBBBBB;\"></div>

	<div id=content>\n\n";

	echo $textbox;

	echo "\n\n<!-- textboxes -->\n\n
	<!-- <input type=submit style=\"background-color:#cccccc;color:#FFFFFF;font-weight:bold;width:100px;\" name=erstellen value=speichern style=\"width:70;background-color:#BBBBBB;\"> -->
	</form>
		";
echo "<br>$show";

?>

</div>

<?
include("footer.php");
?>