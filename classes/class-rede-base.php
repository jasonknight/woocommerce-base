<?php 
/**
 * WooCommerce Plugin Base Class
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
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
 *
 * @package     REDEBase
 * @author      Jason Knight
 * @copyright   Copyright (c) 2013, Red(E) Tools Ltd.
 * @license    	http://opensource.org/licenses/MIT
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Red(E) Tools WooCommerce Plugin Base Clase
 *
 * A collection of generic functions.
 *
 * @since 1.0
 */

class REDE_Base {

	/** @var string, the name of this plugin, to be set in the inheriting class */
	public $name;
	/** @var string, the text domain of this plugin */
	public $td; 
	/** @var string, a prefix to use for settings, form fields, and javascript functions */
	public $prefix;
	/** 
	* @var array, notices to be displayed. These are saved in the DB across requests until they
	* are echoed to the user. 
	*/
	public $notices;

	/**
	 * Get the admin/notification email from woocom, or WP.
	 *
	 * @since 1.0
	 * @return string, the admin email
	 */
	public function red_get_admin_email() {
		$e = get_option('woocommerce_new_order_recipient');
		if ( ! $e ) {
			$e = get_option('admin_email');
		}
		return $e;
	}
	/**
	 * Send a notification to admin
	 *
	 * @since 1.0
	 * @param string subject of the email
	 * @param string body, possibly rendered template
	 */
	public function red_notify_admin($sub,$body) {
		$to = $this->red_get_admin_email();
		if ($to) {
			$this->red_add_notice("Sending email to $to");
			wp_mail($to,$sub,$body);
		}
	}

	/**
	 * Add or update post meta.
	 *
	 * @since 1.0
	 * @param int post_id
	 * @param string key name
	 * @param mixed value
	 */
	public function red_add_post_meta($post_id, $meta_key, $meta_value) {
		if ( ! add_post_meta( $post_id, $meta_key, $meta_value, true ) ) {
			update_post_meta( $post_id, $meta_key, $meta_value );
		}
	}
	
