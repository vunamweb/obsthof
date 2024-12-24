<?php
/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

global $morpheus;

if($_POST) {
    include("../nogo/config.php");
    include("../nogo/funktion.inc");

    $subject = "calculation form";
    $to = "info@obsthof-am-steinberg.de";
	// $to = "b@7sc.eu";
	
    $header = $morpheus["mail_start"];
    
	// print_r($_POST); 
	// die();

    $personen = $_POST['personen'];
    $kinder_7 = $_POST['kinder-7'];
    $kinder_3 = $_POST['kinder-3'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $datum = $_POST['datum'];
    $uhrzeit = $_POST['uhrzeit'];
    $begruessung = $_POST['begruessung'];
    $biobuffet = $_POST['biobuffet'];
    $kaffee = $_POST['kaffee'];
    $raclette = $_POST['raclette'];
    $grand_total = $_POST['grand-total'];

    $message = $header;

    $message .= 'Name: ' . $fullname . '</td></tr>';
    $message .= '<tr><td>' . 'E-Mail: ' . $email . '</td></tr>';
    $message .= '<tr><td>' . 'Telefon: ' . $phone . '</td></tr>';
    $message .= '<tr><td>' . 'Datum: ' . $datum . '</td></tr>';
    $message .= '<tr><td>' . 'Uhrzeit: ' . $uhrzeit . '</td></tr>';
    $message .= '<tr><td>' . 'Erwachsene: ' . $personen . '</td></tr>';
    $message .= '<tr><td>' . 'Kinder 4-7 Jahre und Personen mit Behindertenausweis: ' . $kinder_7 . '</td></tr>';
    $message .= '<tr><td>' . 'Kinder 0-3 Jahre: ' . $kinder_3 . '</td></tr>';
    $message .= '<tr><td>' . 'Begrüßung: ' . $begruessung . '</td></tr>';
    $message .= '<tr><td>' . 'Bio Buffet: ' . $biobuffet . '</td></tr>';
    $message .= '<tr><td>' . 'Kaffee & Kuchen: ' . $kaffee . '</td></tr>';
    $message .= '<tr><td>' . 'Fackelwanderung und Apfel-Raclette: ' . $raclette . '</td></tr>';
    $message .= '<tr><td>' . '<b>Total</b>: ' . $grand_total . '</td></tr>';
    
    $footer = $morpheus["mail_end"];

    $message .= $footer;

    // sendMailSMTP($to, $subject, $message);
    return;
}
$url =  $morpheus['url'] . 'page/calculation/index.html';
$output = '<div class="form_calculation">' . file_get_contents($url) . '</div>';
