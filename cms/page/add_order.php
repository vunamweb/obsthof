<?php
/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

session_start();

function redirectFrompaypal() {
  $parameter = ($_GET['paymentId'] == '') ? 'paymentId=0' : 'paymentId=1';
  $redirect = 'https://' . $_SERVER['SERVER_NAME'] . '/?'.$parameter.'';
  //header("Location: ".$redirect."");
}

global $morpheus, $mylink, $ParticipantPaymentMethod, $Zahlungsart;

$root = $_SERVER['DOCUMENT_ROOT'];
include_once($root."nogo/config.php");
$url = $morpheus["url"];
$dir = $morpheus["url"];
include_once($root."nogo/funktion.inc");
include_once($root."nogo/db.php");
if(!$mylink) dbconnect();
// print_r($_GET);

// if call from coupon, then only save data form by session
if($_POST['save_session']=="Paypal") {
	// $_POST['data'];
	$_SESSION['data'] = $_POST['data'];
	$_SESSION["art"] = $_POST["art"];
	$_SESSION["sum"] = $_POST["sum"];
	$_SESSION["anz"] = $_POST["anz"];
	die();
}
else if($_POST['save_session']=="Vorab" || $_POST['save_session']=="Rechnung" || $_POST['save_session']=="Kostenfrei" || $_POST['save_session']=="Bar" ) {
  $data = json_decode($_POST['data'], true);  
  $art = $_POST["art"];
  $sum = $_POST["sum"];
  $anzahl = $_POST["anz"];
}
// if cancel paypal, then do nothing
else if($_GET['paymentId'] == '') {
  redirectFrompaypal();
  return;
}
else if($_GET['paymentId']) {
	$output .= '<div class="text-center">Ihre Bestellung war erfolgreich. Vielen Dank. Sie erhalten in Kürze eine Bestätigung.</div>';

	$data = json_decode($_SESSION['data'], true);	
	$x = count($data);	
	// add paypal id
	$data[$x]['name'] = 'paymentid';
	$data[$x]['value'] = $_GET['paymentId'];
	
	$art = $_SESSION["art"];
	$sum = $_SESSION["sum"];
	$anzahl = $_SESSION["anz"];
	$Zahlungsart = "PayPal";
	$paymentID = $_GET['paymentId'];
	$ParticipantPaymentMethod=7; 
}

include($root."/paypal/library/function.php");


// read session again, after paypal library destroyed $data
if($_GET['paymentId']) $data = json_decode($_SESSION['data'], true);
$x = count($data);
// echo '#'.	$x = count($data);	
// print_r($_SESSION['data']); 
// print_r($data); 

$allData = array();
foreach($data as $arr) {
	$allData[$arr["name"]]=$arr["value"];
}
 // print_r($allData); 

$kostenfrei = 0;
if($_POST['save_session']=="Vorab") { $Zahlungsart = "Vorabüberweisung"; $ParticipantPaymentMethod=5; }
else if($_POST['save_session']=="Rechnung") { 
	$Zahlungsart = "Rechnung"; 
	$ParticipantPaymentMethod=1; 
	if($allData["altName"] && $allData["altStrasse"]) $ParticipantPaymentMethod=6; 
}
else if($_POST['save_session']=="Bar") { $Zahlungsart = "Bar"; $ParticipantPaymentMethod=3; }
else if($_POST['save_session']=="Kostenfrei") { $Zahlungsart = "Kostenfrei"; $kostenfrei = 1; $ParticipantPaymentMethod=2; }
if(!$ParticipantPaymentMethod) $ParticipantPaymentMethod = 1;

$ParticipantInfoField = utf8_decode($allData['ParticipantInfoField']);

$sql = "SELECT * FROM morp_settings WHERE 1";
$res = safe_query($sql);
while($row = mysqli_fetch_object($res)) {
	$morpheus[$row->var] = $row->value;
}

include($root."/inc/api_start.php");

$mail_start = $morpheus["mail_start"].utf8_decode('<h1>Anmeldung</h1><p>&nbsp;</p><table>');
$mail_start_copy = $morpheus["mail_start"].'<h1>Registrierungs Informationen / Registration Information</h1><p>&nbsp;</p><h2>Anmeldung '.($typ == "firma" ? 'Anmeldung für Gruppen / Institutionen / Familien' : 'Teilnehmer').'</h2><p>&nbsp;</p>#txt#<table>';
$mail_content = '';
	
$customer_mail = '';

