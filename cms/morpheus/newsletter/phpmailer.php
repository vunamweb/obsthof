<?php
require_once '/var/www/vhosts/apothekerkammer.de/httpdocs/morpheus/newsletter/class.phpmailer.php';

$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
	
try {
	$mail->AddReplyTo($kundemail, $name);
	$mail->SetFrom($kundemail, $name);
	$mail->Subject = $Betreff;
	$mail->Body = $mail_txt;
	if($raw) $mail->AltBody = $raw;
	$mail->Sender = $kundemail;
	# $mail->MsgHTML(file_get_contents(strip_tags($mail_txt)));
  
	if (count($upload)>0) {
		foreach ($upload as $upl) {
			$pfad 		= '/var/www/vhosts/apothekerkammer.de/httpdocs/nldownloads/';
			$attachment = $pfad.$upl;
			$mail->AddAttachment($attachment);      // attachment
		}
	}
		
//	$mail->AddAddress($bcc, $name);

  	$mail->Mailer            = 'smtp';

	$mail->AddAddress($sendto, $sendto);
	$mail->Send();
  	# echo "Message Sent OK</p>\n";
				mail('post@pixel-dusche.de', 'Apothekerkammer PHP MAILER', $sendto.' OK');
} catch (phpmailerException $e) {
	 echo $e->errorMessage(); //Pretty error messages from PHPMailer
				mail('post@pixel-dusche.de', 'Apothekerkammer PHP MAILER', $e->errorMessage().' NO');
} catch (Exception $e) {
	 echo $e->errorMessage(); //Boring error messages from anything else!
				mail('post@pixel-dusche.de', 'Apothekerkammer PHP MAILER', $e->errorMessage().' NO2');
}

// echo "$reply, $replyNM - $sender, $sendername - $Empfaenger, $name #### $bcc, $name";
// echo "<p>$Empfaenger, $name</p>";
# die();
?>