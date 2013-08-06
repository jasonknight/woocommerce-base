<div id="red_settings_page" class='red-settings-page'>
	<form method="post" id="red_settings_form" enctype="multipart/form-data">
	<input type="hidden" name="red_option_form_submitted" value="yes" />
	<?php echo $plugin->red_hidden_form_fields($plugin->prefix.'settings') ?>
	<h2><?php echo $plugin->menu_title; ?> </h2>
	<?php 
		foreach ( $plugin->settings_sections as $section ) {
			$plugin->red_do_settings_section( $section );
		}
	 ?>
	 <div class='red-submit-button'>
		<input type="submit" name="red-submit" value="<?php echo __('Save Settings', $plugin->td) ?>" />
	</div>
	</form>
	<p class="red-small-text">This is the default admin settings template, you can find it in
	<strong>templates/admin/admin_settings_page.php</strong></p>
</div>