jQuery(document).ready(function($){	

	// functions executed after page load
	init_global();
	init_events();
	init_single_event();
	init_event_calendar()
	init_map();
	init_webshop();
	init_shop_cat();
	init_single_product();
	init_cart();
	init_checkout();
	init_reservation();
	init_rmv();
	init_bps();

/*
		$("#shipping_phone_field").removeClass("validate-required");
		$("#shipping_phone_field label abbr").removeClass("required");
*/
		setTimeout(function(){ 
			$("input#shipping_phone").val("NA");
			$("input#shipping_email").val("NA");
		}, 1000);


  if ($("#oas-vaa tr:last-child").length > 0) var vaa_offset = $("#oas-vaa tr:last-child").offset().top;
	init_va_calc(vaa_offset);

	// functions executed after special events
	$(window).scroll(function() { sticky_elements(); });
	$("#wpcf7-f1835-p145-o1 .wpcf7-form").change( function () { init_va_calc( vaa_offset) });
	$( "#oas-personen, #oas-kinder-7, #oas-kinder-3, #oas-serviceh" ).keyup(function () { init_va_calc( vaa_offset ); });

	$(document).on("sf:ajaxfinish", ".searchandfilter", function(){ re_init_shop_cat_after_ajax("ajax"); });
	
	$("a[href^='#']").bind("click", function(event) {
		event.preventDefault();
		var ziel = $(this).attr("href");
		if ($(ziel).offset()) {		
			var offset = $(ziel).offset().top - 130;
			$('html,body').animate({
				scrollTop: offset
			}, 1500);
		}
	});
	$("h1#news, #cta-news").click(function () {
		var ziel = $("h3.widgettitle");
		if (ziel.offset()) {		
			var offset = ziel.offset().top - 60;
			$('html,body').animate({
				scrollTop: offset
			}, 1500);
		}
		
	});

	// custom accordion
	$(".accordion-trigger").prepend('<i class="fa fa-plus-square" aria-hidden="true"></i><i class="fa fa-minus-square hide" aria-hidden="true"></i>');

	$(".accordion-trigger").click( function () { 
		var divid = $(this).attr("data-accordion-id");
		if (divid == undefined) divid = "accordio" + $(this).attr("id");

		console.log(divid);

		if ($("#" + divid) != undefined) $("#" + divid).slideToggle();
		$(this).find("i").toggleClass("hide");
	});
	
	function sticky_elements() {
		if ($("body.page-aktuelles").length > 0) {
		  //console.log('H1: ' + $("h1#news").offset().top);
		  //console.log('Widget: ' + $("h3.widgettitle").offset().top);

		  if ($("h1#news").offset().top > $("h3.widgettitle").offset().top) {
			 $("h1#news").removeClass("fixed center");
			 $("h1#news i").hide();
			 $("h1#news").unbind('click');
		  }
    }
    if ($("body.page-anfrage").length > 0) {
    }
	}


/*************************
 * start functions here
 *************************/

	function init_global() {
		// global operations concerning many/all pages
   	setInterval(function() { 
   		$(".woocommerce-Price-amount").each( function () { 
				var wc_price = $(this).text();
   			if (wc_price.match(/,00/) != null) {
   				console.log("matched: ,00");
					$(this).text(wc_price.replace(",00",""));
				}
			});
 		}, 1000);
	}

	function init_events() {
		if ($("body.post-type-archive-tribe_events").length == 0) return;

	 		// workaround: hide rate of 3€ for Apfelfest (3.10.) on calendar
	 		$("#post-1879 .tribe-events-event-cost > span").text("Eintritt frei");      
	
	   	// workaround: hide rate of 3€ for parking fees (1.5. and 3.10.) on event list
			$(".post-1876 .tribe-events-event-cost span").text("Eintritt frei");
	
			// workaround: replace untranslated strings on event calendar
			$(".tribe-events-event-cost span").each( function () { 
				var cost = $(this).text();
				cost = cost.replace("Free","Eintritt frei");
				cost = cost.replace(",5",",50");
				$(this).text(cost);
			});
  }		
	
	function init_single_event() {
		if ($("body.single-tribe_events").length == 0) return;
				
		// workaround: hide rate of 3€ for parking fees (1.5. and 3.10.) on single event
		$(".postid-1876 span.tribe-events-cost").text("Eintritt frei");

		// workaround: replace untranslated strings on single event
		var cost = $("span.tribe-events-cost").text();
		console.log(cost);
		cost = cost.replace(/\./g,",");
		cost = cost.replace("Free","Eintritt frei");
		cost = cost.replace("kostenlos","Eintritt frei");
		//cost = cost.replace(",5",",50");
		//$("span.tribe-events-cost").text(cost);
		
		// replace untranslated strings
		var qty = $(".tribe-tickets__tickets-footer-quantity-label").text();
		//console.log(qty);
		qty = qty.replace("Quantity","Anzahl");
		$(".tribe-tickets__tickets-footer-quantity-label").text(qty);

		var sum = $(".tribe-tickets__tickets-footer-total-label").text()
		sum = sum.replace("Total","Summe");
		$(".tribe-tickets__tickets-footer-total-label").text(sum);
				
		var buy = $(".tribe-tickets__tickets-buy").text()
		buy = buy.replace("Get Tickets","Tickets kaufen");
		$(".tribe-tickets__tickets-buy").text(buy);
				
		// hide tickets if entrance is free
		if (cost == "Eintritt frei") $("body:not(.postid-1876) .form.cart").hide();

		// replace "kostenlos" with "kostenfrei"
		$(".tribe-events-event-cost, .tribe-events-cost").each( function () { 
			var tr_price = $(this).text();
			tr_price = tr_price.replace(/kostenlos/i,"Kostenfrei");
			tr_price = tr_price.replace(/€/g," €");
			//console.log("matched: €");
			$(this).text(tr_price);
		});
		$("table.tribe-events-tickets tr td.tickets_price").each( function () { 
			var tprice = $(this).text();
			if (tprice.match(/kostenlos/i)) {
				tprice = tprice.replace(/kostenlos/i,"Kostenfrei");
				$(this).text(tprice);
			}
		});

		// hide ticket form if tickets are sold out. the sold-out indicator has to be set manually
		if ($("#oas-sold-out").length > 0) {
			$("#tribe-tickets__tickets-form").html('<h2 class="tribe-events-tickets-title center">AUSVERKAUFT</h2>');
			$("a[href='#tribe-tickets__tickets-form']").append(" AUSVERKAUFT!");
		}
		// hide ticket form without any message - needed for free events which shall be diplayed in category "Kostenfreie Events"
		if ($("#oas-free-event-with-ticket").length > 0) {
			$("#tribe-tickets__tickets-form").hide();
		}		

		// AR+Kombi Vollzahler: allow max 6 tickets in steps by 2
		$(".cat_fackelwanderungen-und-apfel-raclette form#tribe-tickets__tickets-form .tribe-tickets__tickets-item").each( function () { 
			var title = $(this).find(".textwidget").text();	
			console.log(title);		
			if (title.match(/voller preis/gi) != null) {
				$(this).find(".tribe-tickets__tickets-item-quantity-number-input").attr({"step":"2","max":"6","readonly":"readonly"});
			}
		});				

		// AR To Go and AR/FW: allow max 6 tickets in steps by 2
		$(".cat_apfel-raclette-to-go form#tribe-tickets__tickets-form .tribe-tickets__tickets-item-quantity-number > input, .tribe_events_cat-fackelwanderungen-und-apfel-raclette form#tribe-tickets__tickets-form .tribe-tickets__tickets-item-quantity-number-input ").attr({"step":"2","max":"6","readonly":"readonly"});
		
		// Neujahrsschoppen: group tickets by timeslot using 3 accordions for 9 tickets. 
		// The accordion IDs start with 10 to avoid conflicts with other accordions in the text area aove the ticket form. 

		$("input#tribe-tickets__tickets-item-quantity-number--69668").attr({"step":"2","max":"6","readonly":"readonly"});
		$("input#tribe-tickets__tickets-item-quantity-number--69669").attr({"step":"2","max":"6","readonly":"readonly"});

		$("input#tribe-tickets__tickets-item-quantity-number--69682").attr({"step":"2","max":"6","readonly":"readonly"});
		$("input#tribe-tickets__tickets-item-quantity-number--69683").attr({"step":"2","max":"6","readonly":"readonly"});
		
		$("input#tribe-tickets__tickets-item-quantity-number--69675").attr({"step":"2","max":"6","readonly":"readonly"});
		$("input#tribe-tickets__tickets-item-quantity-number--69676").attr({"step":"2","max":"6","readonly":"readonly"});


		if ($("body.postid-69668").length + $("body.postid-58478").length + $("body.postid-58507").length > 0) {		
			
			$("table.tribe-events-tickets tr.tribe-tickets-form-row").first().before('<h3>Für welche Uhrzeit möchtet Ihr reservieren?</h3>');
	
			// add 3 empty accordions to the DOM starting with id 10
			for (id = 10; id < 13; id ++) {  
				$("table.tribe-events-tickets tr.tribe-tickets-form-row").first().before('<h3 class="accordion-trigger" data-accordion-id="accordion-' + id + '"></h3><div class="accordion-content" id="accordion-' + id + '"></div>');		
			}
			
			// detach all table rows (=tickets) and insert them into the accordions 
			var accordion_id = 10;
			$("table.tribe-events-tickets tr.tribe-tickets-form-row").each ( function (index) { 
				console.log(index);
				console.log(accordion_id);
	
				$(this).detach();
				$("#accordion-" + accordion_id).append($(this));
	
				// IMPORTANT timeslot has to be in every ticket title: Apfel-Raclette 12 - 14 Uhr
				// get timeslots from ticket titles and add them as accordion titles
				if ( index === 2 || index === 5 || index === 8 ) {
					var ticket_desc = $(this).find("td.tickets_name").text();
					var timeslot = ticket_desc.match(/[1-2][0-9].+Uhr/);
					$('h3[data-accordion-id="accordion-' + accordion_id + '"]').text(timeslot[0]);
					accordion_id ++;
				}
			});
		}				
	}
	
	function init_event_calendar() {
		
		if ($("body.post-type-archive-tribe_events").length == 0)  return;
		
		$("a.tribe-events-read-more").each( function () { 
			$(this).text("Hier mehr erfahren >>");
		});
	}
	
	function init_map() {
		if ($(".google-maps-widget").length == 0)  return;
		$("a.gmw-thumbnail-map").attr("target","_blank");
	}

	function init_webshop() {
		if ($(".post-type-archive-product").length == 0) return;
		$(".post-type-archive-product ul.products li.virtual .woocommerce-price-suffix").each( function () {
			$(this).text("inkl. MwSt.");
		});
		$("ul.products > li > a > h3").each( function () { 
			var title = $(this).text();
			//console.log(title.length);
			if ( title.length > 35 || ($(window).outerWidth() < 970 && title.length > 26)) {
				$(this).find("mark").css("display","inline");
			}
		});
		$("a[rel^='wp_lightbox_prettyPhoto']").prettyPhoto( { 
			iframe_markup: '<iframe allowfullscreen="allowfullscreen" src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>', 
			default_width: 800,
			default_height: 550
		});		
	}
	
	function init_shop_cat() {
		if ($(".tax-product_cat").length == 0) return;
		
		$(".select-tooltbars form, .select-tooltbars select, .select-tooltbars option").prop("disabled", true);
		$(".select-tooltbars").remove();

		$(".product-grid .product-content").click( function () { 
			var allLinks = $(this).find("a");
			var myUrl = allLinks[1].href;
			//console.log(myUrl);
			window.location.href = myUrl;
		});
		
		// show only "Details"-Buttons for free events
		$(".product-desc").each( function () { 
			if ( $(this).find(".amount").text() == "Eintritt frei" || $("body.term-kostenfreie-events .product-action").length > 0) {
				$(this).find("a.button").text("Details");
			}
		});

		var product_images =	new Array();

		if ($(".term-event-apfel-raclette").length > 0) {
		  // set new css class
		  $("body").addClass("oas-product-listview");
			// reset checkboxes and labels
			$(".sf-input-checkbox").prop("checked",false);
			$(".sf-input-checkbox[value='apfel-raclette'],.sf-input-checkbox[value='fackelwanderung'],.sf-input-checkbox[value='kombiticket']").prop("checked",true);
			$(".sf-input-checkbox[value='apfel-raclette'],.sf-input-checkbox[value='fackelwanderung'],.sf-input-checkbox[value='kombiticket']").next().show();
			product_images[116] = "https://obsthof-am-steinberg.de/wp-content/uploads/2016/09/Obsthof-am-Steinberg_Fackelwanderung_1920x1200-300x300.jpg";
			product_images[117] = "https://cdn.obsthof-am-steinberg.de/wp-content/uploads/2017/07/OAS-Events_Apfel-Raclette-2_420x300-300x300.jpg";
			product_images[118] = "https://obsthof-am-steinberg.de/wp-content/uploads/2018/08/OAS-Event_Kombiticket_420x300-300x300.jpg";
			//$("#search-filter-form-6047").after("<h2 class='oas-header-listview'>Die Buchung von Apfel-Raclettes und Fackelwanderungen ist zur Zeit nicht möglich. Bitte versuche es später noch einmal.<br /><br />Dein Obsthof-Team.</h2>"); 
		} else if ($(".term-event-feste-kinder-musik").length > 0) {			
			return;	// feste-kinder-musik list view deactivated - show grid view with images
		  // set new css class
		  $("body").addClass("oas-product-listview");
			// reset checkboxes and labels
			$(".sf-input-checkbox").prop("checked",false);
			$(".sf-input-checkbox[value='fest'],.sf-input-checkbox[value='fuer-kinder'],.sf-input-checkbox[value='mit-live-musik']").prop("checked",true);
			$(".sf-input-checkbox[value='fest'],.sf-input-checkbox[value='fuer-kinder'],.sf-input-checkbox[value='mit-live-musik']").next().show();
			product_images[119] = "https://obsthof-am-steinberg.de/wp-content/uploads/2016/06/Obsthof-am-Steinberg-Galerie-Veranstaltungen-Apfelgarten-mit-Gaesten-300x300.jpg";
			product_images[120] = "https://obsthof-am-steinberg.de/wp-content/uploads/2016/08/Obsthof-am-Steinberg_Einschulungskids_mit_Chef_1920x1200-300x300.jpg";
			product_images[121] = "https://obsthof-am-steinberg.de/wp-content/uploads/2016/09/Obsthof-am-Steinberg_Musikprogramm_Bembellinies1920x1200-300x300.jpg";
		}

			//console.log(product_images);
	
			// add images to S&F form
			product_images.forEach(sf_images);
		
			// set functions of new images
			$("form.searchandfilter li.sf-level-0").click( function () { 
				if ($(this).find("input[type='checkbox']").prop("checked") == true ) {
					if ($(".sf-input-checkbox:checked").length > 1) {
						$(this).find("input[type='checkbox']").prop("checked", false);
						$(this).find(".fa-check-square-o").hide();
						$(this).find(".fa-square-o").show();
						$(this).find("img").addClass("op50");
						$("form.searchandfilter input[type='submit']").click();
					} else {
						alert("Es ist nicht möglich, alle Tickets auszublenden. Möchtest Du diesen Typ ausblenden? Dann aktiviere zunächst einen anderen Typ.");
					}
				} else {
					$(this).find("input[type='checkbox']").prop("checked", true);
					$(this).find(".fa-check-square-o").show();
					$(this).find(".fa-square-o").hide();
					$(this).find("img").removeClass("op50");
					$("form.searchandfilter input[type='submit']").click();					
				}

			});
		
		re_init_shop_cat_after_ajax("init");
	}

	function unique(a) {
        return a.sort().filter(function(value, index, array) {
            return (index === 0) || (value !== array[index-1]);
        });
    }
    
	function sf_images(item, index) {
		$(".sf-level-0.sf-item-" + index + " > label").after('<i class="fa fa-square-o" aria-hidden="true"></i><i class="fa fa-check-square-o" aria-hidden="true"></i><img src="' + item + '"/>' );
	}
	
	function re_init_shop_cat_after_ajax(from) {
	  if (from === "ajax") {
	    // re-order events
 			var dates = new Array; 
 			$("time").each( function () {
   			dates.push($(this).attr("datetime"));
   		});
 			//console.log(dates);
 			var elem;
 			dates.forEach(function (date, index) {
  	    $("ul.products > li").eq(index).attr("data-event-date",date);
 			});
 			dates.sort();
 			//console.log(dates);
 			dates.forEach(function (date, index) {
  	    elem = $("ul.products > li[data-event-date='"+date+"']").detach();
  	    $("ul.products").append(elem); 			
 			});
	  }		
		if ($(".term-event-apfel-raclette").length > 0 || $(".term-event-feste-kinder-musik").length > 0) {
			var month = -1;
			$("time").each( function () { 
				var msec = new Date($(this).attr("datetime"));
				//console.log(msec);
				if (month != msec.getMonth()) {
					$(this).parents("li").prepend("<h2>" + $(this).attr("data-month-title") + "</h2>");
				}
				month = msec.getMonth();
			});	
			$(".product-img").each( function () { 
				if ($(this).find(".product-label").length > 0) {
					$(this).find("time small").css({ "display":"block", "margin-bottom":"15px" });
				}
			});
		}
		if ($(".term-event-apfel-raclette").length > 0) {
		
			if ($(".sf-input-checkbox:checked").length == 1 ) {
				$(".sf-input-checkbox:checked").prop("disabled",true);
			} else {
				$(".sf-input-checkbox:checked").prop("disabled",false);					
			}
		}
		return;
	}

  function init_single_product () {
  		if ($("body.single-product").length == 0)  return;
      //console.log("single product");      
      setTimeout(function(){ $(".owl-item.active").first().find("a").click(); }, 1000);
  }

	function init_cart() {
		if ($(".woocommerce-cart").length == 0)  return;
		var tmp = $(".cart-collaterals").detach();
		tmp.prependTo( $("table.shop_table td.actions .row"));

		$('<th id="shipping-label"></th><td id="shipping-fee"></td>').appendTo(".cart_totals tr.shipping");
		tmp = $(".cart_totals tr.shipping > td span.amount").detach();
		tmp.appendTo( $("#shipping-fee"));
		tmp = $("td[data-title='Versand']").html();
		$("#shipping-label").append(tmp);
		$("td[data-title='Versand']").remove();
		
		// AR+Kombi + Neujahr Vollzahler + AR To Go: allow max 6 tickets in steps by 2	
		$("tr.cart_item").each( function () { 
			var title = $(this).find(".product-thumbnail").text();			
			console.log("Title: " + title);

			if (title.match(/raclette/gi) != null || title.match(/abholung/gi) != null)
			{
				$(this).find("input.qty").attr({"step":"2","max":"6", "readonly":"readonly"});
			}
		});		
		
		// remove delivery hint if the cart item is an online tasting
		$("form table.shop_table.cart tr.cart_item").each( function () { 
			var delivery = $(this).find("a").attr("href");
			//console.log(delivery);
			var hide_delivery = false;
			if (delivery.match(/online/gi) != null && delivery.match(/tasting/gi) != null) {
				hide_delivery = true;
			}
			if (!hide_delivery) {
				delivery = $(".product-thumbnail").text();
				//console.log(delivery);
				if (delivery.match(/Tasting/gi) != null) {
					hide_delivery = true;
				}
			} 
			
			if (hide_delivery) $(this).find(".delivery-time-info").remove();

		});
	}
	
	function init_checkout() {
		if ($(".woocommerce-checkout").length == 0) return;

		setTimeout(function() { 
			$("table.shop_table tr.cart_item").each( function () { 
				var delivery = $(this).find(".wc-gzd-product-name-right").text();
				console.log(delivery);
				if (delivery.match(/Tasting/gi) != null) {
					$(this).find(".delivery-time-info").remove();
				}
			});
		}, 5000 );
		
		if (typeof cart_has_virtual === 'undefined' || cart_has_virtual === null) return; 

		// dirty hack to hide "Kauf auf Rechnung": position white layer over option in iframe
		if ( cart_has_virtual ) {
			var myVar = setInterval(function() { 
				if ($("#step-wrapper-payment:visible").length > 0) {
					//$("iframe").first().css('height','280px');
					$("iframe").first().after('<div style="background-color:#fff;height:67px;position:relative;top:-99px"></div>');
					$(".woocommerce-multistep-checkout .step-buttons").css( "margin-top","-10px" );
					clearInterval(myVar);
				}
			}, 1000);
		}

		// show/hide the hint when user clicks on "Yes, we want to sit next to another group"
		$("input#custom_checkbox_group").click( function () {
			$("#group-hint").slideToggle();			
		});
		
/*
		$("#shipping_phone_field").removeClass("validate-required");
		$("#shipping_phone_field label abbr").removeClass("required");
*/
		setTimeout(function(){ 
			$("input#shipping_phone").val("NA");
			$("input#shipping_email").val("NA");
		}, 1000);

	}
	
	function init_reservation() {
    if ( $('h1.tribe-events-single-event-title').text() === 'Sitzplatz-Reservierung' ) {
      $('#oas-before-single-event, oas-after-single-event').remove();
      //$('span.tribe-events-cost').text( $('span.tribe-events-cost').text() + ' p.P.');
      //$('.tribe-events-event-cost').text( $('.tribe-events-event-cost').text() + ' p.P.');
      var ticket_price_suffix = $('.woocommerce-Price-amount').first().text();
      $('.woocommerce-Price-amount').text(ticket_price_suffix);
    }
    if ( $('h1.tribe-events-single-event-title').text() === 'Picknickplatz-Reservierung' ) {
      $('#oas-before-single-event, oas-after-single-event').remove();
    }
 }	
	
	function init_rmv() {
		var oas_rmv_title = $(".tribe-events-after-html #oas-rmv-title").detach();
		var oas_rmv_btn = $(".tribe-events-after-html #oas-rmv-btn").detach();

		if ( $(".tribe-events-meta-group-venue").length == 0) return;
		
		// append the RMV button inside the location box
		$(".tribe-events-meta-group-venue dl").append(oas_rmv_title);
		$(".tribe-events-meta-group-venue dl").append(oas_rmv_btn);
		
		// modify the querystring of the button link to match the current event
		var date = $(".tribe-events-start-date").attr("title");
		var date_de = date.match(/[0-9]{2}/g);
		date_de = date_de[3] + '.' + date_de[2] + '.' + date_de[1];
		var time = $(".tribe-events-start-time").text();
		var timestart = time.match(/[0-2][0-9]:[0-5][0-9]/);		
		var url = $("#oas-rmv-btn").attr("href");
		url = url.replace(/01.01.70/, date_de);		
		url = url.replace(/00:00/, timestart);		

		//console.log(date_de);
		//console.log(timestart); 
		//console.log(url);

		$("#oas-rmv-btn").attr("href", url);		
	}
	
	function init_va_calc(vaa_offset) {
		if ($("#wpcf7-f1835-p145-o1").length == 0) return;

		// make the grand total sticky when it reached the top of the page
    $(window).scroll(function() {
      if ($(document).scrollTop() > vaa_offset) {
        $("#oas-vaa tr:last-child").addClass("oas-vaa-sticky");
      } else if ($(document).scrollTop() <= vaa_offset ){
        $("#oas-vaa tr:last-child").removeClass("oas-vaa-sticky");
      }
    });	

		var dialog_width = 600;
		if ($(window).outerWidth() < 600) dialog_width = $(window).outerWidth();
		// initiate jquery UI widgets
		$( "#oas-datum" ).datepicker();
		$( "#bg-dialog, #bb-dialog, #sb-dialog, #fz-dialog, #ar-dialog, #fw-dialog, #op-dialog, #ec-dialog, #lw-dialog" ).dialog({
			autoOpen: false,
			width: dialog_width,
			show: {
				effect: "blind",
				duration: 1000
      	},
      	hide: {
        		effect: "blind",
        		duration: 1000
      	}
    	});
    	
    	$( ".bg-opener" ).on( "click", function() { $( "#bg-dialog" ).dialog( "open" ); });
    	$( ".bb-opener" ).on( "click", function() { $( "#bb-dialog" ).dialog( "open" ); });
    	$( ".sb-opener" ).on( "click", function() { $( "#sb-dialog" ).dialog( "open" ); });
    	$( ".fz-opener" ).on( "click", function() { $( "#fz-dialog" ).dialog( "open" ); });
    	$( ".ar-opener" ).on( "click", function() { $( "#ar-dialog" ).dialog( "open" ); });
    	$( ".fw-opener" ).on( "click", function() { $( "#fw-dialog" ).dialog( "open" ); });
    	$( ".op-opener" ).on( "click", function() { $( "#op-dialog" ).dialog( "open" ); });
    	$( ".ec-opener" ).on( "click", function() { $( "#ec-dialog" ).dialog( "open" ); });
    	$( ".lw-opener" ).on( "click", function() { $( "#lw-dialog" ).dialog( "open" ); });
		
		// make all the totals readonly
		$("#oas-vaa .wpcf7-text").prop("readonly", true);

		// number fields
		$( "#oas-personen, #oas-kinder-7, #oas-kinder-3, #oas-serviceh" ).keyup(function () {
    		if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       		this.value = this.value.replace(/[^0-9\.]/g, '');
    		}
		});
		$( "#oas-personen, #oas-kinder-7, #oas-kinder-3, #oas-serviceh" ).focusout(function () {
    		if (this.value == "") this.value = 0;
		});
		
		
		// start the calculations
		var cost_pp = 
		    cost_pc = 
		    cost_food = 
		    cost_fw_ar = 
		    cost_lw = 
		    cost_catering = 
		    cost_service = 
		    cost_tent = 
		    total = 0;
		
		var persons = parseInt($("#oas-personen").val());
		var children = parseInt($("#oas-kinder-7").val());
		var children_small = parseInt($("#oas-kinder-3").val());
		if (isNaN(persons)) persons = 0;
		if (isNaN(children)) children = 0;
		if (isNaN(children_small)) children_small = 0;
		var persons_total = persons + children + children_small;

		// calculate the food per person
		$(".calc-food").each( function () {
			var ovalues = $(this).attr("class");
			var svalues = explode_ovalues(ovalues);
			var cost_pp = svalues[$(this)[0].selectedIndex] * persons;
			var cost_pc = svalues[$(this)[0].selectedIndex] / 2 * children;
			cost_food = cost_food + cost_pp + cost_pc;
			//console.log("food: " + cost_food);		
		}); 
		
		// calculate the AR and FW
		$(".calc-fw-ar").each( function () {
			var ovalues = $(this).attr("class");
			var svalues = explode_ovalues(ovalues);
			var cost_pp = svalues[$(this)[0].selectedIndex] * persons;
			var cost_pc = svalues[$(this)[0].selectedIndex] / 2 * children;
			cost_fw_ar = cost_fw_ar + cost_pp + cost_pc;
			//console.log("FW + AR: " + cost_fw_ar);		
		}); 
		
		// calculate the LW
		$(".calc-lw").each( function () {
			var ovalues = $(this).attr("class");
			var svalues = explode_ovalues(ovalues);
			var cost_pp = svalues[$(this)[0].selectedIndex] * persons;
			var cost_pc = svalues[$(this)[0].selectedIndex] / 2 * children;
			cost_lw = cost_lw + cost_pp + cost_pc;
			//console.log("LW: " + cost_lw);		
		}); 

		// calculate the catering
		$(".calc-catering").each( function () {
			var ovalues = $(this).attr("class");
			var svalues = explode_ovalues(ovalues);
			var cost_pp = svalues[$(this)[0].selectedIndex] * persons;
			var cost_pc = svalues[$(this)[0].selectedIndex] / 2 * children;
			cost_catering = cost_catering + cost_pp + cost_pc;
			//console.log("catering: " + cost_catering);		
		}); 

		// calculate the cost for the service
		var serviceh = parseInt($("#oas-serviceh").val());
		if (isNaN(serviceh)) serviceh = 0;
		ovalues = $("#oas-servicek").attr("class");
		svalues = explode_ovalues(ovalues);
		cost_service = svalues[$("#oas-servicek")[0].selectedIndex] * serviceh;
		//console.log("service nr: " + cost_service);
		//console.log("service h: " + serviceh);
		
		// calculate the cost for the tent
		//console.log($("#oas-zelt")[0].selectedIndex);
		//console.log($("#oas-zelt").prop("selectedIndex"));
	  $("span.fz-message").addClass("hide");
		if ($("#oas-zelt")[0].selectedIndex == 1) {
			if (persons_total <= 70) {
        cost_tent = 490;
			} else if (persons_total <= 80) {
        cost_tent = 600;
			} else if (persons_total <= 90) {
        cost_tent = 720;
			} else if (persons_total <= 100) {
        cost_tent = 850;
			} else if (persons_total <= 110) {
        cost_tent = 990;
			} else if (persons_total <= 120) {
        cost_tent = 1140;
			} else {
        cost_tent = 1140;
			  $("span.fz-message").removeClass("hide");
			}
			//console.log("tent: " + cost_tent);
		}

		// format and display the totals
		total = cost_food + cost_fw_ar + cost_lw + cost_catering + cost_service + cost_tent;
		var options = { symbol : "€",	decimal : ",", thousand: ".", precision : 2, format: "%v%s" };
		$("#oas-total-speisen").val(accounting.formatMoney(cost_food, options));		
    $("#oas-total-fw-ar").val(accounting.formatMoney(cost_fw_ar, options));
    $("#oas-total-lw").val(accounting.formatMoney(cost_lw, options));
    $("#oas-total-catering").val(accounting.formatMoney(cost_catering, options));
    $("#oas-total-service").val(accounting.formatMoney(cost_service, options));
    $("#oas-total-zelt").val(accounting.formatMoney(cost_tent, options));
		$("#oas-total").val(accounting.formatMoney(total, options));			
	}

	function init_bps() {
      var ajaxDialog = $('<div id="ajax-dialog" style="display:hidden"></div>').appendTo('body');
      var dialog_width = 800;
		if ($(window).outerWidth() < 800) dialog_width = $(window).outerWidth();
		
		$("#airview-dialog").dialog({
        width: 1024,
      	autoOpen: false,
      	width: dialog_width,
			show: {
				effect: "blind",
				duration: 500
      	},
      	hide: {
        		effect: "blind",
        		duration: 500
      	}});

      $('a.ajax-dialog-opener').click (function(event) {
          //prevent the browser from following the link
          event.preventDefault();
          // load remote content
          //console.log(this.href);
          $("#ajax-dialog").load(this.href, function () {
          	$("#ajax-dialog").dialog("open");
          });
          return;
      });

    	$( "#airview-opener" ).on( "click", function() { $( "#airview-dialog" ).dialog( "open" ); });
      $( "#nopermission").dialog({ 'modal' : true });
    	$( "#nopermission" ).dialog( "open" );
	}

	function explode_ovalues(ovalues) {
		var svalue = ovalues.match(/(v[0-9]+)/g);
		for (i = 0; i < svalue.length; i++) {
			svalue[i] = svalue[i].replace(/v/, "");
			svalue[i] = parseInt(svalue[i]) / 100;
		}
		return svalue; 
	}
	
	function round(value, decimals) {
	  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
	}

  function format_price(value) {
    price = Number(Math.round(value+'e'+2)+'e-'+2);
		price = price.toString();
		if (/^[0-9]+$/.test(price)) price = price + ',00';
		if (/\.[0-9]{1}$/.test(price)) price = price + '0'; 
		price = price.replace(/\./,",");
		return price;
  }
});