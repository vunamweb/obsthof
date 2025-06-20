function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

$(document).ready(function() {
	// Highlight any found errors
	$('.form_calculation').parent().parent().css('width', '100%');

	if($('.input-group').length > 0) {
        $('.clear_date').click(function(){
			jQuery.ajax({
				url: window.location.href,
				type: 'POST',
				data: {
					filter_event: "filter",
					filter_date: '0-0'
				},
				success: function (msg) {
					$('#main_content').html(msg);
					$('#grid-view').click();
					$('.clear_date').hide();
					$('.date_event').hide();
					$('.date_event_1').show();
				}
			});
		})

		$('.input-group').datetimepicker({
			format: "MM-YYYY",
		viewMode: "months", 
		//startView: "year", 
		minViewMode: "months",
		isRTL: false,
		autoclose:true,
		language: 'de'
		});

		var start_filter = false;

	$('.input-group').datetimepicker().on('dp.change', function(e){
		if(e.date && start_filter){

			jQuery.ajax({
				url: window.location.href,
				type: 'POST',
				data: {
					filter_event: "filter",
					filter_date: $('.datetimepicker').val()
				},
				success: function (msg) {
					$('#main_content').html(msg);
					$('#grid-view').click();
					$('.clear_date').show();
					$('.date_event').show();
					$('.date_event_1').hide();

					var searchDate = $('.datetimepicker').val();
					searchDate = searchDate.split('-');

					switch(searchDate[0]) {
						case '02':
							searchDate[0] = 'Februar';
							break;
							case '03':
							searchDate[0] = 'März';
							break;
							case '04':
							searchDate[0] = 'April';
							break;
							case '05':
							searchDate[0] = 'Mai';
							break;
							case '06':
							searchDate[0] = 'Juni';
							break;
							case '07':
							searchDate[0] = 'Juli';
							break;
							case '08':
							searchDate[0] = 'August';
							break;
							case '09':
							searchDate[0] = 'September';
							break;
							case '10':
							searchDate[0] = 'Oktober';
							break;
							case '11':
							searchDate[0] = 'November';
							break;
							case '12':
							searchDate[0] = 'Dezember';
							break;
						default:  
						searchDate[0] = 'Januar';		
					}

					var value_date = searchDate[0] + ' ' + searchDate[1];

					$('.date_event').html(value_date);

					/*var searchDate = $('.datetimepicker').val();

			$('.product-grid').each(function(){
				var dateEvent = $(this).find('.date_event').val();

				if(searchDate == dateEvent)
				  $(this).show();
				else 
				  $(this).hide();  
			})*/
}
			});
        } else {
			start_filter = true;
		}
	  });
	}

	$('.show_date_event').click(function() {
		$('.datetimepicker-addon').click();
	});

	$('#news').click(function() {
	   var height = $('#bestseller').offset().top;
	   $("html, body").animate({scrollTop:height}, 1000);  
	   //$(window).scrollTop(height);
	});

	$('.text-danger').each(function() {
		var element = $(this).parent().parent();

		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});

	// Currency
	$('#form-currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#form-currency input[name=\'code\']').attr('value', $(this).attr('name'));

		$('#form-currency').submit();
	});

	// Language
	$('#form-language .language-select').on('click', function(e) {
		e.preventDefault();

		$('#form-language input[name=\'code\']').attr('value', $(this).attr('name'));

		$('#form-language').submit();
	})

	/* Search */
	$('#search input[name=\'search\']').parent().find('button').on('click', function() {
		var url = $('base').attr('href') + 'index.php?route=product/search';

		var value = $('header input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('#search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('header input[name=\'search\']').parent().find('button').trigger('click');
		}
	});

	function lsTest(){
		var test = 'test';
		try {
			localStorage.setItem(test, test);
			localStorage.removeItem(test);
			return true;
		} catch(e) {
			return false;
		}
	}

	function getCookie(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1);
			if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
		}
		return "";
	}

	// Product List
	$('#list-view').click(function() {
		$('#content .product-grid > .clearfix').remove();
		$(this).addClass('active');
		$('#grid-view').removeClass('active');
		$('#content .product-grid').attr('class', 'product-layout product-list col-xs-12');

		if (lsTest() === true) {
			localStorage.setItem('display', 'list');
		} else {
			document.cookie = "display=list";
		}
	});

	// Product Grid
	$('#grid-view').click(function() {
		// What a shame bootstrap does not take into account dynamically loaded columns
		var cols = $('#column-right, #column-left').length;
		$(this).addClass('active');
		$('#list-view').removeClass('active');
		if (cols == 2) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
		} else if (cols == 1) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
		} else {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
		}

		if (lsTest() === true) {
			localStorage.setItem('display', 'grid');
		} else {
			document.cookie = "display=grid";
		}
	});
	$('#grid-view, #list-view').click(function(){
		$.fn.matchHeight._update();
	});	

	if (lsTest() === true) {
		if (localStorage.getItem('display') == 'list') {
			$('#list-view').trigger('click');
		} else {
			$('#grid-view').trigger('click');
		}
	} else {
		if (getCookie('display') == "list") {
			$('#list-view').trigger('click');
		} else {
			$('#grid-view').trigger('click');
		}
	}

	// Checkout
	$(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function(e) {
		if (e.keyCode == 13) {
			$('#collapse-checkout-option #button-login').trigger('click');
		}
	});

	// tooltips on hover
	var iOS = /iPad|iPhone|iPod/.test(navigator.platform);
	if (!iOS) {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body', delay: { "show": 500, "hide": 100 }, trigger: 'hover'});
	}

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		if (!iOS) {
			$('[data-toggle=\'tooltip\']').tooltip({container: 'body', delay: { "show": 500, "hide": 100 }, trigger: 'hover'});
		}
	});

	// Ajax Add To Cart
	$('.ajax-overlay_close').on('click touchstart', function (e) {
		e.preventDefault();
		$('.ajax-overlay').removeClass('visible');
		returnOptions();
	});

	$('body').delegate('.alert .close', 'click', function () {
		$(this).parent().addClass('fadeOut');
	});
});

