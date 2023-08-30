<?php

global $dir, $navID, $SID, $acceptCookie, $morpheus;

require_once("inc/send.php");

$eventId = $_REQUEST['cancelEvent'];
$submit = isset($_POST["submit"]) ? 1 : '0';
$email = $_POST['email'];
$note_cancel_event = $_POST['note_cancel_event'];

$to = 'vukynamkhtn@gmail.com';

if ($submit) {
    $sql = "UPDATE `morp_cms_form_auswertung` SET register = 0 WHERE `aid`= " . $eventId;
    $res = safe_query($sql);

    $mailsubject = 'Cancel Event';
    $mailbody .= $note_cancel_event;
    $mail_txt .= $morpheus["mail_start"] . $mailbody . $morpheus["mail_end"];

    sendMailSMTP($to, $mailsubject, $mail_txt, 0);

    $output = '<div class="alert alert alert-success" role="alert">Your request has been sent to admin</div><p>&nbsp;</p>';
} else {
    $output .= getFormCancelEvent($eventId);
}
