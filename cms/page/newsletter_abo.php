<?php
global $jsAddOn;

$siteKEY = '6LdgKNQlAAAAAIzXUqu8yniVIoBg3XiYDrRSKHUh';
$secret = '6LdgKNQlAAAAAK4bZCAO8zV07oeiUmf4tyxcKzPQ';


// <script src="https://www.google.com/recaptcha/enterprise.js?render=6LflX8QlAAAAAER467mTPVytZ2o6JVUzE-1DiMO_"></script>
// <script src="https://www.google.com/recaptcha/enterprise.js?onload=onloadCallback&render=explicit"></script>
// <div class="h-captcha-response" >x</div>
// <div class="h-captcha" data-sitekey="'.$siteKEY.'"></div>

// <script src="https:js.hcaptcha.com/1/api.js" async defer></script>

// $json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response=03AL8dmw8');
// $data = json_decode($json);
// var_dump($data);


$output .= '
	

<div id="message" class="nl alert alert-primary text-center hide">alert</div>

<div id="cr" class="cr_form cr_signup">
	<form action="https://eu2.cleverreach.com/f/158420-158231/wcs/" id="mykNL" method="post" target="_blank">
		<div class="container">
			
			<div class="row">
				<div class="col-12">
					<div id="3305882" rel="radio" class="cr_ipe_item ui-sortable sel-button">
						<label><input id="Frau3305882" class="cr_ipe_radio form-check-input" name="1027275" type="radio" value="Frau"><span>Frau</span></label>
						<label><input id="Herr3305882" class="cr_ipe_radio form-check-input" name="1027275" type="radio" value="Herr"><span>Herr</span></label>
						<label><input id="Divers3305882" class="cr_ipe_radio form-check-input" name="1027275" type="radio" value="Divers"><span>Divers</span></label>						
					</div>
				</div>
				<div class="col-12 mb-1">
					<input id="text3305267" name="1027276" class="form-control" type="text" value="" placeholder="Vorname">
				</div>
				<div class="col-12 mb-1">
					<input id="text3305269" name="1027277" class="form-control" type="text" value="" placeholder="Name">
				</div>
				<div class="col-12">
					<input id="text3305243" name="email" class="form-control" value="" type="text" placeholder="E-Mail*">
				</div>
				<div class="col-12 mt2">						
					Bitte informieren Sie mich über folgende Themen:
				</div>
				<div class="col-12 mt2">						
					<label class="itemname">
						<input id="Ja8043660" class="form-check-input cr_ipe_checkbox" name="1113076[]" value="Ja" type="checkbox" />
						<span>Informationen zum Eltern-Kind-Treff</span>
					</label>							
				</div>
				<div class="col-12">												
					<label class="itemname">
						<input id="Ja8043661" class="form-check-input cr_ipe_checkbox" name="1173416[]" value="Ja" type="checkbox" />
						<span>Informationen zum Kultur-Bildungs-Treff</span>
					</label>
				</div>
				<div class="col-12">												
					<label class="itemname">
						<input id="Ja8043662" class="form-check-input cr_ipe_checkbox" name="1173417[]" value="Ja" type="checkbox"  />
						<span>Weitere Veranstaltungen und allgemeine Stiftungsinformationen</span>
					</label>
				</div>			
				<div class="col-12 mt3">												
					<div id="html_element"></div>
					<input type="hidden" value="" id="response"/>
					<div id="message_error"></div>											
				</div>
			</div>
			
			<div id="3305245" rel="button" class="cr_ipe_item ui-sortable submit_container mt-2" >
				<button type="submit" class="cr_button btn btn-info">Jetzt anmelden</button>
			</div>			
		</div>			
			
		<input type="hidden" name="apLgK_ONntJT" value="G0yxMk8bsIdH"><input type="hidden" name="wKQpavdVPZqk" value="a2E.8cnOro"><input type="hidden" name="FmEKpuflIheCgoZ" value="Q_c15.x"><input type="hidden" name="ACLEBe" value="oMNA.dKm">
	</form>
		
</div>

';



$jsAddOn .= '
<script src="https://www.google.com/recaptcha/enterprise.js?onload=onloadCallback&render=explicit"></script>
<script>
var verifyCallback = function(response) {
	$(\'#response\').val(response);
  };

var onloadCallback = function() {
	grecaptcha.enterprise.render(\'html_element\', {
		\'sitekey\' : \''.$siteKEY.'\',
		\'callback\' : verifyCallback,
	});
};

