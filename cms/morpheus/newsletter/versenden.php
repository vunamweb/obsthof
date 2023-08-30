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

#	mail('post@pixel-dusche.de', 'Apothekerkammer', '01 START // LAK LIVE // versenden cronjob ALLES included');

$dir = 'https://www.apothekerkammer.de/';

include_once ("/var/www/vhosts/apothekerkammer.de/httpdocs/nogo/config.php");
include_once("/var/www/vhosts/apothekerkammer.de/httpdocs/nogo/funktion.inc");
include_once("/var/www/vhosts/apothekerkammer.de/httpdocs/nogo/db_nl.php");
include_once("/var/www/vhosts/apothekerkammer.de/httpdocs/morp_edm/newsletter/function.php");

dbconnect();

#$inhalt =		mail('post@pixel-dusche.de', 'Apothekerkammer', 'versenden - alle funktionen geladen');
#save_data("mail-check-".date("Y-m-d").".txt",$inhalt.", \t".date("H:i:s")."\n","a+");

#				$sql 	= "INSERT aaa set text='$inhalt ---FUNKTIONEN GELADEN--- versandzeit=".date("H:i:s").", vdat=".date("Y-m-d")."'";
#				safe_query($sql);

//include("cms_header.php");
//include("../login.php");

#	mail('post@pixel-dusche.de', 'Apothekerkammer', 'LAK LIVE // versenden cronjob ALLES included');

$versandzeit = date("H:i:s");
$vdat = date("Y-m-d");

$liste 	= isset($_GET["liste"]) ? $_GET["liste"] : '';
$live 	= isset($_GET["live"]) ? 1 : '';
$livetrue = isset($_GET["livetrue"]) ? 1 : '';
$stopimport = isset($_GET["stopimport"]) ? 1 : '';
$imp 	= isset($_GET["imp"]) ? 1 : '';
$csv 	= isset($_GET["csv"]) ? 1 : '';
// $nlgetid = isset($_GET["nlid"]) ? $_GET["nlid"] : '';
// $nlid = $nlgetid;
$bis 	= isset($_GET["bis"]) ? $_GET["bis"] : 0;
$max 	= 70;


?>

