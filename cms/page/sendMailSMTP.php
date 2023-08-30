<?php
use PHPMailer\PHPMailer\PHPMailer;
require "PHPMailer.php";
require "OAuth.php";
require "SMTP.php";
require "Exception.php";

function sendMailSMTP($to, $subject, $message, $return=1) {
	global $morpheus, $mailSendSuccess;
	$mailSendSuccess = 0;
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 1; 	// enables SMTP debug information (for testing)
    						// 1 = errors and messages
    						// 2 = messages only
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = "tls";
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

