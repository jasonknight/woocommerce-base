Welcome to **[<?php echo $plugin->red_titleize($plugin->name) ?>](<?php echo $plugin->plugin_url ?>)**, another awesome plugin by 
**<?php echo $plugin->author ?>**.

If you'd like to learn more, get additional help, or find more useful plugins, visit <<?php echo $plugin->author_url ?>>.

### <?php echo __('Settings'); ?>

Here is an explanation of each setting. 

<?php 
	foreach ($plugin->settings_fields as $section) {
		echo "#### " . __($section['title'], $plugin->td) . "\n";
		foreach ($section['settings'] as $field ) {
			if (! isset($field['description']) ) {
				$field['description'] = __('No Documentation',$plugin->td);
			}
			echo "- **{$field['label']}**: {$field['description']}\n";
		}
	}
 ?>