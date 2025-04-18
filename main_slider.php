<? $acf_id = get_true_acf_id();
if ( empty( get_field( 'main_slider', get_true_acf_id() ) ) ) {
    return;
    // $acf_id = HOME;
}
?>
<div class="main_slider">
    <? while ( have_rows( 'main_slider', $acf_id ) ) { the_row(); ?>
        <div class="main_slide">
            <? if ( !empty(get_sub_field('link')) ) { ?> <a href="<?=get_sub_field('link')?>"> <? } ?>
                <div class="main_slide_inner"<?=get_main_slider_style(get_sub_field('text_color'), get_sub_field('bg_color'))?>>
                    <div class="main_slide_content">
                        <div class="main_slide_content_inner">
                            <div class="main_slide_tit title"><?=get_sub_field('tit')?></div>
                            <div class="main_slide_text"><?=get_sub_field('text')?></div>
                        </div>
                    </div>
                    <div class="main_slide_banner">
                        <?=getACFpic(get_sub_field('img'))?>
                        <?php if (get_sub_field('anim')) { ?>
                            <div class="main_top_svg">
                                <div class="main_top_svg_cont main_top_svg_cont1">
                                    <svg width="617" height="639" viewBox="0 0 617 639" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M474.953 543.298C617.23 418.292 657.966 216.901 565.939 93.4774C473.913 -29.9458 283.972 -28.6628 141.695 96.343C-0.582053 221.349 -41.3181 422.74 50.7084 546.163C142.735 669.587 332.676 668.304 474.953 543.298Z" stroke="#9ACDC2" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="main_top_svg_cont main_top_svg_cont2">
                                    <svg width="812" height="501" viewBox="0 0 812 501" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M355.638 494.051C576.904 521.533 778.817 434.712 806.624 300.132C834.431 165.551 677.601 34.1724 456.335 6.69007C235.069 -20.7922 33.1554 66.0283 5.34856 200.609C-22.4583 335.19 134.372 466.568 355.638 494.051Z" stroke="#F3D7D3" stroke-width="3"/>
                                    </svg>
                                </div>
                                <div class="main_top_svg_cont main_top_svg_dots">
                                    <svg width="110" height="111" viewBox="0 0 110 111" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="5" cy="5.92896" r="5" fill="#9ACDC2"/>
                                    <circle cx="25" cy="5.92896" r="5" fill="#9ACDC2"/>
                                    <circle cx="45" cy="5.92896" r="5" fill="#9ACDC2"/>
                                    <circle cx="65" cy="5.92896" r="5" fill="#9ACDC2"/>
                                    <circle cx="85" cy="5.92896" r="5" fill="#9ACDC2"/>
                                    <circle cx="105" cy="5.92896" r="5" fill="#9ACDC2"/>
                                    <circle cx="5" cy="25.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="25" cy="25.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="45" cy="25.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="65" cy="25.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="85" cy="25.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="105" cy="25.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="5" cy="45.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="25" cy="45.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="45" cy="45.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="65" cy="45.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="85" cy="45.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="105" cy="45.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="5" cy="65.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="25" cy="65.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="45" cy="65.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="65" cy="65.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="85" cy="65.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="105" cy="65.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="5" cy="85.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="25" cy="85.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="45" cy="85.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="65" cy="85.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="85" cy="85.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="105" cy="85.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="5" cy="105.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="25" cy="105.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="45" cy="105.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="65" cy="105.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="85" cy="105.929" r="5" fill="#9ACDC2"/>
                                    <circle cx="105" cy="105.929" r="5" fill="#9ACDC2"/>
                                    </svg>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <? if ( !empty(get_sub_field('link')) ) { ?> </a> <? } ?>
        </div>
    <? } ?>
</div>