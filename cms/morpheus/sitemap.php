<?php
$que  	= "SELECT * FROM `morp_cms_nav` where sichtbar=1 AND `lock` < 1 ORDER BY navid DESC";
$res 	= safe_query($que);
$menge	= mysqli_num_rows($res);
$rw 	= mysqli_fetch_object($res);

$arr_H = array();
$arr_S = array();

//
for($c=5; $c>0; $c--) {								// komplette nav auslesen und navid in die richtige reihenfolgen bringen
	$que  	= "SELECT * FROM `morp_cms_nav` where ebene=$c AND sichtbar=1 AND `lock` < 1 order by parent, sort";
	$res 	= safe_query($que);
	$num	= mysqli_num_rows($res);

	while ($rw = mysqli_fetch_object($res)) {
		$par	 = $rw->parent;
		$nid	 = $rw->navid;

		$arr	 = "arr_".$par;						// fuer jedes parent eine eigene globale mit dem parentwert schreiben
		$$arr .= $nid.'<!-- split:'.$nid.' -->,';	// das split wird mit dem navid wert belegt
	}
}

for ($c=0; $c <= $menge; $c++) {					// gehe alle navid durch. suche fuer jeden wert, ob ein split vorhanden ist
	$val 	= "arr_".$c;							// falls vorhanden wird am split gesplittet und der globale wert mittendrin eingesetzt
	$spl 	= explode("<!-- split:$c -->", $tmp);	// nicht die eleganteste methode, aber effektiv ;-) und scheint zu funken :-))
	$tmp	= $spl[0].",".$$val."," .$spl[1];
}

# echo $tmp;
$new = explode(",", $tmp);							// temporaeren datensatz in array wandeln
# print_r($new);

$arr_H = array();

foreach ($new as $val) {							// alle leeren elemente loeschen und neues nav-array schreiben
	if ($val) $arr_H[] = "$val";
}
# print_r($arr_H);

// jetzt gehts los. jeden wert in en oder de auslesen und richtigen link erzeugen!

$lnk_arr = array();
$nm_arr  = array();

$output .= '<table cellspacing="0" cellpadding="0"><tr>';

foreach ($arr_H as $val) {
	$y++;
	if ($lastid != $val) {
		settype($val, "integer");	# warum auch immer der typ festgesetzt werden muss. bei einigen abfragen gab es fehler :-((
		$que  	= "SELECT * FROM `morp_cms_nav` where navid=$val";
		$res 	= safe_query($que);
		$rw 	= mysqli_fetch_object($res);

		$n		= "n_".$lang;						// falls englisch und kein englischer eintrag, dann deutschen nav anzeigen
		$hl		= $rw->$n;
		if (!$hl && $lang = "en") $hl = $rw->n_de;

		$par	= $rw->parent;
		$ebene	= $rw->ebene;
		$nid	= $rw->navid;

		// link id's einsetzen
		$lnk_arr[$ebene] = $nid;
		$link			 = "";

		// nav namen einsetzen
		$nm_arr[$ebene]  = $hl;
		$nm			     = "";

		if ($ebene < 2) {
			$link 	= $lnk_arr[1]."-".$lnk_arr[1];
			$nm		= eliminiere($nm_arr[1])."/";

			$output 	.= '</td></tr><tr><td>&nbsp;</td></tr><tr><td valign="top" nowrap>';
			$hl_format 	= "<strong>$hl</strong>";
			$back		= "background-color:#e2e6ef;";
			$einzug		= 2;
		}
		else {
			$link = $lnk_arr[$ebene];
			for ($i=1; $i<=$ebene; $i++) {
				$link .= "-".$lnk_arr[$i];
				$nm	  .= eliminiere($nm_arr[$i])."/";
				$einzug		= (20 * $i);
			}
			$hl_format = "$hl";
			$back		= "";
		}

		if ($ebene > 2) $w = ($ebene-1)*12;
		else $w = 4;

		// echo '<p>'.$dir.$nm.$link.'.html" title="'.$hl.''.$hl_format.'</p>';
	}
}

$output .= '</tr></table>';

?>