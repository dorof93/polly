<?php
add_theme_support('post-thumbnails');
add_theme_support( 'title-tag' );
register_nav_menus();

define('HOME',8); // Главная страница
define('WISH',82); // страница Избранное
define('REG_PAGE',84); // страница Регистрации (кастомная)
define('LK_PAGE',86); // страница входа в личный кабинет (кастомная) 
define('SUBSCR',88); // страница e-mail рассылок 
define('REV_PAGE',94); // страница отзывы
define('SHOP',78); // страница магазина
define('PAY_PAGE',687); // страница оплаты
define('TMPL',get_bloginfo('template_directory'));

add_image_size('rev_inst',289,474,true);
add_image_size('showroom',488,328,true);

// Включение jquery
function enable_css_js(){
	wp_enqueue_script('jquery');
}
add_action( 'wp_enqueue_scripts', 'enable_css_js' );

// LOGO
function get_site_logo(){
	if (!empty(getACFpic(get_field('logo', HOME)))) {
		return getACFpic(get_field('logo', HOME));
	} else {
		return 'Polly';
	}
}

// Хлебные крошки
function breadcrumbs() {
	if ( function_exists('yoast_breadcrumb') ) {
		yoast_breadcrumb( '<div class="brcr" id="breadcrumbs">', '</div>' );
	}
}

function remove_post_title_wpseo_breadcrumb($link_info, $index, $crumbs)
{
	if ( (isset($link_info['id']) && $link_info['id'] != SHOP) || is_shop()) {
		return array();
	}

	return $link_info;
}
// add_filter('wpseo_breadcrumb_single_link_info', 'remove_post_title_wpseo_breadcrumb', 10, 3);

// Получение ACF картинки
function getACFpicUrl($field, $size_name='', $stub='') {
	if (is_array($field)) {
		if (!empty($size_name)) {
			$pic = $field['sizes'][$size_name];
		} else {
			$pic = $field['url'];
		}
	} else {
		$pic = $field;
	}
	if (empty($pic) && !empty($stub)) {
		$pic = $stub;
	}
	return $pic;
}
function getACFpic($field, $size_name='', $modal = false, $stub='', $class='', $alt='') {
	$pic = getACFpicUrl($field, $size_name, $stub);
	if (!empty($pic)) {
		$pic_info = pathinfo($pic);
		if ($pic_info["extension"] == 'svg') {
			return file_get_contents($pic);
		}
		switch ($modal) {
			case 'fancybox':
				return '<a href="'.getACFpicUrl($field).'" class="fancybox"><img class="'.$class.'" src="'.$pic.'" alt="'.$alt.'"></a>';
		}
		return '<img class="'.$class.'" src="'.$pic.'" alt="'.$alt.'">';
	}
	return false;
}
function getACFbgImg($field, $size_name='', $stub='') {
	$pic = getACFpicUrl($field, $size_name, $stub);
	if (!empty($pic)) {
		return 'background-image: url('.$pic.')';
	}
	return false;
}

// Обертывание в ACF ссылку
function wrapACFlink($field, $content, $class='') {
	if (!empty($field)) {
		return '<a href="'.$field.'" class="'.$class.'">'.$content.'</a>';
	} 
	return '<span class="'.$class.'">'.$content.'</span>';
}

// Получение css класса страницы
function get_page_class() {
	if (is_product()) {
		return 'pr_page';
	} elseif (is_product_taxonomy() || is_shop()) {
		return 'pr_cat';
	} elseif (is_cart()) {
		return 'cart_page';
	} elseif (is_account_page()) {
		$class = 'account_page';
		if (is_wc_endpoint_url('customer-logout')) {
			$class = $class.' page_logout';
		}
		return $class;
	} elseif (is_page() || is_single()) {
		global $post;
		if (!empty($post)) {
			$class = 'page_'.$post->post_name;
			if (is_order_received_page()) {
				$class = $class.' order_received';
			}
			return $class;
		}
	}
	return false;
}

// Формирование блоков над контентом страницы
function page_wrapper_start () { ?>
	<div class="content">
		<div class="wrapper"><?
			?><div class="page_content"><?
				if ( !is_cart() && !is_checkout() ) {
					get_sidebar();
					?><div class="page_side_content"><?
				} else {
					?><div class="page_full_content"><?
				}
				breadcrumbs();
				if (is_paged()) {
					?><div class="h1 title woocommerce_catalog_title"><?
						echo page_title();
					?></div><?
				} else {
					if ( is_product_taxonomy() || is_shop() ) {
						get_template_part( 'main_slider' );
					}
					page_h1_title();
				}
}

// Формирование блоков под контентом страницы
function page_wrapper_end () { 
					if ( is_product_taxonomy() || is_shop() ) { ?>
						<div class="cat_page_text">
							<? echo get_field( 'cat_page_text', get_true_acf_id() ); ?>
						</div>
					<? } ?>
				</div>
			</div>
		</div>
		<? if (is_product()) { ?>
			<div class="wrapper">
				<section class="pseudo_upsell products">
					<div class="h2">С этим товаром часто покупают:</div>
					<? echo do_shortcode('[products category="43" per_page="5" columns="5"]'); ?>
				</section>
				<div class="pay_delivery_prod_page">
					<div class="prod_pg_deliv">
						<p class="deliv_tit title"><?=get_field('deliv_tit', HOME)?></p>
						<div class="deliv_list deliv_prod_page_list">
							<? while (have_rows('deliv_list', HOME)) { the_row(); ?>
								<div class="deliv_list_item">
									<div class="deliv_list_item_inner">
										<div class="deliv_list_item_img"><?=getACFpic(get_sub_field('img'))?></div>
										<div class="deliv_list_item_tit"><?=get_sub_field('tit')?></div>
										<div class="deliv_list_item_text"><?=get_sub_field('text')?></div>
									</div>
								</div>
							<? } ?>
						</div>
					</div>
					<div class="prod_pg_pay">
						<p class="pay_tit title"><?=get_field('pay_tit', HOME)?></p>
						<div class="pay_list">
							<? while (have_rows('pay_list', HOME)) { the_row(); ?>
								<div class="pay_list_item">
									<div class="pay_list_item_tit"><?=get_sub_field('tit')?></div>
									<div class="pay_list_item_text"><?=get_sub_field('text')?></div>
								</div>
							<? } ?>
						</div>
						<div class="go_to_pay_pg">
							<a class="btn" href="<?=get_permalink(PAY_PAGE)?>"><?=get_field('go_to_pay', HOME)?></a>
						</div>
					</div>
				</div>
			</div>
		<? } ?>
	</div>
<? }

