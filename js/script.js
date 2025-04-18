jQuery(document).ready(function($){

	// Брейкпоинты размеров экрана
	var WIDTH_TABL = 1000;
	var WIDTH_MOB = 768;
	var WIDTH_SMOB = 560;
	

	// Ночная тема


	/* Летающие затухающие звезды */
	/* Credit to Collin Henderson @ AstralApp.com */
	var WIDTH, HEIGHT, canvas, con, g;
    var pxs = [];
    var rint = 50;
  
    $.fn.sprites = function () {
      this.append($('<canvas id="sprites"></canvas>'));
      setup(this);
    }
  
    function setup (container) {
      var windowSize = function() {
        WIDTH = container.innerWidth();
        HEIGHT = container.innerHeight();
        canvas = container.find('#sprites');
        canvas.attr('width', WIDTH).attr('height', HEIGHT);
      };
  
      windowSize();
  
      $(window).resize(function() {
        windowSize();
      });
  
      con = canvas[0].getContext('2d');
  
      for (var i = 0; i < 500; i++) {
        pxs[i] = new Circle();
        pxs[i].reset();
      }
  
      requestAnimationFrame(draw);
    }
  
    function draw () {
      con.clearRect(0, 0, WIDTH, HEIGHT);
      con.globalCompositeOperation = "lighter";
  
      for (var i = 0; i < pxs.length; i++) {
        pxs[i].fade();
        pxs[i].move();
        pxs[i].draw();
      }
  
      requestAnimationFrame(draw);
    }
  
    function Circle() {
      this.s = {
        ttl: 15000,
        xmax: 5,
        ymax: 2,
        rmax: 7,
        rt: 1,
        xdef: 960,
        ydef: 540,
        xdrift: 4,
        ydrift: 4,
        random: true,
        blink: true
      };
  
      this.reset = function() {
        this.x = (this.s.random ? WIDTH * Math.random() : this.s.xdef);
        this.y = (this.s.random ? HEIGHT * Math.random() : this.s.ydef);
        this.r = ((this.s.rmax - 1) * Math.random()) + 1;
  
        this.dx = (Math.random() * this.s.xmax) * (Math.random() < 0.5 ? -1 : 1);
        this.dy = (Math.random() * this.s.ymax) * (Math.random() < 0.5 ? -1 : 1);
  
        this.hl = (this.s.ttl / rint) * (this.r / this.s.rmax);
        this.rt = Math.random() * this.hl;
  
        this.stop = Math.random() * 0.2 + 0.4;
  
        this.s.rt = Math.random() + 1;
        this.s.xdrift *= Math.random() * (Math.random() < 0.5 ? -1 : 1);
        this.s.ydrift *= Math.random() * (Math.random() < 0.5 ? -1 : 1);
      };
  
      this.fade = function() {
        this.rt += this.s.rt;
      };
  
      this.draw = function() {
        var newo, cr;
  
        if (this.s.blink && (this.rt <= 0 || this.rt >= this.hl)) {
          this.s.rt = this.s.rt * -1;
        }
        else if (this.rt >= this.hl) {
          this.reset();
        }
  
        newo = 1 - (this.rt / this.hl);
  
        con.beginPath();
        con.arc(this.x, this.y, this.r, 0, Math.PI * 2, true);
        con.closePath();
  
        cr = this.r * newo;
  
        g = con.createRadialGradient(this.x, this.y, 0, this.x, this.y, (cr <= 0 ? 1 : cr));
        // opacity variant

        // g.addColorStop(0.0, 'rgba(193,254,254,' + newo + ')');
        // g.addColorStop(this.stop, 'rgba(193,254,254,' + (newo * 0.2) + ')');
        // g.addColorStop(1.0, 'rgba(193,254,254,0)');

        g.addColorStop(0.0, 'rgba(255,255,255,1)');
        g.addColorStop(this.stop, 'rgba(255,255,255,1)');
        g.addColorStop(1.0, 'rgba(255,255,255,0)');
  
        con.fillStyle = g;
        con.fill();
      };
  
      this.move = function() {
        this.x += (this.rt / this.hl) * this.dx;
        this.y += (this.rt / this.hl) * this.dy;
        if (this.x > WIDTH || this.x < 0) this.dx *= -1;
        if (this.y > HEIGHT || this.y < 0) this.dy *= -1;
      };
  
      this.getX = function() {
        return this.x;
      };
  
      this.getY = function() {
        return this.y;
      };
	};
	

    var now = new Date();

    if (now.getHours() < 7 || now.getHours() > 16) {
		$('body').addClass('night_time');
		$('body').sprites();
		$('.contact_block').each(function(){
			var night_bg = $(this).data('night_time');
			if ( night_bg != '' && night_bg != undefined ) {
				$(this).css('background-color', night_bg);
			}
		});
    } else {
        $('body').removeClass('night_time');
	}
	
	// Анимирование кнопок
	$('.btn, .button, button.submit, .wc-backward').not('input').each(function(){
		$(this).addClass('anim_btn');
		$(this).html('<span class="anim_helpers"><span class="anim_helper">'+$(this).text()+'</span><span class="anim_helper2">'+$(this).text()+'</span></span>');
	});

	// Фансибокс
	function init_fancybox() {
		$('a.fancybox, area.fancybox, li.fancybox a').fancybox();
		$(".gallery a").attr('rel', 'gallery').fancybox();
	}
	init_fancybox();
		  
	
	// Подгрузка формы регистрации
	$( ".reg_container" ).load( $( ".reg_container" ).data("reg_page")+" #registerform" );

    $( ".content" ).on( "click", "#wp-submit", function() {
		var form = $(this).closest('form');
		$.ajax({
			type: "POST",
			url: form.attr('action'),
			data: form.serialize(),
			success: function(answ){
				$('.reg_answer').html('');
				$('.reg_answer').prepend($(answ).find('#login_error'));
				$('.reg_answer').prepend($(answ).find('.message').not('.register'));
			}
		});
		return false;
	});
	
	// Анимация полей форм при заполнении
	var animate_fields = 'input[type=text], ';
	animate_fields += 'input[type=password], ';
	animate_fields += 'input[type=tel], ';
	animate_fields += 'input[type=email], ';
	animate_fields += '.input-text';

	// Состояние по умолчанию
	function set_animate_fields (animate_fields) {
		$(".content").find(animate_fields).each(function(){
			$(this).addClass('animate_field');
			var font_size = $(this).closest('p').find('label').css('font-size');
			if ( $(this).closest('p').find('label').attr('data-font') === undefined ) {
				$(this).closest('p').find('label').attr('data-font', font_size);
			}
	
			// console.log($(this).val());
			$(this).change();
			if ($(this).val().length == 0) {
				$(this).closest('p').find('label').css({
					"position": "absolute", 
					"bottom": get_add_pos($(this))+7+"px",
				});
			} else {
				$(this).closest('p').find('label').css({
					"position": "absolute", 
					"bottom": get_add_pos($(this))+37+"px",
					"font-size": "14px",
				});
			}
		});
	}
	set_animate_fields(animate_fields);

	// При заполнении поля
	$(".content").on("focus", animate_fields, function(){
		$(this).closest('p').find('label').animate({
			fontSize: '14px',
			"bottom": get_add_pos($(this))+37+"px",
		}, 400);
	});
   
	// Если поле оставили пустым
	$(".content").on("blur", animate_fields, function(){
		if ($(this).val().length == 0) {
			var font_size = $(this).closest('p').find('label').data('font');
			$(this).closest('p').find('label').animate({
				bottom: get_add_pos($(this))+7+"px",
				fontSize: font_size,
			}, 400);
		}
	});


	// Вычисление позиции заголовка поля
	function get_add_pos(sel) {
		if (sel.closest('p').find('label').css('position') != 'absolute') {
			var label_height = sel.closest('p').find('label').outerHeight();
		} else {
			var label_height = 0;
		}
		var parent_height = sel.closest('p').outerHeight();
		var field_height = sel.outerHeight();
		var add_pos = parent_height - label_height - field_height;
		return add_pos;
	}

	// Коррекция позиции заголовка поля при изменениях структуры DOM другими скриптами

	var target_jq = $(".woocommerce-EditAccountForm").find('fieldset');
	var target_html = target_jq[0];

	if (target_jq.length != 0) {
		// Конфигурация observer (за какими изменениями наблюдать)
		var config = {
			childList: true,
			subtree: true,
		};

		// Колбэк-функция при срабатывании мутации
		var callback = function(mutationsList, observer) {
			for (var mutation of mutationsList) {
				if (mutation.type === 'childList') {
					set_animate_fields( target_jq.find(animate_fields) );
				}
			}
		};

		// Создаём экземпляр наблюдателя с указанной функцией колбэка
		var observer = new MutationObserver(callback);

		// Начинаем наблюдение за настроенными изменениями целевого элемента
		observer.observe(target_html, config);
			
	}

	// Для прокрутки таблиц в моб. разрешении
	$('.text, .woocommerce-MyAccount-content').find('table').wrap('<div class="table"></div>');



	
	// Слайдеры
	var sl1_sel = $('.rev_list');
	var sl1_conf = {
		dots: true,
		arrows: false,
		slidesToShow: 1,
		slidesToScroll: 1,
		adaptiveHeight: true,
	};
	setSlick(sl1_sel, sl1_conf);

	var sl2_sel = $('.rev_inst_list');
	var sl2_conf = {
		dots: false,
		arrows: true,
		slidesToShow: 5,
		slidesToScroll: 5,
		adaptiveHeight: true,
		responsive: [
		  {
			breakpoint: WIDTH_TABL,
			settings: {
			  slidesToShow: 3,
			  slidesToScroll: 3,
			}
		  },
		  {
			breakpoint: WIDTH_MOB,
			settings: {
			  slidesToShow: 2,
			  slidesToScroll: 2,
			}
		  },
		  {
			breakpoint: WIDTH_SMOB,
			settings: {
			  slidesToShow: 1,
			  slidesToScroll: 1,
			}
		  },
		]
	};
	setSlick(sl2_sel, sl2_conf);

	var sl3_sel = $('.main_rev_list');
	var sl3_conf = {
		dots: false,
		arrows: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		adaptiveHeight: true,
	};
	setSlick(sl3_sel, sl3_conf);

	var sl4_sel = $('.complect_products_wrap');
	var sl4_conf = {
		dots: false,
		arrows: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		adaptiveHeight: true,
	};
	setSlick(sl4_sel, sl4_conf);

	var sl5_sel = $('.flex-control-nav');
	var sl5_conf = {
		dots: false,
		arrows: true,
		slidesToShow: 3,
		slidesToScroll: 1,
		adaptiveHeight: true,
	};
	setSlick(sl5_sel, sl5_conf);

	var sl6_sel = $('.main_slider');
	var sl6_conf = {
		dots: false,
		arrows: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		adaptiveHeight: true,
	};
	setSlick(sl6_sel, sl6_conf);

	var sl7_sel = $('.showroom_slider');
	var sl7_conf = {
		dots: false,
		arrows: true,
		slidesToShow: 2,
		slidesToScroll: 1,
		adaptiveHeight: true,
		centerMode: true,
		variableWidth: true,
	};
	setSlick(sl7_sel, sl7_conf);

	// var sl8_sel = $('.deliv_list');
	// var sl8_conf = {
	// 	dots: false,
	// 	arrows: true,
	// 	slidesToShow: 4,
	// 	slidesToScroll: 1,
	// 	adaptiveHeight: true,
	// };
	// setSlick(sl8_sel, sl8_conf);


	function setSlick (sl_sel, sl_conf) {
		if (sl_sel.length) {
			if (sl_sel.hasClass('slick-initialized')) {
				sl_sel.slick('refresh');
			} else {
				sl_sel.slick(sl_conf);
			}
		}
	}


	// При ресайзе
	var sl_flag1 = 0;
	$(window).on("orientationchange load resize", function () {
		var sl_flag2 = 0;
		if (window.innerWidth <= WIDTH_TABL) {
			sl_flag2 = WIDTH_TABL;
			if (window.innerWidth <= WIDTH_MOB) {
				sl_flag2 = WIDTH_MOB;
			}
		}
		if (sl_flag1 != sl_flag2) {
			setSlick(sl2_sel, sl2_conf);
			// $('.sidebar').removeClass('opened');
			// sidebar_scroll();
			init_fancybox();
			sl_flag1 = sl_flag2;
		}
	});


	

	// $('.sidebar').on( 'mouseenter', '.sidebar_cat_list > li', function() {
	// 	$(this).find('.sidebar_subcat_list').slideDown();
	// });
	// $('.sidebar').on( 'mouseleave', '.sidebar_cat_list > li', function() {
	// 	$(this).find('.sidebar_subcat_list').slideUp();
	// });

	// Меню сайдбара в мобильной версии
	// $('.sidebar').on( 'click', '.sidebar_switch_mob, .close', function() {
	// 	$('.sidebar').toggleClass('opened');
	// 	sidebar_scroll();
	// });
	$('.sidebar').on( 'click', '.sidebar_panel_state', function() {
		$('.sidebar').toggleClass('opened');
	});

	// function sidebar_scroll() {
	// 	$(".sidebar_panel").mCustomScrollbar("destroy");
	// 	if ( $(".sidebar_panel").hasClass('mCustomScrollbar') === false && window.innerWidth < WIDTH_TABL ) {
	// 		$(".sidebar_panel").mCustomScrollbar({
	// 			theme:"dark-thin"
	// 		});
	// 	}
	// }

	$(window).on("orientationchange load resize scroll", function () {
		stick_sidebar();
		stick_form_cart_prod_page();
		stick_mob_head();
	});
	
	function stick_sidebar() {
		var stick_block = $(".sidebar_content");
		if (window.innerWidth >= WIDTH_TABL) {
			if ( $(".page_content").length > 0 ) {
				var container = $(".page_content");
			} else if ( $(".main_top").length > 0 ) {
				var container = $(".main_top");
			} else {
				return false;
			}
			var start = container.offset().top;
			var end = container.height() - stick_block.height();
			// var absolute_end = container.height();
			var current = $(this).scrollTop();
			var sb_width = $('.sidebar').width();
			// console.log($('.page_content').position().top);
			// console.log($('.page_content').height());
			if ( current >= start && current <= end ) {
				stick_block.removeClass('absolute').addClass('fixed').width(sb_width);
			} else {
				stick_block.removeClass('fixed');
				if ( current >= start ) {
					stick_block.addClass('absolute');
				} else {
					stick_block.removeClass('absolute');
				}
			}
		} else {
			stick_block.removeClass('absolute, fixed').removeAttr('style');
		}
	}

	function stick_form_cart_prod_page() {
		var stick_block = $("form.cart");
		if ( window.innerWidth >= 1490 && $("div.product").length > 0 ) {
			var container = $("div.product");
			var start = container.offset().top;
			var end = start + container.height() - stick_block.innerHeight();
			// var absolute_end = container.height();
			var current = $(this).scrollTop();
			var sb_width = container.width() * 0.49;
			var margin_left = container.width() - sb_width;

			if ( current >= start && current <= end ) {
				stick_block
					.removeClass('absolute')
					.addClass('fixed')
					.width(sb_width)
					.css('margin-left', margin_left);
			} else {
				stick_block.removeClass('fixed').css('margin-left', 0);
				if ( current >= start ) {
					stick_block.addClass('absolute');
				} else {
					stick_block.removeClass('absolute');
				}
			}
		} else {
			stick_block.removeClass('absolute, fixed').removeAttr('style');
		}
	}

	function stick_mob_head() {
		if (window.innerWidth <= WIDTH_MOB) {
			if ( $(this).scrollTop() >= 200) {
				$(".head_fix").addClass('fixed');
			} else {
				$(".head_fix").removeClass('fixed');
			}
		}
		return false;
	}


	// Мобильное верхнее  меню
	$('header').on( 'click', '.fix_mob_menu_switch', function() {
		$('.fix_mob').addClass('active');
	});
	$('.fix_mob').on( 'click', '.close', function() {
		$('.fix_mob').removeClass('active');
	});
	$('.fix_mob').on( 'click', '.menu-item-has-children', function(e) {
		if ($(e.target).is('a') === false) {
			$(this).find('ul').addClass('active');
			$('.close_sub_menu').addClass('active');
		}
	});
	$('.fix_mob').on( 'click', '.close_sub_menu', function() {
		$(this).removeClass('active');
		$('.fix_mob_panel_menu').find('ul').removeClass('active');
	});
	$('body').click(function(e){
		if ($(e.target).closest('.fix_mob').length == 0 && $(e.target).closest('.fix_mob_menu_switch').length == 0) {
			$('.fix_mob').removeClass('active, opened');
		}
	});



	// Фикс. меню в шапке
	if (window.innerWidth <= WIDTH_MOB) {
		$(window).scroll(function(){
			if ( $(this).scrollTop() >= 200) {
				// $(".h_btns").addClass('fix_head');
				$(".head_fix").addClass('fixed');
			} else {
				// $(".h_btns").removeClass('fix_head');
				$(".head_fix").removeClass('fixed');
			}
		});
	}


	if (window.history.length > 1) {
		$('.back_btn').attr('href', 'javascript:history.back()');
	}



	// Woocommerce

	// Запуск галереи на стр. товара по клику на изображение
	$('.content').on('click', '.woocommerce-product-gallery__image img', function(){
		if ($('.woocommerce-product-gallery__trigger').length > 0) {
			$('.woocommerce-product-gallery__trigger').click();
		}
		
	});


	// Анимация добавления в корзину
	$( ".content" ).on( "click", ".add_to_cart_button", function() {
		if ( /^\?add-to-cart(.*?)$/.test($(this).attr('href')) &&(!$(this).hasClass('prod_with_opts'))) {
			var img = $(this).closest('li').find('.woocommerce-loop-product__link').find('img');
			var minicart = $('.h_cart');
			img
			.clone()
			.appendTo('body')
			.css({
				"position": "absolute", 
				"top": img.offset().top+"px", 
				"left": img.offset().left+"px", 
				"z-index": 10000
			}).animate({
				left: minicart.offset().left + minicart.width()+"px",
				top: minicart.offset().top+"px",
				width: 'hide',
				height: 'hide',
			}, 700);
		}
	});

	// Обновление мини-корзины ajax
	$('body').on('added_to_cart removed_from_cart wc_fragments_refreshed', function(){
		$.ajax({
			type: "GET",
			data: 'cart_ajax=1',
			success: function(answ){
				if (answ.qty != 0) {
					if ($('.mini_cart_qty').length != 0) {
						$('.mini_cart_qty').text(answ.qty);
					} else {
						$('.mini_cart_qty_wrap').html('<div class="h_cart_qty mini_cart_qty">'+answ.qty+'</div>');
					}
				}
			}
		});
	});
		  

	// Избранное

	// Добавление товара в избранное
    $('.content').on('click', '.wish-add', function () {
        var wish = $.cookie('shop_wish');
        if (wish) {
            wish += ',' + $(this).data('product');
        } else {
            wish = '' + $(this).data('product');
		}
		var wish_arr = wish.split(',');
		var wish_uniq_arr = uniq_arr(wish_arr);
		var wish_qty = wish_uniq_arr.length;

        if (wish_qty > 0) {
			if ($('.wish_qty').length == 0) {
				$('.wish_qty_wrap').html('<div class="wish_qty">'+wish_qty+'</div>');
			} else {
				$('.wish_qty').html(wish_qty);
			}
        }
        $.cookie('shop_wish', wish_uniq_arr.join(','), { expires: 30, path: '/'});
		$(this).removeClass('wish-add').addClass('wish-remove');
        return false;
	});
	
	// Удаление товара из избранного
    $('.content').on('click', '.wish-remove', function () {
        var wish = $.cookie('shop_wish');
        if (wish) {
            wish_arr = wish.split(',');
        } else {
            wish_arr = [];
        }
		var wish_uniq_arr = uniq_arr(wish_arr);
        var i = $.inArray($(this).data('product') + '', wish_uniq_arr);
        if (i != -1) {
            wish_uniq_arr.splice(i, 1);
        }
        $(this).closest('.wish_list ul').remove();
        if (wish_uniq_arr.length > 0) {
            $.cookie('shop_wish', wish_uniq_arr.join(','), { expires: 30, path: '/'});
			var countwish = parseInt($('.h_wish .wish_qty').text(), 10);
			var countwish = countwish-1;
			$('.wish_qty').text(countwish);
        } else {
			$('.wish_qty').remove();
            $.cookie('shop_wish', null, {path: '/'});
        }
		if ( !$(this).hasClass('wishlist_remove_btn') ) {
			$(this).removeClass('wish-remove').addClass('wish-add');
		} else {
			$(this).closest('.wish_list_item').remove();
			if ( $('.content').find('.wish_list_item').length == 0 ) {
				$('.page_full_content').html('<p class="empty_page">У Вас нет избранных товаров</p>');
			}
		}
        return false;
	});
	

	// Для автообновления на странице корзины
    $( ".content" ).on( "change", ".quantity .qty", function() {
		$('[name=update_cart]').removeAttr('disabled').click();
	});
	// Кнопки - / +
    $('.content').on('click', '.pr_qty_minus', function(){
        var prod_change = $(this).siblings().find('input');
        var prod_count = $(prod_change).val();
        if (prod_count > 1) {
            prod_change.val(+prod_count - 1);
        }
        prod_change.change();
    });
    $('.content').on('click', '.pr_qty_plus', function(){
        var prod_change = $(this).siblings().find('input');
        var prod_count = $(prod_change).val();
        prod_change.val(+prod_count +1);
        prod_change.change();
	});



	// Стилизация фильтра цвета в каталоге
	$('select.woocommerce-widget-layered-nav-dropdown').each(function(select_index, select_element){
		if ($(select_element).hasClass("select2-hidden-accessible")) {
			$(select_element).select2('destroy');
		}
		$(select_element).hide();
		$(select_element).next('button').hide();
	});

	$('.woocommerce-widget-layered-nav-list__item').each(function(select_index, select_element){
		if ($(select_element).find('.filter_item').length > 0) {
			$(select_element).closest('.widget').prev().hide();
			if ($(select_element).hasClass('chosen')) {
				$(select_element).find('.filter_item').addClass('active');
			}
		}
	});




	// Объединение фильтров в каталоге
	$('.filter_go').click(function(){
		var filter = {};
		var rules = $('.sidebar form').serializeArray();
		for (var i = 0; i < rules.length; i++) {
			if (rules[i].value != '') {
				filter[rules[i].name] = rules[i].value;
			}
		}
		var min_price = $('#min_price').val();
		var max_price = $('#max_price').val();
		if (max_price != 0 && min_price !== undefined && max_price !== undefined) {
			filter['min_price'] = min_price;
			filter['max_price'] = max_price;
		}
		var href = '';
		for (var i in filter) {
			if (i.length != 0) {
				href = href+i+'='+filter[i]+'&';
			}
		}
		if (href != '') {
			window.location.href = window.location.href
			.replace(/\/page\/(.*)$/, '/')
			.replace(/\/\?(.*)$/, '/')
			+'?'+href;
		}
	});
	
    $('.sidebar').on('click', '.filter_item', function(){
		filter_rule_keys = []; 
		$(this).toggleClass('active');
		$(this).closest('.widget').find('.active').each(function(item_index, item_element) {
			filter_rule_keys.push($(item_element).data('value'));
		});
		$(this).closest('.widget').prev().find('select').val(filter_rule_keys).change();
	});


	// Подсветка активной метки в каталоге

	var curr_tag_url = set_tag_mask(window.location.href);
	$('.tagcloud').find('a').each(function() {
		var tag_url = set_tag_mask($(this).attr('href'));
		if (tag_url == curr_tag_url) {
			$(this).addClass('curr');
		}
	});

	function set_tag_mask (url) {
		var mask_url = url.replace(/^(.*?)(\/product-tag\/)(.*?)\/(.*?)$/, '$2$3/');
		return mask_url;
	}


	// Стилизация вариаций товара
	$('form.cart, .complect_select').find('select').each(function(select_index, select_element){
		var vb_id = '#sel_'+select_index;
		$(this).closest('.zaddon-type-container').addClass('zaddon-select-container');
		$(this).closest('.zaddon_select, .complect_select').append('<div class="variations_box" id="sel_'+select_index+'"></div>');
		$(vb_id).append('<div class="variations_active_item"></div>');
		$(vb_id).append('<div class="variations_box_items"></div>');
		if ($(select_element).val() == '') {
			var empty_val = true;
		} else {
			var empty_val = false;
		}
		$(select_element).find('option').each(function(option_index, option_element) {
			var option_text = $(option_element).text().trim();
			if ($(option_element).attr('selected') == 'selected') {
				var item_class = ' checked';
			} else {
				var item_class = '';
			}
			if (option_text != 'Choose an option') {
				$(vb_id).find('.variations_box_items').append('<div class="variations_item'+item_class+'" data-value="'+$(option_element).val()+'">'+option_text+'</div>');
			} else {
				if (empty_val) {
					$(vb_id).find('.variations_box_items').append('<div class="variations_item checked">Не нужно</div>');
				}
			}
		});
		$(vb_id).find('.variations_active_item').html($(vb_id).find('.checked').clone());
	});

    $('.content').on('click', '.variations_item', function(){
		$(this).closest('.variations_box').toggleClass('active');
		$('.variations_box').not('#'+$(this).closest('.variations_box').attr('id')).removeClass('active');
		$(this).closest('.zaddon_select, .complect_select').find('.variations_item').removeClass('checked');
		$(this).closest('.variations_box').find('.variations_active_item').html($(this).clone());
		$(this).closest('.zaddon_select, .complect_select').find('select').val($(this).data('value')).change();
	});
	
	$('body').click(function(e){
		if ( !$(e.target).hasClass('variations_item') ) {
			$('.variations_box').removeClass('active');
		}
	});


	//  Отправка форм в телеграм
	// $('body').on('click', '.wpcf7-submit, #place_order', function(){
	// 	var text = '';
	// 	$('.cart_item').each(function(){
	// 		text += $(this).text().replace(/(?:\r\n|\r|\n)/g, "")+"\n";
	// 	});
	// 	if (text != '') {
	// 		text += "\n\nИТОГО: "+$('.order-total td').text();
	// 	}
	// 	$.ajax({
	// 		type: "POST",
	// 		data: 'tgram=1&prods='+text+'&'+$(this).closest('form').serialize(),
	// 		// success: function(answ){
	// 		// 	console.log(answ);
	// 		// }
	// 	});
	// });

    // Модальные окна
	$('.show_modal').click(function(){
		var mod_window = '#'+$(this).data('window');
		$(mod_window).show().offset({top: $(window).scrollTop() + 100});
        $('.modal_blackout').show();
    
	});
	$('.modal_blackout, .modal_close').click(function(){
		$('.modal, .modal_blackout').hide();
    });
	
	// Самовсплывающее модальное окно
	// function modal_call_form_show() {
	// 	if ($.cookie('modal_call_form_show') != 1) {
	// 		$('.modal_call_form_btn').click();
	// 		$.cookie('modal_call_form_show', '1', {path: '/'});
	// 	}
	// }

	// setTimeout(modal_call_form_show, 30000);
	
	// Скрытие адреса в оформлении заказа, если выбран самовывоз
	function hide_address_for_pickup () {
		if ($('#shipping_method_0_local_pickup3:checked').length > 0) {
			$('#customer_details').find('.address-field').hide();
		} else {
			$('#customer_details').find('.address-field').show();
		}
	}
	hide_address_for_pickup();
	$('body').on('click', '#shipping_method li', hide_address_for_pickup);

	// Переход к якорям из меню
	$('.main_menu li a, .menu li a').click(function(){
		if (/^\/#(.*)$/.test($(this).attr('href'))) {
			var block_id = $($(this).attr('href').replace(/\//, ''));
			if (block_id.length) {
				// $('.f_mob_fix').hide();
				var pos = block_id.offset().top;
				$("html,body").animate({scrollTop: pos}, 500);
				return false;
			}
		}
	});

	$('.modal_print_form_subtit').click(function(){
		$(this).next().slideToggle();
	});


	// Стилизация кнопки выбора файла
	$( ".modal_print_form_file input" ).on( "change", function() {
		var file_label = $('.modal_print_form_file_wrap').find( '.load_file_label' );
		var file_name = $(this).val().replace(/^(.*)\\/, '');
		if ( $(this).val() != '' ) {
			file_label.text(file_name);
		} else {
			file_label.text('Переместите свое изображение сюда');
		}
	});
    



	// Удаление повторяющихся элементов из массива
	function uniq_arr(arr) {
		var seen = {};
		var out = [];
		var len = arr.length;
		var j = 0;
		for(var i = 0; i < len; i++) {
			 var item = arr[i];
			 if(seen[item] !== 1) {
				   seen[item] = 1;
				   out[j++] = item;
			 }
		}
		return out;
	}






	// DP-Studio комплектация товара
				
    var options = [];          
    init_options();

    $('#complect_form').on( 'click', '.slick-arrow', function() {
		refresh_options();
    });

    $('.complect_checkbox input[type="checkbox"]').on( 'change', function(e) {
        if ( $(this).is(':checked') ) {
            add_option( $(this) );
        } else {
            remove_option( $(this) );
        }
    });
    $('.complect_form select').on( 'change', function(e) {
		$(this).attr('name', $(this).find(':selected').data('option_id'));
		remove_option( $(this) );
        if ( $(this).val() != 0 ) {
            add_option( $(this) );
        }
    });

    $('#complect_form').on( 'submit', function(e) {
        e.preventDefault();
        let opts = $('input[name!="complect_product_id"], select', this).serialize();
        let add_options = {};
        add_options[ $('#pa_razmer-spalnogo-mesta').attr('name') ]  = $('#pa_razmer-spalnogo-mesta').val();			
        let request = { 
            options: opts, 
            add_options: $.param(add_options),
            product_id: $('input[name="complect_product_id"]').val(),
            action: 'complect_add_to_cart'
        };
        $.post('/wp-admin/admin-ajax.php', request, function(data, textStatus, xhr) {
			var data_obj = JSON.parse(data);
			// console.log(request);
			// console.log(data_obj);
            $('.complect_form_added_cart, .complect_form_added_cart_err').remove();
            if (data_obj.status != 'failed') {
				$('.complect_buy_block').append('<div class="complect_form_added_cart">Добавлено в корзину</div>');
				$('body').trigger('wc_fragments_refreshed');
            } else {
                $('.complect_buy_block').append('<div class="complect_form_added_cart_err">Не удалось добавить в корзину</div>');
            }
        });
    });

    function init_options() {
        $('.complect_checkbox input[type="checkbox"]').each(function(index, el) {
            if ( $(this).is(':checked') ) options[$(this).attr('name')] = $(this).val();
        });
        refresh_options();
    }

    function add_option( el ) {
        if (!(el.attr('name') in options)) {
            options[el.attr('name')] = el.val();
            refresh_options();
        }
    }

    function remove_option( el ) {
        if (el.attr('name') in options) {
            delete options[el.attr('name')];
		}
		$(el).find('option').each(function() {
			delete options[$(this).data('option_id')];
		});
		// console.log(options);
		refresh_options();
    }

    function refresh_options() {
        let amount = $('.slick-current').find('.complect_product').data('price');
        for (const [key, value] of Object.entries(options)) {
          amount += parseInt(value);
        }
        $('input[name="complect_product_id"]').val( $('.slick-current').find('.complect_product').data('id') );
        $('#complect_price').html(amount);
    }

});