<footer>
		<div class="container">
			<div class="row">
				<div class="col-12 col-md-12 col-lg-9 offset-lg-3">
					<h2><?php echo $morpheus["headline"]; ?></h2>
				</div>
				<div class="col-12 col-sm-6 col-md-6 col-lg-4 offset-lg-2 text-center">
					<img src="<?php echo $dir; ?>images/userfiles/image/Melanie_Kuehl_Footer.png" class="portrait" />
					<p><b><?php echo $morpheus["emailname"]; ?></b><br/>
					<?php echo nl2br($morpheus["bezeichnung"]); ?></p>
				</div>
				<div class="col-12 col-sm-6 col-md-6 col-lg-4 abst">
					<div class="contact">
						<img src="<?php echo $dir; ?>images/userfiles/image/Footer_Praxis.svg" />
						<p><b>Privatpraxis</b><br/>
						<?php echo nl2br($morpheus["vcard"]); ?></p>
					</div>
					
					<div class="contact">
						<img src="<?php echo $dir; ?>images/userfiles/image/Footer_Telefon.svg" />
						<p><b>Telefon</b><br/>
						<?php echo $morpheus["fon"]; ?></p>
					</div>
					
					<div class="contact">
						<img src="<?php echo $dir; ?>images/userfiles/image/Footer_E-Mail.svg" />
						<p><b>E-Mail</b><br/>
						<a href="mailto:<?php echo email_code($morpheus["email"]); ?>"><?php echo email_code($morpheus["email"]); ?></a>
						</p>
					</div>
					
				</div>
			</div>		
		</div>		
	</footer>
	
	<section class="abrechnung">
		<div class="container">
			<div class="row">
				<div class="col-12 text-center">
<?php echo $morpheus["abrechnung"]; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="metanav">
		<div class="container">
			<div class="row">
				<div class="col-12 text-center">
<?php echo $nav_meta; ?>
				</div>
			</div>
		</div>
	</section>	
</main>

<div id="movetop">
	<a class="movetop btn_down show" href="#top"><i class="fa fa-chevron-up"></i></a>
</div>

<?php
if($morpheus_edit) include("design/edit.php");

//$js_inc = file_get_contents('js/jquery.min.js')."\n\n";
//$js_inc .= file_get_contents('js/jquery.easing.min.js')."\n\n";
//$js_inc .= file_get_contents('js/popper.min.js')."\n\n";
//$js_inc .= file_get_contents('js/bootstrap.min.js')."\n";
//$js_inc .= file_get_contents('js/datepicker.js')."\n";
//$js_inc .= file_get_contents('js/swiper.min.js')."\n";
//$js_inc .= file_get_contents('js/imagesloaded.pkgd.min.js')."\n";
//$js_inc .= file_get_contents('js/current-device.min.js')."\n";
//$js_inc .= file_get_contents('js/functions.js')."\n";
echo '<script>'.$js_inc.'</script>';
?>
<?php if($videoPlay) { ?>
<script src="<?php echo $dir; ?>js/fitvids.js?v=<?php echo $rand; ?>"></script>
<?php } ?>
<?php if($morpheus_edit) { ?>
<script src="<?php echo $dir; ?>js/functions_edit.js?v=<?php echo $rand; ?>"></script>
<?php } ?>

<script type="text/javascript">
function config() {
	request = $.ajax({
		url: "<?php echo $lokal_pfad; ?>inc/_config.php",type: "post",data: "cid=<?php echo $cid ? $cid : 1; ?>&lan=<?php echo $lan; ?>&sec=<?php echo md5(date("ymd:Hi")."x6RvD*u#"); ?>&width="+screen.availWidth+"x"+screen.availHeight
	});
	$(".mm1.mm2").addClass("offset-lg-3");
}
<?php
	global $jsFunc, $js;
	echo $js;
	echo $jsFunc;

	global $formMailAntwort, $plichtArray;

	if($formMailAntwort) {
		$pflicht = ' var warn = "<br><br>"; pr=$("#datenschutz").prop("checked"); if(pr==false) { pflicht=0; warn += "Bitte Datenschutz akzeptieren<br>"; } ';
		
		foreach($plichtArray as $arr) {
			$nm = $arr[0];
			$feld = $arr[1];
			$art = $arr[2];
			if($art == "Radiobutton")$pflicht .= ' if ($("input:radio[name=\''.$feld.'\']").is(":checked")) {} else { pflicht=0; warn += "'.$nm.'<br>"; } ';
			else if($art == "Checkbox")$pflicht .= ' if ($("#'.$feld.'").prop("checked") == false) { pflicht=0; warn += "'.$nm.'<br>"; } ';
			else $pflicht .= ' pr = $("#'.$feld.'").val(); if(pr=="") { pflicht=0; warn += "'.$nm.'<br>"; } ';
		}
?>	
	$(".sendform").on("click", function(event) { var data = $("#kontaktf").serializeArray(); data = JSON.stringify(data); var pflicht=1; <?php echo $pflicht; ?>
		if(pflicht==1) { event.preventDefault(); request = $.ajax({ url: "<?php echo $dir; ?>page/sendmail.php", type: "post", datatype:'json', data: 'mystring=<?php echo md5($morpheus["code"].date("ymd")); ?>&data='+data, success: function(msg) {
			// console.log(msg);
			if(msg == "Mail sent") $('#kontaktformular').html("<div class='alert alert-primary' role='alert'><?php echo str_replace(array("\n","\r"), "", $formMailAntwort); ?></div>"); 
			else $('#kontaktformular').html("The request was not sent. There was an error: "+msg+". Please contact us directly.<br/><br/>Die Anfrage wurde nicht gesendet. Es gab einen Fehler: "+msg+". Bitte nehmen Sie direkt Kontakt zu uns auf."); 
			kontaktformular
}  }); } else { $("#message").html("Bitte füllen Sie alle Pflichtfelder aus"+warn); $("#message").addClass("auto"); $("#message").removeClass("hide"); setTimeout(function(){ $("#message").addClass("hide"); $("#message").removeClass("auto"); },3000); $(".miss").on("click", function() { $(this).removeClass("miss"); });	}
	});
<?php } ?>