$(document).on('click touchstart', function (e) {
	var container = $(".product-option-wrap");
	var overlay = $('.ajax-overlay');
	if (container.is(e.target)
		&& overlay.hasClass('visible')) {
		overlay.removeClass('visible');
	returnOptions();
}
});

function ajaxAdd(el, product_id) {
	var width = $(window).width();
	var selector = el.parents('.product-thumb').find('.product-option-wrap');
	if (width > 767 && selector.length) {
		var overlay = $('.ajax-overlay');
		overlay.append(selector);
		overlay.addClass('visible');
		$('body').addClass('ajax-overlay-open');
	} else {
		cart.add(product_id);
	}
}

function returnOptions() {
	var options = $('.ajax-overlay').find('.product-option-wrap');
	$('body').removeClass('ajax-overlay-open');
	$('.product-thumb.options').each(function () {
		if ($(this).find('.product-option-wrap').length == 0) {
			$(this).append(options);
			return;
		}
	});
}
var timer;
// Cart add remove functions
;(function ($) {
	if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
		console.log(sessionStorage);
	}
})(jQuery);
var cart = {
	'removeAndSave': function(product_id, cart_id) {
		wishlist.add(product_id);
		cart.remove(cart_id);
		$('.tooltip').remove();
	},
	'addPopup': function (el) {
		var selector = el.parents('.product-option-wrap');
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: selector.find('input[type=\'text\'], input[type=\'radio\']:checked, input[type=\'hidden\'], input[type=\'checkbox\']:checked, select, textarea'),
			dataType: 'json',
			success: function (json) {
				$('.alert, .text-danger').remove();
				clearTimeout(timer);
				if (json['error']) {
					if (json['error']['option']) {
						for (i in json['error']['option']) {
							var element = $('[id*="input-option' + i.replace('_', '-') +'"]');

							if (element.parent().hasClass('input-group')) {
								element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
							} else {
								element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
							}
						}
					}
					if (json['error']['recurring']) {
						$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
					}
					$('.text-danger').parent().addClass('has-error');
				}
				if (json['success']) {
					$('.ajax-overlay').removeClass('visible');
					returnOptions();

					$('#content').parent().before('<div class="alert alert-success"><i class="material-design-verification24"></i> ' + json['success'] + '<button type="button" class="close material-design-close47"></button></div>');
					$('#cart-total').html(json['total']);
					$('#cart-total2').html(json['total2']);
					$('#cart-total3').html(json['total3']);

					$('#cart > ul').load('index.php?route=common/cart/info ul li');
					timer = setTimeout(function () {
						$('.alert').addClass('fadeOut');
					}, 4000);
					$('.fancybox-close').length ? $('.fancybox-close').trigger('click') : null;
				}
			}
		});
	},
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			success: function(json) {
				$('.alert, .text-danger').remove();
				clearTimeout(timer);
				if (json['redirect']) {
					location = json['redirect'];
				}
				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="material-design-verification24"></i> ' + json['success'] + '<button type="button" class="close material-design-close47"></button></div>');
					$('#cart-total').html(json['total']);

					$('#cart-total2').html(json['total']);
					$('#cart-total2').removeClass('hide');

					$('#cart-total3').html(json['total3']);

					$('#cart > ul').load('index.php?route=common/cart/info ul li');
					timer = setTimeout(function () {
						$('.alert').addClass('fadeOut');
					}, 4000);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			success: function(json) {
				clearTimeout(timer);
				$('#cart > button').button('reset');
				$('#cart-total').html(json['total']);
				$('#cart-total2').html(json['total3']);
				$('#cart-total2').html(json['total3']);
				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
				timer = setTimeout(function () {
					$('.alert').addClass('fadeOut');
				}, 4000);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			success: function(json) {
				clearTimeout(timer);

				$('#cart-total').html(json['total']);
				$('#cart-total2').html(json['total']);
				$('#cart-total3').html(json['total3']);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
					location.reload();
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
				timer = setTimeout(function () {
					$('.alert').addClass('fadeOut');
				}, 4000);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			success: function(json) {
				clearTimeout(timer);
				$('#cart-total').html(json['total']);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart').load('index.php?route=common/cart/info #cart');
				}
				timer = setTimeout(function () {
					$('.alert').addClass('fadeOut');
				}, 4000);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var wishlist = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['info']) {
					$('#content').parent().before('<div class="alert alert-info"><i class="material-design-round52"></i> ' + json['info'] + '<button type="button" class="close material-design-close47"></button></div>');
				}

				$('#wishlist-total').html(json['total']);
				timer = setTimeout(function () {
					$('.alert').addClass('fadeOut');
				}, 4000);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['info']){
					$('#content').parent().before('<div class="alert alert-info"><i class="material-design-round52"></i> ' + json['info'] + '<button type="button" class="close material-design-close47"></button></div>');
				}

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="material-design-verification24"></i> ' + json['success'] + '<button type="button" class="close material-design-close47"></button></div>');
					if (json['warning']) {
						$('.alert').append('<div class="alert alert-warning"><i class="material-design-warning37"></i> ' + json['warning'] + '<button type="button" class="close material-design-close47"></button></div>');
					}

					$('#compare-total').attr('data-original-title', json['total']);
					$('#compare-total').html('<span>' + json['total'] + '</span>');
					var tmp = $('#compare-total2');
					tmp.html(json['total']);
					tmp.attr('title', json['total']);
				}
				timer = setTimeout(function () {
					$('.alert').addClass('fadeOut');
				}, 4000);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});

// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function() {
				this.request();
			});

			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
					this.hide();
					break;
					default:
					this.request();
					break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				value = $(event.target).parent().attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function() {
				var pos = $(this).position();

				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('ul.dropdown-menu').show();
			}

			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function(json) {
				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('ul.dropdown-menu').html(html);
			}

			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

		});
	}
})(window.jQuery);

$(window).on("scroll", function() {
	setNavbar();
})
function setNavbar() {
	if($(window).scrollTop() > 100) {
		$('body').addClass('black');
	}
	else {
		$('body').removeClass('black');
	}
}