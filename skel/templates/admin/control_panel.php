<?php 
	if ( isset($plugin->control_panel['tabs']) ) {
		$tabs = $plugin->control_panel['tabs'];
		?><div><ul id="<?php echo $plugin->prefix; ?>_control_panel_tabs" class="red-tab-controls"><?php
		foreach ( $tabs as $id=>$desc ) {
			?>
				<li data-target="#<?php echo $id ?>">
					<?php echo $desc['name'] ?>
				</li>
			<?php
		}
		?></ul><div><div><?php
		foreach ( $tabs as $id=>$desc ) {
			?>
				<div id="<?php echo $id ?>" class="red-tab">
					<?php echo $plugin->{ $desc['content'] }(); ?>
				</div>
			<?php
		}
		?></div><?php
	}
?>
<script type="text/javascript">
	(function ($) {
		$('.red-tab-controls li').unbind('click').on('click',function () {
			var t = $(this).attr('data-target');
			$('.red-tab').hide();
			$(t).show();
			$('.red-tab-controls li').removeClass('active');
			$(this).addClass('active');
		});
		$('.red-tab-controls li:first').trigger("click");
	})(jQuery);
</script>