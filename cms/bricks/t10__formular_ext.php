<?php
session_start();

global $form_desc, $cid, $navID, $lan, $nosend, $morpheus, $ssl_arr, $ssl, $lokal_pfad, $js, $cid, $formMailAntwort, $plichtArray, $multilang, $register_form, $full, $eventAnzahlReserviert, $eventName, $eventAnzahlTeilnehmer, $eventid, $mid;

$register_form = 1;

// FROM EVENT PAGE
if ($full){
	$output .= '<div class="container mb6"><h2>There are no more seats available</h2></div>';
}
else {

// print_r($_REQUEST);


// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// DESIGN DES FORMULARES
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .

	$designStart = '
			<div class="form-group">
	';
	$designEnde = '
			</div>
	';

	$design = '
			<div class="form-group">
				#cont#
			</div>
	';

	$designFull = '
			<div class="form-group col-12">
				#zeile2# #cont#
			</div>
	';

	$designCheckbox = '
		<div class="form-group col-12">
			<div class="row">
				<div class="col-1">
					#cont#
				</div>
				<div class="col-10">
					<label>#desc# #zeile2#</label>
				</div>
			</div>
		</div>
	';

	$designCheckboxSchmal = '
		<div class="form-group col-md-6 col-12">
			<div class="row">
				<div class="col-1">
					#cont#
				</div>
				<div class="col-10">
					<label>#desc# #zeile2#</label>
				</div>
			</div>
		</div>
	';

	$designschmal = '
		<div class="form-group col-md-6 col-12">
			<label>#desc#</label> &nbsp; &nbsp;<br/>
			#cont#
		</div>
	';


	$designTEXT = '
			<p class="mb2">#desc#</p>
	';
	$designTEXTschmal = '
		<div class="col-12 col-md-6 mb2">
			<p>#desc#</p>
		</div>
	';
	$designFETT = '
		<div class="col-12 ">
			<h3>#desc#</h3>
		</div>
	';

// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// ENDE DESIGN
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .
// .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .    .

	$fid 	= $text;

	$query  	= "SELECT * FROM morp_cms_form WHERE fid=".$fid;
	$result 	= safe_query($query);
	$row 		= mysqli_fetch_object($result);
	$formMailAntwort = $row->antwort;

	$query  = "SELECT * FROM morp_cms_form_field WHERE fid=$fid ORDER BY reihenfolge";
	$result = safe_query($query);

	$x = 0;

	$plichtArray = array();

	while ($row    = mysqli_fetch_object($result)) {
		$nm 	= $row->fname;
		$text 	= $row->fform;

		$art 	= $row->art;
		$feld 	= $row->feld;
		$desc 	= ($row->desc);
		$hilfe 	= $row->hilfe;
		$email 	= $row->email;
		$size	= $row->size;
		$parent	= $row->parent;
		$fehler	= $row->fehler;
		$style  = $row->klasse;
		$cont	= $row->cont;
		$auswahl = ($row->auswahl);

		$star = ' *';
		$pflicht = '';

		if($row->pflicht) $plichtArray[]=$row->feld;

		if ($cont == "email" && $row->pflicht) 	{ $pflicht = ' required'; }
		elseif ($cont == "number" && $row->pflicht) 	{ $pflicht = ' required'; $rules .= $feld.': { required:true, number: true },
	'; }
		elseif ($cont == "number") 	{ $star = ''; $rules .= $feld.': { number: true },
	'; }
		elseif ($cont == "email") 	{ $pflicht = ' class="email"'; $star = ''; }
		elseif ($row->pflicht) 	{ $pflicht = ' required'; }
		else					{ $pflicht = ''; $star = ''; }

		//$desc .= $star;

		if ($fehler) 	$messages .= $feld.': "'.$fehler.'"'.",\n";

		$data = "";

		// FELD IST ABHAENGIG DAVON; DASS EINE CHECKBOX AKTIVIERT WURDE
		if ($art == "Fieldset Start") $form .= '</table><fieldset id="'.$feld.'" style="">'.$table;

		elseif ($art == "Fieldset Ende") $form .= '</table></fieldset>'.$table;

		// elseif (isin("^Ende", $art)) $form .= '<br style="clear:both;" />';

		elseif ($art == "Eingabefeld") {
			$data = '<input id="'.$feld.'" name="'.$desc.'"'.$pflicht. ' placeholder="'.$desc.$star.'" type="text" class="form-control" />';
			if ($style == "schmal") $form .= str_replace(array('#cont#', '#desc#', '#style#'), array($data, $desc, $style), $designschmal);
			else $form .= str_replace(array('#cont#', '#desc#', '#style#'), array($data, $desc, $style), $design);
		}

		elseif ($art == "Checkbox") {
			$x++;

			unset($value);
			if (isin("\|", $feld)) {
				$t	 = explode("|", $feld);
				$feld 	= $t[0];
				$value  = $t[1];
			}

			$data = '<input type="checkbox"'. ($feld=="de"?' checked':'') .' class="checkbox" id="'.$feld.'" '. ($value ? ' value="'.$value.'"' : ' value="ja"') .' name="'.$desc.'"'.$pflicht.' /> ';

			$selectDesign = $style == "sp2" ? $designCheckboxSchmal : $designCheckbox;
			$desc = $desc ? ''.$desc.'<br/>' : '';
			$form .= str_replace(array('#cont#', '#desc#', '#style#', '#anz#', '#zeile2#'), array($data, nl2br($desc), $style, $x, '<span class="hilfe">'.nl2br($hilfe).'</span>' ), $selectDesign);

		}

		elseif ($art == "Radiobutton") {
			$data .= fpdForm($feld, $auswahl, "radio", $pflicht);
			if ($pflicht) {
				$rules .= $feld.': "required",
	';
			}
			$form .= str_replace(array('#cont#', '#desc#', '#style#'), array($data, $desc, $style), $design);
		}

		elseif ($art == "Dropdown") {
/*
			if ($style == " StSp1" || $style == " StSp2" || $style == " StSp3") $breite = 100;
			else $breite = 180;
*/

			if (!isin("print.php", $uri)) 	$data .= fpdForm($feld, $auswahl, "sel", $pflicht, $breite).'</select>';
			else 							$data .= fpdForm($feld, $auswahl, "radio", $pflicht);

			if ($pflicht) {
				// $data .= '<label for="'.$feld.'" class="error">Bitte w&auml;hlen Sie eine Option</label>';
				$rules .= $feld.': "required",
	';
			}
			if ($style == " schmal") $form .= str_replace(array('#cont#', '#desc#', '#style#'), array($data, $desc, $style), $designschmal);
			else $form .= str_replace(array('#cont#', '#desc#', '#style#'), array($data, $desc, $style), $designschmal);
		}

		elseif ($art == "Mitteilungsfeld") {
			$data .= '<textarea id="'.$feld.'" name="'.$feld.'"'.$pflicht.' placeholder="'.$desc.'" class="form-control h100"></textarea>';
			$form .= str_replace(array('#cont#', '#desc#', '#style#', '#zeile2#'), array($data, $desc, $style, '<p class="hilfe">'.nl2br($hilfe).'</p>'), $design);
		}

		elseif ($art == "Freitext Fett") {
			$form .= str_replace(array('#desc#', '#anz#'), array(nl2br($hilfe), $x), $designFETT);
		}

		elseif ($art == "Freitext") {
			$form .= str_replace(array('#desc#', '#anz#'), array(nl2br($hilfe), $x), $designTEXT);
		}

		elseif ($art == "Freitext Headline") {
			$form .= str_replace(array('#desc#', '#anz#'), array('<h2 class="underline">'.nl2br($hilfe).'</h2>', $x), $designTEXT);
		}

		elseif ($art == "Freitext halbe Spalt") {
			$form .= str_replace(array('#desc#', '#anz#'), array(''.nl2br($hilfe).'', $x), $designTEXTschmal);
		}

		elseif ($art == "Ende Spalte") {
			$form .= str_replace(array('#desc#', '#anz#'), array('END', $x), $designEnde);
		}
		elseif ($art == "Start Spalte") {
			$form .= str_replace(array('#desc#', '#anz#'), array('START', $x), $designStart);
		}

	}


	$dsText = '';
	if($lan == "de") $datenschutzID = 6;
	else if($lan == "en") $datenschutzID = 10;

	$dsText = '<div class="form-group"><input id="datenschutz" type="checkbox" name="datenschutz" required ><label for="datenschutz" class="lb">';
	
	if($lan == "de") $dsText .= 'Mit der Anmeldung zur Veranstaltung erklären Sie sich damit einverstanden, dass Ihre Anmeldedaten (Name, Organisation, Datum des Webinars) für eine interne Auswertung des Webinars gespeichert und verarbeitet werden. Ihre Kontaktdaten werden nur für die Kommunikation vor und nach der Veranstaltung verwendet. Die Veranstaltung wird nicht aufgezeichnet.</label></div>';
	
	elseif($lan == "en") $dsText .= 'By registering for the event, you agree that your registration data (name, organisation, date of the webinar) will be stored and processed for internal evaluation of the webinar. Your contact details will only be used for communication before and after the event. The event will not be recorded.</label></div>';
	
	$bitteCode = '';
	if($lan == "de") $bitteCode = 'Bitte diesen Code einsetzen:';
	else if($lan == "en") $bitteCode = 'Please insert this code:';
	
	$senden = '';
	if($lan == "de") $senden = 'Registrieren';
	else if($lan == "en") $senden = 'Registration';
	
	$Pflichtfelder = '';
	if($lan == "de") $Pflichtfelder = 'Pflichtfelder';
	else if($lan == "en") $Pflichtfelder = 'Mandatory fields';


	$js = str_replace(array('<!-- rules -->', '<!-- optional -->', '<!-- messages -->'), array($rules, $optional, $messages), $js);

	$sql_event = "SELECT aid FROM morp_cms_form_auswertung WHERE event=$eventid AND mid=$mid";
	$rs = safe_query($sql_event);
	// $rw = mysqli_fetch_object($rs);
	
	if(mysqli_num_rows($rs)>0) $output .= '<div class="container"><div class="row" style="min-height:300px;"><h2>'.textvorlage(68).'</h2></div></div>';
	else 
		$output .= '
			<div class="container">
			<div class="row">
				<div class="col-12 col-md-8 offset-md-2 mb6">
					<div id="kontaktformular" class="box_frm_contact">
					
						<form id="kontaktf" method="post">
							<input type="Text" name="myid" value="'.$mid.'">							
							<input type="Hidden" name="fid" value="'.$fid.'">
							<input type="Hidden" name="rest" value="'.$eventAnzahlReserviert.'">
							<input type="Hidden" name="sum" value="'.$eventAnzahlTeilnehmer.'">
							<input type="Hidden" name="lang" value="'.$lan.'">
							<input type="Hidden" name="eventid" value="'.$eventid.'">
							
							
							<div class="frm_contact_body">
								'.$form .'
								'.$dsText.'
								<span class="">* '.$Pflichtfelder.'</span>
								<p class="mt2"><button class="btn btn-send sendform" type="submit">'.$senden.'</button></p class="mt2">
							</div>
						</form>

					</div>
				</div>
			</div>
			</div>
	';
}

	$morp = '<b>FORMULAR</b>';

// print_r($_SESSION);

$js .= '

$(document).ready(function() {
	//$("#anrede").val("'.$_SESSION["anrede"].'");
	$("#name").val("'.$_SESSION["vname"].' '.$_SESSION["nname"].'");
	$("#email").val("'.$_SESSION["email"].'");
});
';