	/**
	 * Add or update user meta.
	 *
	 * @since 1.0
	 * @param int post_id
	 * @param string key name
	 * @param mixed value
	 */
	public function red_add_user_meta($user_id, $meta_key, $meta_value) {
		if ( ! add_user_meta( $user_id, $meta_key, $meta_value, true ) ) {
			update_user_meta( $user_id, $meta_key, $meta_value );
		}
	}
	/**
	 * Are there any notices to print?
	 *
	 * @since 1.0
	 * @return bool
	 */
	public function red_notices_any() {
		$notices = get_option($this->prefix.'notices');
		if ( count( $notices ) > 0 ) {
			return true;
		}
		return false;
	}
	/**
	 * Add a notice to the database
	 *
	 * @since 1.0
	 * @param \WC_Order instance
	 * @return \WC_Authorize_Net_CIM_API_Response instance
	 */
	public function red_add_notice($text) {
		if (! $this->notices ) {
			$this->notices = array();
		}
		$this->notices[] = $text;
		// Notice here we use prefix, so several  plugins
		// can maintain separate notices.
		update_option($this->prefix.'notices',$this->notices);
	}
	/**
	 * Echo an unordered list of notices
	 *
	 * @since 1.0
	 */
	public function red_echo_notices() {
		$notices = get_option($this->prefix.'notices');
			
		echo "<ul class='ignite-woo-notices'>\n";
		foreach ( $notices as $notice ) {
			if ( strpos( strtolower($notice) ,"error" ) !== false) {
				$c = "class='ign-error'";
			} else {
				$c = '';
			}
			?>
			<li <?php echo $c ?>><?php echo $notice ?></li>
			<?php
		}
		echo "</ul>\n";
		update_option($this->prefix.'notices',array());
	}
	/**
	 * Simple mustache like text templates. vars are like {myVar} and map to keys in the
	 * vars argument.
	 *
	 * @since 1.0
	 * @param string template text
	 * @param array of key=>value pairs
	 * @return \WC_Authorize_Net_CIM_API_Response instance
	 */
	public function red_compile_template($text,$vars) {
		foreach ($vars as $key=>$value) {
			$text = str_replace('{'. $key . '}',$value,$text);
		}
		return $text;
	}
	/**
	 * Get something from $_REQUEST, tests for key set first.
	 *
	 * @since 1.0
	 * @param string key
	 * @param mixed default value if not found
	 * @return mixed
	 */
	public function red_req($key,$default=null) {
		if ( isset( $_REQUEST[$key] ) ) {
			return $_REQUEST[$key];
		}
		return $default;
	}
	/**
	 * Get something from $_POST, tests for key set first.
	 *
	 * @since 1.0
	 * @param string key
	 * @param mixed default value if not found
	 * @return mixed
	 */
	public function red_post($key,$default=null) {
		if ( isset( $_POST[$key] ) ) {
			return $_POST[$key];
		}
		return $default;
	}
	/**
	 * Get something from $_GET, tests for key set first.
	 *
	 * @since 1.0
	 * @param string key
	 * @param mixed default value if not found
	 * @return mixed
	 */
	public function red_get($key,$default=null) {
		if ( isset( $_GET[$key] ) ) {
			return $_GET[$key];
		}
		return $default;
	}
	/**
	 * Get something from $array, tests for key set first.
	 *
	 * @since 1.0
	 * @param string key
	 * @param mixed default value if not found
	 * @return mixed
	 */
	public function red_a($arr, $key, $default=null) {
		if ( isset( $arr[$key] ) ) {
			return $arr[$key];
		}
		return $default;
	}
	/**
	 * sanitize a string into a slug
	 *
	 * @since 1.0
	 * @param string text to sanitize
	 * @return string slugified text
	 */
	public function red_create_slug($text) {
		$text = sanitize_title($text);
		return $text;
	}
	/**
	 * When passed a name, like 'something_else' will return class-something-else.php
	 *
	 * @since 1.0
	 * @param string name
	 * @return string file name
	 */
	public function red_class_fileify($name) {
		$name = str_replace('_','-',$name);
		return strtolower("class-" . str_replace('woocommerce','wc',$this->name) . "-$name.php");
	}
  /**
	 * Take a file name and guess what the class name should be.
	 * If you class is: class-wc-my-class.php then it will return
	 * WC_My_Class
	 *
	 * @since 1.0
	 * @param string file basename
	 * @return string guessed class name
	 */
	public function red_classify($file_name) {
		$name = $file_name;
		$name = str_replace('class-','',$name);
		$name = str_replace('wc-','WC-',$name);
		$name = str_replace('.php','',$name);
		$name = ucwords( str_replace( '-',' ', $name ) );
		$name = str_replace(' ','_',$name);
		return $name;
	}
	/**
	 * Find a plugin template, searches in the current theme
	 * first to see if it has been overidden.
	 *
	 * @since 1.0
	 * @param string template name, including extension
	 * @return string full path to template
	 */
	public function red_find_template($template_name) {
		$theme_root = get_theme_root( get_template() );
		$test_path = $theme_root . '/'. $this->name . '/templates/' . $template_name;
		if ( file_exists( $test_path ) ) {
		  return $test_path;
		} else {
		  $test_path = dirname(__FILE__) . '/../templates/' . $template_name;
		  if ( file_exists($test_path) ) {
		    return $test_path;
		  } else {
		    throw new Exception( __('Core Template was not found: ') . ' ' . $template_name . ' in ' . $test_path);
		  }
		}
	}
	/**
	 * Find a class file, allows it to be overidden in the theme
	 *
	 * @since 1.0
	 * @param string full file name with extension
	 * @param bool throw error if not found
	 * @return mixed string if found, false if not
	 */
	public function red_find_class_file( $filename, $throw_error = false ) {
		$theme_root = get_theme_root( get_template() );
		$test_path = $theme_root . '/'. $this->name . '/classes/' . $filename;
		if ( file_exists( $test_path ) ) {
		  return $test_path;
		} else {
		  $test_path = dirname(__FILE__) . '/../classes/' . $filename;
		  if ( file_exists($test_path) ) {
		    return $test_path;
		  } else {
		    if ( $throw_error ) {
		      throw new Exception( __('Core Class File was not found: ') . ' ' . $filename );
		    } else {
		      return false;
		    }
		  }
		}
	}
	/**
	 * Renders a php file in an output buffer. Only passed in variables will
	 * be available, as well as all global variables and functions.
	 *
	 * The second argument is an array or hash of key=>value pairs where the key
	 * will be the variable name. You would access them inside the template as $key_name.
	 * Makes use of Variable Variables to accomplish this.
	 *
	 * You will also have access to: $woocommerce, $wpdb, $user_id, and $plugin
	 * $plugin is set to $this.
	 *
	 * @since 1.0
	 * @param string template name, include extension
	 * @param array hash of key value pairs
	 * @return string the rendered template
	 */
	public function red_render_template($template_name, $vars_in_scope=array()) {
		global $woocommerce,$wpdb, $user_ID;
	    $vars_in_scope['__VIEW__'] = $template_name; //could be user-files.php or somedir/user-files.php
	    $vars_in_scope['plugin'] = $this;
	                                                 
	    // The filter will look like: woo_commerce_json_api_vars_in_scope_for_user_files if the
	    // views name was user-files.php, if it was in a subdir, like dir/user-files.php it would be dir_user_files
	    $vars_in_scope = apply_filters( 'red_drop_shipping_vars_in_scope_for_' . basename( str_replace('/','_', $template_name),".php"), $vars_in_scope );
	    foreach ($vars_in_scope as $name=>$value) {
	      $$name = $value;
	    }
	    $template_path = $this->red_find_template($template_name);
	    ob_start();
	    try {
	      include $template_path;
	      $content = ob_get_contents();
	      ob_end_clean();
	      $content = apply_filters( 'red_drop_shipping_template_rendered_' . basename( str_replace('/','_', $template_name) ,".php") ,$content );
	    } catch ( Exception $err) {
	      ob_end_clean();
	      throw new Exception( __('Error while rendering template ' . $template_name . ' -- ' . $err->getMessage(), 'rede_plugins' ) );
	    }
	    return $content;
	}
	/**
	 * Convert an hash to html attrs.
	 *
	 * @since 1.0
	 * @param array hash of key=>value pairs
	 * @return string joined escaped html attributes
	 */
	public function red_to_html_attrs($arr) {
		$attrs = array();
		foreach ($arr as $name=>$value) {
			$attrs[] = $name . '="' . esc_attr($value). '"';
		}
		return join(" ",$attrs);
	}
	/**
	 * Simpler implementation of in_array, looks to see if
	 * $needle is found in $haystack, returning only true, or false
	 *
	 * @since 1.0
	 * @param mixed needle
	 * @param array haystack
	 * @return bool
	 */
	public function red_in_array($needle,$haystack) {
		if ( ! is_array($haystack)) {
			return false;
		}

		foreach ($haystack as $stack) {
			if ( $stack == $needle) {
				return true;
			}
		}
		return false;
	}
	/**
	 * Convert an array of key=>value pairs to options for a select.
	 * Optionally takes a values parameter for setting the selected item.
	 *
	 * @since 1.0
	 * @param array of key=>value pairs where key will be the value attribute
	 * @param array of values that are selected
	 * @return mixed
	 */
	public function red_to_select_options($kvpairs,$values=array()) {
		$opts = array();
		foreach ($kvpairs as $key=>$value) {
			if ( $this->red_in_array($key,$values) ) {
				$selected = "selected=\"true\"";
			} else {
				$selected = "";
			}
			$opts[] = "<option value=\"$key\" {$selected} >$value</option>";
		}
		return join("\n",$opts);
	}
	/**
	 * Get the last element of any array
	 *
	 * @since 1.0
	 * @param array
	 * @return mixed the element, or false
	 */
	public function red_last_array_element($arr) {
		if (isset($arr[ count( $arr ) - 1]) ) {
			return $arr[ count( $arr ) - 1];
		} else {
			return false;
		}
	}
	/**
	 * Get the meta of a WooCom order item
	 *
	 * @since 1.0
	 * @param int order_item_id
	 * @param string key to be fetched
	 * @return mixed
	 */
	public function red_get_order_item_meta($id,$key) {
		$meta = get_metadata('order_item',$id,$key);
		if ( isset( $meta[0]) ) {
			return $meta[0];
		} else {
			return $meta;
		}	
	}
}