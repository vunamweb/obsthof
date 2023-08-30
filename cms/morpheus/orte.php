<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # # 
# www.pixel-dusche.de                               #
# bjˆrn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # # 

include("cms_include.inc");

// print_r($_REQUEST);

$edit 	= $_GET["edit"];
$del 	= $_GET["del"];
$delete	= $_GET["delete"];

$col = array("#FFFFFF","#EFECEC");
$ct  = 0;

$save 	= $_POST["save"];
$neu	= $_GET["neu"];
$new	= $_POST["new"];

global $bereich_arr ;

if ($del) {
	echo "<div id=content_big><p>&nbsp;</p><p>Sind Sie sich sicher, dass Sie den Veranstaltungsort l&ouml;schen m&ouml;chten?</p>
		<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href=\"orte.php?delete=$del\">Ja</a>
		&nbsp; &nbsp; &nbsp; &nbsp; <a href=\"orte.php\">Nein</a></p></div>";
	die("</body></html>");
}
elseif ($delete) {
	$query = "delete FROM ec_orte where ortID=$delete";
	safe_query($query);
#	dbconnect();
#	safe_query($query);
#	dbconnect_live();
}

if ($save || $new) {
	$set = " set stadt='".$_POST["stadt"]."', veranstOrt='".$_POST["veranstOrt"]."', raum='".$_POST["raum"]."', text='".$_POST["text"]."', plz='".$_POST["plz"]."', strasse='".$_POST["strasse"]."'";

	if (!$new) $query = "update ec_orte $set where ortID=$save";
	else $query = "insert ec_orte $set";
	safe_query($query);

	if ($new) {
		$nid = mysqli_insert_id($mylink);
		$save = $nid;
	}
	else {		
#		dbconnect();
#		safe_query($query);
#		dbconnect_live();
	}


	// orte zuordnungen loeschen und neu erstellen
	if ($save) {
		$query = "DELETE FROM ec_orte_zuord WHERE oid=$save";
		safe_query($query);

		if (isset($_REQUEST["bereich"])) {
			$arr = $_REQUEST["bereich"];
			foreach($arr as $val) {
				$query = "INSERT ec_orte_zuord set oid=$save, bereich=$val";
				safe_query($query);
			}
		}
	}
}

echo '<div id="content_big"><table border="0" cellspacing="1" cellpadding="6" class="text01" width="100%">
<p><b>Veranstaltungsorte Verwaltung</b></p>
';

if ($edit || $neu) {
	if ($edit) {
		$query = "SELECT * FROM ec_orte where ortID=$edit"; 
		$result = safe_query($query);
		
		$row = mysqli_fetch_object($result);
		$id	   		= $row->ortID;
		$stadt 		= $row->stadt;
		$raum  		= $row->raum;
		$vort  		= $row->veranstOrt;
		$text  		= $row->text;
		$plz  		= $row->plz;
		$strasse  	= $row->strasse;

		$query 		= "SELECT * FROM ec_orte_zuord where oid=$edit"; 
		$result 	= safe_query($query);
		$n 			= mysqli_num_rows($resulte);
		$tmp_arr	= array();
		
		if ($n > 0) {
			while ($row = mysqli_fetch_object($result)) $tmp_arr[] = $row->oid;
		}

		foreach($bereich_arr as $key=>$val) {
			$tmp .= '		<tr><td valign="top"></td><td valign="top"><input type="checkbox" value="'.$key.'" name="bereich[]"'. (in_array($tmp_arr, $key) ? ' checked' : '') .'> &nbsp; '.$val.'</td></tr>
';
		}
	}
	
	echo '<form action="orte.php" method="post"><input type="Hidden" name="save" value="'.$edit.'"><input type="Hidden" name="new" value="'.$neu.'">';
	
	echo '<tr><td valign="top"><b>Stadt &nbsp;</b></td><td valign="top"><input type="Text" name="stadt" value="'.$stadt.'" style="width: 300px;"></td></tr>
		<tr><td valign="top"><b>Ort (Halle, Uni, Hotel, etc.) &nbsp; </b></td><td valign="top"><input type="Text" name="veranstOrt" value="'.$vort.'" style="width: 300px;"></td></tr>
		<tr><td valign="top"><b>Raum &nbsp; </b></td><td valign="top"><input type="Text" name="raum" value="'.$raum.'" style="width: 300px;"></td></tr>
		<tr><td valign="top">&nbsp;</td><td valign="top"></td></tr>
		<tr><td valign="top"><b>Straﬂe &nbsp; </b></td><td valign="top"><input type="Text" name="strasse" value="'.$strasse.'" style="width: 300px;"></td></tr>
		<tr><td valign="top"><b>PLZ &nbsp; </b></td><td valign="top"><input type="Text" name="plz" value="'.$plz.'" style="width: 100px;"></td></tr>
		<tr><td valign="top"><b>Freier Text</b></td><td valign="top"><textarea cols="60" rows="5" name="text">'.$text.'</textarea></td></tr>
		'.$tmp.'
		<tr><td valign="top"></td><td><p><input type="submit" name="speichern" value="speichern"></p></td></tr>
	';	
	
	echo '</form>';
}
else {
	$query = "SELECT * FROM ec_orte order by stadt, veranstOrt"; 
	$result = safe_query($query);
	$x = 0;
	while ($row = mysqli_fetch_object($result)) {
		$id	   = $row->ortID;
		$stadt = $row->stadt;
		$vort  = $row->veranstOrt;
		$text  = $row->text;
		$text  = repl("\n","<br>",$text);
		$x++;
		
		$lnk = '<a name="orte" href="orte.php?edit='.$id.'" title="orte">';
		
		echo '<tr bgcolor='.$col[$ct].'><td valign="top" align="right">'.$lnk.''.$x.'</a> &nbsp;</td><td valign="top">'.$lnk.''.$stadt.' ('.$id.')</a></td><td valign="top">'.$vort.'</td><td valign="top">'.$text.'</td><td>'.$lnk.'<img src="images/edit.gif" alt="" width="18" height="10" border="0"></a></td><td><a name="orte" href="orte.php?del='.$id.'" title="orte"><img src="images/delete.gif" alt="" border="0"></a></td></tr>
		';
	
		if ($ct == 0) $ct = 1;		//farbendefenition
		else $ct = 0;	
	}
}

echo '</table>
';
if (!$edit) echo '<p><a name="neu" href="orte.php?neu=1" title="neu">'.ilink().' NEU</a></p>
';
?>

<?
include("footer.php");
?>