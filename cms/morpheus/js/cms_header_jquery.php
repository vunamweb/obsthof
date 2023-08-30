<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<head>
	<title>content-management-system - pixel-dusche.de, pixeldusche.com</title>

	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<meta name="KEYWORDS" content="webdesign gestaltung internet php mysql online-shops cms c-m-s content management individual l&ouml;sung flash programmierung frankfurt main ebusiness ecommerce neue medien new media business2business b2b benutzeroberfl&auml;chen navigation pflege-tools">
	<meta name="DESCRIPTION" CONTENT="pixel-dusche + pixeldusche.com bietet individuall&ouml;sungen f&uuml;r internetauftritte mit hohen anspruch auf gestaltung und benutzerf&uuml;hrung - einfache pflege-tools f&uuml;r den kunden und klare informationsaufbereitung der inhalte f&uuml;r seine zielgruppe">
	<meta NAME="page-topic" CONTENT="business">
	<meta NAME="audience" CONTENT="Alle">
	<meta name="ROBOTS" content="index,follow">
	<meta name="Content-Language" content="de">
	<meta name="AUTHOR" content="pixel-dusche.de, pixeldusche.com, frankfurt am main - bjoern knetter">
	<meta name="PUBLISHER" content="pixel-dusche.de, pixeldusche.com, frankfurt am main - bjoern knetter">
	<meta name="PAGE-TOPIC" content="">
	<meta name="REVISIT-AFTER" content="20 days">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="0"> 
	
	

<?php 
	$op = $_GET["open"];
	echo '<link rel="stylesheet" href="css/font.css" type="text/css">
	<script type="text/javascript" src="js/jquery.js"></script>
	';
	
	$stelle = $_REQUEST["stelle"]; 
	$split	= $_REQUEST["split"];
	$sav	= $_REQUEST["erstellen"];

	if ($split) {
		$t 		= explode("-", $split);
		$stelle = trim($t[1]);
	}
	elseif ($sav) {
		$t 		= explode(" ", $sav);
		$stelle = trim($t[1]);
	}
	
	$jump = $stelle - 1;
	$multi_lang = 0;
	 
?>
</head>

<body>
<a name="top"></a>


<!-- deko und copyright -->
<table border="0" cellpadding="0" cellspacing="0" id="screen" height="100%" background="images/back.gif">
	<tr>
		<td valign="top"><img src="images/leer.gif" alt="" width="950" height="1" border="0">
