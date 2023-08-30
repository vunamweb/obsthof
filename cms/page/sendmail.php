<?php
// use PHPMailer\PHPMailer\PHPMailer;
// require "PHPMailer.php";
// require "SMTP.php";
// require "Exception.php";

session_start();

//  error_reporting(E_ALL);
//	print_r($_POST);

$pixel = isset($_POST["pixel"]) ? $_POST["pixel"] : 0;
$checkit = isset($_SESSION['evC']) ? $_SESSION['evC'] : '';
$send = 1;

include("../nogo/config.php");
include("../nogo/funktion.inc");
include("../nogo/db.php");
dbconnect();
if ($checkit) {
	$checkit = explode("-", $checkit);
	$checkit = $checkit[1];
	$send = $pixel == $checkit ? 1 : 0;
}

$checkMySec = md5($morpheus["code"].date("ymd"));
$sec = isset($_POST["mystring"]) ? $_POST["mystring"] : 0;
// $mid = isset($_POST["mid"]) ? $_POST["mid"] : 0;

$data = json_decode($_POST['data'],true);
$data_arr = array();
foreach($data as $arr) {
	$data_arr[$arr["name"]] = $arr["value"];
}
// print_r($data_arr);

$fid = $data[0];
$fid = $fid["value"];
$fid = check_valid_value($fid, "i");
if(!$fid) die();


$sql = "SELECT * FROM morp_settings WHERE 1";
$res = safe_query($sql);
while($row = mysqli_fetch_object($res)) {
	$morpheus[$row->var] = $row->value;
}

$x = count($data);
$mail_txt = $morpheus["mail_start"];
$mail_txt_copy = $morpheus["mail_start"];
$mail_end = utf8_decode($morpheus["mail_end"]);
$mail_end = str_replace("#ADDRESS#", '<p>'.nl2br(($morpheus["mailfooter"])).'</p>', $mail_end);
$clientMail = '';

// for($i=1; $i<($x); $i++) {
// 	// if(isin('Mail',$data[$i]["name"])) $clientMail = $data[$i]["value"];
// 	
// 	// if(isin('datenschutz',$data[$i]["name"]))
// 	// 	$mail_txt .= '';
// 	// 
// 	// else if(isin('cb_',$data[$i]["name"]))
// 	// 	$mail_txt .= '<tr><td colspan="2">'.str_replace("_", " ", $data[$i]["value"]).'</td></tr>';
// 	// 	
// 	// else
// 	// 	$mail_txt .= '<tr><td valign="top"><span class="small">'.ucfirst(utf8_decode($data[$i]["name"])).':</span></td><td valign="top"><b>'.utf8_decode(nl2br($data[$i]["value"])).'</b></td></tr>';
// }

$sql = "SELECT * FROM `morp_cms_form_field` WHERE fid=$fid ORDER BY reihenfolge";
$res = safe_query($sql);
while($row = mysqli_fetch_object($res)) {
	$feld = $row->feld;
	$bez = $row->desc;
	
	if(isin('mail',$feld)) $clientMail = $data_arr[$feld];
	if($feld)
		$mail_txt .= '<tr><td valign="top"><span class="small">'.utf8_decode($bez).':</span></td><td valign="top" nowrap><b>'.utf8_decode(nl2br($data_arr[$feld])).'</b></td></tr>';
	
}

$mail_txt .= $mail_end;
// echo $clientMail;
// echo($mail_txt);
// echo " $sec && ($sec == $checkMySec) && $send ";

if( $sec && ($sec == $checkMySec) && $send ) {
	$query  	= "SELECT * FROM morp_cms_form WHERE fid=".$fid;
	$result 	= safe_query($query);
	$row 		= mysqli_fetch_object($result);
	$betreff 	= utf8_encode($row->betreff);
	$Empfaenger = $row->post;
	$sendcopy 	= $row->sendcopy;
	$mailTextToClient = $row->mailTextToClient;
	$kundemail 	= $morpheus["email"];
	$name 		= $morpheus["emailname"]; 
	
	// if($mid) {
	// 	$table = "morp_mitarbeiter";
	// 	$tid = "mid";	
	// 	$mid = check_valid_value($mid, "i");
	// 	if(!$mid) die("Fehler 1125");	
	// 	$sql = "SELECT email, name, vorname, email FROM $table WHERE $tid=$mid";
	// 	$res = safe_query($sql);
	// 	$row = mysqli_fetch_object($res);		
	// 	$name = $row->name;	
	// 	$vorname = $row->vorname;	
	// 	
	// 	$Empfaenger = $row->email;	
	// 	
	// 	$betreff .= ' / Nachricht an '.$vorname.' '.$name;
	// }		
	
	$Empfaenger	= "b@7sc.eu";
	// $Empfaenger = "eppler@glsh-stiftung.de";
	
	sendMailSMTP($Empfaenger, $betreff, $mail_txt, '', 1);
	
	if($clientMail && $sendcopy) {
		$mail_txt_copy .= nl2br(utf8_decode($mailTextToClient)) . $mail_end;
		sendMailSMTP($clientMail, 'Ihre Anfrage an '.$morpheus["client"], $mail_txt_copy, '', 0);
	}
}
else echo false;


