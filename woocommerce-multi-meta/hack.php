<?php
/*
 * This is a hack to get around WP Stupidity(TM) where registered pages
 * are not always actually registered. Go Figure.
 */

try {

	require_once(dirname( __FILE__ ) . '/classes/class-wc-multi-metabase.php');
	print_r($_REQUEST);
	if ( REDE\WC_Multi_MetaBase::red_a($_REQUEST,'wp_path',false) ) {
		require( $_REQUEST['wp_path'] . '/wp-load.php' );
	} 

	$prefix = REDE\WC_Multi_MetaBase::red_a($_REQUEST,'prefix',false);
	$metas = get_option($prefix.'category_metas',array());
	if ( REDE\WC_Multi_MetaBase::red_a($_REQUEST,'save_category_meta',false) ) {
		$key = REDE\WC_Multi_MetaBase::red_snake_case($_REQUEST['category_meta_name']);
		$metas[$key] = array('name' => $_REQUEST['category_meta_name'], 'description' => $_REQUEST['category_meta_description']);
		update_option($prefix.'category_metas',$metas);
	}
	if ( REDE\WC_Multi_MetaBase::red_a($_REQUEST,'delete_category_meta',false) ) {
		$key = $_REQUEST['key'];
		$new_metas = array();
		foreach ($metas as $k=>$m) {
			if ( $k == $key ) {
				continue;
			}
			$new_metas[$k] = $m;
		}
		update_option($prefix.'category_metas',$new_metas);
	}
	if ( REDE\WC_Multi_MetaBase::red_a($_REQUEST,'redirect_to',false) ) {
		header("Location: " . $_REQUEST['redirect_to'] );
	}

} catch ( Exception $e ) {
	print_r($e);
}