$mail_content .= '
	<tr><td>Event Type Name</td><td>'.$allData["EventTypeName"].'</td></tr>
	<tr><td>Event Id</td><td>'.$allData["EventId"].'</td></tr>
	<tr><td>Event Type Id</td><td>'.$allData["EventTypeId"].'</td></tr>
	<tr><td>Event Start Datum</td><td>'.$allData["EventStartDate"].'</td></tr>
	<tr><td>Event End Datum</td><td>'.$allData["EventEndDate"].'</td></tr>
	'.($kostenfrei ? '' : '<tr><td>Gesamtsumme</td><td><b>'.ger_p($sum).'</b></td></tr>').'
	<tr><td>Zahlart</td><td><b>'.$Zahlungsart.'</b></td></tr>
	'.($paymentID ? '<tr><td>PayPal Payment ID</td><td>'.$paymentID.'</td></tr>' : '').'
	<tr><td colspan="2"><hr></td></tr>
';

$customer_mail .= '
	<tr><td>Event Type Name</td><td>'.$allData["EventTypeName"].'</td></tr>
	<tr><td>Event Start Datum</td><td>'.$allData["EventStartDate"].'</td></tr>
	<tr><td>Event End Datum</td><td>'.$allData["EventEndDate"].'</td></tr>
	'.($kostenfrei ? '' : '<tr><td>Gesamtsumme</td><td><b>'.ger_p($sum).'</b></td></tr>').'
	<tr><td>Zahlart</td><td><b>'.$Zahlungsart.'</b></td></tr>
	'.($paymentID ? '<tr><td>PayPal Payment ID</td><td>'.$paymentID.'</td></tr>' : '').'
	<tr><td colspan="2"><hr></td></tr>
';

$sendToArray = $allData["sendto"];
// $ezPreis = $allData["Einzelpreis"];
$postData = array();
$FreeAttributeFieldValues = array();
$FreeSelectFieldOptions = array();

global $anredeDropwdon, $formArray, $formSetArray, $formSetArrayTeilnehmer, $formArrayCompany, $formSetArrayCompany, $Zahlungsart;

