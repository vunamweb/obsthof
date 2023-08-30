<?php
/**
* Filename.......: example.1.php
* Project........: HTML Mime Mail class
* Last Modified..: 15 July 2002
*/

//        error_reporting(E_ALL);
        include_once('htmlMimeMail.php');

        $mail = new htmlMimeMail();
        $mail->setHtml($mail_txt, strip_tags($mail_txt));
        #$mail->setText($mbody);
		#$mail->addHtmlImage($background, 'background.gif', 'image/gif');

		// $mail->setReturnPath($Empfaenger);
		// $mail->setReturnPath($mailVonKunde);

		/**
        * Set some headers
        */


        $mail->setReply($mailVonKunde);

		$mail->setFrom('"' .$name .'" <' .$kundemail .'>');
//		if ($bcc) $mail->setBcc( $bcc.' <'.$bcc.'>' );
//		$mail->setReply($em);
		$mail->setSubject($Betreff);
		$mail->setHeader('X-Mailer', 'HTML Mime mail class (http://www.phpguru.org)');

		/**
        * Send it using SMTP. If you're using Windows you should *always* use
		* the smtp method of sending, as the mail() function is buggy.
        */
		# $result = $mail->send(array($Empfaenger), 'smtp');
		$result = $mail->send($Empfaenger);

		// These errors are only set if you're using SMTP to send the message
		if (!$result) {
			echo "NOT SENT :(";
			print_r($mail->errors);
		} else {
			 // echo 'Mail sent!';
		}

?>