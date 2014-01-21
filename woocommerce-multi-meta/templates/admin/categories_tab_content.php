<form method="POST" action="<?php echo $plugin->paths->url ?>/hack.php">
	<input type="hidden" name="page" value="woocommerce-multi-meta-settings" />
	<input type="hidden" name="redirect_to" value="/wp-admin/admin.php?page=woocommerce_multi_meta_settings">
	<input type="hidden" name="prefix" value="<?php echo $plugin->prefix ?>" />
	<input type="hidden" name="wp_path" value="<?php echo ABSPATH ?>">
	<input type="hidden" name="save_category_meta" value="true" />
	<table align="center">
		<tr>
			<td><?php echo __('Name',$plugin->td) ?></td>
			<td><input type="text" name="category_meta_name" /></td>
		</tr>
		<tr>
			<td valign="top"><?php echo __('Description',$plugin->td) ?></td>
			<td><textarea name="category_meta_description" ></textarea></td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" name="category_meta_submit" value="<?php echo __("Add",$plugin->td); ?>" />
			</td>
		</tr>
	</table>
</form>
<table align="center">
<tr><th><?php echo __("Metas Currently Defined",$plugin->td); ?></th></tr>
	<?php 
		foreach ( $metas as $key=>$m ) {
			?>
				<tr>
					<td>
						<?php echo $m['name'] ?>
					</td>
					<td>
						<?php echo $m['description'] ?>
					</td>
					<td><a href="<?php echo $plugin->paths->url ?>/hack.php?delete_category_meta&key=<?php echo $key ?>">Delete</a></td>
				</tr>
			<?php
		}
	 ?>
</table>