if($art=="person") {
	$person_details .= addClientViaCurl($allData, $url, $AccessToken, $kostenfrei);
	$mail_content .= $person_details;
	$customer_mail .= $person_details;
} 
else {
	$funktion = 'addCompany';
	$data = "p1=$funktion&p2=$AccessToken";
	
	$postData["p1"]=$funktion;
	$postData["p2"]=$AccessToken;
	// $postData["EventId"]=$allData['EventId'];
	
	foreach($formArrayCompany as $key=>$arr) {
		if($allData[$key]) $mail_content .= '<tr><td>'.$arr[0].'</td><td>'.$allData[$key].'</td></tr>';
		// if($allData[$key]) $data .= "&$key=".$allData[$key];
		$postData[$key]=$allData[$key];
	}
	
	// print_r($postData);
	
	// $response = makeCurl($url, $headers, $data);
	
	// + + + + WIEDER ANSCHALTEN ZUR ÜBERGABE AN SEMPLAN
	$response = curl_post($url, $postData);
	$response = json_decode($response, true);
	$result = $response["Results"];
	$CompanyId = $result[0]["CompanyId"];
	// + + + + WIEDER ANSCHALTEN ZUR ÜBERGABE AN SEMPLAN
	// echo "\n company ID: $CompanyId -- ".'add teilnehmer: '. print_r($response);		

	$funktion = 'addParticipant';

	$mail_content .= '<tr><td colspan="2"><hr></td></tr>';
	$mail_content .= '<tr><td colspan="2"><h2>'.$anzahl.' Teilnehmer</h2></td></tr>';
	
	$customer_mail .= '<tr><td colspan="2"><hr></td></tr>';
	$customer_mail .= '<tr><td colspan="2"><h2>'.$anzahl.' Teilnehmer</h2></td></tr>';
	
			
	for($i=1; $i<= $anzahl; $i++) {
		$postData = array();
		$postData["p1"]=$funktion;
		$postData["p2"]=$AccessToken;
		$postData["EventId"]=$allData['EventId'];
		$postData["ParticipantCompanyId"] = $CompanyId;
		// $postData["ParticipantInfoField"] = $ParticipantInfoField;
		$postData["ParticipantFreeText"] = $ParticipantInfoField;	
	
		foreach($formSetArrayTeilnehmer as $key=>$val) {
			$getval = $val.'*'.$i;	
			$description = $formArray[$val];
			if($allData[$getval]) $mail_content .= '<tr><td>'.$description[0].' '.$i.'</td><td>'.$allData[$getval].'</td></tr>';
			
			// if($allData[$getval]) $data .= "&$val=".$allData[$getval];
			$postData[$val]=$allData[$getval];
		}	
		
		$postData["ParticipantEventPrice"]=$allData['ep_'.$i];
		$postData["ParticipantPaymentMethod"]=$ParticipantPaymentMethod;
		
		$extra_fields = explode(',', $allData['freeAttribute']);
		$FreeAttributeFieldValues = array();
		
		foreach($extra_fields as $val) {
			// echo $val."\n";
			$val = explode('|', $val);			
			$getval = $val[1].'*'.$i;	
	
			if($allData[$getval]){
				$mail_content .= '<tr><td>'.$val[0].'</td><td>'.$allData[$getval].'</td></tr>';
				$customer_mail .= '<tr><td>'.$val[0].'</td><td>'.$allData[$getval].'</td></tr>';
				
				$value = $allData[$getval] == 'on' ? 1 : $allData[$getval];
				$feld = explode("FA_", $val[1]);
				$oneField = array('FA_Id'=>intval($feld[1]), 'FA_Value'=>utf8_decode($value));
				array_push($FreeAttributeFieldValues,$oneField);
			}
		}
		$postData["FreeAttributeFieldValues"]=$FreeAttributeFieldValues;
		
		$FreeSelectFieldOptions = array();	
		$extra_fields = explode(',', $allData['freeSelect']);
		foreach($extra_fields as $val) {
			$val = explode('|', $val);			
			$getval = $val[1].'*'.$i;	
			if($allData[$getval]) $mail_content .= '<tr><td>'.$val[0].'</td><td>'.$allData[$getval].'</td></tr>';
	
			if($allData[$getval]){
				$value = $allData[$getval] == 'on' ? 1 : $allData[$getval];
				// $feld = explode("FA_", $val[1]);
				// $oneField = array('FS_Id'=>intval($value));
				// array_push($FreeSelectFieldOptions,$oneField);								
				// maybe correct parameter because only exists
				$postData["FS_Id"]=intval($value);
			}
		}
		$preisID = "ep_".$i;
		$mail_content .= '<tr><td>Einzelpreis</td><td>'.$allData[$preisID].'</td></tr>';
		$mail_content .= '<tr><td colspan="2"><hr></td></tr>';
		
		if(!$kostenfrei) $customer_mail .= '<tr><td>Einzelpreis</td><td>'.$allData[$preisID].'</td></tr>';
		$customer_mail .= '<tr><td colspan="2"><hr></td></tr>';
		// $postData["FreeSelectFieldOptions"]=$FreeSelectFieldOptions;		
		// print_r($postData);		
		// echo $data;
		
		// + + + + WIEDER ANSCHALTEN ZUR ÜBERGABE AN SEMPLAN
		$response = curl_post($url, $postData);
		$response = json_decode($response, true);
		$result = $response["Results"];
		// $ParticipantId = $response["Results"]["ParticipantId"];
		$ParticipantId = $result[0]["ParticipantId"];
		// $type = "sendStandardEmail";
		// $headers = array( "Content-Type: application/x-www-form-urlencoded" );
		// echo $GetUrl = $url."/$type/$AccessToken/110/".$allData["EventId"].'/'.$ParticipantId;
		// echo "\n\n";
		// $resp = getCurlData($GetUrl, $headers);
		// $respons = ($resp["Results"]);
		// print_r($respons);
		// echo "\n\n";

		// + + + + WIEDER ANSCHALTEN ZUR ÜBERGABE AN SEMPLAN
		
		// echo "\n Mitarbeiter angelegt: $CompanyId -- ".'add teilnehmer: '. print_r($response);		
	}
	
	// + + + + + sende Mail an Gruppenleiter / Unternehmen
	$type = "sendStandardEmail";
	$headers = array( "Content-Type: application/x-www-form-urlencoded" );
	echo $GetUrl = $url."/$type/$AccessToken/160/".$allData["EventId"].'/'.$CompanyId;
	$resp = getCurlData($GetUrl, $headers);
	$respons = ($resp["Results"]);
	echo "\n";
	print_r($resp);
	echo "\n\n";
	// _________ sende Mail an Gruppenleiter / Unternehmen
}

	
$mail_end = utf8_decode($morpheus["mail_end"]);
$mail_end = str_replace("#ADDRESS#", '<p>'.nl2br(utf8_decode($morpheus["vcard"])).'</p>', $mail_end);

$mail_txt = $mail_start.utf8_decode($mail_content).'</table>'.$mail_end;
$customer_mail_txt = $mail_start.utf8_decode($customer_mail).'</table>'.$mail_end;

