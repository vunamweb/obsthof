<?php
/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

global $morpheus;

if($_POST) {
    include("../nogo/config.php");
    include("../nogo/funktion.inc");

    $subject = "calculation form";
    $to = "vukynamkhtn@gmail.com";
    $message = "shop";

    sendMailSMTP($to, $subject, $message);
    return;
}
$url =  $morpheus['url'] . 'page/calculation/index.html';
$output = '<div class="form_calculation">' . file_get_contents($url) . '</div>';
?>
