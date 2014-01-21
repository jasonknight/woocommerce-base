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
 * @author      Red(E) Tools LTD. 
 * @copyright   Copyright (c)  2014, Red(E) Tools LTD.. 
 * @license    	http://opensource.org/licenses/MIT 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once dirname( __FILE__ ) . '/class-wc-multi-metabase.php';

class WC_Multi_Meta extends WC_Multi_MetaBase {
	public $settings_sections;
	public $control_panel;
	public function __construct() {

		$this->name 		= 'woocommerce-multi-meta';
		$this->td 			= 'woocommerce-multi-meta';
		$this->prefix    	= 'woocommerce_multi_meta_';
		$this->menu_name   	= 'Woo Multi-Meta';
		$this->menu_title  	= 'Woo Multi-Meta';
		$this->author 		= 'Red(E) Tools LTD.';
		$this->plugin_url 	= 'http://thebigrede.net';
		$this->author_url 	= 'http://thebigrede.net';

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

		// no settings

		$this->control_panel = json_decode('		{
		    "tabs": {
		        "categories": {
		            "name": "Categories",
		            "content": "categories_tab_content"
		        },
		        "others": {
		            "name": "Others",
		            "content": "other_tab_content"
		        }
		    }
		}',true);

				
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
		global $_registered_pages;
		// This sets up your settings page.

		if ( ! add_menu_page( 
			"Red ( E ) Tools", 
			"Red ( E ) Tools", 
			'manage_options', 
			'rede-admin-menu', 
			array($this,'rede_splash_page'),
			'',
			50
		) ) {
			echo "Failed to add main menu";
		}
		if ( ! add_submenu_page(
			'rede-admin-menu',
			$this->menu_title,
			$this->menu_name,
			'manage_options',
			$this->prefix.'settings',
			array( $this, 'render_admin_options_page')
		) ) {
			echo "Failed to add submenu";
		}
		echo 'Done adding pages';
	}
	public function render_admin_options_page() {
		if ( $this->red_a($_REQUEST,'show_help',false) ) {
			echo $this->red_render_template("admin/admin_help_page.php", array());
		} else {
			echo $this->red_render_template("admin/control_panel.php", array());
		}
				
	}

	
	/*
	 * Describe this function here.
	 * @param
	 * @return 
	 */
	public function action_product_cat_add_form_fields( $arg1 ) {
		global $woocommerce, $user_ID;
		$metas = get_option($this->prefix.'category_metas',array());
		$new_columns = array();
		foreach ( $metas as $key=>$meta ) {
			
			?>
	        <div class="form-field">
	            <label for="<?php echo $this->prefix ?>term_meta[<?php echo $key ?>]"><?php _e($meta['name'], $this->td); ?></label>
	            <input type="text" name="<?php echo $this->prefix ?>term_meta[<?php echo $key ?>]" id="<?php echo $this->prefix ?>_term_meta_<?php echo $key ?>" value="" />
	            <p class='description'><?php _e($meta['description'],$this->td) ?></p>
	        </div>
	    	<?php
		}
		
	}
	
	/*
	 * Describe this function here.
	 * @param
	 * @return 
	 */
	public function action_product_cat_edit_form_fields( $term ) {
		global $woocommerce, $user_ID;
		$tid = $term->term_id;
		$metas = get_option($this->prefix.'category_metas',array());
		foreach ( $metas as $key=>$meta ) {
			$value = get_option($this->prefix."{$key}_{$tid}","");
			?>
	        <div class="form-field">
	            <label for="<?php echo $this->prefix ?>term_meta[<?php echo $key ?>]"><?php _e($meta['name'], $this->td); ?></label>
	            <input type="text" name="<?php echo $this->prefix ?>term_meta[<?php echo $key ?>]" id="<?php echo $this->prefix ?>_term_meta_<?php echo $key ?>" value="<?php echo $value ?>" />
	            <p class='description'><?php _e($meta['description'],$this->td) ?></p>
	        </div>
	    	<?php
		}
	}
	
	/*
	 * Describe this function here.
	 * @param
	 * @return 
	 */
	public function action_save_product_cat_meta( $term_id ) {
		global $woocommerce, $user_ID;
		if (isset( $_POST[$this->prefix  . 'term_meta'])) {
            $tid = $term_id;
            foreach ($_POST[$this->prefix  . 'term_meta'] as $key=>$value) {
            	update_option( $this->prefix."{$key}_{$tid}", $value );
            }
            
            
        }
		
	}

	
	/*
	 * Describe this function here.
	 * @param
	 * @return 
	 */
	 public function filter_manage_edit_product_cat_columns( $columns ) {
		global $woocommerce, $user_ID;
		$metas = get_option($this->prefix.'category_metas',array());
		$new_columns = array();
		foreach ( $metas as $key=>$meta ) {
			$new_columns[$key] = $meta['name'];
		}
		$columns = array_merge($columns,$new_columns);
        return $columns;
	}
	
	/*
	 * Describe this function here.
	 * @param
	 * @return 
	 */
	 public function filter_manage_product_cat_custom_column( $columns, $column, $id ) {
		global $woocommerce, $user_ID;
		$metas = get_option($this->prefix.'category_metas',array());
		$new_columns = array();
		foreach ( $metas as $key=>$meta ) {
			if ( $column == $key ) {
				$val = get_option($this->prefix."_category_{$key}_{$id}",false);
				if ( !$val ) {
					$val = __("Not Set",$this->td);
				}
				echo $val;
			}
		}
		return $columns;
	}
	public function categories_tab_content() {
		$metas = get_option($this->prefix.'category_metas',array());
		if ( $this->red_a($_GET,'category_meta',false) ) {
			$key = $this->red_snake_case($_POST['category_meta_name']);
			$metas[$key] = array('name' => $_POST['category_meta_name'], 'description' => $_POST['category_meta_description']);
			update_option($this->prefix.'category_metas',$metas);
		}
		return $this->red_render_template("admin/categories_tab_content.php",array('metas' => $metas));
	}
	public function other_tab_content() {
		echo __("Tab Content is not yet defined!",$this->td);
	}
}