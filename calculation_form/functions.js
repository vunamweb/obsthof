(function ($) {
    "use strict";
    $(document).ready(function ($) {
        $(".fancybox").on("click", function () {
            $.fancybox({
                href: this.href,
                type: $(this).data("type")
            }); // fancybox
            return false;
        }); // on
    });
    var w = $(window).width();
    var h = $(window).height();
    $('header:not(.header-v2) .woocommerce-search').css('height', h + 'px'); 
    $('header:not(.header-v2) .woocommerce-search').css('width', w + 'px');
    /* remove old "view cart" text*/

    $('body').on('added_to_cart', function () {
        $("a.added_to_cart").remove();
    });
    // Vertical Menu
    var clickOutSite = true;
    $('.open-vertical').click(function () {
        if (!$(this).hasClass('active')) {
            $(this).addClass('active');
            $('.vertical-menu').show().animate({
                'margin-left' : 0
            }, 400);
        } else {
            $(this).removeClass('active');
            $('.vertical-menu').animate({
                'margin-left' : '-270px'
            }, 400, function () {
                $('.vertical-menu').hide()
            });
        }
        clickOutSite = false;
        setTimeout(function () {
            clickOutSite = true;
        }, 100);
    });
    $('.vertical-menu').click(function () {
        clickOutSite = false;
        setTimeout(function () {
            clickOutSite = true;
        }, 100);
    });
    $(document).click(function () {
        if (clickOutSite && $('.open-vertical').hasClass('active')) {
            $('.open-vertical').trigger('click');
        }
    });
    //Sub-menu
    $(function(){
        $(".dropdown-menu > li > .caret").on("click",function(e){
              $(this).toggleClass('active');
              var current=$(this).next();
              var grandparent=$(this).parent().parent();
              if($(this).hasClass('caret'))
              grandparent.find(".mega-menu li ul li ul:visible").not(current).hide();
              current.toggle();
              e.stopPropagation();
        });
    });
    //category sidebar  
    $("<p></p>").insertAfter(".widget_product_categories ul.product-categories > li > a");
    var $p = $(".widget_product_categories ul.product-categories > li p");
    $(".widget_product_categories ul.product-categories > li:not(.current-cat):not(.current-cat-parent) p").append('<span><i class="fa fa-plus" aria-hidden="true"></i></span>');
    $(".widget_product_categories ul.product-categories > li.current-cat p").append('<span><i class="fa fa-minus" aria-hidden="true"></i></span>');
    $(".widget_product_categories ul.product-categories > li.current-cat-parent p").append('<span><i class="fa fa-minus" aria-hidden="true"></i></span>');
    $(".widget_product_categories ul.product-categories > li:not(.current-cat):not(.current-cat-parent) > ul").hide();

    $(".widget_product_categories ul.product-categories > li").each(function () {
        if ($(this).find("ul > li").length == 0) {
            $(this).find('p').remove();
        }

    });

    $p.click(function () {
        var $accordion = $(this).nextAll('ul');

        if ($accordion.is(':hidden') === true) {

            $(".widget_product_categories ul.product-categories > li > ul").slideUp();
            $accordion.slideDown();

            $p.find('span').remove();
            $p.append('<span><i class="fa fa-plus" aria-hidden="true"></i></span>');
            $(this).find('span').remove();
            $(this).append('<span><i class="fa fa-minus" aria-hidden="true"></i></span>');
        }
        else {
            $accordion.slideUp();
            $(this).find('span').remove();
            $(this).append('<span><i class="fa fa-plus" aria-hidden="true"></i></span>');
        }
    });


    $("<p></p>").insertAfter(".widget_product_categories ul.product-categories > li > ul > li > a");
    var $pp = $(".widget_product_categories ul.product-categories > li > ul > li p");
    $(".widget_product_categories ul.product-categories > li >ul >li > ul").hide();
    $(".widget_product_categories ul.product-categories > li > ul > li p").append('<span><i class="fa fa-plus" aria-hidden="true"></i></span>');

    $(".widget_product_categories ul.product-categories > li > ul > li").each(function () {
        if ($(this).find("ul > li").length == 0) {
            $(this).find('p').remove();
        }
    });

    $pp.click(function () {
        var $accordions = $(this).nextAll('ul');

        if ($accordions.is(':hidden') === true) {

            $(".widget_product_categories ul.product-categories > li > ul > li > ul").slideUp();
            $accordions.slideDown();

            $pp.find('span').remove();
            $pp.append('<span><i class="fa fa-plus" aria-hidden="true"></i></span>');
            $(this).find('span').remove();
            $(this).append('<span><i class="fa fa-minus" aria-hidden="true"></i></span>');
        }
        else {
            $accordions.slideUp();
            $(this).find('span').remove();
            $(this).append('<span><i class="fa fa-plus" aria-hidden="true"></i></span>');
        }
    });

    //countdown
    $("#DateCountdown").TimeCircles();
    var updateTime = function(){
        var date = $("#date").val();
        var time = $("#time").val();
        var datetime = date + ' ' + time + ':00';
        $("#DateCountdown").data('date', datetime).TimeCircles().start();
    }
    //Scroll to top
    $(window).load(function () {
        var wd = $(window).width();
        if ($('.scroll-to-top').length) {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 1) {
                    $('.scroll-to-top').css({bottom: "50px"});
                    if(foodfarm_params.header_sticky_mobile != 1){
                        if(wd > 991){
                            if(foodfarm_params.header_sticky == 1) {
                                $('.site-header').addClass("is-sticky");
                            }
                        } 
                    }else{
                        if(foodfarm_params.header_sticky == 1) {
                            $('.site-header').addClass("is-sticky");
                        }
                    }
                } else {
                    $('.scroll-to-top').css({bottom: "-100px"});
                    $('.site-header').removeClass("is-sticky");
                }
                if ($(this).scrollTop() > 500) {
                    $('.slide-section').addClass("active");
                }
                else {
                    $('.slide-section').removeClass("active");
                }
            });

            $('.scroll-to-top').click(function () {
                $('html, body').animate({scrollTop: '0px'}, 800);
                return false;
            });
        }
    });
    var wdw = $(window).width();
    if(wdw > 991){
        var left_ser = $('.page-home-4 .left-services').height();
        $('.page-home-4 .right-services').height(left_ser);
    }
    //like count gallery
    $('body').on('click', '.foodfarm-post-like', function (event) {
        event.preventDefault();
        var heart = $(this);
        var post_id = heart.data("post_id");
        var like_type = heart.data('like_type') ? heart.data('like_type') : 'post';
        heart.html("<i id='icon-like' class='fa fa-heart-o'></i><i id='icon-spinner' class='fa fa-spinner fa-spin'></i>");
        $.ajax({
            type: "post",
            url: ajax_var.url,
            data: "action=foodfarm-post-like&nonce=" + ajax_var.nonce + "&foodfarm_post_like=&post_id=" + post_id + "&like_type=" + like_type,
            success: function (count) {
                if (count.indexOf("already") !== -1)
                {
                    var lecount = count.replace("already", "");
                    if (lecount === "0")
                    {
                        lecount = "Like";
                    }
                    heart.prop('title', 'Like');
                    heart.removeClass("liked");
                    heart.html("<i id='icon-unlike' class='fa fa-heart-o'></i>" + lecount);
                }
                else
                {
                    heart.prop('title', 'Unlike');
                    heart.addClass("liked");
                    heart.html("<i id='icon-like' class='fa fa-heart-o'></i>" + count);
                }
            }
        });
    });
    /* Filter iostop */
    $(window).load(function() {
        var container = $('.isotope').isotope({
            itemSelector: '.item',
            layoutMode: 'fitRows',
            getSortData: {
                name: '.item'
            }
        });
        $('.btn-filter').each( function( i, buttonGroup ) {
            var filterLoadValue = $(this).find('.active').attr('data-filter');
            container.isotope({ filter: filterLoadValue });
        });
    });
    $(window).ready(function() {
        var container = $('.isotope').isotope({
            itemSelector: '.item',
            layoutMode: 'fitRows',
            getSortData: {
                name: '.item'
            }
        });
        $('.btn-filter').on( 'click', '.button', function() {
            var filterValue = $(this).attr('data-filter');
            container.isotope({ filter: filterValue });
        });
        $('.btn-filter').each( function( i, buttonGroup ) {
            var buttonGroup = $(buttonGroup);
            buttonGroup.on( 'click', '.button', function() {
                buttonGroup.find('.active').removeClass('active');
                $(this).addClass('active');
            });
        });
        $('.grid').isotope({
          itemSelector: '.grid-item',
          percentPosition: true
        })
    });
    /*Animation*/
    window.scrollReveal = new scrollReveal({
        mobile: false
    });
    //mini cart
    $(document).ready(function(){
        /* Menu responsive*/
        $(".btn-open").click(function(){
            $(".mega-menu").slideToggle("slow");
        });
    });
    $('#commentform .form-submit .submit').addClass("btn btn-default btn-icon");
    //remove class
    $( ".megamenu .dropdown-menu.children > li > ul.children" ).removeClass( "dropdown-menu" )
    //woocommerce
    $('body').bind('added_to_cart', function (response) {
        $('body').trigger('wc_fragments_loaded');
    });
    //tooltip
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    //last menu level 3
    //$('li.menu-item-has-children > .children li ul.children').last().addClass('menu-lv3-last');
    //ajaxload cart
    function woocommerce_add_cart_ajax_message() {
        if ($('.add_to_cart_button').length !== 0 && $('#cart_added_msg_popup').length === 0) {
            var message_div = $('<div>')
                    .attr('id', 'cart_added_msg'),
                    popup_div = $('<div>')
                    .attr('id', 'cart_added_msg_popup')
                    .html(message_div)
                    .hide();

            $('body').prepend(popup_div);
        }
    }

    woocommerce_add_cart_ajax_message();
    //Woocommerce update cart sidebar
    $('body').bind('added_to_cart', function (response) {
        $('body').trigger('wc_fragments_loaded');
        $('ul.products li .added_to_cart').remove();
        var msg = $('#cart_added_msg_popup');
        $('#cart_added_msg').html(foodfarm_params.ajax_cart_added_msg);
        msg.css('margin-left', '-' + $(msg).width() / 2 + 'px').fadeIn();
        window.setTimeout(function () {
            msg.fadeOut();
        }, 2000);
    });
    $('.page-coming-soon').css('height', h + 'px'); 
    $('.page-404').css('height', h + 'px');
    $(window).resize(function () {
        $('.page-coming-soon').css('height', h + 'px'); 
        $('.page-404').css('height', h + 'px');
        var wdw = $(window).width();
        if(wdw > 991){
            var left_ser = $('.page-home-4 .left-services').height();
            $('.page-home-4 .right-services').height(left_ser);
        }
    });
    $('.page-coming-soon .mc4wp-form input').attr("placeholder", "Subscribe for more information");
    $('.page-coming-soon .mc4wp-form button[type="submit"]').html("Notify me");
