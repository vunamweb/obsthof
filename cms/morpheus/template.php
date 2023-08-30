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

global $sprache;

if ($_REQUEST["sprache"]) {
	$sprache = $_REQUEST["sprache"];
	$_SESSION["sprache"] = $sprache;
}
$sprache = $_SESSION["sprache"];
if (!$sprache) $sprache = "1";

$_SESSION["comeFrom"] = 'template.php';

$vorl_active = ' class="active"';
$redaktion_in = 'in';
include("cms_include.inc");

$edit 	   	= $_REQUEST["edit"];
$del 	   	= $_REQUEST["del"];
$delete	   	= $_REQUEST["delete"];
$neu	   	= $_REQUEST["neu"];
$speichern 	= $_REQUEST["speichern"];
$tname	   	= $_REQUEST["tname"];
$update	   	= $_REQUEST["update"];
$dupl	   	= $_REQUEST["dupl"];
$pos	   	= $_REQUEST["so"];
$visible   = $_GET["visible"];


if($visible) {
	$vis = $_GET["vis"];
	$sql  	= "UPDATE `morp_cms_content` SET ton=$vis WHERE cid=$visible";
	safe_query($sql);

	$sql  	= "UPDATE `morp_cms_content_live` SET ton=$vis WHERE cid=$visible";
	safe_query($sql);
}

if ($neu) {
	$warnung = "<form action=\"template.php\" method=post>
		<p>Bitte geben Sie einen eindeutigen Name f&uuml;r die Text-Vorlage an.</p>
		<p><input type=\"text\" name='tname' value=''></p>
		<p><input type=\"submit\" name=\"speichern\" value=\"speichern\"></p></form>";
}

elseif ($edit) {
	$query 	= "SELECT * FROM `morp_cms_content` WHERE cid=$edit";
	$result = safe_query($query);
	$row 	= mysqli_fetch_object($result);
	$tn 	= $row->vorl_name;

	$warnung = "<form action=\"template.php\" method=post><input type=\"hidden\" name='update' value='$edit'>
		<p>&nbsp;</p>
		<p>Bitte geben Sie einen neuen eindeutigen Name f&uuml;r die Text-Vorlage an.</p>
		<p>&nbsp;</p>
		<p><input type=\"text\" name='tname' value='$tn' size=100></p>
		<p>&nbsp;</p>
		<p>Reihenfolge <input type=\"text\" name='so' value='$pos' size=30></p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p><input type=\"submit\" name=\"speichern\" value=\"speichern\"></p></form>";
}

elseif ($update) {
	$query = "UPDATE `morp_cms_content` SET vorl_name='$tname', `pos`='$pos', edit=1 WHERE cid=$update";
	$result = safe_query($query);

	$query = "UPDATE `morp_cms_content_live` SET vorl_name='$tname', `pos`='$pos', edit=1 WHERE cid=$update";
	$result = safe_query($query);
}

elseif ($dupl) {
	$tn 	= "dupliziert - Bitte Namen ändern";
	$sql  	= "SELECT * FROM `morp_cms_content` WHERE cid=$dupl";
	$res 	= safe_query($sql);
	$row 	= mysqli_fetch_object($res);
	$th 	= $row->theadl;
	$de 	= $row->content;
	$tlink  = $row->tlink;
	$tb		= $row->tb;
	$tid	= $row->tid;
	$timage	= $row->timage;

	$sql	= "INSERT `morp_cms_content` SET vorlage=1, vorl_name='$tn', theadl='$th', content='$de', tid='$tid', tlink='$tlink', tbackground='$tb', timage='$timage', edit=1";
	$res	= safe_query($sql);

	$sql	= "INSERT `morp_cms_content_live` SET vorlage=1, vorl_name='$tn', theadl='$th', content='$de', tid='$tid', tlink='$tlink', tbackground='$tb', timage='$timage', edit=1";
	$res	= safe_query($sql);
}

elseif ($speichern) {
	$query = "INSERT `morp_cms_content` SET vorlage=1, `pos`='$pos', vorl_name='$tname', edit=1";
	$result = safe_query($query);
	$c = mysqli_insert_id($mylink);

	$query = "INSERT `morp_cms_content_live` SET vorlage=1, `pos`='$pos', vorl_name='$tname', edit=1";
	safe_query($query);
}

