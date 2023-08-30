<!DOCTYPE html>
<head>
	<title>content-management-system - pixel-dusche.de, pixeldusche.com</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
	<meta http-equiv="content-type" content="text/html;charset=utf-8">
	<meta name="Content-Language" content="de">
	<meta http-equiv="Cache-Control" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/ekko-lightbox.css" type="text/css">
	<link rel="stylesheet" href="css/multi-list.css" type="text/css">
<?php if(!$ekko) { ?> 
	<link rel="stylesheet" href="css/semantic.css" type="text/css">
<?php } ?>
	<link rel="stylesheet" href="css/style_light.css" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css">
<?php if($box) { ?> 
	<link rel="stylesheet" href="css/style_light_edit.css" type="text/css">
<?php } ?>
	<link rel="stylesheet" href="css/jquery.Jcrop.min.css" type="text/css" />
	<script src="js/jquery.js"></script>
	<script src="js/jquery.Jcrop.min.js"></script>
 	<script src="js/jquery-ui.js"></script>
	<script src="js/multi-list.js"></script>
	<script src="js/clipboard.min.js"></script>

	<script src="https://unpkg.com/web-animations-js@2.3.1/web-animations.min.js"></script>
	<script src="https://unpkg.com/hammerjs@2.0.8/hammer.min.js"></script>

	<script src="js/muuri.js"></script>

	<script type="text/javascript" src="js/pixeldusche.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/ekko-lightbox.min.js"></script>
<?php if(!$ekko) { ?> 
	<script type="text/javascript" src="js/semantic.js"></script>
<?php } ?>
	<link href="js/skins/square/aero.css" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="60x60" href="fav/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="fav/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="fav/favicon-16x16.png">
	<link rel="manifest" href="fav/site.webmanifest">
	<link rel="mask-icon" href="fav/safari-pinned-tab.svg" color="#5bbad5">

<?php
	/*
	<script src="js/icheck.js"></script>
	<script>
		$(document).ready(function(){
		  $('input').iCheck({
		    checkboxClass: 'icheckbox_square-aero',
		    radioClass: 'iradio_square-aero',
		    increaseArea: '20%' // optional
		  });
		});
	</script>
*/
?>
<?php

	if(isset($_REQUEST["stelle"])) 		$stelle = $_REQUEST["stelle"]; else $stelle = '';
	if(isset($_REQUEST["split"])) 		$split	= $_REQUEST["split"]; else $split = '';
	if(isset($_REQUEST["erstellen"])) 	$sav	= $_REQUEST["erstellen"]; else $sav = '';

	if ($split) {
		$t 		= explode("-", $split);
		$stelle = trim($t[1]);
	}
	elseif ($sav) {
		$t 		= explode(" ", $sav);
		if(count($t)>1) $stelle = trim($t[1]);
	}

	$multi_lang = 0;

?>

	<script type="text/javascript">
		function sf(){document.nav_edit.name.focus();}
		function jump(){

		}
		function check (url) {
			chk = document.check.check.value;
			console.log(url);
			if (chk < 1) {
				document.location.href=url;
			} else {
				alert("Ihre Änderungen wurden noch nicht gespeichert.");
				// document.content_edit.submit(); 
			}
		}
		function setchange (x) {
			// console.log("type");
			document.check.check.value=x;
			$('.sve, .SAVE').css({"background":"red"});
		}

		var e=1;

		$(document).ready(function(){
			$('.ta').on('keyup', function() {
				if(e<2) {
					$('.sve, .SAVE').css({"background":"red"});
					e++;
					console.log( "change "+e );
				}
			});
		});

		function setdupl (x) { document.content_edit.duplizieren.value=x; document.content_edit.submit(); }
	</script>
</head>

<body <?php if ($stelle) echo 'onload="jump();"'; ?>>
<a name="top"></a>