function get_true_acf_id() {
	$qo = get_queried_object();
	if ( isset($qo->term_id) ) {
		$acf_id = 'product_cat_'.$qo->term_id;
	} else if ( is_shop() ) {
		$acf_id = SHOP;
	} else {
		$acf_id = '';
	}
	return $acf_id;
}

// Заголовок страницы (h1)
function page_title ($id='') {
	if (is_product_taxonomy()) {
		return woocommerce_page_title(false);
	}
	if (is_shop()) {
		$id = SHOP;
	}
	$cat_id = '';
	if (!empty($id)) {
		$cat_id = 'category_'.$id;
	}
	$alt_title_cat = get_field('alt_title', $cat_id);
	$alt_title_post = get_field('alt_title', $id);
	$title_cat = single_cat_title('', false);
	$title_post = get_the_title($id);
	if (!empty($alt_title_post)) {
		return $alt_title_post;
	} elseif (!empty($alt_title_cat)) {
		return $alt_title_cat;
	} elseif (!empty($title_cat)) {
		return $title_cat;
	} elseif (!empty($title_post)) {
		return $title_post;
	} else {
		return false;
	}
}
function page_h1_title ($id='') {
	if (!get_field('hide_h1') && page_title($id) !== false) { ?>
		<h1 class="title"><?=page_title($id)?></h1>
	<? }
}
// Контент страницы
function page_content () {
	if ( !empty(get_the_content()) ) {
		if (is_cart() || is_checkout() || is_shop() || is_account_page()) { 
			the_content();
		} else { ?>
			<div class="text"><? the_content(); ?></div>
		<? }
	} else { ?>
		<p class="empty_page">Данная страница находится в разработке</p>
	<? }
}



// Стили для слайдера главной
function get_main_slider_style($color, $bg_color) {
	$style = '';
	if (!empty($color)) {
		$style .= 'color: '.$color.'; ';
	}
	if (!empty($bg_color)) {
		$style .= 'background-color: '.$bg_color.'; ';
	}
	if (!empty($style)) {
		$style = ' style="'.$style.'"';
	}
	return $style;
}
// Шорткоды контактов
function get_address($view = array()) { 
	$addr = get_field('addr', HOME);
	$html = '';
	if (!empty($addr)) {
		if ( !empty($view) && $view[0] == 'full' ) {
			$html .= '
			<div data-night_time="'.$addr['color_btn_night'].'" class="contact_block"'.get_bg_style($addr['color_btn']).'>
				<div class="contact_block_img">'.getACFpic($addr['img']).'</div>
				<div class="contact_block_text">'.$addr['text'].'</div>
			</div>
			';
		} else {
			$html .= $addr['text'];
		}
	}
	return $html;
}
add_shortcode('address','get_address');

function get_worktime($view = array()) { 
	$worktime = get_field('work_time', HOME);
	return $worktime;
	// $html = '';
	// if (!empty($worktime)) {
	// 	if ( !empty($view) && $view[0] == 'full' ) {
	// 		$html .= '
	// 		<div class="contact_block"'.get_bg_style($worktime['color_btn']).'>
	// 			<div class="contact_block_img">'.getACFpic($worktime['img']).'</div>
	// 			<div class="contact_block_text">'.$worktime['text'].'</div>
	// 		</div>
	// 		';
	// 	} else {
	// 		$html .= $worktime['text'];
	// 	}
	// }
	// return $html;
}
add_shortcode('worktime','get_worktime');

function get_email($view = array()) { 
	$email = get_field('email', HOME);
	$html = '';
	if (!empty($email)) {
		if ( !empty($view) && $view[0] == 'full' ) {
			$html .= '
			<div data-night_time="'.$email['color_btn_night'].'" class="contact_block"'.get_bg_style($email['color_btn']).'>
				<a href="mailto:'.$email['text'].'">
					<div class="contact_block_img">'.getACFpic($email['img']).'</div>
					<div class="contact_block_text">'.$email['text'].'</div>
				</a>
			</div>
			';
		} else {
			$html .= '<a href="mailto:'.$email['text'].'">'.$email['text'].'</a>';
		}
	}
	return $html;
}
add_shortcode('email','get_email');

function get_tel($view = array()) { 
	$tel = get_field('tel', HOME);
	$html = '';
	if (!empty($tel)) {
		if ( !empty($view) && $view[0] == 'full' ) {
			$html .= '
			<div data-night_time="'.$tel['color_btn_night'].'" class="contact_block"'.get_bg_style($tel['color_btn']).'>
				<a href="tel:'.get_tel_link($tel['text']).'">
					<div class="contact_block_img">'.getACFpic($tel['img']).'</div>
					<div class="contact_block_text">'.$tel['text'].'</div>
				</a>
			</div>
			';
		} else {
			$html .= '<a href="tel:'.get_tel_link($tel['text']).'">'.$tel['text'].'</a>';
		}
	}
	return $html;
}
add_shortcode('tel','get_tel');

// Шорткод логотипов платежных систем
function get_paysys ($view = array()) { 
	$html = '<img src="'.getACFpicUrl(get_field('f_pay', HOME)).'" alt="" class="paysys">';
	return $html;
}
add_shortcode('paysys','get_paysys');

// Очищение ссылки на номер телефона
function get_tel_link($text) {
	$tel_link = preg_replace('|[^0-9\+]|', '', $text );
	return $tel_link;
}

// Расчленение массива 

function get_rows_array($arr, $el_in_row) {
	$count = count($arr);
	for ($i = 0, $j = 0; $i < $count; $i++) {
		if ($i != 0 && $i % $el_in_row == 0) {
			$j++;
		}
		$row_arr[$j][] = $arr[$i];
	}
	return $row_arr;
}

// Расчленение массива (деление по нечетным элементам)

function get_odd_rows_array($arr, $el_in_row) {
	$count = count($arr);
	for ($i = 0, $j = 0; $i < $count; $i++) {
		if ($i != 0 && $i % $el_in_row == 1) {
			$j++;
		}
		$row_arr[$j][] = $arr[$i];
	}
	return $row_arr;
}

function get_bg_style($val) {
	$style = null;
	if (!empty($val)) {
		$style = ' style="background-color: '.$val.'"';
	}
	return $style;
}



// Проверка, что текущая страница - Подписка
function is_subscribe() {
	global $post;
	if (isset($post) && $post->ID == SUBSCR) {
		return true;
	}
	return false;
}


/**
 * Woocommerce функции
 */

// Поддержка темы

