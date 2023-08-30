<?php
	if (!isset($_COOKIE["disclaimer_v21"])){
		$sprungID = array("de"=>9, "en"=>57 );

	    $ac = $lan == "de" ? "Ich akzeptiere" : "I accept";
		$wl = $lan == "de" ? "Datenschutzerklärung" : "Privacy Statement";
		$ac = $lan == "de" ? "Auswahl Zustimmen" : "Agree selection";
		$acall = $lan == "de" ? "Alle akzeptieren" : "Accept all";
		$ab = $lan == "de" ? "Ablehnen" : "Deny";
		
		$dsgvo_text = $lan == "de" ? '<p><b>AUCH WIR VERWENDEN COOKIES:</b></p>
        <p>Wir möchten die Informationen auf dieser Webseite auf Ihre Bedürfnisse anpassen. Zu diesem Zweck setzen wir sog. Cookies ein. Entscheiden Sie bitte selbst, welche Arten von Cookies bei der Nutzung der Website eingesetzt werden sollen. Weitere Informationen erhalten Sie in unserer' : '<p><b>We also use COOKIES:</b></p>
        <p>We would like to adapt the information on this website to your needs. For this purpose, we use so-called cookies. Please decide for yourself which types of cookies to use when using the website. For more information, please see our';
?>

    <div id="cookie_disclaimer">
		<?php echo $dsgvo_text; ?> <a href="<?php echo $dir.$lan.'/'.$navID[$sprungID[$lan]]; ?>"><?php echo $wl; ?></a>.</p>
		<table>
	 		<tr class="ul">
				<td width="50%"><h4><input type="checkbox" id="komfort" checked="" disabled> &nbsp; Technisch notwendige Cookies</h4></td>
				<td width="50%"><h4><input type="checkbox" id="marketing" <?php echo $track ? 'checked' : ''; ?>><label for="marketing"> &nbsp; Marketing-Cookie</label>s</h4></td>
			</tr>
			<tr class="cookie_detail hide">
				 <td valign="top">
					<span class="mobileOn">Technisch notwendige Cookies</span> sind erforderlich, um alle Funktionen dieser Website bereitzustellen und standardmäßig aktiviert
				 </td>
				<td valign="top">
					<span class="mobileOn">Marketing-Cookie</span>
					und ähnliche Technologien würden wir gerne verwenden, um die Präferenzen unserer Besucher besser zu verstehen und auch auf anderen Plattformen personalisierte Informationen bereitstellen zu können.
				</td>
			</tr>
		</table>

			<a href="#" id="acceptall" class="btn btn-info btnMore font_weiss"><?php echo $acall; ?></a> 
			<a href="#" id="cookie_stop" class="btn acc"><?php echo $ac; ?></a>
			<a href="#" id="cookie_detail" class="btn btn-det acc">Details</a>
        
    </div>

	<style>
	/********COOKIES*******/
	#cookie_detail:after {
	    content: "\f107";
	    font: normal normal normal 25px/1 FontAwesome;
	    position: absolute;    
		margin-top: -3px;
    	margin-left: 6px; text-decoration: none;
	}
	#cookie_detail.on:after {
	    content: "\f106";
	    font: normal normal normal 25px/1 FontAwesome;
	    position: absolute;    
		margin-top: 2px;
    	color: #0f407a;
    	margin-left: 10px;
	}
	.cookie_detail td { padding: 5px; }
	#cookie_disclaimer{
	    position: fixed;
	    bottom: 10px;
	    z-index: 9999999;
	    width: 800px;
	    background: #fff;
	    left:50%; margin-left: -400px;
	    color: #666;padding: 10px; line-height: 1.25em; -webkit-box-shadow: 0px 6px 22px 0px rgba(0,0,0,0.75); -moz-box-shadow: 0px 6px 22px 0px rgba(0,0,0,0.75); box-shadow: 0px 6px 22px 0px rgba(0,0,0,0.75);  }
	#cookie_disclaimer a {  }
	#cookie_disclaimer .btn { font-size: 1em; }
	#acceptall { top: -10px; font-size: 1rem; }
	#cookie_disclaimer input {
	    width: inherit;
	    float: none;
	    border: solid 1px; height: auto; margin: 0;
	}
	#cookie_disclaimer table { margin-bottom: 1em; width: 100%; }
	#cookie_disclaimer tr.ul { border-bottom: solid 1px #ccc; }
	#cookie_disclaimer h4 { font-size: 1em; margin: 1em 0 0; }
	#cookie_disclaimer, #cookie_disclaimer td { font-weight: 200; vertical-align: top; margin-bottom: 0; }
	#cookie_disclaimer { font-size: .8em; }
	#cookie_disclaimer .btn-info { background: #95C11F; }
	.btn-det { color: #95C11F; padding-right: 50px; }
	.cookie{float: right;
	    padding: 5px 35px;
	    color: #fff !important; text-decoration: none !important;
	    border-radius: 10px; margin-right: 50px; margin-top: 20px; text-decoration: none; }
	.nocookie{float: right;
	    padding: 5px 35px;
	    color: #999 !important; text-decoration: none !important;
	    border-radius: 10px; margin-right: 50px; margin-top: 20px; text-decoration: none; }
		.acc { margin-left: 30px; }
	@media (max-width: 1200px) {
		#cookie_disclaimer{ width: 90%; left:5%; margin-left: inherit}
	}
	@media (max-width: 990px) {
	}
	@media (max-width: 540px) {
		#cookie_disclaimer { font-size: 1em; }
		#acceptall { display: block; margin-bottom: 0;}
		#cookie_disclaimer{ width: 90%; }
		#cookie_disclaimer{ bottom: 20px; width: 100%; left:0; padding: 20px ;  line-height: 1.15em;  }
		#cookie_disclaimer .inner { padding: 20px !important; }
		#cookie_disclaimer .btn { display: block; width: 100%; border: solid 1px #95C11F; margin-bottom: .5em; line-height: 28px }
		.acc { margin-left: 0; line-height: 16px; }
		.btn-det { line-height: 40px !important; }
		td{ width: 100%; display: block; }
		#cookie_disclaimer .mobileOn { color: #000; }
		#cookie_detail.on:after, #cookie_detail:after { margin-top: 7px; }
	}		
	/********END*******/
	</style>
	<!-- Cookies -->
	<script type="text/javascript">

	
	$(function(){
	    $('#cookie_detail').click(function(){
			event.preventDefault();
		    ishide = $('.cookie_detail').hasClass("hide");
		    if(ishide) { $(this).addClass("on"); $('.cookie_detail').removeClass("hide"); }
		    else {  $(this).removeClass("on"); $('.cookie_detail').addClass("hide"); }
		});
		
	    $('#acceptall').click(function(){
	        $('#cookie_disclaimer').slideUp("slow");

	        var nDays = 60;
	        var aDays = 720;
	        var cookieValue = "true";
	        var today = new Date();
	        var expire = new Date();
	        var expireDel = new Date();
	        expire.setTime(today.getTime() + 3600000*24*nDays);
	        expireDel.setTime(today.getTime() - 3600000*24*100);

	        var cookieName = "disclaimer_v21";
			document.cookie = cookieName+"="+escape(cookieValue)+";expires="+expire.toGMTString()+";path=/";
	        var cookieName = "marketing";
			expire.setTime(today.getTime() + 3600000*24*aDays);
			document.cookie = cookieName+"="+escape(cookieValue)+";expires="+expire.toGMTString()+";path=/";

			location.reload();
	     });
	     $('#cookie_stop').click(function(){
		    var komfort =$('#komfort').prop('checked');
			var marketing =$('#marketing').prop('checked');

	        $('#cookie_disclaimer').slideUp("slow");

	        var nDays = 60;
	        var aDays = 720;
	        var cookieValue = "true";
	        var today = new Date();
	        var expire = new Date();
	        var expireDel = new Date();
	        expire.setTime(today.getTime() + 3600000*24*nDays);
	        expireDel.setTime(today.getTime() - 3600000*24*100);

		        var cookieName = "disclaimer_v21";
				document.cookie = cookieName+"="+escape(cookieValue)+";expires="+expire.toGMTString()+";path=/";

			if(marketing==true) {
		        var cookieName = "marketing";
				expire.setTime(today.getTime() + 3600000*24*aDays);
				document.cookie = cookieName+"="+escape(cookieValue)+";expires="+expire.toGMTString()+";path=/";
				location.reload();
			}
			else if(marketing==false) {
		        var cookieName = "marketing";
				document.cookie = cookieName+"='';expires="+expireDel.toGMTString()+";path=/";
			}
	     });
	});

	</script>
	<!-- END COOKIES-->
<?php } 

if($track) { ?>

	

<?php }?>
