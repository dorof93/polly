<?php get_header(); ?>
<div class="content">
    <div class="wrapper">
        <div class="main_top">
            <?php get_sidebar(); ?>
            <div class="main_top_content">
			    <? get_template_part( 'main_slider' ); ?>
                <? while (have_rows('prods_list')) { the_row(); 
                    $css_class = get_sub_field('css_class');
                ?>
                    <div class="main_shop_prods <?=$css_class?>">
                        <p class="main_shop_prods_tit<? if ($css_class) { echo ' '.$css_class.'_tit'; } ?>"><?=get_sub_field('tit')?></p>
                        <div class="main_shop_prods_list<? if ($css_class) { echo ' '.$css_class.'_list'; } ?>">
                            <? 
                            $set = get_sub_field('set');
                            switch ($set) {
                                case 'cat' :
                                    $cat_prods_id = intval(get_sub_field('cat_prods'));
                                    if ($cat_prods_id > 0) {
                                        $cat_prods = get_term($cat_prods_id);
                                        echo do_shortcode('[products category="'.$cat_prods->slug.'" per_page="6" columns="3"]');
                                    }
                                break;
                                case 'code' :
                                    echo do_shortcode(get_sub_field('code'));
                                break;
                                case 'manual' :
                                    echo do_shortcode('[products ids="'.implode(",", get_sub_field("prods")).'" orderby="id" per_page="6" columns="3"]');
                                break;
                            } ?>
                        </div>
                    </div>
                <? } ?>
                <div class="main_go_shop">
                    <a class="btn" href="<?=get_permalink(SHOP)?>"><?=get_field('go_to_cat')?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="main_pg_showroom">

        <div class="showroom_bg">
            <div class="wave_layer1">
            </div>
            <div class="wave_layer10">
                <svg width="162" height="68" viewBox="0 0 162 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M149.03 27.77C146.93 27.77 144.96 28.3 143.24 29.22C140.41 24.67 135.38 21.63 129.62 21.63C125.93 21.63 122.53 22.89 119.82 24.99C117.34 21.08 113.47 18.17 108.89 16.93C108.64 15.53 108.25 14.18 107.71 12.9C108.43 11.77 108.86 10.43 108.86 8.98997C108.86 4.96997 105.6 1.71997 101.59 1.71997C100.26 1.71997 99.0202 2.07996 97.9402 2.70996C95.4102 1.51996 92.6002 0.839966 89.6102 0.839966C82.6301 0.839966 76.5202 4.49997 73.0402 9.98997C68.8102 6.69997 63.5002 4.73997 57.7202 4.73997C47.3602 4.73997 38.4702 11.05 34.6802 20.04C34.0402 19.97 33.3902 19.94 32.7302 19.94C26.5902 19.94 21.1502 22.91 17.7402 27.48C16.2002 26.78 14.4902 26.38 12.6902 26.38C5.91016 26.38 0.410156 31.88 0.410156 38.66C0.410156 45.44 5.91016 50.94 12.6902 50.94C14.4902 50.94 16.2002 50.54 17.7402 49.84C21.1502 54.41 26.5902 57.38 32.7302 57.38C37.9402 57.38 42.6502 55.25 46.0402 51.82C49.5302 53.67 53.5002 54.72 57.7202 54.72C58.1302 54.72 58.5302 54.71 58.9402 54.69C63.2302 62.28 71.3701 67.4 80.7001 67.4C90.4401 67.4 98.8801 61.82 103 53.68C103.34 53.7 103.67 53.73 104.01 53.73C109.26 53.73 114 51.56 117.41 48.08C120.35 51.54 124.73 53.74 129.62 53.74C133.98 53.74 137.92 52 140.81 49.18C142.99 51.15 145.86 52.35 149.03 52.35C155.81 52.35 161.31 46.85 161.31 40.07C161.31 33.29 155.82 27.77 149.03 27.77Z" fill="white"/>
                </svg>
            </div>
            <div class="wave_layer11">
                <svg width="465" height="195" viewBox="0 0 465 195" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="5.47244" y="171.034" width="18.9347" height="20.323" transform="rotate(-30.3299 5.47244 171.034)" stroke="#9ACDC2" stroke-width="8"/>
                    <circle cx="443" cy="22" r="22" fill="#9ACDC2"/>
                    <path d="M250.364 58.1942C247.073 63.9463 238.319 66.986 232.566 63.6942C226.814 60.4024 227.865 49.1942 232.566 44.6093C235.858 38.8572 243.19 36.8627 248.942 40.1545C254.694 43.4463 253.656 52.4421 250.364 58.1942Z" fill="#ffc8b6"/>
                </svg>

            </div>
        </div>
        <div class="showroom_content" id="showroom">
                <p class="showroom_tit title"><?=get_field('showroom_tit')?></p>
                <div class="showroom_slider">
                    <? while (have_rows('showroom_slider')) { the_row(); ?>
                        <div class="showroom_slide gallery">
                            <?// wrapACFlink(get_sub_field('link'), getACFpic(get_sub_field('img'))) ?>
                            <?=getACFpic(get_sub_field('img'), 'showroom', 'fancybox')?>
                        </div>
                    <? } ?>
                </div>
                <div class="showroom_btn">
                    <span class="btn show_modal" data-window="modal_call_form"><?=get_field('showroom_btn')?></span>
                </div>
        </div>
    </div>
    <div class="main_pg_pay">
        <div class="wrapper">
			<p class="pay_tit title"><?=get_field('pay_tit')?></p>
            <div class="pay_list">
                <? while (have_rows('pay_list')) { the_row(); ?>
                    <div class="pay_list_item">
                        <div class="pay_list_item_tit"><?=get_sub_field('tit')?></div>
                        <div class="pay_list_item_text"><?=get_sub_field('text')?></div>
                    </div>
                <? } ?>
            </div>
            <div class="go_to_pay_pg">
                <a class="btn" href="<?=get_permalink(PAY_PAGE)?>"><?=get_field('go_to_pay')?></a>
            </div>
        </div>
    </div>
    <div class="main_pg_deliv">
        <div class="wrapper">
            <p class="deliv_tit title"><?=get_field('deliv_tit')?></p>
            <div class="deliv_list">
                <? while (have_rows('deliv_list')) { the_row(); ?>
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
    </div>
    <div class="rev_inst">
        <div class="wrapper">
            <div class="rev_inst_inner">
                <p class="rev_inst_tit title"><?=get_field('rev_inst_tit')?></p>
                <div class="rev_inst_list">
                    <? while (have_rows('rev_inst', REV_PAGE)) { the_row(); ?>
                        <div class="rev_inst_block gallery">
                            <?=getACFpic(get_sub_field('img'), 'rev_inst', 'fancybox')?>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="main_page_text">
        <div class="wrapper">
            <div class="main_page_text_inner">
                <?=get_field('main_page_text')?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>