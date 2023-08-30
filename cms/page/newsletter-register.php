<?php
global $jsFunc, $SID, $dir, $navID, $morpheus; 

$output .= '
<form method="post">
	<div class="row">
		<div class="col-6">
			<input type="text" class="form-control register" id="mymail" placeholder="E-Mail Adresse eingeben"/>
		</div>
		<div class="col-6">
			<button class="btn btn-register mt0">Ich möchte den Newsletter erhalten</button>
		</div>
	</div>
</form>

<div id="message" class="alert alert-success hide mt2" role="alert"></div>
';
	
$jsFunc .= '

 	$(".btn-register").on("click", function(event) {
		event.preventDefault();
		var data = $("#mymail").val();
		checkmail = isValidEmailAddress(data);
		if(checkmail==true) {
		    request = $.ajax({
		        url: "'.$dir.'page/newsletter-registerme.php",
		        type: "post",
		        data: \'mystring='.md5($SID.date("ymd")).'&data=\'+data,
		        success: function(msg) {
	                if(msg == "register") {
						$(\'#message\').html("'.str_replace("\n", "", $morpheus["anmeldung"]).'");
						$(\'#message\').removeClass("hide");
					}
	                else {
						$(\'#message\').html("Die Anfrage wurde nicht gesendet. Es gab einen Fehler: <b>"+msg+"</b>");
						$(\'#message\').removeClass("hide");
					}
	            }
		    });
		} else {
			$(\'#message\').html("Bitte gib eine gültige E-Mail Adresse ein.");
			$(\'#message\').removeClass("hide");
		}
    });

';