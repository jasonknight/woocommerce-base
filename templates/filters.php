<?php 
	foreach ( $filters as $filter ) {
		echo "add_filter( '$filter', array( \$this, 'filter_$filter') );";
	}
?>