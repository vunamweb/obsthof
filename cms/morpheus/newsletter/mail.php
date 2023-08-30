<?php
/**
* Project........: HTML Mime Mail class
* Last Modified..: 15 July 2002
*/
// echo '-------START MAILING-------<br>';

        error_reporting(none);
        include_once('htmlMimeMail.php');

        $mail = new htmlMimeMail();
        $mail->setHtml($data, strip_tags($pure));

#		$mail->setReturnPath($kundemail);
#		$mail->setSMTPParams('mail.apothekerkammer.de', 25, 'apothekerkammer.de', 1, 'Info-Postfach', 'info');
		$mail->setFrom($kundemail);
#		$mail->setBcc("post@pixel-dusche.de");
		$mail->setSubject($Betreff);
		$mail->setHeader('X-Mailer', 'HTML Mime mail class (http://www.phpguru.org)');
		/**
        * Send it using SMTP. If you're using Windows you should *always* use
		* the smtp method of sending, as the mail() function is buggy.
        */

	if (count($upload)>0) {
		foreach ($upload as $upl) {
			$pfad 		= '../../nldownloads/';
//			$pfad 		= '../../nldownloads/';
#echo			$attachment = $pfad.$upl;
#echo "<br>";
			$attachment = $mail->getFile($pfad.$upl);
			$mail->AddAttachment($attachment,$upl);      // attachment
		}
	}

#		echo "<br>Mail: $Empfaenger<br>";
		$result = $mail->send(array($Empfaenger));
		// print_r($result);
		// These errors are only set if you're using SMTP to send the message
		if (!$result) {
			 echo 'Mail PROBLEM!<br>';
#			print_r($mail->errors);
		} else {
			 echo 'Mail was sent to : '.$Empfaenger.'!<br>';
		}

// echo ' # # # # # # END MAILING # # # # # #<br><br><br>';

?>