//product list view mode
if(foodfarm_params.type_product == 'list-default' || foodfarm_params.type_product == 'grid-default' || foodfarm_params.shop_list != true || foodfarm_params.type_product == ''){
    $('#grid_mode').unbind('click').click(function () {
        var $toggle = $('.viewmode-toggle');
        var $parent = $toggle.parent();
        var $products = $parent.find('ul.products');
        $('.product_types').addClass('product-grid').removeClass('product-list');
        $products.find('li').removeClass('col-md-12 col-sm-12');
        $('this').addClass('active');
        $('#list_mode').removeClass('active');
        if (($.cookie && $.cookie('viewmodecookie') == 'list') || !$.cookie) {
            if ($toggle.length) {
                $products.fadeOut(300, function () {
                    $products.addClass('grid').removeClass('list').fadeIn(300);
                });
            }
        }
        if ($.cookie)
            $.cookie('viewmodecookie', 'grid', {
                path: '/'
            });
        return false;
    });

    $('#list_mode').unbind('click').click(function () {
        var $toggle = $('.viewmode-toggle');
        var $parent = $toggle.parent();
        var $products = $parent.find('ul.products');
        $('.product_types').addClass('product-list').removeClass('product-grid');
        $products.find('li').addClass('col-md-12 col-sm-12');
        $(this).addClass('active');
        $('#grid_mode').removeClass('active');
        if (($.cookie && $.cookie('viewmodecookie') == 'grid') || !$.cookie) {
            if ($toggle.length) {
                $products.fadeOut(300, function () {
                    $products.addClass('list').removeClass('grid').fadeIn(300);
                });
            }
        }
        if ($.cookie)
            $.cookie('viewmodecookie', 'list', {
                path: '/'
            });
        return false;
    });

    if ($.cookie && $.cookie('viewmodecookie')) {
        var $toggle = $('.viewmode-toggle');
        if ($toggle.length) {
            var $parent = $toggle.parent();
            if ($parent.find('ul.products').hasClass('grid')) {
                $.cookie('viewmodecookie', 'grid', {
                    path: '/'
                });
            } else if ($parent.find('ul.products').hasClass('list')) {
                $.cookie('viewmodecookie', 'list', {
                    path: '/'
                });
            } else {
                $parent.find('ul.products').addClass($.cookie('viewmodecookie'));
            }
        }
    }
    if ($.cookie && $.cookie('viewmodecookie') == 'grid') {
        var $toggle = $('.viewmode-toggle');
        var $parent = $toggle.parent();
        var $products = $parent.find('ul.products');
        $('.viewmode-toggle #grid_mode').addClass('active');
        $('.product_types').addClass('product-grid').removeClass('product-list');
        $('.viewmode-toggle #list_mode').removeClass('active');
    }
    if ($.cookie && $.cookie('viewmodecookie') == 'list') {
        var $toggle = $('.viewmode-toggle');
        var $parent = $toggle.parent();
        var $products = $parent.find('ul.products');
        $('.viewmode-toggle #grid_mode').addClass('active');
        $('.product_types').addClass('product-grid').removeClass('product-list');
        $('.viewmode-toggle #list_mode').removeClass('active');
    }
    if(foodfarm_params.type_product == 'grid-default' || foodfarm_params.shop_list != true){
        if ($.cookie && $.cookie('viewmodecookie') == null) {
            var $toggle = $('.viewmode-toggle');
            if ($toggle.length) {
                var $parent = $toggle.parent();
                $parent.find('ul.products').addClass('grid');
                $('.product_types').addClass('product-grid')
            }
            $('.viewmode-toggle #grid_mode').addClass('active');
            if ($.cookie)
                $.cookie('viewmodecookie', 'grid', {
                    path: '/'
                });
        }
    }  
    if(foodfarm_params.type_product == 'list-default' || foodfarm_params.shop_list != true){
        if ($.cookie && $.cookie('viewmodecookie') == null) {
            var $toggle = $('.viewmode-toggle');
            if ($toggle.length) {
                var $parent = $toggle.parent();
                $parent.find('ul.products').addClass('list');
                $('.product_types').addClass('product-list')
            }
            $('.viewmode-toggle #list_mode').addClass('active');
            if ($.cookie)
                $.cookie('viewmodecookie', 'list', {
                    path: '/'
                });
        }
    }      
}
    // fix placeholder IE 9
    $('[placeholder]').focus(function () {
        var input = $(this);
        if (input.val() === input.attr('placeholder')) {
            input.val('');
            input.removeClass('placeholder');
        }
    }).blur(function () {
        var input = $(this);
        if (input.val() === '' || input.val() === input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
        }
    }).blur().parents('form').submit(function () {
        $(this).find('[placeholder]').each(function () {
            var input = $(this);
            if (input.val() === input.attr('placeholder')) {
                input.val('');
            }
        });
    });
    // Media product details
    if(!!$.prototype.elevateZoom) {
        $("img.zoom").elevateZoom({ zoomType: "inner", cursor: "crosshair", gallery:'thumbs_list_frame', imageCrossfade: true });
    }
    //quantily
    $('div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)').addClass('buttons_added').append('<div class="qty-number"><span class="increase-qty plus" onclick="">+</span></div>').prepend('<div class="qty-number"><span class="increase-qty minus" onclick="">-</span></div>');

    // Target quantity inputs on product pages
    $('input.qty:not(.product-quantity input.qty)').each(function () {
        var min = parseFloat($(this).attr('min'));

        if (min && min > 0 && parseFloat($(this).val()) < min) {
            $(this).val(min);
        }
    });

    $(document).off('click', '.plus, .minus').on('click', '.plus, .minus', function () {

        // Get values
        var $qty = $(this).closest('.quantity').find('.qty'),
                currentVal = parseFloat($qty.val()),
                max = parseFloat($qty.attr('max')),
                min = parseFloat($qty.attr('min')),
                step = $qty.attr('step');

        // Format values
        if (!currentVal || currentVal === '' || currentVal === 'NaN')
            currentVal = 0;
        if (max === '' || max === 'NaN')
            max = '';
        if (min === '' || min === 'NaN')
            min = 0;
        if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN')
            step = 1;

        // Change the value
        if ($(this).is('.plus')) {

            if (max && (max === currentVal || currentVal > max)) {
                $qty.val(max);
            } else {
                $qty.val(currentVal + parseFloat(step));
            }

        } else {

            if (min && (min === currentVal || currentVal < min)) {
                $qty.val(min);
            } else if (currentVal > 0) {
                $qty.val(currentVal - parseFloat(step));
            }

        }

        // Trigger change event
        $qty.trigger('change');
    });
    //wishlist
    $( document ).ready( function($){
        if(typeof yith_wcwl_l10n != 'undefined') {
            var update_wishlist_count = function() {
                var data = {
                    action: 'update_wishlist_count'
                };
                $.ajax({
                    type: 'POST',
                    url: yith_wcwl_l10n.ajax_url,
                    data: data,
                    dataType: 'json',
                    beforeSend: function () {

                    },
                    success   : function (data) {
                        $('a.update-wishlist span').html('('+data+')');
                    }
                });
            };

            $('body').on( 'added_to_wishlist removed_from_wishlist', update_wishlist_count );
        }
    } );
    $('.ult_acord').remove();
    //prettyPhoto
    $(document).ready(function(){
        $("area[rel^='prettyPhoto']").prettyPhoto();
        
        $(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: false});
        $(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
    });
    // Viewby
    $( '.woocommerce-viewing' ).off( 'change' ).on( 'change', 'select.count', function() {
        $( this ).closest( 'form' ).submit();
    });
    //gallery
    var gallery_paged = $('#gallery-loadmore').data('paged');
    var gallery_page = gallery_paged ? gallery_paged + 1 : 2;
    var Gallery = {
        _initialized: false,
        init: function () {
            if (this._initialized)
                return false;
            this._initialized = true;
            this.galleryMasonry();
            this.galleryLoadmore();
        },
        galleryMasonry: function () {
            $(window).load(function () {
                $('.gallery-entries-wrap').isotope({
                    itemSelector: '.grid-item',
                });
            });    
        },
        galleryLoadmore: function () {
            $('#gallery-loadmore').click(function (event) {
                event.preventDefault();
                var el = $(this);
                var gallery_wrap = $('.gallery-entries-wrap');
                var url = $(this).attr('href');
                $('.load-more').append('<i class="fa fa-refresh fa-spin"></i>');
                el.find('.fa-long-arrow-right').remove();
                el.addClass('hide-loadmore');
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {paged: gallery_page},
                    success: function (response) {
                        $('.load-more').find('.fa-spin').remove();
                        el.removeClass('hide-loadmore');
                        el.append('<i class="fa fa-long-arrow-right"></i>');
                        var result = $(response).find('.gallery-entries-wrap').html();
                        if ($().isotope) {
                            $(result).imagesLoaded(function () {
                                if (gallery_wrap.data('isotope')) {
                                    gallery_wrap.isotope('insert', $(result));
                                }
                            });
                        }
                        gallery_page++;
                        if (gallery_page > parseInt(el.data('totalpage'))) {
                            el.parent().remove();
                        }
                    }
                });
            });
        }
    };
    
    
    //knowledgebase
    var knowledgebase_paged = $('#knowledgebase-loadmore').data('paged');
    var knowledgebase_page = knowledgebase_paged ? knowledgebase_paged + 1 : 2;
    var Knowledgebase = {
        _initialized: false,
        init: function () {
            if (this._initialized)
                return false;
            this._initialized = true;
            this.knowledgebaseLoadmore();
        },
        knowledgebaseLoadmore: function () {
            $('#knowledgebase-loadmore').click(function (event) {
                event.preventDefault();
                var el = $(this);
                var knowledgebase_wrap = $('.knowledge-list ul.knowledge-list-wrap');
                var url = $(this).attr('href');
                $('.load-more').append('<i class="fa fa-refresh fa-spin"></i>');
                el.find('.fa-long-arrow-right').remove();
                el.addClass('hide-loadmore');
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {pager: knowledgebase_page},
                    success: function (response) {
                        $('.load-more').find('.fa-spin').remove();
                        el.removeClass('hide-loadmore');
                        el.append('<i class="fa fa-long-arrow-right"></i>');
                        var result = $(response).find('.knowledge-list ul.knowledge-list-wrap').html();
                        knowledgebase_wrap.append(result);
                        knowledgebase_page++;
                        if (knowledgebase_page > parseInt(el.data('totalpage'))) {
                            el.parent().remove();
                        }
                    }
                });
            });
        }
    };
    $(document).ready(function () {
        Gallery.init();
        Knowledgebase.init();
    });

})(jQuery);
// Active Cart, Search
function toggleFilter(obj){
    if(jQuery(obj).parent().find('.content-filter').hasClass('active')){
        jQuery(obj).parent().find('.content-filter').removeClass('active');
    }else{
        jQuery('.content-filter').removeClass('active');
        jQuery(obj).parent().find('.content-filter').addClass('active');
    }
}