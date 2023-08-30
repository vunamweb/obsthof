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

include("cms_include.inc");


$edit	= $_REQUEST["edit"];
$pos	= $_REQUEST["pos"];
$db	 	= $_REQUEST["db"];
$back	= $_REQUEST["back"];
$parent = $_REQUEST["parent"];
$ebene 	= $_REQUEST["ebene"];

$cid 	= $_REQUEST["cid"];
$p2 	= $_REQUEST["p2"];
$p3 	= $_REQUEST["p3"];
$p4 	= $_REQUEST["p4"];
$p5 	= $_REQUEST["p5"];

$abt 	= $_REQUEST["abt"];
$pos 	= $_REQUEST["pos"];
$tid 	= $_REQUEST["tid"];
$liste 	= $_REQUEST["liste"];
$de		= $_REQUEST["de"];

$sprache= $_SESSION["sprache"];
$sprach_back = $_SESSION["sprache"];
if ($de || !$sprache) $sprache = 1;

$targ	= $_REQUEST["target"];

$nid 	= $_REQUEST["nid"];
$ngid 	= $_REQUEST["ngid"];
$ng 	= $_REQUEST["ng"];

$navid 	= $_REQUEST["navid"];
$vorlage= $_REQUEST["vorlage"];
$ganzeseite= $_REQUEST["ganzeseite"];

$navLink= $_REQUEST["navLink"];

# navigation verschieben
$ebenenew 	= $_REQUEST["ebenenew"];

if (!$ebene) {
	$ebene = 1;
	$parent = 0;
}

echo "<div id='content_big' class='text'>\n<p><b>W&auml;hlen Sie bitte die Zielseite</b></p>";

if ($ebenenew) {
	$bck 	= repl(";;", "&", $back);
	$bck 	= repl(";:;", "=", $bck);
	echo "<p><a href=\"navigation.php?sprache=$sprach_back&$bck\" title=\"zur&uuml;ck\">" .backlink() ." zur&uuml;ck zur content-pflege</a></p>";
}

elseif($navLink) echo "<p><a href=\"nav_edit.php?edit=$navLink\" title=\"zur&uuml;ck\">" .backlink() ." zur&uuml;ck zur navigations-verwaltung</a></p>";

elseif($navid) 	echo "<p><a href=\"content.php?edit=$edit\" title=\"zur&uuml;ck\">" .backlink() ." zur&uuml;ck zur template-verwaltung</a></p>";

elseif ($back || $targ)
				echo "<p><a href=\"content_edit.php?edit=$edit&db=$db&back=$back&target=$targ\" title=\"zur&uuml;ck\">" .backlink() ." zur&uuml;ck zur content-pflege</a></p>";

elseif($tid) 	echo "<p><a href=\"termine.php?db=termine&liste=$liste&tid=$tid&abt=$abt\" title=\"zur&uuml;ck\">" .backlink() ." zur&uuml;ck zur termin liste</a></p>";

elseif($nid) 	echo "<p><a href=\"news.php?edit=$nid&ngid=$ngid\" title=\"zur&uuml;ck\">" .backlink() ." zur&uuml;ck zur news verwaltung</a></p>";

elseif($ng) 	echo "<p><a href=\"news.php?edit=$ngid\" title=\"zur&uuml;ck\">" .backlink() ." zur&uuml;ck zur news verwaltung</a></p>";



if ($ebene > 1) echo "<p><a href=\"javascript:history.back();\">^eine ebene zur&uuml;ck</a></p>";
else echo "<p>&nbsp;</p>";

echo '<table class="autocol p20">';

$query  = "SELECT * FROM `morp_cms_nav` WHERE parent=$parent AND ebene=$ebene AND lang=$sprache ORDER BY `sort`";
$result = safe_query($query);
# echo mysqli_num_rows($result);

