<?php
	get_header();
	the_post();
	page_wrapper_start();
?>
	<div class="auth_block">
		<p class="auth_enter"><a class="btn" href="<?=get_permalink( get_option('woocommerce_myaccount_page_id') )?>">Войдите</a></p>
		<p class="auth_text">Или</p>
		<p class="auth_reg"><a class="btn" href="<?=get_permalink(REG_PAGE)?>">Зарегистрируйтесь</a></p>
	</div>
<?php
	page_wrapper_end();
	get_footer();
?>