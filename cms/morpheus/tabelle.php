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

include("../nogo/config.php");

include("cms_header.php");
include("../nogo/db.php");
dbconnect();

include("../nogo/funktion.inc");
include("cms_navigation.php");

# welche ebene und welche subnav wurde ausgewaehlt
$edit	= $_REQUEST["edit"];
$pos	= $_REQUEST["pos"];
$db		= $_REQUEST["db"];
$cid	= $_REQUEST["cid"];
$save	= $_REQUEST["save"];
$back	= $_REQUEST["back"];
$neu	= $_REQUEST["neu"];
$spalte	= $_REQUEST["spalte"];
$reihe	= $_REQUEST["reihe"];

if ($neu && !$spalte) {
	$spalte = 3;
	$reihe	= 3;
}
elseif (!$spalte || !$reihe) {
	$query  	= "SELECT * FROM tabelle where tabid=$edit";
	$result 	= safe_query($query);
	$row 		= mysqli_fetch_object($result);
	$spalte 	= $row->spalte;
	$reihe		= $row->reihe;
}

if ($save) {
	for($i=1; $i<=$reihe; $i++) {
		for($n=1; $n<=$spalte; $n++) {
			#$tab .= htmlspecialchars($_POST[("td_".$i.$n)]);
			$tab .= $_POST[("td_".$i.$n)];
			if ($n<$spalte) $tab .= "¿";
		}
		if ($i < $reihe) $tab .= "°°";
	}

	if ($neu)	$query  = "insert tabelle ";
	else $query  = "update tabelle ";
	
	$set = "set tabtext='$tab', spalte=$spalte, reihe=$reihe, cid=$cid";
	if (!$neu) $set .= " where tabid=$edit";
	
	$result = safe_query($query .$set);
	if ($neu) {
		$edit	= mysqli_insert_id($mylink);
		unset($neu);
	}
}
	

if ($edit && !$neu) {
	$query  	= "SELECT * FROM tabelle where tabid=$edit";
	$result 	= safe_query($query);
	$row 		= mysqli_fetch_object($result);
	$text 		= $row->tabtext;
	$reihe		= $row->reihe;
	$spalte		= $row->spalte;
}
elseif ($neu) {
	$text 		= "¿¿°°¿¿°°¿¿";
	$spalte		= 3;
	$reihe		= 3;
}

$reihen_inh = explode("°°", $text);

echo "\n\n<div id=content_big class=text>\n<p><b>Tabelle erstellen und editieren</b></p>";

echo "<form method=post>
	<input type=hidden name=save value=1>
	<input type=hidden name=neu value=$neu>
	<input type=hidden name=cid value=$cid>
	<input type=hidden name=back value='$back'>
	<input type=hidden name=db value='$db'>
	<input type=hidden name=pos value=$pos>\n
	Spalten: <input type=text name=spalte value='$spalte' style=\"width:30px;\">\n
	&nbsp; &nbsp; &nbsp; Reihen: <input type=text name=reihe value='$reihe' style=\"width:30px;\">\n
	<input type=hidden name=edit value=$edit><p>\n";	

$x 	= 0;	
$wd = 680/$spalte;

foreach($reihen_inh as $val) {
	$spalten = explode("¿", $val);
	$x++;
	$y = 0;
		
	foreach($spalten as $sp) {
		$y++;
		echo "<textarea name=\"td_$x$y\" style=\"width:".$wd."px; height: 80px;\">$sp</textarea> ";
	}
	echo "<input type=hidden value='break_$x'><p><hr width=\"720\" size=\"1\" noshade align=left>";
}

echo "<p>
		<input type=submit style=\"background-color:#7B1B1B;color:#FFFFFF;font-weight:bold;width:100px;\" name=erstellen value=speichern style=\"width:70;background-color:#BBBBBB;\"></form>
		";

echo "<p><a href=\"content_edit.php?edit=$cid&pos=$pos&tab=$edit&back=$back&db=$db\" title=\"zurück\">" .backlink() ." Tabelle verwalten/editieren beenden</a> &nbsp; [!!! <b>vorher speichern</b>, sonst gehen Änderungen verloren!!!]</p>";
?>

</div>

<?
include("footer.php");
?>