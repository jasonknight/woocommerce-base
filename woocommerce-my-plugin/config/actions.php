<?php
add_action( 'admin_menu', array( $this, 'action_admin_menu' ) );
add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_scripts' ) );
add_action( 'admin_enqueue_scripts', array( $this, 'action_admin_enqueue_scripts' ) );
add_action( 'wp_head', array( $this, 'red_wp_head' ) );
add_action( 'admin_head', array( $this, 'red_wp_admin_head' ) );
add_action( 'woocommerce_admin_attribute_types', array( $this, 'action_woocommerce_admin_attribute_types') );