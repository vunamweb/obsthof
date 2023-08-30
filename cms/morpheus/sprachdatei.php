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

$myauth = 40;
$config_in = 'in';
$conf1_active = ' class="active"';

include("cms_include.inc");
	
$db   = "morp_sprachdatei";
$edit = $_REQUEST["edit"];
$save = $_REQUEST["save"];
$neu  = $_REQUEST["neu"];
$del  = $_REQUEST["del"];
$del_ = $_REQUEST["del_"];

$suche	= $_REQUEST["suche"];
$s_art 	= $_REQUEST["s_art"];
$admin	= 1;

echo "<div id=content_big>";

if ($del && $admin) {
	$nm  = $_REQUEST["nm"];
	echo '<p>&nbsp;</p>
		<p>Sind Sie sich sicher, dass sie den Lexikoneintrag <b>'.$nm.'</b> löschen wollen?</p>
		<p>&nbsp; &nbsp; &nbsp; &nbsp; <a href="?del_=' .$del .'">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?">Nein</a></p></body></html>';
	die();
}
elseif ($del_) {
	$query = "delete from $db where id=$del_";
	$result = safe_query($query);
	# protokoll($uid, $db, $del_, "del");
}
elseif ($save) {
	foreach($morpheus["lan_arr"] as $lang)	{
		$tmp  = $_POST[$lang];
		$set .= $lang."='$tmp', ";
	}
	
	$bez 	= $_POST["bez"];
	
	if ($neu) {
		$query = "insert $db set $set bez='$bez'";
		unset($neu);
	}
	elseif ($edit) {
		$query = "update $db set $set bez='$bez' WHERE id=$edit";
		unset($edit);
	}
	$result = safe_query($query);
	
	#  schreibe datei
	
	foreach($morpheus["lan_arr"] as $lang) {
		$data = '<?php
global $language;
	
$language = array();
	
$language[0] = "";
';
		$query 	= "SELECT * FROM $db order by id";
		$result = safe_query($query);
		while ($row = mysqli_fetch_object($result))	{	
			$word 	= $row->$lang;
			$id		= $row->id;
			$data .= '$language['.$id.'] = "'.addslashes(trim($word)).'";
';
		}
		
		$data .= '	?>';
	
		save_data("../nogo/".$lang.".inc",$data,"w");
		save_data("../nogo/".$lang.".inc",$data,"w");
	}
	$lang = "de";
}

if (($neu  || $edit) && $admin) {
	echo "<a href='?'>" .backlink() ." zur&uuml;ck</a><p><p><b>Eintrag bearbeiten</b></p>";
	
	if (!$neu) {
		$query 	= "SELECT * FROM $db where id=$edit";
		$result = safe_query($query);
		$row 	= mysqli_fetch_object($result);
		
		foreach($morpheus["lan_arr"] as $lang)	{
			$$lang  = stripslashes($row->$lang);
		}

		$bez 	= $row->bez;		
	}
	
	echo '<form method="post"><input type="hidden" name=edit value=' .$edit .'>
		<input type="hidden" name=save value=1>
		<input type="hidden" name=neu value='.$neu.'>
		<input type="hidden" name=edit value='.$edit.'>
		<input type="text" name=bez value="'.$bez.'">
		<p>&nbsp;</p>
	';
	
		foreach($morpheus["lan_nm_arr"] as $lang=>$bez)	{
			echo '<p style="margin: 10px 0px 10px 0px;">
			'.$bez.'<br>
			<textarea cols="120" rows="6" name='.$lang.'>'.$$lang.'</textarea></p>
';
		}

	echo '<p><input type="submit" class="button" name="save" value="speichern"></p>
		';
}
else {
	if ($suche)	{
		if ($s_art == "beg" || !$s_art)	{
			$query 	 = "SELECT * FROM $db WHERE de LIKE '%$suche%' OR en LIKE '%$suche%' OR bez LIKE '%$suche%' order by id";
			$beg_sel = "checked";
		}
		else	{
			$query 	= "SELECT * FROM $db WHERE id = '$suche'";
			$id_sel = "checked";
		}
	}
	else	{
		$query 	 = "SELECT * FROM $db ORDER BY bez,de,id";
		$beg_sel = "checked";
	}
	
	$result = safe_query($query);
	
	echo "\n<p class=text><b>Sprachdatei verwalten</b></p>
".'<p><form method="get"><input type="radio" name="s_art" value="beg" '.$beg_sel.'> Suche nach Begriff &nbsp; &nbsp; <input type="radio" name="s_art" value="ids" '.$id_sel.'> Suche nach ID &nbsp; &nbsp; &nbsp; &nbsp; <input type="Text" name="suche" value="'.$suche.'"> &nbsp; <input type="submit" class="button" name="absenden" value="absenden"></form></p>
		<table width="100%" class="autocol">
			<tr>
				<td width=40><p><b>ID</b></p></td>
				<td></td>
			</tr>
	';
	
	###########################################################
	// formular wird jetzt zusammengestellt
	while ($row = mysqli_fetch_object($result))	{	
		echo "<tr bgcolor=\"".$morpheus["col"][$ct]."\" height=18>
			<td width=\"40\">" .$row->id ."</td>
			<td nowrap>" .$row->bez ."</td>
			";
		
		foreach($morpheus["lan_arr"] as $lang)	{
			echo "<td>".substr(strip_tags($row->$lang), 0, 100) ."</td>
";
		}
		
		echo '<td width="40" align="center"><a href="?edit=' .$row->id .'&db='.$db.'"><i class="fa fa-edit"></i></a></td>
		<td align=center>';

		echo '</td>
		</tr>
		';
		
		if ($ct == 0) $ct = 1;		//farbendefenition
		else $ct = 0;
	}
	// formular ist fertig
	###########################################################
	
	echo "</table><br>";
	
	echo "<a href='?neu=1'>" .ilink() ." neu</a>";
}
?>

</div>

</body>
</html>