// $mail_txt = str_replace("#start#", $details, $mail_txt);
// $mail_txt .= utf8_decode($morpheus["mailfooter"]);
// $mail_client = 'mail_client_'.$language;
// $mail_client_text = utf8_decode($morpheus[$mail_client]);

	
$Empfaenger	= $event_zusage;
$Empfaenger	= 'eppler@glsh-stiftung.de';
// $Empfaenger	= 'b@7sc.eu';
$betreff 	= 'Anmeldung  / '.utf8_decode($allData["EventTypeName"]);

$kundemail 	= $morpheus["email"];
$name 		= utf8_decode($morpheus["emailname"]); 	

// sendMailSMTP($Empfaenger, $betreff, $mail_txt, '', 1);

// if($allData["CompanyEmail"]) sendMailSMTP($allData["CompanyEmail"], $betreff, $customer_mail_txt, '', 0);
// else if($allData["ParticipantEmail"]) sendMailSMTP($allData["ParticipantEmail"], utf8_decode('Bestätigung - ').$betreff, $customer_mail_txt, '', 0);


// ParticipantEmail
// CompanyEmail

// get email
// for ($i = 0; $i < $x; $i++) {
//   if($data[$i]['name'] == 'E-Mail')
//     $email = $data[$i]['value'];
// }
// 
// // get total
// for ($i = 0; $i < $x; $i++) {
//     if($data[$i]['name'] == 'quantity')
//       $quantity = $data[$i]['value'];
//     
//     if($data[$i]['name'] == 'preis')
//       $price = $data[$i]['value'];
// }
// 
// $total = $quantity * $price;
// // add total
// $data[$x]['name'] = 'total';
// $data[$x]['value'] = $total;
// 
// $x = count($data);
// insert order
// $sql = "INSERT morp_cms_form_auswertung SET ";
// for ($i = 0; $i < $x; $i++) {
//     if (in_array($data[$i]["name"], $sql_arr)) {
//         $data_array[$data[$i]["name"]] = utf8_decode(nl2br($data[$i]["value"]));
//         // $mail_txt .= '<p><b>'.utf8_decode($data[$i]["name"]).'</b>: '.utf8_decode(nl2br($data[$i]["value"])).'</p>';
//         $sql .= ($data[$i]["name"]) . '="' . (($data[$i]["value"])) . '", ';
//     }
// }

// $sql = substr($sql, 0, strlen($sql) - 2);
// echo $sql;

//$res = safe_query($sql);

// insert order detail
/*$id_insert = mysqli_insert_id($mylink);

$sql = "insert morp_cms_form_auswertung_detail set aid = ".$id_insert.", quantity = ".$quantity.", price = ".$price."";
safe_query($sql);*/



// $betreff = utf8_decode('Buchungs Bestätigung GLHS');
// $Empfaenger = $row->post;
// $Empfaenger = "b@7sc.eu";
// $Empfaenger = $morpheus["email"];
// $kundemail = $morpheus["email"];

// $mail_txt .= '<tr><td colspan="3"><h1>' . $betreff . ' am ' . date("d.m.Y") . '</h1></td></tr>
// 	';
// 
// foreach ($data_array as $key => $val) {
//   if($fields[$key] != '')  
//     $mail_txt .= '<tr><td valign="top"><p><b>' . utf8_decode($fields[$key]) . '</b></p></td><td> &nbsp; </td><td><p>' . $val . '</p></td></tr>';
// }
// 
// $mail_txt .= '</table>';
// $mail_txt .= utf8_decode($morpheus["mail_footer"]);

// $output = $mail_txt;
// $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');

# # # # -->  $mpdf->WriteHTML($output);
# # # # -->  $mpdf->Output($root.'/pdf/order.pdf','F');

// send mail
# # # # -->  $file = "../pdf/order.pdf";
# # # # -->  sendMailSMTP($email, $betreff, $mail_txt, $file, 0);

// sendMailSMTP("b@7sc.eu", $betreff, $mail_txt, 0, 0);
// sendMailSMTP($Empfaenger, $betreff, $mail_txt, 0, 0); 

//sendMailSMTP("post@pixel-dusche.de", $betreff, $mail_txt);
//sendMailSMTP("vukynamkhtn@gmail.com", $betreff, $mail_txt);
# # # # -->  sendMailSMTP($Empfaenger, $betreff, $mail_txt, $file, 0); 

// redirect from paypal
// if(!$_POST['coupon']) {
//   redirectFrompaypal();
// }
// exit();
