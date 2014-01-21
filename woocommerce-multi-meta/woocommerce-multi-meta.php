<?php
namespace REDE;
/*
Plugin Name: Woocommerce Multi Meta 
Plugin URI: http://thebigrede.net 
Description: A plugin to add meta to various things that don't have it.. 
Version: 1.0
Author: Red(E) Tools LTD. 
Author URI: http://thebigrede.net
Copyright © 2014 Red(E) Tools LTD.
*/
require_once dirname( __FILE__ ) . "/classes/class-wc-multi-meta.php";
$WC_Multi_Meta_Instance = new WC_Multi_Meta();