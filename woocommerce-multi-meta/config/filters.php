<?php
add_filter( 'manage_edit-product_cat_columns', array( $this, 'filter_manage_edit_product_cat_columns') );
add_filter( 'manage_product_cat_custom_column', array( $this, 'filter_manage_product_cat_custom_column'),0,3 );