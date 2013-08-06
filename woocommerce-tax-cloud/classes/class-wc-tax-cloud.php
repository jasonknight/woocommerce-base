<?php
/**
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author      IgniteWoo 
 * @copyright   Copyright (c)  2013, IgniteWoo. 
 * @license    	http://opensource.org/licenses/MIT 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once dirname( __FILE__ ) . '/class-wc-tax-cloud-base.php';

class WC_Tax_Cloud extends WC_Tax_Cloud_Base {
	public $settings_sections;
	public function __construct() {

		$this->name 		= 'woocommerce-tax-cloud';
		$this->td 			= 'woocommerce-tax-cloud';
		$this->prefix    	= 'woocommerce-tax-cloud-';
		$this->menu_name   	= 'TaxCloud';
		$this->menu_title  	= 'TaxCloud Settings';
		
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
	public function action_admin_menu() {

		// This sets up your settings page.

		add_submenu_page(
			'woocommerce',
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
		echo $this->red_render_template("admin/admin_settings_page.php", array());
	}
	
	/*
	 * Describe this function here.
	 * @param
	 * @return 
	 */
	 public function filter_woocommerce_calc_tax( $arg1 ) {
		
		die( __('You will need to fill out the code for ', $this->td ) . "filter_woocommerce_calc_tax" );

		return $arg1;
	}
}