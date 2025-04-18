<?php
	get_header();
	the_post();
	page_wrapper_start();
	
	$wish_products = get_wish_products();
	if (empty($wish_products)) { ?>
		<p class="empty_page">У Вас нет избранных товаров</p>
	<? } else { ?>
		<ul class="products wish_list_items">
			<?php foreach ($wish_products as $product_id) {
				$product = wc_get_product($product_id);
				$post = get_post($product_id);
				do_action( 'woocommerce_shop_loop' );
				wc_get_template_part( 'content', 'product' );
			}
		?>
		</ul>
<?php }
	page_wrapper_end();
	get_footer();
?>