while ($row = mysqli_fetch_object($result)) {
	$id = $row->navid;
	$nm = $row->name;
	$eb = $row->ebene;
	$pa = $row->parent;

	if ($ebene == 1) $p2 = $id;
	elseif ($ebene == 2) $p3 = $id;
	elseif ($ebene == 3) $p4 = $id;
	elseif ($ebene == 4) $p5 = $id;

	if ($db == "morp_cms_content") echo "<tr>
		<td width=20><a href=\"link.php?de=$de&sprache=$sprach_back&ebene=" .($eb+1) ."&target=$targ&parent=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&edit=$edit&pos=$pos&db=$db&back=$back\"><img src=\"images/plus.gif\" border=0></a></td>
		<td width=200>$nm</td>
		<td><a href=\"content_edit.php?de=$de&sprache=$sprach_back&ebene=$eb&target=$targ&parent=$pa&cid=$id&link=$edit&edit=$edit&pos=$pos&db=$db&back=$back\">" .ilink() ." diese Quelle w&auml;hlen</a></td>
	</tr>";

	elseif ($db == "template") echo "<tr>
		<td width=20><a href=\"link.php?sprache=$sprache&ebene=" .($eb+1) ."&target=$targ&parent=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&edit=$edit&pos=$pos&db=$db\"><img src=\"images/plus.gif\" border=0></a></td>
		<td width=200>$nm</td>
		<td><a href=\"content_edit.php?sprache=$sprache&ebene=$eb&target=$targ&parent=$pa&cid=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&link=$edit&edit=$edit&pos=$pos&db=$db\">" .ilink() ." dieses Template w&auml;hlen</a></td>
	</tr>";

	elseif ($db == "content_template") echo "<tr>
		<td width=20><a href=\"link.php?sprache=$sprache&vorlage=$vorlage&ebene=" .($eb+1) ."&target=$targ&parent=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&edit=$edit&pos=$pos&db=$db\"><img src=\"images/plus.gif\" border=0></a></td>
		<td width=200>$nm</td>
		<td><a href=\"content_template.php?edit=$edit&ilink=$id&vorlage=$vorlage\">" .ilink() ." dieses Template w&auml;hlen</a></td>
	</tr>";

	elseif ($ganzeseite) echo "<tr>
		<td width=20><a href=\"link.php?sprache=$sprache&ganzeseite=1&ebenenew=1&ebene=" .($eb+1) ."&parent=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&back=$back\"><img src=\"images/plus.gif\" border=0></a></td>
		<td width=200>$nm</td>
		<td><a href=\"navigation.php?sprache=$sprache&pageget=$id&$bck\">" .ilink() ." diese Seite w&auml;hlen</a></td>
	</tr>";

	elseif ($ebenenew) echo "<tr>
		<td width=20><a href=\"link.php?sprache=$sprache&ebenenew=1&ebene=" .($eb+1) ."&parent=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&back=$back\"><img src=\"images/plus.gif\" border=0></a></td>
		<td width=200>$nm</td>
		<td><a href=\"navigation.php?sprache=$sprache&ordnerget=$id&$bck\">" .ilink() ." diese Quelle w&auml;hlen</a></td>
	</tr>";

	elseif ($tid) echo "<tr>
		<td width=20><a href=\"link.php?sprache=$sprache&ebene=" .($eb+1) ."&parent=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&tid=$tid&abt=$abt&db=$db&liste=$liste&pos=$pos\"><img src=\"images/plus.gif\" border=0></a></td>
		<td width=200>$nm</td>
		<td><a href=\"termine.php?sprache=$sprache&ebene=$eb&parent=$pa&cid=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&tid=$tid&abt=$abt&db=$db&link=1&liste=$liste&pos=$pos\">" .ilink()	 ." diese Quelle w&auml;hlen</a></td>
	</tr>";

	elseif ($nid) echo "<tr>
		<td width=20><a href=\"link.php?sprache=$sprache&ebene=" .($eb+1) ."&parent=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&nid=$nid&ngid=$ngid\"><img src=\"images/plus.gif\" border=0></a></td>
		<td width=200>$nm</td>
		<td><a href=\"news.php?sprache=$sprache&ebene=$eb&parent=$pa&cid=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&edit=$nid&ngid=$ngid\">" .ilink() ." diese News w&auml;hlen</a></td>
	</tr>";

	elseif ($ng) echo "<tr>
		<td width=20><a href=\"link.php?sprache=$sprache&ebene=" .($eb+1) ."&parent=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&ng=$ng&ngid=$ngid\"><img src=\"images/plus.gif\" border=0></a></td>
		<td width=200>$nm</td>
		<td><a href=\"news.php?sprache=$sprache&ebene=$eb&parent=$pa&cid=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&edit=$ngid\">" .ilink() ." diese News w&auml;hlen</a></td>
	</tr>";

	elseif ($navLink) echo "<tr>
		<td width=20><a href=\"link.php?sprache=$sprache&ebene=" .($eb+1) ."&parent=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&navLink=$navLink\"><img src=\"images/plus.gif\" border=0></a></td>
		<td width=200>$nm</td>
		<td><a href=\"nav_edit.php?sprache=$sprache&cid=$id&edit=$navLink\">" .ilink() ." diese Seite w&auml;hlen</a></td>
	</tr>";

	elseif ($navid) echo "<tr>
		<td width=20><a href=\"link.php?de=$de&sprache=$sprache&ebene=" .($eb+1) ."&parent=$id&p2=$p2&p3=$p3&p4=$p4&p5=$p5&navid=$navid\"><img src=\"images/plus.gif\" border=0></a></td>
		<td width=200>$nm</td>
		<td><a href=\"link_template.php?cid=$id&edit=$navid\">" .ilink() ." diese Quelle w&auml;hlen</a></td>
	</tr>";
}

?>
</table>
</div>

<?php
	include("footer.php");
?>