<div id=vorschau style="text-align:center;">
<p><a href="../newsletter.php?edit=<?php echo $nlgetid; ?>">&laquo; zurück</a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<?php
if($live && !$livetrue) {
	echo '<p><a href="?live='.$live.'&liste='.$liste.'&livetrue=1&imp=1&nlid='.$nlgetid.'" style="font-weight:bold;color:#ff0000;">&raquo; EXCEL LISTE WIRD IMPORTIERT UND VERSAND GESTARTET !!!</a></p>';
}
else {
	//if(!$liste) echo '<p><a href="?live='.$live.'&liste='.$liste.'&livetrue=1&nlid='.$nlid.'&stopimport=1">&raquo; WEITER VERSENDEN !!!!!!!!</a></p>';
	if(!$liste) echo '<p><a href="?nlid='.$nlgetid.'">&raquo; WEITER VERSENDEN !</a></p>';

	$verteiler = 1;
	$id 	= "vid";

	if ($live && $imp) 	{
		echo "import start";
		include("newsletter_set_vt.php");
		echo "----- import ende";
	}

	//	 die();

	$db	= "morp_newsletter_vt_lak_live";
	$sql = "SELECT * FROM $db WHERE versand<1 ORDER BY vid ASC LIMIT 0,$max ";
	//echo "<br>";

	$ress = safe_query($sql);

	// mail('post@pixel-dusche.de', 'Apothekerkammer', $sql.' - SQL');

	$db = "morp_newsletter_cont_lak";
	$id = "nlid";
	$anz = "text";


	if (mysql_num_rows($ress) < 1) { echo '<br><br>KEINE NEWSLETTER MEHR ZU VERSENDEN !!!!'; die(); }
	else {
		while($row = mysql_fetch_object($ress)) {
			echo $sendto = $row->email;
			echo " | ";

			/* --------------------------------------------------------------- */
			/* NEWSLETTER DETAILS UND TEXT EINLESEN */
			$nlgetid = $row->nlid;

			if(!$nlgetid) die();

			$nlid = $nlgetid;

			$sql = "SELECT * FROM $db n LEFT JOIN morp_newsletter_filter_1 f ON f.f1id=n.nlrubrik WHERE $id=".$nlgetid." ORDER BY nlsort";
			$res = safe_query($sql);

			/* * * * *  VAR * * * * */
			$getHTML = '';
			/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


			/* * * * * * GRUNDEINSTELLUNGEN Mailing * * * * * * * * * */
			$sql = "SELECT nlart, nlpreheader, nlname, nlsubj FROM morp_newsletter_lak WHERE nlid=".$nlgetid;
			$rs = safe_query($sql);
			$rw = mysql_fetch_object($rs);

			/* * * * get Content of Mailing * * * * */
			$container = getContJaguar($res, $nlid);

			$file = "design";

			$preheader = $rw->nlpreheader;
			$ct = strlen($preheader);
			$leer = '';
			for($i=$ct; $i<=255; $i++) {
				$leer .= ' &nbsp;';
			}
			$Betreff 	= $rw->nlsubj;

			/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
			/****** PLATZHALTER ******************************/
			$platzhalter = $row->platzhalter;
			$platzhalter = explode(",", $platzhalter);


			$lak_nav = '<h1 style="font-family: Arial, Helvetica, sans-serif;font-size: 14px;line-height:20px;color: #e30613; text-transform: uppercase; letter-spacing: 3px; margin-bottom:10px;">Inhalt</h1>';

			$sql = "SELECT f1id FROM morp_newsletter_cont_lak d1, morp_newsletter_filter_1 d2 WHERE d2.f1id=d1.nlrubrik AND $id=".$nlgetid;
			$rs = safe_query($sql);
			$filter = array();
			while($rw = mysql_fetch_object($rs)) {
				$filter[] = $rw->f1id;
			}

			// print_r($filter);

			$sql = "SELECT f1name, f1link, f1id FROM morp_newsletter_filter_1 WHERE f1vi=1 ORDER by f1sort";
			$rs = safe_query($sql);
			while($rw = mysql_fetch_object($rs)) {
				$f1id = $rw->f1id;
				$link = $rw->f1link;
				if(in_array($f1id, $filter)) $lak_nav .= '<a href="'.($link ? $link : '#'.$rw->f1name).'" style="font-family: Arial, Helvetica, sans-serif;font-size: 14px;line-height:20px;color: #000;text-decoration: none;font-weight: bold;">'.$rw->f1name.'</a><br>';
			}

			$lak_nav .= '
			                                                        <hr style="border: none; border-bottom: solid 2px #fff;margin: 20px 0;">
		'.$extratext;



			$raus = array('/#here_comes_the_message#/', '/#preheader#/', '/#title#/', '/#inhalt#/');
			$rein = array($container, $preheader, $Betreff, $lak_nav);

			foreach($platzhalter as $val) {
				$val = trim($val);
				if($val) {
					$rein[] = $verteiler[$val];
					$raus[] = '/#'.$val.'#/';
				}
			}

			$raus[] = "/\"img\/cta.png\"/";
			$rein[] = '"'.$dir.'img/cta.png"';

			$raus[] = "/\"img\/cta.png\"/";
			$rein[] = '"'.$dir.'img/cta.png"';

			$raus[] = "/#weblink#/";
			$rein[] = 'https://www.apothekerkammer.de/service/lak+aktuell/lak+aktuell+ausgabe-'.$nlid.'/';


			// echo ' # # # # # # END MAILING # # # # # #<br><br><br>';
			// echo $data;

			if($liste) {}
			else $nlid = $row->nlid;

			/* --------------------------------------------------------------- */
			/* --------------------------------------------------------------- */

			/* --------------------------------------------------------------- */
			/* PLATZHALTER IN TEXT EINFÜGEN */
			$data = create_html_doc($row->text, $nlid, $file);
			$data = preg_replace($raus, $rein, $data);
			$pure = preg_replace($raus, $rein, $row->text);

#			print_r($raus);
#			print_r($rein);

			$data 	= preg_replace(array("/<!-- vid -->/","/&lt;!--%20vid%20--&gt;/","/<!-- mail -->/","/&lt;!--%20mail%20--&gt;/","/<img src=\"\/nldownloads/","/#spacer#/"), array($row->vid, $row->vid, $row->email, $row->email, '<img src="https://www.apothekerkammer.de/nldownloads', '<img src="https://www.apothekerkammer.de/timthumb.php?src=img/spacer.png&space='.$row->vid.'"/>'), $data);


			if($liste) {}
			else {
				$sql 	= "UPDATE morp_newsletter_vt_lak_live set versand=1, versandzeit='".date("H:i:s")."', vdat='".date("Y-m-d")."' WHERE vid=".$row->vid;
				safe_query($sql);
			}

			$kundemail 	= 'info@apothekerkammer.de';
			$Empfaenger = $sendto;
			// print_r($Empfaenger);
			//flush();

			save_data(date("Y-m-d").".txt",$sendto.", \t".date("H:i:s")."\n","a+");

			include("/var/www/vhosts/apothekerkammer.de/httpdocs/morp_edm/newsletter/mail.php");
			echo "--- mail gesendet ---<br/>";

			// echo $mail_txt;
			// echo "<br><br><br>";
		}
	}

	// echo $data;

		flush();
		//die();
		#sleep(2);

		// echo ' 		<script language="JavaScript">document.location="newsletter_wait.php?nlid='.$nlgetid.'";</script> 		';
}

?>