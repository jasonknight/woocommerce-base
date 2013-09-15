<div class="red-settings-section">
	<?php 
		$fields = $plugin->settings_fields[$name];
	?>
	<h3><?php echo __( $fields['title'], $plugin->td ) ; ?></h3>
	<hr />
	<table id="red_settings_section_<?php echo $name ?>" class="red-settings-section-table" width="100%">
	<?php 
		foreach ( $fields['settings'] as $field ) {
			$field['value'] = $plugin->red_a( $plugin->settings, $field['name'],'');
			?>
				<tr>
					<td>
						<?php echo $plugin->red_label_tag($field); ?>
					</td>
					<td>
						<!-- This seting is setup in init_settings of the main class -->
						<?php 
							switch ($field['type']) {
								case 'string':
									echo $plugin->red_input_tag($field, $this->prefix.'settings');
									break;
								case 'boolean':
									$field['options'] = array( 
										array( 'value' => 'no',
												'content' => __('No',$plugin->td) 
										),
										array( 'value' => 'yes',
												'content' => __('Yes',$plugin->td) 
										), 
										
									);
									echo $plugin->red_select_tag($field,$this->prefix.'settings');
									break;
							}
						 ?>
					</td>
				</tr>
			<?php
		}
	?>
	</table>
</div>