function mytheme_add_woocommerce_support () {
	add_theme_support ('woocommerce');
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

add_action ('after_setup_theme', 'mytheme_add_woocommerce_support');

// Удаление стандартных оберток

remove_action ('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action ('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action ('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action ('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
add_filter( 'woocommerce_show_page_title', '__return_false' );

// отключаем сортировку и показ количества товаров в категории
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

// Подключение новых оберток

add_action ('woocommerce_before_main_content', 'page_wrapper_start', 10);
add_action ('woocommerce_after_main_content', 'page_wrapper_end', 10);

//отключаем все стандартные css
add_filter( 'woocommerce_enqueue_styles', '__return_false' );






// Изменение навигации в личном кабинете
function custom_woo_acc_menu(){
	wp_nav_menu( array(
		'menu' => 'my-account',
		'container' => 'ul',
		'menu_class' => 'my-account_nav',
	));
}
add_action( 'woocommerce_account_navigation', 'custom_woo_acc_menu' );



// Изменение полей в оформлении заказа

function custom_override_checkout_fields( $fields ) {
	unset($fields['billing']['billing_last_name']);
	unset($fields['billing']['billing_company']);
	unset($fields['billing']['billing_country']);
	unset($fields['billing']['billing_state']);
	unset($fields['billing']['billing_address_2']);

	$fields['billing']['billing_country']['required'] = false;
	$fields['billing']['billing_address_1']['required'] = false;
	$fields['billing']['billing_city']['required'] = false;
	$fields['billing']['billing_postcode']['required'] = false;
	$fields['billing']['billing_city']['label'] = 'Город';
	$fields['billing']['billing_address_1']['placeholder'] = '';
	$fields['billing']['billing_phone']['priority'] = '20';
	$fields['order']['order_comments']['placeholder'] = '';

	return $fields;
}

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Сообщение об успешном оформлении заказа

function custom_thankyou_order_received_text(){
	return '
		<span class="succes_order_title">Ваш заказ успешно оформлен!</span>
		<span class="succes_order_text">Наш менеджер свяжется с вами и все уточнит, это займет всего пару минут</span>
	';
}
add_filter( 'woocommerce_thankyou_order_received_text', 'custom_thankyou_order_received_text');


// Для получения данных мини-корзины аяксом

function show_data_for_ajax_cart() {
	if (isset($_GET['cart_ajax']) && $_GET['cart_ajax'] == 1) {
		$arr = array(
			'qty' => WC()->cart->get_cart_contents_count(),
			'sum' => WC()->cart->get_cart_subtotal(),
		);
		wp_send_json( $arr );
	}
}
add_action( 'wp_loaded', 'show_data_for_ajax_cart' );



// Ссылка на Избранное в шапке
function show_wishlist_page_link() {
	$wish_products = get_wish_products();
	if (!empty($wish_products)) {
		$wish_products_qty = count($wish_products); ?>
		<div class="wish_qty"><?=$wish_products_qty?></div>
	<? }
}

// Кнопка добавления в избранное для карточки, страницы товара, корзины
function show_wishlist_button($product_id = 0) { 
	if (empty($product_id)) {
		global $post;
		if ( isset($post) && !empty($post->ID) ) {
			$product_id = $post->ID;
		} else {
			return false;
		}
	}
	$wish_btn_css_class = 'wish-add';
	$wish_products = get_wish_products();
	if ( in_array($product_id, $wish_products) ) {
		$wish_btn_css_class = 'wish-remove';
	}
	?>
	<span class="wish_btn <?=$wish_btn_css_class?>" data-product="<?=$product_id?>">
		<span class="wish_btn_text">Добавить в избранное</span>
		<span class="wish_icon">
			<svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M9 3.625C8.40444 2.1095 6.736 1 5 1C2.73956 1 1 2.6905 1 4.9375C1 8.02537 4.37156 10.4132 9 15C13.6284 10.4132 17 8.02537 17 4.9375C17 2.6905 15.2604 1 13 1C11.2622 1 9.59556 2.1095 9 3.625Z" stroke="#353535"/>
			</svg>
		</span>
	</span>
<? }
// Получение товаров в избранном
function get_wish_products() {
	$wish_products = array();
	if ( isset($_COOKIE['shop_wish']) && !empty($_COOKIE['shop_wish']) ) {
		$wish_products = explode(',', $_COOKIE['shop_wish']);
	}
	return $wish_products;
}



// Категории


// Вывод фильтра
function get_woo_filters($id_sidebar) {
	ob_start();
	dynamic_sidebar($id_sidebar);
	$sidebar = ob_get_contents();
	ob_end_clean();
	if (empty($sidebar)) {
		return false;
	}
	return $sidebar;
}

// Создание фильтра по цвету
function wrap_woo_filter_item($term_html, $term) {
	if ( $term->taxonomy == 'pa_czvet' ) {
		$style = '';
		$color = get_field('color', 'term_'.$term->term_id);
		$gradient = get_field('gradient', 'term_'.$term->term_id);
		if ( !empty($color) ) {
			$style = 'style="background-color: '.$color.'"';
		}
		if ( !empty($gradient) ) {
			$style = 'style="background-image: '.$gradient.'"';
		}
		return '<div class="filter_item" data-value="'.$term->slug.'" title="'.$term->name.'" '.$style.'></div>';
	}
	return $term_html;
}
add_filter( 'woocommerce_layered_nav_term_html', 'wrap_woo_filter_item', 10, 2 );

// Отключение кол-ва товаров в фильтрах
add_filter( 'woocommerce_layered_nav_count', '__return_false' );
add_filter( 'woocommerce_layered_nav_count_maybe_cache', '__return_false' );


// Получение ссылки каталога магазина без GET-параметров и пагинации
function get_clean_link () {
	$link = $_SERVER['REQUEST_URI'];
	$link = preg_replace('|/page/(.*)$|', '/', $link);
	$link = str_replace('?'.$_SERVER['QUERY_STRING'], '', $link);
	return $link;
}
// Подключение виджетов
function theme_widgets_init() {
	register_sidebar( array(
		'name'          => 'Sidebar',
		'id'            => 'sidebar',
		'description'   => 'Вставьте виджеты сюда',
		'before_title'  => '<div class="widget-title">',
		'after_title'   => '</div>',
	) );
	register_sidebar( array(
		'name'          => 'Shop Top',
		'id'            => 'shop_top',
		'description'   => 'Вставьте виджеты сюда',
		'before_title'  => '<div class="widget-title">',
		'after_title'   => '</div>',
	) );
}
add_action( 'widgets_init', 'theme_widgets_init' );



function check_current_term ($link) {
	$link = preg_replace('|^(.*?)'.$_SERVER['SERVER_NAME'].'(.*?)$|', '\2', $link);
	if ($link == $_SERVER['REQUEST_URI']) {
		return true;
	}
	return false;
}
function check_current_term_parent ($link) {
	$qo = get_queried_object();
	if (isset($qo->term_id) && !empty($qo->term_id)) {
		$parent_id = wp_get_term_taxonomy_parent_id( $qo->term_id, 'product_cat' );
		$parent_link = get_term_link($parent_id);
		if ($parent_link == $link) {
			return true;
		}
	}
	return false;
}
function get_curr_term_class($link) {
	$class = '';
	if (check_current_term($link)) {
		$class .= ' curr';
	}
	if (check_current_term_parent($link)) {
		$class .= ' parent_curr';
	}
	$class = preg_replace('|^ |', '', $class);
	return $class;
}
// Получение css классов для пунктов фиксированного моб. меню
function get_fix_menu_class($term_id) {
	$class = get_curr_term_class(get_term_link($term_id));
	if (!empty(get_woo_categories($term_id))) { 
		$class .= ' menu-item-has-children'; 
	}
	return $class;
}

function get_cat_thumb_svg($cat_id) {
	$svg = '';
	$url = wp_get_attachment_image_url(get_term_meta($cat_id, 'thumbnail_id', true));
	if (!empty($url)) {
		$svg = file_get_contents($url);
	}
	return $svg;
}

// Получение иерархии категорий

function get_woo_categories($id = 0) {
	$woo_categories = get_categories(array(
		'taxonomy'    => 'product_cat',
		// 'orderby'     => 'name', // по какому полю сортировать
		'hide_empty'  => false, // скрывать категории без товаров или нет
		'parent'      => $id, // id родительской категории
		'exclude'     => 15 // исключить категорию uncategorized
	));
	return (empty($woo_categories)) ? array() : $woo_categories;
}

// Пагинация в категориях

function custom_woo_pagination( $src ) {
	echo paginate_links(array(
		'prev_text' => '',
		'next_text' => '',
		'type' => 'list'
	));
}

remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
add_action( 'woocommerce_after_shop_loop', 'custom_woo_pagination', 10 );








// Карточка товара

// Замена заголовка h2 в карточке товара на div

function woo_card_prod_title() {
	echo '<div class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '"><span>' . get_the_title() . '</span></div>';
}
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action( 'woocommerce_shop_loop_item_title', 'woo_card_prod_title', 10 );


// Добавляем описание в карточку товара

function woo_card_description($product_id = 0) {
	if (empty($product_id)) {
		global $post;
		if ( !isset($post) || empty($post->ID) ) {
			return false;
		}
	} else {
		$post = get_post($product_id);
	}
	$text = $post->post_content;

	$text = preg_replace ('~\[[^\]]+\]~', '', $text ); //убираем шорткоды

	//удаляем все html символы
	$text = strip_tags( $text);

	// Обрезаем
	$text = wp_trim_words( $text, 8 ); //максимальное кол-во слов
	echo '<div class="product-description">'. $text .'</div>';
}
// add_action( 'woocommerce_after_shop_loop_item', 'woo_card_description', 7 );


// опускаем цену в карточке товаров
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price');
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 8 );

// вывод кнопки добавления в Избранное на карточку товара
add_action( 'woocommerce_after_shop_loop_item', 'show_wishlist_button', 7);


function custom_get_product_thumbnail(){
	if ( get_field('cat_img') ) {
		echo getACFpic(get_field('cat_img'), 'woocommerce_thumbnail', false, '', 'attachment-woocommerce_thumbnail');
	} else {
		echo woocommerce_get_product_thumbnail();
	}
}
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');
add_action( 'woocommerce_before_shop_loop_item_title', 'custom_get_product_thumbnail');


// Инф-ция о наличии

function woo_stock_product() {
	$product = wc_get_product( get_the_ID() );
	if ($product->is_in_stock()) {
		?><div class="pr_stock">В наличии</div><?
	} else {
		?><div class="pr_stock">Нет в наличии</div><?
	}
}

// Добавляем инф-цию о наличии на карточку товара
add_action( 'woocommerce_after_shop_loop_item', 'woo_stock_product', 6 );

// Изменение миниатюры карточки товара
function change_woo_thumbnail_size($size){

    $size['width'] = 208;
    $size['height'] = 192;
    $size['crop']   = 1;
    return $size;
}
add_filter('woocommerce_get_image_size_thumbnail','change_woo_thumbnail_size',1,10);


//namespace ZAddons\Frontend;
use ZAddons\Model\Group;

function add_zaddon_product_css_class( $wp_parse_args, $product ){
	
	$groups  = Group::getByProduct( $product, true );

	if (!empty($groups)){
		
		$wp_parse_args['class'] .= ' prod_with_opts';
	}

	return $wp_parse_args;
}

add_filter( 'woocommerce_loop_add_to_cart_args', 'add_zaddon_product_css_class', 10, 2 );


function custom_add_to_cart_text( $__, $that ){
	return 'Купить';
}
add_filter( 'woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text', 10, 2 );



// Страница товара

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
// add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_excerpt', 6 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);


// Изменение миниатюры страницы товара
function change_woo_single_size($size){

    $size['width'] = 598;
    $size['height'] = 450;
    $size['crop']   = 1;
    return $size;
}
add_filter('woocommerce_get_image_size_single','change_woo_single_size',1,10);

// Изменение миниатюры галереи страницы товара
function change_woo_gallery_thumbnail_size($size){

    $size['width'] = 186;
    $size['height'] = 180;
    $size['crop']   = 1;
    return $size;
}
add_filter('woocommerce_get_image_size_gallery_thumbnail','change_woo_gallery_thumbnail_size',1,10);

// вывод кнопки добавления в Избранное на стр. товара
add_action( 'woocommerce_before_add_to_cart_quantity', 'show_wishlist_button', 9);


// Изменение заголовка Похожих товаров
function change_related_title () {
	echo '<div class="h2">С этим товаром покупают:</div>';
	return false;
}
add_filter( 'woocommerce_product_related_products_heading', 'change_related_title' );

// количество выводимых похожих товаров
function woo_related_products_limit( $args ) {
	$args['posts_per_page'] = 5;
	$args['columns'] = 5;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'woo_related_products_limit' );
  
// Заголовок блока плагина Product Add-Ons for WooCommerce
function show_zaddon_title() {
	if (has_zaddon_options()) {
		?><p class="zaddon_title">Выберите комплектацию на Ваш выбор:</p><?
	}
}
add_action( 'woocommerce_before_add_to_cart_button', 'show_zaddon_title', 5 );

// Проверка на наличие у товара опций плагина Product Add-Ons for WooCommerce
function has_zaddon_options() {
	global $wpdb;
	global $product;
	$product_id = $product->get_id();
	$res = (int)$wpdb->get_results(esc_sql("SELECT `product_id` FROM `wp_za_products_to_groups` WHERE `product_id` = $product_id LIMIT 1"));
	return $res;

}


// Кастомизация табов на стр. товара

function woo_additional_tab () {
	global $product;
	if( $product->has_attributes() || $product->has_dimensions() || $product->has_weight() || !empty(get_field('char_text')) ) { 
		?><p class="pr_tab_title">Основные характеристики</p><?
		do_action( 'woocommerce_product_additional_information', $product );
		echo get_field('char_text');
	} else {
		echo 'У данного товара нет характеристик';
	}
}
function woo_descr_tab () {
	if (!empty(get_the_content())) {
		the_content();
	} else {
		echo 'У данного товара нет описания';
	}
	
}

function woo_customize_pr_tabs( $tabs ) {

	unset( $tabs['reviews'] );  	// Удалить таб "Отзывы"
	
	$tabs['description'] = array(
		'title' => 'Описание',
		'priority' 	=> 5,
		'callback' => 'woo_descr_tab',
	);
	$tabs['additional_information'] = array(
		'title' => 'Характеристики',
		'priority' 	=> 10,
		'callback' => 'woo_additional_tab',
	);

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woo_customize_pr_tabs', 98 );





// DP-Studio комплектация товара


// Получить категорию комплектующую для товара
function get_complect_category_id( $product_id ) {
	$compl_cat = get_post_meta( $product_id, 'complectation', true );
	return (int)$compl_cat;
}

// Получить все продукты комлектующей категории
function get_complect_cat_products( $category_id ) {
	$compls = [];
	if ( $category_id ) {

		$args = array(
		  'post_type' => 'product',
		  'numberposts' => -1,
		  'post_status' => 'publish',
		  'tax_query' => array(
		      array(
		          'taxonomy' => 'product_cat',
		          'field' => 'term_id',
		          'terms' => $category_id,
		          'operator' => 'IN',
		          )
		       ),
   		);

		$compls = wc_get_products( $args );
	}
	return $compls;
}

// Получить опции для комлектующей категории 
function get_zaddons_by_category_id ( $category_id ) {
	global $wpdb;
	$group_id = (int)$wpdb->get_results(esc_sql("SELECT `group_id` FROM `wp_za_categories_to_groups` WHERE `category_id` = $category_id LIMIT 1"))[0]->group_id;

	$types = $wpdb->get_results(esc_sql("SELECT * FROM `wp_za_types` WHERE `group_id` = $group_id"));

	foreach ($types as $type) {
		$addons[$type->id]['type'] = $type;
		$addons[$type->id]['prods'] = $wpdb->get_results(esc_sql("SELECT * FROM `wp_za_values` WHERE `type_id` = $type->id"));
	}

	return $addons;
}
function is_zaddon_active ($zaddon, $tag) {
	if ($zaddon->checked) {
		if ($tag == 'select') {
			return 'selected';
		} else {
			return 'checked';
		}
	}
	return false;
}

function get_zaddons_descr_by_category_id ( $category_id ) {
	global $wpdb;
	$group_id = (int)$wpdb->get_results(esc_sql("SELECT `group_id` FROM `wp_za_categories_to_groups` WHERE `category_id` = $category_id LIMIT 1"))[0]->group_id;
	$descr = (string)$wpdb->get_results(esc_sql("SELECT `description` FROM `wp_za_types` WHERE `group_id` = $group_id LIMIT 1"))[0]->description;
	return $descr;
}

// Получить опцию по id 
function get_zaddon_by_id ( $id ) {
	global $wpdb;
	$addon = $wpdb->get_results("SELECT * FROM `wp_za_values` WHERE `id` = $id LIMIT 1")[0];
	return $addon;
}

add_action( 'wp_ajax_complect_add_to_cart', 'complect_add_to_cart_callback' );
add_action( 'wp_ajax_nopriv_complect_add_to_cart', 'complect_add_to_cart_callback' );

// Добавление комплектуещего заказа в корзину
function complect_add_to_cart_callback() {
	global $woocommerce;
	$product_id = (int)$_POST['product_id'];
	$product = new WC_Product($product_id);
	$options = [];
	$add_options = [];
	parse_str($_POST['options'], $options);
	parse_str($_POST['add_options'], $add_options);

	foreach ( $options as $key => $opt ) {
		$id = end(explode('_', $key));
		$options[$id] = $opt;
		unset($options[$key]);
	}

	if ( empty($options) || empty($product_id) ) {
		status_header( 400 );
		return wp_die(json_encode(array('status' => 'failed', 'msg' => 'Bad Request')));
	}

	$cart_item_data = array();
	$amount = $product->get_price();

	foreach ( $options as $name => $price ) {
		$amount += $price;
	}

	if ( !$amount ) {
		status_header( 400 );
		return wp_die(json_encode(array('status' => 'failed', 'msg' => 'Any options not selected')));
	}

	$cart_item_data['price'] = $amount;
	$cart_item_data['options'] = $options;
	$cart_item_data['add_options'] = $add_options;

	$woocommerce->cart->add_to_cart( $product_id, 1, 0, 0, $cart_item_data );
	$woocommerce->cart->calculate_totals();
	$woocommerce->cart->set_session();
	$woocommerce->cart->maybe_set_cart_cookies();

	status_header( 200 );
	return wp_die(json_encode(array('status' => 'OK', 'msg' => 'Product added to cart')));
}

add_action( 'woocommerce_before_calculate_totals', 'woocommerce_custom_price_to_cart_item', 99 );

function woocommerce_custom_price_to_cart_item( $cart_object ) {  
    if( !WC()->session->__isset( "reload_checkout" )) {
        foreach ( $cart_object->cart_contents as $key => $value ) {
            if( isset( $value["price"] ) ) {
                $value['data']->set_price($value["price"]);
            }
        }  
    }  
}

add_action( 'woocommerce_checkout_create_order_line_item', 'save_cart_item_custom_meta_as_order_item_meta', 10, 4 );

function save_cart_item_custom_meta_as_order_item_meta( $item, $cart_item_key, $values, $order ) {
    $item->update_meta_data( 'options', $values['options'] );
    $item->update_meta_data( 'add_options', $values['add_options'] );
}


// Вывод на фронт


function custom_complect_pr_page() {
	global $product;
	$compl_cat = get_complect_category_id( $product->get_id() ); 
	if ( $compl_cat ) { 
		$term = get_term($compl_cat);
		?>
		<div class="complect_wrap">
			<form id="complect_form" class="complect_form">
				<div class="complect_pic">
					<?php $complect_products = get_complect_cat_products( $compl_cat ); ?>
					<input type="hidden" name="complect_product_id" value="<?= $complect_products[0]->get_id() ?>">
					<div class="complect_products_wrap">
						<?php foreach ( $complect_products as $key => $_product ) { ?>
							<div class="complect_product <?= $key === 0 ? 'show' : ''?>" data-id="<?= $_product->get_id() ?>" data-price="<?=$_product->get_price()?>">
								<p class="complect_title"><?=$term->name?> «<?= $_product->get_name() ?>»</p>
								<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $_product->get_id() ), 'shop_single' ); ?>
								<?php $fullimage = wp_get_attachment_image_src( get_post_thumbnail_id( $_product->get_id() ), 'full' ); ?>
								<? if ($image) { ?>
									<a href="<?=$fullimage[0]?>" class="fancybox"><img src="<?php  echo $image[0]; ?>" data-id="<?php echo $_product->get_id(); ?>" alt=""></a>
								<? } ?>
							</div>
						<?php } ?>
					</div>
				</div>

				<div class="complect_text">
					<div class="complect_checkboxes_wrap">
					<?php 
						$addons = get_zaddons_by_category_id( $compl_cat ); 
						foreach ($addons as $addon_info) { ?>
							<p class="complect_group_title"><?=$addon_info['type']->title?></p>
							<?php if ($addon_info['type']->type == 'checkbox') {
								foreach ($addon_info['prods'] as $key => $addon) { ?>
									<div class="complect_checkbox">
										<input type="checkbox" value="<?= $addon->price ?>" name="complect_<?= $addon->id ?>" id="complect_<?= $addon->id ?>" data-name="<?= $addon->title ?>" <?= is_zaddon_active($addon, 'checkbox')?>>
										<label for="complect_<?= $addon->id ?>">
											<p class="complect_checkbox_title"><?= $addon->title ?></p>
											<?php if ($addon->price) { ?>
												<p class="complect_checkbox_price"> +<?= $addon->price ?> <?= get_woocommerce_currency_symbol() ?></p> 
											<?php } ?>
											<p class="complect_checkbox_desc"><?= $addon->description ?></p> 
										</label>
									</div>
								<?php } ?>
							<?php } else if ($addon_info['type']->type == 'select') { ?>
								<div class="complect_select">
									<select name="complect_<?=$addon_info['prods'][0]->id?>">
										<?php foreach ($addon_info['prods'] as $key => $addon) { ?>
											<option value="<?= $addon->price ?>" data-option_id="complect_<?= $addon->id ?>" <?= is_zaddon_active($addon, 'select')?>>
												<?= $addon->title ?>
												<?php if ($addon->price) { ?>
													 +<?= $addon->price ?> <?= get_woocommerce_currency_symbol() ?>
												<?php } ?>
											</option>
										<?php } ?>
									</select>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="complect_short_descr">
						<?= get_zaddons_descr_by_category_id($compl_cat); ?>
					</div>
					<div class="complect_buy_block">
						<div class="complect_price_block">
							<p class="complect_price_title">Стоимость:</p>
							<p class="complect_price" id="complect_price">0</p>
							<p class="complect_currency"><?=get_woocommerce_currency_symbol()?></p>
						</div>
						<div class="complect_add_cart">
							<button class="btn" type="submit"><?= __('Add to cart', 'woocommerce' ) ?></button>
						</div>
					</div>
				</div>
			</form>
		</div>
	<?php }
}

add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
// add_action( 'woocommerce_after_single_product_summary', 'custom_complect_pr_page', 11 );


// Админка


add_filter( 'woocommerce_product_data_tabs', 'complectation_product_tab', 10, 1 );

function complectation_product_tab( $default_tabs ) {
	$default_tabs['custom_tab'] = array(
        'label'   =>  __( 'Комплектация', 'domain' ),
        'target'  =>  'complectation_product_tab',
        'priority' => 60,
        'class'   => array()
    );
    return $default_tabs;
}

add_action( 'woocommerce_product_data_panels', 'complectation_product_tab_content' );
function complectation_product_tab_content() {
	global $post;

	$all_categories = get_terms( 'product_cat');
	$complectation = get_post_meta( $post->ID, 'complectation', true );
	?>
	<div id="complectation_product_tab" class="panel woocommerce_options_panel">
		<div class="complectation_tab">		
			<?php 
				$select = array(
					'id' => 'complectation',
					'label' => __( 'Категория для комплектации', 'woocommerce' ) . ':',
					'options' => array( 0 => 'Выберите категорию: '),
					'value' => $complectation
				);
				foreach ($all_categories as $key => $category):					
					$select['options'][$category->term_id] = $category->name;
				endforeach;

				woocommerce_wp_select( $select );
			?>
		</div>
	</div>

	<style type="text/css">
		.complectation_tab label { margin-left: 0; width: auto; max-width: unset; display: block; float: none; }
		.complectation_tab { padding: 1rem; }
	</style>
	<?php
}

add_action( 'woocommerce_process_product_meta', 'woo_complectation_save' );
function woo_complectation_save( $post_id ){
    $complectation = $_POST['complectation'];
    if( !empty( $complectation ) )
        update_post_meta( $post_id, 'complectation', esc_attr( $complectation ) );
    else {
        update_post_meta( $post_id, 'complectation',  '' );
    }
}

// Переименования стандартных надписей

function change_rp_text($translated, $text, $domain)
{
	if ($domain == 'product-add-ons-woocommerce') {
		switch($text) {
			case 'Select options':
				$translated = esc_html__('Выбрать', $domain);
			break;
			case 'Total':
				$translated = esc_html__('Стоимость', $domain);
			break;
			case 'Additional':
				$translated = esc_html__('Цена дополнений', $domain);
			break;
		}
	}
	return $translated;
}
add_filter('gettext', 'change_rp_text', 10, 3);
add_filter('ngettext', 'change_rp_text', 10, 3);




// SEO
function set_pic_alt($html, $post_thumbnail_id){
	global $product;

	$name = htmlspecialchars($product->get_name());

	$new_html = str_replace('alt=""', 'alt="'.$name.' фото '.$post_thumbnail_id.'"', $html);

	return $new_html;
}
add_filter('woocommerce_single_product_image_thumbnail_html','set_pic_alt',2,10);


function set_pic_title( $array, $attachment_id, $image_size, $main_image ){
	global $product;

	$name = htmlspecialchars($product->get_name());

	$new_title = $name.' фото '.$attachment_id;
	$array['title'] = $new_title;

	return $array;
}
add_filter( 'woocommerce_gallery_image_html_attachment_image_params', 'set_pic_title', 10, 4 );


function set_lowercase_url(){

	if (!is_admin() && empty($_GET)) {

		if ( $_SERVER['REQUEST_URI'] != strtolower( $_SERVER['REQUEST_URI']) ) {
			header('Location: http://'.$_SERVER['HTTP_HOST'] . 
					strtolower($_SERVER['REQUEST_URI']), true, 301);
			exit();
		}

	}

}
add_action( 'wp_loaded', 'set_lowercase_url' );


// Отправка в телеграм
// function send_telegram() {
// 	$token = '2079158973:AAFuRpkvKKiamDqjPhtKTVE32z_-4txKRiQ';
// 	$chat_id = '-660609060';
// 	if (isset($_POST['tgram']) && $_POST['tgram'] == 1) {
// 		$txt = '';
// 		if ( isset($_POST['_wpcf7']) ) {
// 			if ($_POST['_wpcf7'] == 10 && !empty($_POST['your-name']) && !empty($_POST['your-phone']) ){

// 				$txt .= "ЗАКАЗ ЗВОНКА";
// 				$txt .= "%0A%0AИмя: ".$_POST['your-name'];
// 				$txt .= "%0AТелефон: ".$_POST['your-phone'];

// 			} elseif ($_POST['_wpcf7'] == 879 && !empty($_POST['your-phone']) ) {
				
// 				$txt .= "ЗАКАЗ ЗВОНКА (всплывающая форма)";
// 				// $txt .= "%0AТелефон: ".$_POST['your-phone'];
// 				// $txt .= "%0A".$_POST['radio-85'];
// 				$txt .= "%0A%0AИмя: ".$_POST['your-name'];
// 				$txt .= "%0AТелефон: ".$_POST['your-phone'];
// 			}
// 		} elseif ( isset($_POST['woocommerce-process-checkout-nonce']) && 
// 			!empty($_POST['billing_first_name']) && !empty($_POST['billing_phone']) ) {

// 			$prods = str_replace("\n", "%0A", $_POST['prods']);
// 			$prods = str_replace("\t", " ", $prods);
// 			$txt .= "НОВЫЙ ЗАКАЗ";
// 			$txt .= "%0A%0AИмя: ".$_POST['billing_first_name'];
// 			$txt .= "%0AТелефон: ".$_POST['billing_phone'];
// 			$txt .= "%0AE-mail: ".$_POST['billing_email'];
// 			$txt .= "%0AГород: ".$_POST['billing_city'];
// 			$txt .= "%0AАдрес: ".$_POST['billing_address_1'];
// 			$txt .= "%0AПочтовый индекс: ".$_POST['billing_postcode'];
// 			$txt .= "%0AПримечание к заказу: ".$_POST['order_comments'];
// 			$txt .= "%0AТовары: %0A".$prods;
// 			switch ($_POST['shipping_method'][0]) {
// 				case 'local_pickup:3' :
// 					$txt .= "%0AДоставка: Самовывоз";
// 				break;
// 				case 'local_pickup:6' :
// 					$txt .= "%0AДоставка по Воронежу";
// 				break;
// 				case 'local_pickup:7' :
// 					$txt .= "%0AДоставка: В другой город";
// 				break;
// 				default: 
// 					$txt .= "%0AДоставка: не нужна";
// 				break;
// 			}
// 			switch ($_POST['payment_method']) {
// 				case 'cod' :
// 					$txt .= "%0AОплата при получении";
// 				break;
// 				case 'rbspayment' :
// 					$txt .= "%0AОплата на сайте";
// 				break;
// 				default: 
// 					$txt .= "%0AОплата: неизвестно";
// 				break;
// 			}

// 		}
// 		if ( !empty($txt) ) {
// 			file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}");
// 		}
// 		// wp_send_json( $_FILES );
// 		die();
// 	}
// }
// add_action( 'wp_loaded', 'send_telegram' );







// remove_action('template_redirect', 'wpcf7_cleanup_upload_files', 20);

// function wpcf7_before_send_mail_hook() {
// 	// if (isset($_POST['tgram']) && $_POST['tgram'] == 1) {
// 		$upload_dir   = wp_upload_dir();
// 		$submission   = WPCF7_Submission::get_instance();
// 		$files        = $submission->uploaded_files();
// 		$copy_dir     = 'tgram_attach';
// 		$time_now     = time();
// 		foreach ($files as $file_key => $file) {
// 			$file = is_array( $file ) ? reset( $file ) : $file;
// 			$file_path = $copy_dir.'/'.$time_now.'-'.$file_key.'-'.basename($file);
// 			// if( empty($file) ) continue;
// 			copy($file, $upload_dir['basedir'].'/'.$file_path);
// 			$text = date("Y-m-d H:i:s")."\t".$file."\t".$upload_dir['basedir'].'/'.$file_path;
// 			$text .= "\n";
// 			file_put_contents(__DIR__."/notes.log", $text, FILE_APPEND);
// 			$upload_file = $upload_dir['baseurl'].'/'.$file_path;
// 		}
// 		$token = '2079158973:AAFuRpkvKKiamDqjPhtKTVE32z_-4txKRiQ';
// 		$chat_id = '-660609060';
// 		$txt = '';
// 		$txt .= "ЗАКАЗ ПРИНТА";
// 		$txt .= "%0A%0AИмя: ".$_POST['id:your-name-print'];
// 		$txt .= "%0AТелефон: ".$_POST['id:your-phone-print'];
// 		$txt .= "%0AФайл: ".$upload_file;
// 		if ( !empty($txt) ) {
// 			file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}");
// 		}
// 	// }
// }

// add_action( 'wpcf7_before_send_mail', 'wpcf7_before_send_mail_hook' );





















// Отправка в телеграм
function wpcf7_before_send_telegram() {
	$token = '2079158973:AAFuRpkvKKiamDqjPhtKTVE32z_-4txKRiQ'; // тестовый бот
	$chat_id = '-660609060'; // тестовый чат
 
	$txt = '';
	$txt .= "ЗАЯВКА С САЙТА";
	$txt .= "%0A";
	$fields = array(
		'Имя' => $_POST['your-name'],
		'Телефон' => $_POST['your-phone'],
	);
	foreach ($fields as $field_label => $field_val) {
		if ( !empty($field_val) ) {
			$txt .= "%0A$field_label: ".strip_tags(trim(urlencode($field_val)));
		}
	}
   
	if ( !empty($txt) ) {
   	    file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}");
	}
}
 
add_action( 'wpcf7_before_send_mail', 'wpcf7_before_send_telegram' );


// Отправка в телеграм
function wpcf7_before_send_telegram_multiple() {
	$token = '2079158973:AAFuRpkvKKiamDqjPhtKTVE32z_-4txKRiQ'; // тестовый бот
	$chat_id = '-660609060'; // тестовый чат
 
	$txt = '';

	switch ($_POST['_wpcf7']) { // Разделение по id контаткных форм (посмотреть id можно в админке)
		case 879:
			$txt .= "ЗАЯВКА НА ЗВОНОК";
			$fields = array(
				'Имя' => $_POST['your-name'],
				'Телефон' => $_POST['your-phone'],
			);
		break;
		case 1042:
			$txt .= "ЗАЯВКА НА ПОЧТУ";
			$fields = array(
				'Имя' => $_POST['your-name2'],
				'E-mail' => $_POST['your-email'],
			);
		break;
	}
	$txt .= "%0A";

	foreach ($fields as $field_label => $field_val) {
		if ( !empty($field_val) ) {
			$txt .= "%0A$field_label: ".strip_tags(trim(urlencode($field_val)));
		}
	}
   
	if ( !empty($txt) ) {
   	    file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}");
	}
}
 
add_action( 'wpcf7_before_send_mail', 'wpcf7_before_send_telegram_multiple' );


function wpcf7_before_send_telegram_file() {
	$token = ''; // тестовый бот
	$chat_id = ''; // тестовый чат
 
	// Формирование сообщения
	$txt = '';
	switch ($_POST['_wpcf7']) { // Разделение по id контаткных форм
		case 879:
			$txt .= "ЗАЯВКА НА ЗВОНОК";
			$fields = array(
				'Имя' => $_POST['your-name'],
				'Телефон' => $_POST['your-phone'],
			);
		break;
		case 1042:
			$txt .= "ЗАЯВКА НА ПОЧТУ";
			$fields = array(
				'Имя' => $_POST['id:your-name-print'],
				'E-mail' => $_POST['id:your-phone-print'],
			);
		break;
	}
	$txt .= "%0A";

	foreach ($fields as $field_label => $field_val) {
		if ( !empty($field_val) ) {
			$txt .= "%0A$field_label: ".strip_tags(trim(urlencode($field_val)));
		}
	}


	// Прикрепление файла (файлы будут храниться на сайте в папке /wp-content/uploads/tgram_attach/)
	$upload_dir   = wp_upload_dir();
	$submission   = WPCF7_Submission::get_instance();
	$files    	= $submission->uploaded_files();
	$copy_dir_name = 'tgram_attach';
	$copy_dir 	= $upload_dir['basedir'].'/'.$copy_dir_name;
	$time_now 	= time();
	if (!file_exists($copy_dir)) {
    	mkdir($copy_dir);
	}

	foreach ($files as $file_key => $file) {
    	$file = is_array( $file ) ? reset( $file ) : $file;
    	$file_path = $copy_dir.'/'.$time_now.'-'.$file_key.'-'.basename($file);
    	copy($file, $file_path);
		$upload_file = $upload_dir['baseurl'].'/'.$copy_dir_name.'/'.$time_now.'-'.$file_key.'-'.basename($file);
		
		if ( !empty($file) ) {
			$txt .= "%0AФайл: ".$upload_file;
		}
	}
 
 
	// Отправка в телеграм
	if ( !empty($txt) ) {
    	file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}");
	}
}
 
