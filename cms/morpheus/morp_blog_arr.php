<?php

//// EDIT_SKRIPT
// 0 => Feldbezeichnung, 1 => Bezeichnung für Kunden, 2 => Art des Formularfeldes
$arr_form = array(
	array("fTitle", "Titel", '<input type="Text" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">'),

	array("fSubtitle", "Sub Title", '<input type="Text" value="#v#" class="form-control" name="#n#" id="#n#" style="#s#" placeholder="#ph#">'),

	array("fText", "Blog Text", '<textarea cols="" rows="3" name="#n#" id="#n#" class="summernote form-control" style="height:400px; #s#" placeholder="#ph#">#v#</textarea>'),
	array("fAbstr", "Blog Abstract", '<textarea cols="" rows="3" name="#n#" id="#n#" class="form-control" style="#s#" placeholder="#ph#">#v#</textarea>'),

	array("sichtbar", 'sichtbar', 'chk'),
	array("split", "CONFIG", '</div><div class="col-md-6">'),
#	array("split", "CONFIG", '<input type="submit" name="speichern" value="speichern"></div><div class="col-md-6">'),

	array("fDatum", "Erstellungs Datum", '<input type="Text" value="#v#" name="#n#" id="#n#" class="form-control" style="#s#">', 'date', 'heute'),
#	array("fAuthor", "Autor", '<input type="Text" value="#v#" name="#n#" id="#n#" class="form-control" style="#s#">', ' col-md-6', $_SESSION["firstname"].' '.$_SESSION["lastname"]),

	array("split", "CONFIG", '<hr>'),
	
	array("fvon", "Anzeigen von", '<input type="Text" value="#v#" name="#n#" id="#n#" class="form-control" style="#s# ">', 'date', ' col-md-6'),
	array("fbis", "Anzeigen bis", '<input type="Text" value="#v#" name="#n#" id="#n#" class="form-control" style="#s# ">', 'date', ' col-md-6'),

#	array("fLanguage", "Sprache", 'sellan'),

	array("split", "CONFIG", '<div class="clearfix"></div>'),

# 	array("fBlogKatID", "Kategorien", 'multisel', 'morp_blog_kat', 'fKat', 'fBlogKatID'),

	array("split", "CONFIG", '<hr>'),

#	array("fLink", "Link setzen // ID oder https://www.xx.de", '<input type="Text" value="#v#" name="#n#" id="#n#" class="form-control" style="#s#" placeholder="#ph#">'),

# 	array("fBlogKatID", "Kategorien", 'multisel', 'tPankreasProdukt', 'fProduktname', 'fKundenID', 'tKunden', 'fKundenName1'),


	// array("pid", "PDF aus Archiv wählen", 'sel', 'pdf', 'pname'),

#	array("img1", "Foto", 'foto', 'image', 'imgname', 6, 'gid'),
	array("img1", "Foto", 'foto', 'image', 'imgname', 6, 'gid'),

);
///////////////////////////////////////////////////////////////////////////////////////
/* OPTIONS

	array("fCAPAKommentar", "CAPA Kommentar", '<textarea cols="" rows="3" name="#n#" id="#n#" style="#s#">#v#</textarea>'),

	array("fCAPAID", "CAPA Status:", 'sel', 'tCAPAStatus', 'fStatusName', "fCAPAID"),

 	array("fWGID", "Warengruppe", 'sel2', 'tWG', 'fWGName', 'fWGID'),

 	array("fLieferantenID", "Lieferant", 'sel3', 'tKunden', 'fKundenName1', 'fKundenID', 'tFirmenArt', 'fArtID', 5),

 	array("fProduktID", "Produkt", 'sel4', 'tPankreasProdukt', 'fProduktname', 'fKundenID', 'tKunden', 'fKundenName1'),


	// 2 Tabellen verknüpfen - Übereinstimmende Spalte (fArtID) - Wert des Filter => 2
	array("fKundenID", "Kunde", 'sel5', 'tKunden', 'fSpitzname', 'fKundenID', 'tFirmenArt', 'fArtID', '2'),

	array("fMengeBrutto", "Gewicht Brutto", '<input type="Text" value="#v#" name="#n#" id="#n#" style="#s# ; width:80px;">','float'),

	array("fEingangsDatumLager", "Eingangsdatum Lager", '<input type="Text" value="#v#" name="#n#" id="#n#" style="#s# ; width:80px;">', 'date'),



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
	if(preg_match("/varchar/", $t) || preg_match("/int/", $t)) echo 'array("'.$f.'", "'.$f.'", \'<input type="Text" value="#v#" name="#n#" id="#n#" style="#s#">\'),'."\n";
	elseif(preg_match("/text/", $t)) echo 'array("'.$f.'", "'.$f.'", \'<textarea cols="" rows="10" name="#n#" id="#n#" style="#s#">#v#</textarea>\'),'."\n";
}
*/
?>
