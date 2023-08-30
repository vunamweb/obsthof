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
	
$db   = "morp_glossar";
$tid  = "gid";
$edit = $_REQUEST["edit"];
$save = $_REQUEST["save"];
$neu  = $_REQUEST["neu"];
$del  = $_REQUEST["del"];
$del_ = $_REQUEST["del_"];

$arr  = array("Glossar Eintrag"=>"gname", "Glossar (Full)Text"=>"gtext");

echo "<div id=content_big>";

if ($del && $admin) {
	$nm  = $_REQUEST["nm"];
	echo '<p>&nbsp;</p>
		<p>Sind Sie sich sicher, dass sie die Veranstaltung <b>'.$nm.'</b> löschen wollen?</p>
		<p>&nbsp; &nbsp; &nbsp; &nbsp; <a href="?del_=' .$del .'">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?">Nein</a></p></body></html>';
	die();
}

elseif ($del_) {
	$query = "delete from $db where $tid=$del_";
	$result = safe_query($query);
	protokoll($uid, $db, $del_, "del");
}

elseif ($save) {
	$x = count($arr);
	$c = 0;
	foreach ($arr as $key=>$val) {
		$c++;
		$set .= $val."='";
		$set .= $_POST[$val];
		$set .= "'";
		
		if ($c < $x) $set .= ', ';
	}

	if ($neu) {
		$query = "insert $db set $set";
		unset($neu);
	}
	elseif ($edit) {
		$query = "update $db set $set where $tid=$edit";
		unset($edit);
	}
	$result = safe_query($query);

	$query 	= "SELECT * FROM $db order by gname";
	$result = safe_query($query);
	
	$set = '<?php 
$glossar = array(';
		
	while ($row = mysqli_fetch_object($result)) {
		$id		= $row->gid;
		$nm		= $row->gname;
	
		if ($nm) {
			$set .= '"'.$nm.'"=>"'.$id.'", ';
		}
	}	

	$set = substr($set, 0, -2);
	$set .= ');
?>';

	save_data("../nogo/glossar.inc",$set,"w");
}

if (($neu  || $edit) && $admin) {
	echo "<a href='?'>" .backlink() ." zurück</a><p><p><b>Veranstaltung anlegen/bearbeiten</b></p>";
	
	if (!$neu) {
		$query = "SELECT * FROM $db where $tid=$edit";
		$result = safe_query($query);
		$row = mysqli_fetch_object($result);
	}
	
	echo '<form method="post"><input type="hidden" name=edit value=' .$edit .'>
		<input type="hidden" name=save value=1>
		<input type="hidden" name=neu value='.$neu.'>
		<input type="hidden" name=edit value='.$edit.'>
		<p>&nbsp;</p>
	';

	foreach ($arr as $key=>$val) {
		if ($val == "gtext") {
			echo '<p><span style="float:left; width: 100px; display: block;">'.$key.'</span><span><textarea cols="" rows="" name="'.$val.'" style="width: 300px; height: 300px;">'.$row->$val.'</textarea></span></p>
';
		}
		else {
			echo '<p><span style="float:left; width: 100px; display: block;">'.$key.'</span><span><input type="text" name="'.$val.'" value="'.$row->$val.'" style="width: 300px;"></span></p>
';
		}
	}
	
	echo '<p><input type="submit" class="button" name="save" value="speichern"></p>
';
}

else {
	$query 	= "SELECT * FROM $db order by gname";
	$result = safe_query($query);

	$bgcolor = "#EFECEC";  // tabellen-hintergrundfarbe soll wechseln. erster farbwert wird gesetzt

	echo "\n<p class=text><b>Veranstaltungen verwalten</b></p>
		<table border=0 cellspacing=2 cellpadding=4>
			<tr>
				<td width=200><p><b>Name</b></p></td>
				<td></td>
			</tr>
	";
	
	###########################################################
	// formular wird jetzt zusammengestellt

	while ($row = mysqli_fetch_object($result))	{	
		echo "<tr bgcolor=$bgcolor height=18>
			<td><font color=#000000>";
		
		echo "<a href='?edit=" .$row->$tid ."&db=$db'>" .$row->gname ."<img src=\"images/stift.gif\" border=0 hspace=8></a></td>";
		echo "<td><a href='?del=" .$row->$tid ."&nm=" .$row->name ."'><img src=\"images/delete.gif\" alt=\"löschen\" border=0></a></td>
</tr>
";
		
		if ($bgcolor == '#EFECEC') $bgcolor = "#FFFFFF";
		else $bgcolor = '#EFECEC';
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
