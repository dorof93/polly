<?php
	get_header();
	the_post();
	page_wrapper_start();
?>
<div class="rev_list">
	<? $rev_list = get_rows_array(get_field('rev'), 2);
	foreach ($rev_list as $row) { ?>
		<div class="rev_row">
			<? foreach ($row as $item) { ?>
				<div class="rev_block">
					<div class="rev_block_img">
						<?=getACFpic($item['img_prod'])?>
						<div class="rev_block_svg_cont rev_block_svg_cont1">
							<svg width="563" height="582" viewBox="0 0 563 582" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M433.453 494.6C563.115 380.678 600.239 197.143 516.372 84.6629C432.505 -27.817 259.406 -26.6477 129.744 87.2745C0.081394 201.197 -37.0428 384.732 46.8242 497.212C130.691 609.692 303.791 608.522 433.453 494.6Z" stroke="#FFD15B" stroke-width="2"/>
							</svg>
						</div>
						<div class="rev_block_svg_cont rev_block_svg_cont2">
							<svg width="740" height="458" viewBox="0 0 740 458" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M324.115 451.07C525.763 476.115 709.774 396.992 735.115 274.344C760.457 151.696 617.532 31.9662 415.884 6.92061C214.237 -18.125 30.2258 60.9977 4.88449 183.646C-20.4569 306.294 122.468 426.024 324.115 451.07Z" stroke="#b84a62" stroke-width="2"/>
							</svg>
						</div>
					</div>
					<div class="rev_block_content">
						<div class="rev_block_text">
							<?=$item['text']?>
						</div>
						<div class="rev_block_info">
							<div class="rev_block_author">
								<?=$item['author']?>
							</div>
							<div class="rev_block_prod">
								<? $prod = new WC_Product($item['prod']); ?>
								<div class="rev_block_prod_name"><?=$prod->get_name()?></div>
                                <? if ($prod->get_price()) { ?>
									<div class="rev_block_prod_buy">
										<span class="rev_block_prod_price"><?=$prod->get_price()?> <?=get_woocommerce_currency_symbol()?></span>
										<a href="<?=get_permalink($item['prod'])?>" class="btn">Купить</a>
									</div>
								<? } ?>
							</div>
						</div>
					</div>
				</div>
			<? } ?>
		</div>
	<? } ?>
</div>
<div class="rev_inst">
	<p class="rev_inst_tit title"><?=get_field('rev_inst_tit')?></p>
	<div class="rev_inst_list">
		<? while (have_rows('rev_inst')) { the_row(); ?>
			<div class="rev_inst_block gallery">
				<?=getACFpic(get_sub_field('img'), 'rev_inst', 'fancybox')?>
			</div>
		<? } ?>
	</div>
</div>
<?php
	page_wrapper_end();
	get_footer();
?>