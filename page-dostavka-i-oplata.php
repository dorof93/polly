<?php
	get_header();
	the_post();
	page_wrapper_start();
?>
	<div class="deliv_list deliv_page_list">
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
<?php
	page_wrapper_end();
	get_footer();
?>