/* Document Ready Script */
document.ready = function (callback) {
	if (document.readyState != "loading") {
		callback();
	} else {
		document.addEventListener("DOMContentLoaded", callback);
	}
};

/* Automattically resize the iFrame */
var iFrame2C = {};
iFrame2C.rescale = function (iframe, format) {
	let formatWidth = parseInt(format.split(":")[0]);
	let formatHeight = parseInt(format.split(":")[1]);
	let formatRatio = formatHeight / formatWidth;
	var iframeBounds = iframe.getBoundingClientRect();

	let currentWidth = iframeBounds.width;
	let newHeight = formatRatio * currentWidth;

	iframe.style.height = Math.round(newHeight) + "px";
};

/* Resize iFrame */
function iframeResize() {
	var iframes = document.querySelectorAll('iframe[data-scaling="true"]');
	if (!!iframes.length) {
		for (var i = 0; i < iframes.length; i++) {
			let iframe = iframes[i];
			let videoFormat = "16:9";
			let is_data_format_existing =
				typeof iframe.getAttribute("data-format") !== "undefined";
			if (is_data_format_existing) {
				let is_data_format_valid = iframe.getAttribute("data-format").includes(":");
				if (is_data_format_valid) {
					videoFormat = iframe.getAttribute("data-format");
				}
			}
			iFrame2C.rescale(iframe, videoFormat);
		}
	}
}

/* Event Listener on Resize for iFrame-Resizing */
document.ready(function () {
	window.addEventListener("resize", function () {
		iframeResize();
	});
	iframeResize();
});
function get_source_url(data_type) {
	switch (data_type) {
		case "youtube":
			return "https://www.youtube-nocookie.com/embed/{SOURCE}?rel=0&controls=0&showinfo=0&autoplay=1&mute=1";
		case "google-maps":
			return "https://www.google.com/maps/embed?pb={SOURCE}";
		default:
			break;
	}
}
/* 2-Click Solution */
document.ready(function () {
	var video_wrapper = document.querySelectorAll(".video_wrapper");
	if (!!video_wrapper.length) {
		for (var i = 0; i < video_wrapper.length; i++) {
			let _wrapper = video_wrapper[i];
			var video_triggers = _wrapper.querySelectorAll(".video_trigger");

			if (!!video_triggers.length) {
				for (var l = 0; l < video_triggers.length; l++) {
					var video_trigger = video_triggers[l];
					var accept_buttons = video_trigger.querySelectorAll(
						'input[type="button"]'
					);

					if (!!accept_buttons.length) {
						for (var j = 0; j < accept_buttons.length; j++) {
							var accept_button = accept_buttons[j];
							accept_button.addEventListener("click", function () {
								var _trigger = this.parentElement;
								var data_type = _trigger.getAttribute("data-type");
								var source = "";
								_trigger.style.display = "none";

								source = get_source_url(data_type);

								var data_source = _trigger.getAttribute("data-source");
								source = source.replace("{SOURCE}", data_source);

								var video_layers = _trigger.parentElement.querySelectorAll(
									".video_layer"
								);
								if (!!video_layers.length) {
									for (var k = 0; k < video_layers.length; k++) {
										var video_layer = video_layers[k];
										video_layer.style.display = "block";
										video_layer.querySelector("iframe").setAttribute("src", source);
									}
								}

								_wrapper.style.backgroundImage = "";
								_wrapper.style.height = "auto";

								var timeout = 100; // ms
								setTimeout(function () {
									iframeResize();
								}, timeout);
							});
						}
					}
				}
			}
		}
	}
});

</script>

<?php
global $jsAddOn;
echo $jsAddOn;
