<?php
	get_header();
	the_post();
	page_wrapper_start();
?>
<div class="reg_container" data-reg_page="<?=wp_registration_url()?>">
	<img class="reg_loading" src="<?=TMPL?>/images/loading_spinner.gif" alt="">
</div>
<div class="reg_answer"></div>
<?php
	page_wrapper_end();
	get_footer();
?>