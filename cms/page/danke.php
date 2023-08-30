<?php
session_start();

global $form_desc, $cid, $navID, $lan, $nosend, $morpheus, $ssl_arr, $ssl, $lokal_pfad, $js;

$uri	 = $_SERVER["REQUEST_URI"];


// print_r($_REQUEST);

$absenden = $_POST["absenden"];
$ei = $_POST["eintrag"];

$checkit = isset($_SESSION['evC']) ? $_SESSION['evC'] : '';
$send = 0;

if ($checkit) {
	$checkit = explode("-", $checkit);
	$checkit = $checkit[1];
	$send = $ei == $checkit ? 1 : 0;
}

if ($send) {
	// felder werden ausgelesen
	$query  = "SELECT * FROM form_field WHERE fid=".$_POST["fid"]." ORDER BY reihenfolge";
	$result = safe_query($query);
	while ($row = mysqli_fetch_object($result)) {
		$feld = $row->feld;
		$art = $row->art;

		if (isin("\|", $feld)) {
			$t	 = explode("|", $feld);
			$feld 	= $t[0];
			$value  = $t[1];
		}

		$desc = str_replace(array("√º"), array("¸"), $row->desc);
		$post = utf8_decode($_POST[$feld]);
		$c = strlen($feld)*-1;
		# if ($feld && $post) $mail .= $feld.':'.substr($filler,0,$c).str_replace("\r\n", "\n", $post)."\n";
		if ($feld && $art == "Mitteilungsfeld") $mail .= '<p>'.utf8_decode($desc).': &nbsp; &nbsp; &nbsp; &nbsp; <span style="color:#000;">'.nl2br($post).'</span></p>
';
		elseif ($feld) $mail .= '<p>'.utf8_decode($desc).': &nbsp; &nbsp; &nbsp; &nbsp; <span style="color:#000;">'.$post.'</span></p>
';
	}

	// formular einstellungen laden
	$query  	= "SELECT * FROM form WHERE fid=".$_POST["fid"];
	$result 	= safe_query($query);
	$row 		= mysqli_fetch_object($result);

	$fname 		= $row->fname;
	$Betreff 	= $row->betreff;

	$fname  	= 'Betreff:'.substr($filler,0,-7).$fname."\n\n";
	$mail_txt 	= email_style().$mail;

	$Empfaenger = array($row->post);

	$mailVonKunde = $_POST["email"];
	if(!$mailVonKunde) $mailVonKunde = $row->post;

	// $Empfaenger	= array("post@pixel-dusche.de");
	$kundemail 	= $morpheus["email"];			#$email; #"b.knetter@gmx.de";
	// $kundemail 	= "post@pixel-dusche.de";
	$name 		= $morpheus["emailname"]; 	#$email; #"b.knetter@gmx.de";
	$reply 		= $row->post;
	$replyNM 	= $morpheus["emailname"];
	$sender 	= $row->post;
	$sendername = $morpheus["emailname"];

	include("page/mail.php");

	#$output = nl2br($row->antwort).'<p>--------------------E M A I L---------------------------</p>'.$mail_txt;
	$output .= '<p><strong>'.nl2br($row->antwort).'</strong></p>';

	global $popup;
	$popup='<p><strong>'.nl2br($row->antwort).'</strong></p>';
}

elseif ($absenden) {
	$output .= '<p> &nbsp; </p> <p> &nbsp;</p> <p> &nbsp;</p> <p><strong style="color:#ff0000;">Wir konnten Ihre Anfrage leider nicht annehmen.</strong></p> <p> &nbsp;</p> <p>&nbsp; </p> <p> &nbsp;</p> <p> &nbsp;</p> ';
}

?>