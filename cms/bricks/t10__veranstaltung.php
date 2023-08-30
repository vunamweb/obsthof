<?php
/* pixel-dusche.de */
global $templCt, $cid, $dir, $monate, $lang, $month, $navID, $lan, $date_box, $full, $eventAnzahlReserviert, $eventName, $eventAnzahlTeilnehmer, $eventid, $mid, $eventlist_id, $event_url_id,$event_register_id;

// print_r($_GET);

$eventid = isset($_GET["nid"]) ? $_GET["nid"] : 0;
	
if(!$eventid) {
	
}	
else {
	$table = "morp_events";
	$tid = "eventid";
	$sql = "SELECT * FROM $table WHERE eventid=$eventid AND aktiv=1 ORDER BY eventDatum ASC";
	$res = safe_query($sql);
	if(mysqli_num_rows($res)>0) {
		$reg_btn = $lang == 1 ? 'Registrieren' : 'Register';
		$archive_btn = $lang == 1 ? 'Im Archiv anzeigen' : 'Details in archive';
		$month_arr = $lang == 1 ? $monate : $month;
		$cid_lan = $lang == 1 ? "cid" : "cidEn";
		
		$row = mysqli_fetch_object($res);
		
		$datum_start = explode("-",$row->eventDatum);
		$datum_end = explode("-",$row->eventEndDatum);
		
		$datum = $datum_start;
		$tag_start = $datum[2];
		$monat_start = intval($datum[1]);
		$jahr_start = $datum[0];
		
		$datum = $datum_end;
		$tag_end = $datum[2];
		$monat_end = intval($datum[1]);
		$jahr_end = $datum[0];	
		
		$event_id = $row->$cid_lan;
		
		$eventText = $lang == 1 ? $row->eventText : $row->eventTextEn;
		$eventName = $lang == 1 ? $row->eventName : $row->eventNameEn;
		$eventAnzahlTeilnehmer = $row->eventAnzahlTeilnehmer;
		// $eventAnzahlReserviert = $row->eventAnzahlRest;
		
		$sql = "SELECT * FROM `morp_cms_form_auswertung` WHERE event=$eventid AND register=1";
		$rs = safe_query($sql);
		$eventAnzahlReserviert = mysqli_num_rows($rs);
		
		$event_reg_text1 = $lang == 1 ? $row->event_reg_text1 : $row->event_reg_text1En;
		$event_reg_text2 = $lang == 1 ? $row->event_reg_text2 : $row->event_reg_text2En;
			
		$full = 0;
		// if($eventAnzahlReserviert >= $eventAnzahlTeilnehmer) $full = 1;
		// 
		// $sql_event = "SELECT aid FROM morp_cms_form_auswertung WHERE event=$eventid AND mid=$mid";
		// $rs = safe_query($sql_event);		
		// $is_registered = mysqli_num_rows($rs);
		
		$date_box = '
			'.($event_register_id == $cid ? '<section>' : '').'
			<div class="date_box_abs">
				<div class="date_box xxx">
					<span class="tag">'.$tag.'</span>
					<span class="monat">'.$month_arr[$monat].'</span>
					<span class="jahr">'.$jahr.'</span>
				</div>
			</div>
			'.($event_register_id == $cid ? '</section>' : '').'
			';
	
		
		if($event_register_id == $cid) {
			$HL = '<h1>'.nl2br($eventName).'</h1>';
			$output .= '<div class="event_detail"><div class="container"><div class="row time"><div class="col-12 vvv">'.$HL.'</div></div></div></div>';
		} else {
			$HL = '<h1>'.nl2br($eventName).'</h1><h2>'.nl2br($eventText).'</h2>';
			$register = '
		<div class="row time">
			<div class="col-12 col-md-8">
				<h3>'.$event_reg_text1.'</h3>
				<h4>'.$event_reg_text2.'</h4>
			</div>
			<div class="col-12 col-md-4 text-center">
				'.($is_registered ? '<span class="btn btn-info small mobil-btn-pad">Sie haben sich bereits angemeldet</span>' : '<a href="'.getUrl($event_register_id,1).'+'.$eventid.'/" class="btn btn-info btn-regbig small">'.$reg_btn.'</a>').'
			</div>
		</div>';
			$back_button = '<a href="'.$dir.$lan.'/'.$navID[$eventlist_id].'" class="btn btn-back">'.textvorlage(66).'</a>';
			$event_output = '<div class="event_detail"><div class="container">'.$back_button.'</div>'.getVorlagenText($row->$cid_lan, $lang, $dir).'</div><div class="container mt-4 mb-5"><hr>'.$back_button.'</div>';
			$output .= str_replace(array('#register#','#HL#'), array($register,$HL), $event_output);
			
		}
		
	} else {
		$output .= $lang == 1 ? 'Dieser Event ist nicht mehr verfügbar' : 'This event is no longer available';
	}
}

$morp = "Veranstaltungen / ";
