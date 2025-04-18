<div class="sidebar">
	<!-- <div class="sidebar_switch_mob"><span>Каталог</span></div> -->
	<div class="sidebar_content">
		<div class="sidebar_panel">
			<div class="sidebar_panel_close">
				<div class="close"></div>
			</div>
			<div class="sidebar_panel_state">
				<svg width="19" height="10" viewBox="0 0 19 10" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M8.31676 9.65865C8.70692 10.0471 9.33763 10.0471 9.72779 9.65865L17.7576 1.66485C18.1402 1.28402 18.1402 0.664743 17.7576 0.28391C17.3775 -0.0945073 16.763 -0.0945073 16.3829 0.28391L9.72779 6.90913C9.33763 7.29754 8.70692 7.29754 8.31676 6.90913L1.66165 0.283908C1.28152 -0.0945087 0.667027 -0.0945087 0.286904 0.283908C-0.0956489 0.664741 -0.095649 1.28402 0.286902 1.66485L8.31676 9.65865Z" fill="white"/>
				</svg>
			</div>
			<?php wp_nav_menu( array(
				'menu' => 'sidebar',
				'container' => 'ul',
			)); ?>
			<div class="sidebar_btn">
				<span class="btn show_modal" data-window="modal_print_form"><?=get_field('order_design_btn', HOME)?></span>
			</div>
		</div>
	</div>
</div>