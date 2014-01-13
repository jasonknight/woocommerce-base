<div id="red_settings_page" class='red-settings-page red-settings-help-page'>
	<h1><?php echo __('User and Developer Help', $plugin->td); ?> </h1>
	<p class="red-small-text"><a href="<?php echo str_replace('&show_help=true','', $plugin->red_current_url()); ?>"><?php echo __('Back to settings? Click here') ?></a></p>
	<h2><?php echo __('End User Help', $plugin->td) ?></h2>
	<?php echo $plugin->red_render_template("help/user_help.md",$original_vars_in_scope); ?>
	<h2><?php echo __('Developer Help', $plugin->td) ?></h2>
	<?php echo $plugin->red_render_template("help/developer_help.md",$original_vars_in_scope); ?>
	<table width="100%" class="red-help-table">
		<tr>
			<td colspan="2">
				<h3><?php echo __('Pre-configured Paths', $plugin->td ) ?></h3>
				<p class="red-smallish-text"> access these paths through: <strong>$plugin->paths->{...}</strong> when inside a template, 
				or <strong>$this->paths->{...}</strong> when inside the class. Actually, <strong>$this</strong> is always present when
				rendering templates. But you should use $plugin because this variable is setup for you with special goodness.</p>
				<p class="red-smallish-text">You also have the magical variable <strong>$__VIEW__</strong> which will let you know what template you are in.
				The current value of <strong>$__VIEW__</strong> is <?php echo $__VIEW__ ?>. Templates can be rendered recursively.
				Views with the extension <strong>.md</strong> will be rendered as markdown. PHP code will be interpolated before markdown rendering. It is also
				possible to render <strong>.js</strong> and <strong>.css</strong> files this way. But you shouldn't.</p>
			</td>
		</tr>

		<?php 
			foreach ( $plugin->paths as $key=>$value) {
				?>
					<tr>
						<td  valign="top" align="right">
							<strong><?php echo $key ?></strong>
						</td>
						<td valign="top" class='red-normal-text'>
							<em><?php echo $value ?></em>
							<?php 
								if ( file_exists($plugin->paths->templates . 'help/_explain_path_' . $key . '.md')) {
									echo '<span class="red-smallish-text">' . $plugin->red_render_template('help/_explain_path_' . $key . '.md',$original_vars_in_scope) . '</span>';
								}
							 ?>
						</td>
					</tr>
				<?php
			}
		 ?>
	</table>
	<table width="100%" class="red-help-table">
		<tr>
			<td colspan="2" valign="top" >
				<h3><?php echo __('Plugin Settings', $plugin->td ) ?></h3>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" >
				<strong><?php echo __('Option Name is: ', $plugin->td ) ?></strong>
			</td>
			<td valign="top" >
				<em><?php echo $plugin->prefix.'settings' ?></em>  <strong class="red-smallish-text">use get_option('<?php echo $plugin->prefix.'settings' ?>');</strong>
			</td>
		</tr>
		<?php 
			foreach ( $plugin->settings as $key=>$value) {
				?>
					<tr>
						<td align="right" valign="top" >
							<strong><?php echo $key ?></strong>
						</td>
						<td valign="top" >
							<em><?php echo var_export($value,true); ?></em>
						</td>
					</tr>
				<?php
			}
		 ?>
	</table>
	<p class="red-small-text">This is the default admin help template, you can find it in
	<strong>templates/admin/admin_help_page.php</strong></p>
</div>