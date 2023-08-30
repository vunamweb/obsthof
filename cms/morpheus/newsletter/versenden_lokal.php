<?php
session_start();
error_reporting("E_ALL");
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

// mail('post@pixel-dusche.de', 'Apothekerkammer', 'versenden cronjob START');

include_once ("../../nogo/config.php");
include_once("../../nogo/funktion.inc");
include_once("../../nogo/db.php");
include_once("function.php");

dbconnect();

// include_once("/var/www/vhosts/apothekerkammer.de/httpdocs/morpheus3/0_gremien.php");

#$inhalt =		mail('post@pixel-dusche.de', 'Apothekerkammer', 'versenden - alle funktionen geladen');
#save_data("mail-check-".date("Y-m-d").".txt",$inhalt.", \t".date("H:i:s")."\n","a+");

#				$sql 	= "INSERT aaa set text='$inhalt ---FUNKTIONEN GELADEN--- versandzeit=".date("H:i:s").", vdat=".date("Y-m-d")."'";
#				safe_query($sql);


//include("cms_header.php");
//include("../login.php");

//	mail('post@pixel-dusche.de', 'Apothekerkammer', 'versenden cronjob ALLES included');

$versandzeit = date("H:i:s");
$vdat = date("Y-m-d");

$liste 	= isset($_GET["liste"]) ? $_GET["liste"] : '';
$live 	= isset($_GET["live"]) ? 1 : '';
$livetrue = isset($_GET["livetrue"]) ? 1 : '';
$stopimport = isset($_GET["stopimport"]) ? 1 : '';
$imp 	= isset($_GET["imp"]) ? 1 : '';
$csv 	= isset($_GET["csv"]) ? 1 : '';
$nlgetid = isset($_GET["nlid"]) ? $_GET["nlid"] : '';

$bis 	= isset($_GET["bis"]) ? $_GET["bis"] : 0;
$max 	= 100;
?>

<div id=vorschau style="text-align:center;">
<p><a href="../newsletter.php?edit=<?php echo $nlgetid; ?>">&laquo; zurück</a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<?php
if($live && !$livetrue) {
	echo '<p><a href="?live='.$live.'&liste='.$liste.'&livetrue=1&imp=1&nlid='.$nlgetid.'" style="font-weight:bold;color:#ff0000;">&raquo; VERSENDEN !!!!!!!!</a></p>';
}
else {
	//if(!$liste) echo '<p><a href="?live='.$live.'&liste='.$liste.'&livetrue=1&nlid='.$nlid.'&stopimport=1">&raquo; WEITER VERSENDEN !!!!!!!!</a></p>';
	if(!$liste) echo '<p><a href="?">&raquo; WEITER VERSENDEN !</a></p>';

	$verteiler = 1;
	$id 	= "vid";

	if ($live && $imp) 	{
		echo "import start";
		include("newsletter_set_vt.php");
		echo "import ende";
	}

	//	 die();

	if ($liste && $csv) 	{
		$db  = "morp_newsletter_vt_csv";
	}
	elseif ($liste) 	{
		$db  = "morp_newsletter_vt_test";
	}
	else {
		$db	= "morp_newsletter_vt";
	}

	//echo $db."<br>";

	$sql = "SELECT * FROM $db WHERE versand<1 LIMIT 0,$max";
	//echo "<br>";

	$res = safe_query($sql);

	// mail('post@pixel-dusche.de', 'Apothekerkammer', $sql.' - SQL');

	if (mysql_num_rows($res) < 1) { echo '<br><br>KEINE NEWSLETTER MEHR ZU VERSENDEN !!!!'; die(); }
	else {
		while($row = mysql_fetch_object($res)) {
			echo $sendto = $row->email;
			echo " ";


			/* --------------------------------------------------------------- */
			/* NEWSLETTER DETAILS UND TEXT EINLESEN */
			if($nlgetid) $sql = "SELECT * FROM morp_newsletter WHERE nlid=".$nlgetid;
			else $sql = "SELECT * FROM morp_newsletter WHERE nlid=".$row->nlid;
			$rs  = safe_query($sql);
			$rw = mysql_fetch_object($rs);

			$kundemail	= $rw->nlmail;
			$name 		= $rw->nlmailname;
			$txt 		= $rw->text;

			/* --------------------------------------------------------------- */
			/* PLATZHALTER AUSLESEN  */
			$platzhalter = $row->platzhalter;
			$platzhaltertext = $row->platzhaltertext;

			$platzhalter = explode("\n", $platzhalter);
			$platzhaltertext = explode("\n", $platzhaltertext);

			$raus = array('/#banner#/');
			$rein = array(getImg ($rw->banner));

			for($i=0; $i<count($platzhalter); $i++) {
				$val = trim($platzhalter[$i]);
				if($val) {
					$rein[] = trim(utf8_decode($platzhaltertext[$i]));
					$raus[] = '/#'.trim($platzhalter[$i]).'#/';
				}
			}

			if($liste) {}
			else $nlid = $row->nlid;

			/* --------------------------------------------------------------- */
			/* --------------------------------------------------------------- */

			/* --------------------------------------------------------------- */
			/* PLATZHALTER IN TEXT EINFÜGEN */
			$senddata 	= create_html_doc($txt);
			$senddata	= preg_replace($raus, $rein, $senddata);

#			print_r($raus);
#			print_r($rein);

			$mail_txt 	= preg_replace(array("/<!-- vid -->/","/&lt;!--%20vid%20--&gt;/","/<!-- mail -->/","/&lt;!--%20mail%20--&gt;/","/<img src=\"\/nldownloads/",), array($row->vid, $row->vid, $row->email, $row->email, '<img src="http://www.apothekerkammer.de/nldownloads'), $senddata);

	        # &lt;!--%20mail%20--&gt;
			$raw 		= strip_tags($mail_txt);
			$raw 		= str_replace(array("\t", "\n\r",  '&raquo;', '&nbsp;'), array("", "", "", ""), $raw);

			$Betreff 	= utf8_decode($rw->nlsubj);

			/* --------------------------------------------------------------- */
			/* UPLOADS / ANHAENGE -------------------------------------------- */
			//$upload = array("20130503-test_1.pdf");
			$upload = array();
			for($u=1; $u<=6;$u++) {
				$pdf = "pdf".$u;
				if($rw->$pdf) $upload[]=$rw->$pdf;
			}

			if($liste) {}
			else {
				$sql 	= "UPDATE morp_newsletter_vt set versand=1, versandzeit='".date("H:i:s")."', vdat='".date("Y-m-d")."' WHERE vid=".$row->vid;
				safe_query($sql);
			}

			$Empfaenger = $sendto;
			 print_r($Empfaenger);
			flush();

			save_data(date("Y-m-d").".txt",$sendto.", \t".date("H:i:s")."\n","a+");

#			include("../../morpheus/newsletter/phpmailer.php");
			include("mail.php");
#			echo "--- mail gesendet ---<br/>";

#				mail('post@pixel-dusche.de', 'Apothekerkammer Sende Bestaetigung', $sendto.' versendet');
			// echo $mail_txt;
			// echo "<br><br><br>";
		}
	}
	echo $mail_txt;

		flush();
		#die();
		#sleep(2);

		echo '
		<script language="JavaScript">document.location="newsletter_wait.php";</script>
		';
}

?>