add_action( 'wpcf7_before_send_mail', 'wpcf7_before_send_telegram_file' );
 




// Отправка в телеграм заказа
function send_order_telegram($order_id) {
    if ( $order_id && ! get_post_meta( $order_id, '_thankyou_action_done', true ) ) {

		$token = '2079158973:AAFuRpkvKKiamDqjPhtKTVE32z_-4txKRiQ'; // тестовый бот
		$chat_id = '-660609060'; // тестовый чат

		$order = wc_get_order( $order_id );
		$order_data = $order->get_data();

		$txt = '';
		$txt .= "НОВЫЙ ЗАКАЗ";
		$txt .= "%0A";

		// Поля заказа (заменить на свои) см. также https://wp-kama.ru/plugin/woocommerce/function/WC_Order
		$fields = array(
			'Имя' => $order->get_billing_first_name(),
			'Фамилия' => $order->get_billing_last_name(),
			'Телефон' => $order->get_billing_phone(),
			'E-mail' => $order->get_billing_email(),
			'Адрес' => $order->get_billing_address_1(),
			'Примечание к заказу' => $order->get_customer_note(),
			'Метод оплаты' => $order->get_payment_method_title(),
		);
		foreach ($order_data['shipping_lines'] as $shipping_line) {
			$fields['Доставка'] = $shipping_line->get_name();
		}
		foreach ($fields as $field_label => $field_val) {
			if ( !empty($field_val) ) {
				$txt .= "%0A$field_label: ".strip_tags(trim(urlencode($field_val)));
			}
		}
		
		$txt .= "%0A%0AТовары: %0A";

		foreach ($order_data['line_items'] as $line_item) {
			$product = $line_item->get_product();
			$product_name = $product->get_name();
			$qty = $line_item->get_quantity();
			$total = $line_item->get_total();
			$txt .= '- '.strip_tags(trim(urlencode($product_name.' x '.$qty.' = '.$total)))." руб. %0A";
		}

		$txt .= "%0AИТОГО: ".strip_tags(trim(urlencode($order->get_total()))).' руб.';

		// Отправка в телеграм
		if ( !empty($txt) ) {
			file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}");
		}

		// Установка в БД флага отправки заказа
        $order->update_meta_data( '_thankyou_action_done', true );
        $order->save();
    }
}
add_action( 'woocommerce_thankyou', 'send_order_telegram', 10, 1 );


// function cfdb_fuck($form) {
// 	var_dump($form);
// }
// add_action( 'cfdb7_after_formdetails', 'cfdb_fuck' );
?>