$("#mykNL").submit(function(e) {
	mail = $("#text3305243").val();
	mail = isValidEmailAddress(mail);
	warn = ""; send = 1;
	if(mail==false) {
		event.preventDefault();
		warn += "Bitte geben Sie Ihre E-Mail an<br><br>";		
		send = 0;
	}
	cb = 0;
	if( $("#Ja8043660").is(":checked")) cb=1;
	if( $("#Ja8043661").is(":checked")) cb=1;
	if( $("#Ja8043662").is(":checked")) cb=1;
	if(cb<1) {
		event.preventDefault();
		warn += "Bitte wählen Sie mindestens 1 Thema aus<br>";		
		send = 0;
	}
	resp = $(\'#response\').val();
	if(resp != \'\') {
		request = $.ajax({
			url: "'.$dir.'page/_captcha.php", type: "post",	data: "secret='.$secret.'&resp="+resp,
			success: function(msg) {
				if(msg!=1) {
					send=0;
					warn += "Fehler<br>";		
				}
			}
		});
	}
	else { 
		send=0;
		warn += "Bitte Captcha bestätigen<br>";		
	}
	if(send > 0) 
		$(\'#mykNL\').submit();
	else {
		event.preventDefault();
		$("#message").html(warn);
		$("#message").addClass("auto");
		$("#message").removeClass("hide");
		setTimeout(function(){
 			$("#message").addClass("hide");
 			$("#message").removeClass("auto");
		},2000);
		$(".miss").on("click", function() {
 			$(this).removeClass("miss");
		});	
	}	  
});
</script>
';



$XXXXX_js .= '
<script>
function loadjQuery(e,t){var n=document.createElement("script");n.setAttribute("src",e);n.onload=t;n.onreadystatechange=function(){if(this.readyState=="complete"||this.readyState=="loaded")t()};document.getElementsByTagName("head")[0].appendChild(n)}function main(){
var $cr=jQuery.noConflict();var old_src;$cr(document).ready(function(){$cr(".cr_form").submit(function(){$cr(this).find(\'.clever_form_error\').removeClass(\'clever_form_error\');$cr(this).find(\'.clever_form_note\').remove();$cr(this).find(".musthave").find(\'input, textarea\').each(function(){if(jQuery.trim($cr(this).val())==""||($cr(this).is(\':checkbox\'))||($cr(this).is(\':radio\'))){if($cr(this).is(\':checkbox\')||($cr(this).is(\':radio\'))){if(!$cr(this).parents(".cr_ipe_item").find(":checked").is(":checked")){$cr(this).parents(".cr_ipe_item").addClass(\'clever_form_error\')}}else{$cr(this).addClass(\'clever_form_error\')}}});if($cr(this).attr("action").search(document.domain)>0&&$cr(".cr_form").attr("action").search("wcs")>0){var cr_email=$cr(this).find(\'input[name=email]\');var unsub=false;if($cr("input[\'name=cr_subunsubscribe\'][value=\'false\']").length){if($cr("input[\'name=cr_subunsubscribe\'][value=\'false\']").is(":checked")){unsub=true}}if(cr_email.val()&&!unsub){$cr.ajax({type:"GET",url:$cr(".cr_form").attr("action").replace("wcs","check_email")+window.btoa($cr(this).find(\'input[name=email]\').val()),success:function(data){if(data){cr_email.addClass(\'clever_form_error\').before("<div class=\'clever_form_note cr_font\'>"+data+"</div>");return false}},async:false})}var cr_captcha=$cr(this).find(\'input[name=captcha]\');if(cr_captcha.val()){$cr.ajax({type:"GET",url:$cr(".cr_form").attr("action").replace("wcs","check_captcha")+$cr(this).find(\'input[name=captcha]\').val(),success:function(data){if(data){cr_captcha.addClass(\'clever_form_error\').after("<div style=\'display:block\' class=\'clever_form_note cr_font\'>"+data+"</div>");return false}},async:false})}}if($cr(this).find(\'.clever_form_error\').length){return false}return true});$cr(\'input[class*="cr_number"]\').change(function(){if(isNaN($cr(this).val())){$cr(this).val(1)}if($cr(this).attr("min")){if(($cr(this).val()*1)<($cr(this).attr("min")*1)){$cr(this).val($cr(this).attr("min"))}}if($cr(this).attr("max")){if(($cr(this).val()*1)>($cr(this).attr("max")*1)){$cr(this).val($cr(this).attr("max"))}}});old_src=$cr("div[rel=\'captcha\'] img:not(.captcha2_reload)").attr("src");if($cr("div[rel=\'captcha\'] img:not(.captcha2_reload)").length!=0){captcha_reload()}});function captcha_reload(){var timestamp=new Date().getTime();$cr("div[rel=\'captcha\'] img:not(.captcha2_reload)").attr("src","");$cr("div[rel=\'captcha\'] img:not(.captcha2_reload)").attr("src",old_src+"?t="+timestamp);return false}

}
if(typeof jQuery==="undefined"){loadjQuery("//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js",main)}else{main()}

</script>';