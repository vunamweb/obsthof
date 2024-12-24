<?php
/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

global $morpheus;

include("../nogo/config.php");
include("../nogo/funktion.inc");

// $to = "janskibicki@gmail.com";
//$to= "vukynamkhtn@gmail.com";
$to = "info@obsthof-am-steinberg.de";
// $to = "b@7sc.eu";

if($_POST) {
	$header = $morpheus["mail_start"];
	
	//print_r($_POST); 
	// die();

	$personen = $_POST['personen'];
	$kinder_7 = $_POST['kinder-7'];
	$kinder_3 = $_POST['kinder-3'];
	
	$fullname = $_POST['fullname'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$datum = $_POST['datum'];
	$uhrzeit = $_POST['uhrzeit'];
	
	if(!$fullname || !$email) die('Fehler!');
	
	$begruessung = $_POST['begruessung'];
	$biobuffet = $_POST['biobuffet'];
	$kaffee = $_POST['kaffee'];
	$raclette = $_POST['raclette'];
	$grand_total = $_POST['grand-total'];


	$totalspeisen = $_POST["total-speisen"];
	$totalfwar = $_POST["total-fw-ar"];
	$totallw = $_POST["total-lw"];
	$totalcatering = $_POST["total-catering"];
	$totalzelt = $_POST["total-zelt"];
	$totalservice = $_POST["total-service"];
	$grandtotal = $_POST["grand-total"];
	
	$lagenwanderung = $_POST["lagenwanderung"];
	$fremdcatering = $_POST["fremdcatering"];
	$zelt = $_POST["zelt"];
	$servicek = $_POST["servicek"];
	$serviceh = $_POST["serviceh"];
	$nachricht = $_POST["nachricht"];

	$anlass = $_POST["anlass"];
	$subject = "Anfrage vom Anfrageformular - ".$anlass;
	$subjectCopy = "Vielen Dank für Deine Anfrage - ".$anlass;

	$message = $header;
	$messageCopy = $header.'<p>Vielen Dank für Deine Anfrage.<br/><br/>Wir melden uns schnellstmöglich bei Dir.<br/><br/>Dein Team vom<br/>Obsthof am Steinberg</p><p>&nbsp;</p>';

	$messageText .= '<table style="background:#ddd; padding:30px;">';
	$messageText .= '<tr><td colspan="2" align="center"><h2>' . $anlass . '</h2></td></tr>';
	$messageText .= '<tr><td>Name: </td><td>' . $fullname . '</td></tr>';
	$messageText .= '<tr><td>E-Mail: </td><td>' . $email . '</td></tr>';
	$messageText .= '<tr><td>Telefon: </td><td>' . $phone . '</td></tr>';
	$messageText .= '<tr><td>Datum: </td><td>' . $datum . '</td></tr>';
	$messageText .= '<tr><td>Uhrzeit: </td><td>' . $uhrzeit . '</td></tr>';
	$messageText .= '<tr><td>Erwachsene: </td><td>' . $personen . '</td></tr>';
	$messageText .= '<tr><td>Kinder 4-7 Jahre und Personen mit Behindertenausweis: </td><td>' . $kinder_7 . '</td></tr>';
	$messageText .= '<tr><td>Kinder 0-3 Jahre: </td><td>' . $kinder_3 . '</td></tr>';
	$messageText .= '<tr><td>Begrüßung: </td><td>' . $begruessung . '</td></tr>';
	$messageText .= '<tr><td>Bio Buffet: </td><td>' . $biobuffet . '</td></tr>';
	$messageText .= '<tr><td>Kaffee & Kuchen: </td><td>' . $kaffee . '</td></tr>';
	$messageText .= '<tr><td>Fackelwanderung und Apfel-Raclette: </td><td>' . $raclette . '</td></tr>';
	
	$messageText .= '<tr><td>Lagenwanderung: </td><td>'.$lagenwanderung.'</td></tr>';
	$messageText .= '<tr><td>Fremdcatering: </td><td>'.$fremdcatering.'</td></tr>';
	$messageText .= '<tr><td>Zelt: </td><td>'.$zelt.'</td></tr>';
	$messageText .= '<tr><td>Servicekräfte: </td><td>'.$servicek.'</td></tr>';
	$messageText .= '<tr><td>Service in Stunden: </td><td>'.$serviceh.'</td></tr>';
	$messageText .= '<tr><td>Nachricht: </td><td>'.nl2br($nachricht).'</td></tr>';
	
	$messageTextCalculation .= '<tr><td colspan="2"><hr></td></tr>';
	$messageTextCalculation .= '<tr><td>Speisen und Getränke</td><td>'.$totalspeisen.'</td></tr>';
	$messageTextCalculation .= '<tr><td>Raclette und Wanderung</td><td>'.$totalfwar.'</td></tr>';
	$messageTextCalculation .= '<tr><td>Lagenwanderung</td><td>'.$totallw.'</td></tr>';
	$messageTextCalculation .= '<tr><td>Catering</td><td>'.$totalcatering.'</td></tr>';
	$messageTextCalculation .= '<tr><td>Zelt</td><td>'.$totalzelt.'</td></tr>';
	$messageTextCalculation .= '<tr><td>Service</td><td>'.$totalservice.'</td></tr>';
	$messageTextCalculation .= '<tr><td><b>Total</b></td><td><b>'.$grandtotal.'</b></td></tr>';

	// $message .= '<tr><td></td><td><b>Total</b>: </td><td>' . $grand_total . '</td></tr>';
	
	$footer = '</table>'.$morpheus["mail_end"];

	$message .= $messageText.$messageTextCalculation.$footer;
	
	$footer = '</table>'.str_replace('<adress>','<p class="small">Obsthof am Steinberg<br/>Am Steinberg 24<br/>60437 Frankfurt / Nieder-Erlenbach<br/><br/>Telefon Büro:<br/>06101 - 987 57 25</p>',$morpheus["mail_end"]);
	
	$messageCopy .= $messageText.$footer;
	// if($fullname && $email) {
		echo sendMailSMTP($to, $subject, $message);
		sendMailSMTP($email, $subjectCopy, $messageCopy);
		return;		
	// }
} else {
	$message = 'test';
	// echo sendMailSMTP($to, $subject, $message);
}
// $url =  $morpheus['url'
// . 'page/calculation/index.html';
// $output = '<div class="form_calculation">' . file_get_contents($url) . '</div>';
