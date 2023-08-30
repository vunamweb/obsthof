<?php
use PHPMailer\PHPMailer\PHPMailer;
include("./nogo/config.php");
require $_SERVER['DOCUMENT_ROOT'] . $morpheus['subFolder'] ."inc/mail/PHPMailer.php";
require $_SERVER['DOCUMENT_ROOT'] . $morpheus['subFolder'] . "inc/mail/SMTP.php";
require $_SERVER['DOCUMENT_ROOT'] . $morpheus['subFolder'] . "inc/mail/Exception.php";

session_start();
$SID = session_id();

global $morpheus, $dir;

if(isset($_GET["em"])) $mail = trim($_GET["em"]);
else if(isset($_POST["mymail"])) $mail = trim($_POST["mymail"]);
else $mail = false;

if($mail) {	
	$table = "morp_newsletter_vt_live";
	$tid = "vid";
	$tmail = "email";
	
 	$sql = "SELECT * FROM $table WHERE $tmail='$mail'";
	$res = safe_query($sql);
	if(mysqli_num_rows($res)<1) {
		$output .= '<div class="alert alert-success" role="alert">Ihre E-Mail Adresse ist uns nicht bekannt</div>';
	} else {
		$output .= '<div class="alert alert-success text-center" role="alert">Wir haben Ihre E-Mail Adresse aus dem Verteiler gelöscht</div>';
		
		$sql = "DELETE FROM $table WHERE $tmail='$mail'";
		$res = safe_query($sql);
		
		$mail_txt = $morpheus["mail_start"].utf8_decode('
<h1>Newsletter Kündigung</h1>	
<p>Wir haben Ihre E-Mail Adresse aus dem Verteiler gelöscht.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Ihr '.$morpheus["client"].'</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<!--<p><b>'.nl2br($morpheus["vcard"]).'</b></p>-->
<p>&nbsp;</p>
<p>'.($morpheus["mobile"]).'</p>
<p><a href="mailto:'.$morpheus["email"].'">'.$morpheus["email"].'</a></p>
<p>&nbsp;</p>
<p>'.nl2br(utf8_decode($morpheus["subline"])).'</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<a href="'.$morpheus["url"].'datenschutz/">Datenschutz</a> | <a href="'.$morpheus["url"].'agb/">AGB</a> | <a href="'.$morpheus["url"].'cookie/">Cookie-Einstellungen</a>
');

		$mail_txt .= $morpheus["mail_end"];
		
		$subject = utf8_decode("Kündigung Newsletter");
		// $to = "b@7sc.eu";
		$to = $mail;
		
		sendMailSMTP($to, $subject, $mail_txt, 0);
	}
	
}	
else {
	$output .= '
<form method="post">
	<div class="row">
		<div class="col-12">
			<div id="message" class="alert alert-success hide mt2">xxx</div>
			<input type="text" class="form-control register" name="mymail" placeholder="E-Mail*"/>
			<button type="submit" name="register" value="Newsletter abmelden" class="btn btn-info btn-send btn-register">Newsletter abmelden</button>
		</div>
	</div>
</form>
';	
}

function sendMailSMTP($to, $subject, $message, $return=1) {
	global $morpheus, $mailSendSuccess;
	$mailSendSuccess = 0;
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug = 0; 	// enables SMTP debug information (for testing)
							// 1 = errors and messages
							// 2 = messages only
	$mail->SMTPAuth = true; 
	$mail->SMTPSecure = "ssl";
	$mail->Host = $morpheus["Host"]; 
	$mail->Port = $morpheus["Port"];
	$mail->Username = $morpheus["Username"];
	$mail->Password = $morpheus["Password"];
	// $mail->addBcc("post@pixel-dusche.de");
	$mail->AddAddress($to);
	$mail->Subject = $subject;
	$mail->FromName = $morpheus["FromName"];
	$mail->From = $morpheus["From"];
	$mail->IsHTML(true);
	$mail->Body = $message;
	
	if (!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else if($return) {
		echo "register";
		$mailSendSuccess = 1;
	}
	else { 
		$mailSendSuccess = 1;
	}
}
