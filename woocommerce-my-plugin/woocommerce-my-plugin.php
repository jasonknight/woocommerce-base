<?php
namespace REDE;
/*
Plugin Name: Woocommerce My Plugin 
Plugin URI: http://github.com/jasonknight/woocommerce-base 
Description: This is an example plugin 
Version: 1.0
Author: Jason Knight 
Author URI: http://red-e.eu
Copyright © 2014 Jason Knight
*/
require_once dirname( __FILE__ ) . "/classes/class-wc-my-plugin.php";
$WC_My_Plugin_Instance = new REDE\WC_My_Plugin();