
<?php
	get_header();
	the_post();
    page_wrapper_start();
    ?>
    <div class="page_contacts_blocks">
        <p><strong><?=get_field('address_text', HOME)?></strong></p>
        <p><?=do_shortcode('[address]')?></p>

        <p><strong><?=get_field('data_text', HOME)?></strong></p>
        <?=get_field('data', HOME)?>

        <p><strong><?=get_field('phone_text', HOME)?></strong></p>
        <p><?=do_shortcode('[tel]')?></p>

        <p><strong><?=get_field('wa_text', HOME)?></strong></p>
        <p><a target="_blank" href="https://wa.me/<?=get_tel_link(get_field('wa', HOME))?>"><?=get_field('wa', HOME)?></a></p>

        <p><strong><?=get_field('work_time_text', HOME)?></strong></p>
        <p class="contact_worktime"><?=do_shortcode('[worktime]')?></p>
    </div>
    <div class="page_contacts_map">
        <?=get_field('map', HOME)?>
    </div>
<?php
	page_wrapper_end();
	get_footer();
?>