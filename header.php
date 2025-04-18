<!DOCTYPE html>
<html lang="ru">
<head>
	<meta name="yandex-verification" content="92f59278719e284a" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
	<link rel="stylesheet" href="<?=TMPL?>/css/slick.css">
	<link rel="stylesheet" href="<?=TMPL?>/css/fancybox/jquery.fancybox-1.3.8.css">
	<link rel="stylesheet" href="<?=TMPL?>/css/jquery.mCustomScrollbar.css">
	<link href="<?=TMPL?>/style.css?v=<?=filemtime(get_template_directory() . '/style.css')?>" rel="stylesheet">
	<script src="<?=TMPL?>/js/slick.min.js"></script>
	<script src="<?=TMPL?>/js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="<?=TMPL?>/js/jquery.fancybox-1.3.8.min.js"></script>
	<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(85230955, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/85230955" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</head>
<body class="<?php echo get_page_class(); ?>">


<header>
	<div class="wrapper">
		<div class="head_content head_top head_pc">
			<div class="h_left">
				<div class="h_logo">
					<a href="/">
						<?=get_site_logo()?>
						<div class="h_logo_text"><?=get_field('logo_text', HOME)?></div>
					</a>
				</div>
			</div>
			<div class="h_contacts">
				<p class="h_tel_text"><?=do_shortcode('[worktime]')?></p>
				<p class="h_tel"><?=do_shortcode('[tel]')?></p>
			</div>
			<div class="h_dignity">
				<? while (have_rows('head_advants', HOME)) { the_row(); ?>
					<div class="h_dignity_item">
						<div class="h_dignity_icon"><?=getACFpic(get_sub_field('img'))?></div>
						<div class="h_dignity_text"><?=get_sub_field('text')?></div>
					</div>
				<? } ?>
			</div>
			<div class="h_btns">
				<div class="h_wish">
					<a href="<?=get_permalink(WISH)?>">
						<div class="wish_qty_wrap">
							<? show_wishlist_page_link(); ?>
						</div>
						<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 3.5625C8.79278 1.50575 6.8115 0 4.75 0C2.06572 0 0 2.29425 0 5.34375C0 9.53444 4.00372 12.7751 9.5 19C14.9963 12.7751 19 9.53444 19 5.34375C19 2.29425 16.9343 0 14.25 0C12.1864 0 10.2072 1.50575 9.5 3.5625Z" fill="white"/>
						</svg>
					</a>
				</div>
				<div class="h_cart">
					<a href="<?=wc_get_cart_url()?>">
						<div class="mini_cart_qty_wrap">
							<? if ( !empty(WC()->cart->get_cart_contents_count()) ) { ?>
								<div class="h_cart_qty mini_cart_qty"><?=WC()->cart->get_cart_contents_count()?></div>
							<? } ?>
							<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M19.0441 19.0025L17.8712 6.12562C17.8396 5.77087 17.5426 5.5 17.1865 5.5H15.124V4.125C15.124 3.0195 14.695 1.98275 13.9181 1.20587C13.1536 0.44 12.0894 0 10.999 0C8.72474 0 6.87399 1.85075 6.87399 4.125V5.5H4.81149C4.45537 5.5 4.15837 5.77087 4.12674 6.12562L2.95662 19.0011C2.88649 19.7698 3.14499 20.5356 3.66474 21.1049C4.18449 21.6741 4.92424 22 5.69562 22H16.3037C17.0737 22 17.8135 21.6741 18.3332 21.1063C18.8544 20.537 19.1115 19.7697 19.0441 19.0025ZM13.749 5.5H8.24899V4.125C8.24899 2.60837 9.48237 1.375 10.999 1.375C11.7277 1.375 12.4372 1.6665 12.946 2.17663C13.4644 2.695 13.749 3.38663 13.749 4.125V5.5Z" fill="white"/>
							</svg>
						</div>
						<div class="mini_cart_sum">
							<? if ( !empty(WC()->cart->get_cart_contents_count()) ) { ?>
								<div class="mini_cart_sum_tit">На сумму:</div>
								<div class="mini_cart_sum_val"><?=WC()->cart->get_cart_subtotal()?></div>
							<? } ?>
						</div>
					</a>
				</div>
			</div>
		</div>
		<div class="head_content head_bottom head_pc">
			<div class="h_left">
			</div>
			<div class="h_menu">
                <?php wp_nav_menu( array(
                    'menu' => 'menu2',
                    'container' => 'ul',
                )); ?>
			</div>
			<?php // if ( !empty(get_field('wb_link', HOME)) ) { ?>
				<!-- <div class="h_wb">
					<a class="h_wb_link" href="<?=get_field('wb_link', HOME)?>"><span>Мы на </span><?=getACFpic(get_field('wb_img', HOME))?></a>
				</div> -->
			<? // } ?>
		</div>
		<div class="head_mob">
			<div class="head_fix">
				<div class="head_fix_wrap">
					<div class="fix_mob_menu_switch">
						<svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
							<rect width="32" height="4" rx="2" fill="#F7C6B6"/>
							<rect y="9" width="32" height="4" rx="2" fill="#F7C6B6"/>
							<rect y="18" width="32" height="4" rx="2" fill="#F7C6B6"/>
						</svg>
					</div>
					<div class="h_mob_logo">
						<div class="h_logo"><a href="/"><?=get_site_logo()?></a></div>
						<div class="h_logo_text"><?=get_field('logo_text', HOME)?></div>
					</div>
					<div class="h_wish">
						<a href="<?=get_permalink(WISH)?>">
							<div class="wish_qty_wrap">
								<? show_wishlist_page_link(); ?>
							</div>
							<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 3.5625C8.79278 1.50575 6.8115 0 4.75 0C2.06572 0 0 2.29425 0 5.34375C0 9.53444 4.00372 12.7751 9.5 19C14.9963 12.7751 19 9.53444 19 5.34375C19 2.29425 16.9343 0 14.25 0C12.1864 0 10.2072 1.50575 9.5 3.5625Z" fill="white"/>
							</svg>
						</a>
					</div>
					<div class="h_cart">
						<a href="<?=wc_get_cart_url()?>">
							<div class="mini_cart_qty_wrap">
								<? if ( !empty(WC()->cart->get_cart_contents_count()) ) { ?>
									<div class="h_cart_qty mini_cart_qty"><?=WC()->cart->get_cart_contents_count()?></div>
								<? } ?>
								<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M19.0441 19.0025L17.8712 6.12562C17.8396 5.77087 17.5426 5.5 17.1865 5.5H15.124V4.125C15.124 3.0195 14.695 1.98275 13.9181 1.20587C13.1536 0.44 12.0894 0 10.999 0C8.72474 0 6.87399 1.85075 6.87399 4.125V5.5H4.81149C4.45537 5.5 4.15837 5.77087 4.12674 6.12562L2.95662 19.0011C2.88649 19.7698 3.14499 20.5356 3.66474 21.1049C4.18449 21.6741 4.92424 22 5.69562 22H16.3037C17.0737 22 17.8135 21.6741 18.3332 21.1063C18.8544 20.537 19.1115 19.7697 19.0441 19.0025ZM13.749 5.5H8.24899V4.125C8.24899 2.60837 9.48237 1.375 10.999 1.375C11.7277 1.375 12.4372 1.6665 12.946 2.17663C13.4644 2.695 13.749 3.38663 13.749 4.125V5.5Z" fill="white"/>
								</svg>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="h_mob_contacts">
				<p class="h_tel_text"><?=do_shortcode('[worktime]')?></p>
				<p class="h_tel"><?=do_shortcode('[tel]')?></p>
			</div>
			<div class="h_mob_soc">
				<? while (have_rows('soc', HOME)) { the_row();
					echo wrapACFlink(get_sub_field('link'), getACFpic(get_sub_field('img')));
				} ?>
			</div>
		</div>
	</div>
</header>