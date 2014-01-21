<?php 
	foreach ( $filters as $filter ) {
		$filter_for_func = str_replace('-','_',$filter);
		echo "add_filter( '$filter', array( \$this, 'filter_$filter_for_func') );\n";
	}
?>