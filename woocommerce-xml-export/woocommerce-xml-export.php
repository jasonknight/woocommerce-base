<?php
namespace REDE;
/*
Plugin Name: Woocommerce Xml Export 
Plugin URI: http://github.com/jasonknight/woocommerce-base 
Description: This is an example plugin 
Version: 1.0
Author: Jason Knight 
Author URI: http://red-e.eu
Copyright © 2014 Jason Knight
*/
require_once dirname( __FILE__ ) . "/classes/class-wc-xml-export.php";
$WC_Xml_Export_Instance = new WC_Xml_Export();