elseif ($del) {
	$warnung = "<p><font color=#ff0000><b>Wollen Sie die Text Vorlage wirklich l&ouml;schen?</b></font></p>".'
				<span class="button"><a href="?delete='.$del.'">endg&uuml;ltig l&ouml;schen</a></span> <span class="button"><a href="?">nein</a></span>';
}

# l&ouml;schen
elseif ($delete) {
	$query = "DELETE FROM `morp_cms_content` WHERE cid=$delete";
	safe_query($query);
	$query = "DELETE FROM `morp_cms_content_live` WHERE cid=$delete";
	safe_query($query);
	$query = "DELETE FROM `morp_cms_content` WHERE vid=$delete";
	safe_query($query);
	$query = "DELETE FROM `morp_cms_content_live` WHERE vid=$delete";
	safe_query($query);
}


echo "<div>\n<h2>Text-Vorlagen verwalten</h2>";

/*
foreach ($morpheus["lan_arr"] as $key=>$lan) {
	echo '<a href="?sprache='.$key.'"><img src="images/'.$lan.'.gif" alt="" width="16" height="9" border="'. ($key != $sprache ? 0 : 2) .'"></a> &nbsp; &nbsp; ';
}
*/

if ($warnung) die ($warnung ."</div></body></html>");

$id_arr = array();
$query = "SELECT * FROM `morp_cms_content` c, morp_cms_nav n WHERE c.cid=n.navid";
$result = safe_query($query);

while ($row = mysqli_fetch_object($result)) {
	$text  	= $row->content;
	$cid 	= $row->cid;
	$nm  	= $row->name;

	# zuerst check, ob template im text
	if (preg_match("/template/", $text)) $tchk = 1;

	if ($tchk) {
		$tx = explode("##", $text);
		for($i=0; $i <= count($tx); $i++) {
			$txt = $tx[$i];
			if (ereg("template", $txt)) {
				$txt = explode("@@", $txt);
				$tmp = "t".$txt[1];
				$id_arr[$nm] = $txt[1];
				$$tmp .= "$nm, ";
			}
		}
	}
	# # # # _template
}
# print_r($id_arr);

$ct  = 0;

$query = "SELECT * FROM `morp_cms_content` WHERE vorlage=1 ORDER BY vorl_name";
$result = safe_query($query);

echo "<table width=\"100%\" class='autocol'>";
echo "<tr>
	<td colspan=7><b>Name</b></td>
</tr>
";

while ($row = mysqli_fetch_object($result)) {
	$id = $row->cid;
	$tn = $row->vorl_name;
	$pos = $row->pos;
	$visible = $row->ton;

	if (in_array($id, $id_arr)) { $tmp = "t".$id; $reserved = "[<b>iV</b> in <i>".$$tmp."</i>]*"; }
	else unset($reserved);

	echo "<tr>
		<td><a>$id</a></td>
		<td><a href=\"?edit=$id&vorlage=1\"><i class=\"fa fa-cogs\"></i></a> | <a href=\"content_edit.php?edit=$id&vorlage=1\">$tn</a></td>
		<td><a href=\"?vis=".($visible ? 0 : 1)."&visible=$id\"><i class=\"fa fa-eye".($visible ? '' : '-slash')." vis\"></i></a></td>
		<td>$reserved</td>
		<td><a href=\"content_edit.php?edit=$id&vorlage=1\" class=\"button2\"><i class=\"fa fa-pencil-square-o\"></i></a></td>
		<td><a href=\"content_template.php?edit=$id&vorlage=1\" class=\"button2\"><i class=\"fa fa-cogs\"></i></a></td>
		<td><a href=\"template.php?dupl=$id\" class=\"button2\"><i class=\"fa fa-copy\"></i></a></td>
		<td>";
	if (!$reserved) echo "<a href=\"template.php?del=$id\"  class=\"button2\"><i class=\"fa fa-trash-o\"></i></a>";

	echo "</td>
	</tr>";

	if ($ct == 0) 	$ct = 1;		//farbendefenition
	else 			$ct = 0;
}


echo "</table>
<p>&nbsp;<br>
*) in Verwendung</p>";

echo "<p>&nbsp;</p><p><a href=\"template.php?neu=1\" title=\"Neue Text-Vorlage erstellen\">" .ilink() ." NEU</a></p>";
?>

</div>

</body>
</html>