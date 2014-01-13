<?php
namespace REDE;
/**
 * This Software is Copyrighted Red(E) Tools LTD, and protected under U.S. and International
 * Copyright law. Purchase of this software entitles you to run it on one,
 * and only one installation of Word Press.
 * 
 * You may not alter, enhance or tweak any part of this software without
 * written permission from the copyright holder. All rights are reserved,
 * including but not limited to the rights of distribution, and the 
 * right to revoke this license if this software is misused in anyway.
 * 
 *
 * @author      Jason Knight 
 * @copyright   Copyright (c)  2014, Jason Knight. 
 * @license    	http://opensource.org/licenses/MIT 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once dirname( __FILE__ ) . '/class-wc-my-pluginbase.php';

class WC_My_Plugin extends WC_My_PluginBase {
	public $settings_sections;
	public function __construct() {

		$this->name 		= 'woocommerce-my-plugin';
		$this->td 			= 'woocommerce-my-plugin';
		$this->prefix    	= 'woocommerce-my-plugin-';
		$this->menu_name   	= 'My Plugin';
		$this->menu_title  	= 'My Plugin Settings';
		$this->author 		= 'Jason Knight';
		$this->plugin_url 	= 'http://github.com/jasonknight/woocommerce-base';
		$this->author_url 	= 'http://red-e.eu';
		add_action( 'init', array( $this, 'init' ) );


	}
	public function init() {
		
		// Calls the init function of the base clase
		parent::init(); // causes basic path setup to load

		// Your actions and filters are specified in these two files.
		include dirname( __FILE__ ) . "/../config/actions.php";
		include dirname( __FILE__ ) . "/../config/filters.php";

		

		$this->init_settings();
		
		
	}
	public function init_settings() {
		$this->settings_sections = array(
			'main' 
		);
				$this->settings_fields = array (
			  'main' => 
			  array (
			    'title' => 'Main Settings',
			    'settings' => 
			    array (
			      'one' => 
			      array (
			        'name' => 'api_id',
			        'label' => 'API ID',
			        'type' => 'string',
			        'classes' => 'red-wide',
			        'id' => 'apid_id',
			      ),
			      'two' => 
			      array (
			        'name' => 'api_key',
			        'label' => 'API Key',
			        'type' => 'string',
			        'classes' => 'red-wide',
			        'id' => 'apikey_id',
			      ),
			      'three' => 
			      array (
			        'name' => 'default_tic',
			        'label' => 'Default TIC',
			        'type' => 'string',
			        'id' => 'tic_id',
			      ),
			    ),
			  ),
		);
	}
	public function action_enqueue_scripts() {

		// Enqueue Scripts here.

	}
	public function action_admin_enqueue_scripts() {
		$this->red_register_style('style.less');
		// This will enqueue all the scripts
		$this->red_script( 
			array(
				'jquery'
			) 
		);

	}
	public function rede_splash_page() {
		echo '<iframe src="http://thebigrede.net/plugin_splash.php" width="100%" height="100%"></iframe>';
	}
	public function action_admin_menu() {

		// This sets up your settings page.
		if ( !defined('REDE_ADMIN_MENU') ) {
			add_menu_page( 
				"Red ( E ) Tools", 
				"Red ( E ) Tools", 
				'manage_options', 
				'rede-admin-menu', 
				array($this,'rede_splash_page'),
				'',
				50
			);
			define('REDE_ADMIN_MENU',true);
		}
		
		add_submenu_page(
			'rede-admin-menu',
			$this->menu_title,
			$this->menu_name,
			'manage_options',
			$this->prefix.'settings',
			array( $this, 'render_admin_options_page')
		);

	}
	public function render_admin_options_page() {
		/* Save posted options? */
		if ( $this->red_a($_POST, $this->prefix.'settings',false) ) { // will be true if set :)
			update_option($this->prefix.'settings', $_POST[$this->prefix.'settings'] );
			$this->settings = $_POST[ $this->prefix.'settings' ];
		}
		if ( $this->red_a($_REQUEST,'show_help',false) ) {
			echo $this->red_render_template("admin/admin_help_page.php", array());
		} else {
			echo $this->red_render_template("admin/admin_settings_page.php", array());
		}
		
	}
	
	/*
	 * Describe this function here.
	 * @param
	 * @return 
	 */
	public function action_woocommerce_admin_attribute_types( $arg1 ) {
		global $woocommerce, $user_ID;
		if ( !isset($this->settings['enabled']) || $this->settings['enabled'] != 'yes') 
	 		return;
		
		die( __('You will need to fill out the code for ', $this->td ) . "action_woocommerce_admin_attribute_types" );

		return $arg1;
	}
	
	/*
	 * Describe this function here.
	 * @param
	 * @return 
	 */
	 public function filter_woocommerce_admin_order_actions( $arg1 ) {
		global $woocommerce, $user_ID;
		if ( !isset($this->settings['enabled']) || $this->settings['enabled'] != 'yes') 
	 		return;
		die( __('You will need to fill out the code for ', $this->td ) . "filter_woocommerce_admin_order_actions" );

		return $arg1;
	}
}