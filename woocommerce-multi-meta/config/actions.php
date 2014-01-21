<?php
add_action( 'admin_menu', array( $this, 'action_admin_menu' ) );
add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_scripts' ) );
add_action( 'admin_enqueue_scripts', array( $this, 'action_admin_enqueue_scripts' ) );
add_action( 'wp_head', array( $this, 'red_wp_head' ) );
add_action( 'admin_head', array( $this, 'red_wp_admin_head' ) );
add_action( 'product_cat_add_form_fields', array( $this, 'action_product_cat_add_form_fields'),10,2 );
add_action( 'product_cat_edit_form_fields', array( $this, 'action_product_cat_edit_form_fields'),10,2 );
add_action( 'edited_product_cat',array($this,'action_save_product_cat_meta'),10,2);
add_action( 'create_product_cat',array($this,'action_save_product_cat_meta'),10,2);