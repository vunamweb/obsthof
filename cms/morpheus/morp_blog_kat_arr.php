<?php

//// EDIT_SKRIPT
// 0 => Feldbezeichnung, 1 => Bezeichnung für Kunden, 2 => Art des Formularfeldes
$arr_form = array(
	array("fKat", "Name der Kategorie", '<input type="Text" value="#v#" name="#n#" class="form-control" style="#s#">'),
	array("fLanguage", "Deutsch: 1 // English: 2", 'sellan'),

);
///////////////////////////////////////////////////////////////////////////////////////
/* OPTIONS

	array("fCAPAKommentar", "CAPA Kommentar", '<textarea cols="" rows="3" name="#n#" style="#s#">#v#</textarea>'),

	array("fCAPAID", "CAPA Status:", 'sel', 'tCAPAStatus', 'fStatusName', "fCAPAID"),

 	array("fWGID", "Warengruppe", 'sel2', 'tWG', 'fWGName', 'fWGID'),

 	array("fLieferantenID", "Lieferant", 'sel3', 'tKunden', 'fKundenName1', 'fKundenID', 'tFirmenArt', 'fArtID', 5),

 	array("fProduktID", "Produkt", 'sel4', 'tPankreasProdukt', 'fProduktname', 'fKundenID', 'tKunden', 'fKundenName1'),

 	array("fProduktID", "Produkt", 'sel4', 'tPankreasProdukt', 'fProduktname', 'fKundenID', 'tKunden', 'fKundenName1'),

	// 2 Tabellen verknüpfen - Übereinstimmende Spalte (fArtID) - Wert des Filter => 2
	array("fKundenID", "Kunde", 'sel5', 'tKunden', 'fSpitzname', 'fKundenID', 'tFirmenArt', 'fArtID', '2'),

	array("fMengeBrutto", "Gewicht Brutto", '<input type="Text" value="#v#" name="#n#" style="#s# ; width:80px;">','float'),

	array("fEingangsDatumLager", "Eingangsdatum Lager", '<input type="Text" value="#v#" name="#n#" style="#s# ; width:80px;">', 'date'),



	array("img1", "Foto 1", 'foto', 'image', 'imgname', 6, 'gid'),

	array("img2", "Foto 2", 'sel', 'image', 'imgname', 6, 'gid'),
	array("img3", "Foto 3", 'sel', 'image', 'imgname', 6, 'gid'),


	array("fLanguage", "Deutsch: 1 // English: 2", 'sellan'),

*/


/*
$sql = "SHOW COLUMNS FROM $table";
$res = safe_query($sql);
while($row = mysqli_fetch_assoc($res)){
	$f = $row["Field"];
	$t = $row["Type"];
	if(preg_match("/varchar/", $t) || preg_match("/int/", $t)) echo 'array("'.$f.'", "'.$f.'", \'<input type="Text" value="#v#" name="#n#" style="#s#">\'),'."\n";
	elseif(preg_match("/text/", $t)) echo 'array("'.$f.'", "'.$f.'", \'<textarea cols="" rows="10" name="#n#" style="#s#">#v#</textarea>\'),'."\n";
}
*/
?>
