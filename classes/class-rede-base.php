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
require_once dirname( __FILE__ ) . '/class-wc-red-markdown.php';
class REDEBase {

	/** @var string, the name of this plugin, to be set in the inheriting class */
	public $name;
	/** @var string, the name of the menu entry */
	public $menu_name;
	/** @var string, the title of the menu page */
	public $menu_title;
	/** @var string, the text domain of this plugin */
	public $td; 
	/** @var string, a prefix to use for settings, form fields, and javascript functions */
	public $prefix;
	/** 
	* @var array, notices to be displayed. These are saved in the DB across requests until they
	* are echoed to the user. 
	*/
	public $notices;
	/** @var array, the plugin settings */
	public $settings;
	/** @var array, stylesheets*/
	public $styles = array();
	/** @var stdClass, various paths, WITH trailing space*/
	public $paths;

	/**
	 * initialize the plugin
	 *
	 * @since 1.0
	 */
	public function init() {

		$this->soap_clients = array();

		$this->paths = new stdClass();

		$this->paths->base 				= str_replace('classes','',dirname( __FILE__ ));
		$this->paths->css              	= $this->paths->base . 'templates/css/';
		$this->paths->assets            = $this->paths->base . 'assets/'; 
		$this->paths->data              = $this->paths->assets . 'data/';
		$this->paths->js               	= $this->paths->assets . 'javascripts/'; 
		$this->paths->css               = $this->paths->assets . 'stylesheets/'; 
		$this->paths->templates        	= $this->paths->base . 'templates/';

		$this->paths->js_url 			= $this->red_join("/", plugins_url(), $this->name, "assets", "javascripts") . "/";
		$this->paths->css_url 			= $this->red_join("/", plugins_url(), $this->name, "assets", "stylesheets") . "/";
		$this->paths->data_url 			= $this->red_join("/", plugins_url(), $this->name, "assets", "data") . "/";
	    
	    $wp_template     	 			= get_template();
	    $this->paths->wp_theme_root    	= get_theme_root( $wp_template );

		if ( false === strpos( $this->paths->wp_theme_root, $wp_template) ) {

			$test_path = $this->paths->wp_theme_root . '/' . $wp_template;

			if ( file_exists($test_path) ) {

				$this->paths->wp_theme_root = $test_path . "/";
			}

		} else {

			$this->paths->wp_theme_root .= '/';

		}
		$this->settings = get_option($this->prefix.'settings');
	}
	/**
	 * Detect http setting?
	 *
	 * @return bool 
	 * @since 1.0
	 */
	public function red_is_https() {
		if (
	      (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
	      $_SERVER['SERVER_PORT'] == 443
	    ) {
	      return true;
	    } else {
	      return false;
	    }
	}
	/**
	 * Return the current, full url including URI String
	 *
	 * @return string the full URL
	 * @since 1.0
	 */
	public function red_current_url() {
		$s = $this->red_is_https() ? 's' : '';
		$prot = substr( strtolower( $_SERVER['SERVER_PROTOCOL'] ), 0, strpos($_SERVER['SERVER_PROTOCOL'],'/') ).$s;
		$port = ($_SERVER['SERVER_PORT'] == '80') ? '' : (':'.$_SERVER['SERVER_PORT']);
		return $prot . '://' . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
	}
	public function red_help_url() {
		$url = $this->red_current_url();
		if (strpos($url,'?') !== false) {
			$url .= "&show_help=true";
		} else {
			$url .= "?show_help=true";
		}
		return $url;
	}
	/**
	 * Similar to File.join in Ruby, takes variable arguments, the first is the sep
	 * means you don't have to create an array first.
	 *
	 * @param string sep
	 * @param mixed ... any number of arguments to join together
	 * @return string joined elements
	 * @since 1.0
	 */
	public function red_join($sep) {
		$list = func_get_args();
		return join($sep,array_slice( $list, 1) );
	}
	/**
	 * Default wp_head functions.
	 *
	 * @since 1.0
	 */
	public function red_wp_head() {
		$less = false;
		$throw_error = true;
		foreach ( $this->styles as $handle=>$path ) {
			if ( strpos($path,'.less') !== false) {
				$less = true;
				?>
					<link rel="stylesheet/less" type="text/css" href="<?php echo $path ?>" />
				<?php
			} else {
				?>
					<link rel="stylesheet/css" type="text/css" href="<?php echo $path ?>" />
				<?php
			}
		}
		if ( $less ) {
			$name = "less.min.js";
			if ( file_exists($this->paths->js.$name) ) {
				?>
					<script type="text/javascript" src="<?php echo $this->paths->js_url . $name ?>"></script>
				<?php
				return true;
			} else {
				if ($throw_error) {
					throw new Exception("File $name could not be found " . $this->paths->js_url . "$name", -1);
				} else {
					return false;
				}
			}
		}
	}
	public function red_wp_admin_head() {
		$less = false;
		$throw_error = true;
		foreach ( $this->styles as $handle=>$path ) {
			if ( strpos($path,'.less') !== false) {
				$less = true;
				?>
					<link rel="stylesheet/less" type="text/css" href="<?php echo $path ?>" />
				<?php
			} else {
				?>
					<link rel="stylesheet/css" type="text/css" href="<?php echo $path ?>" />
				<?php
			}
		}
		if ( $less ) {
			$name = "less.min.js";
			if ( file_exists($this->paths->js.$name) ) {
				?>
					<script type="text/javascript" src="<?php echo $this->paths->js_url . $name ?>"></script>
				<?php
				return true;
			} else {
				if ($throw_error) {
					throw new Exception("File $name could not be found " . $this->paths->js_url . "$name", -1);
				} else {
					return false;
				}
			}
		}
	}
	/**
	 * Display and admin settings section.
	 *
	 * @since 1.0
	 */
	public function red_do_settings_section($name) {
		echo $this->red_render_template('admin/_settings_section.php',array('name' => $name));
	}
    /**
	 * This function takes a hash, possibly from $_POST, and looks for
	 * a validator for each key in the passed in hash table, which is
	 * why you should only pass the parent hash item, otherwise it could
	 * try to load hundres of validators.
	 *
	 * Say you have a has like $_POST['my-custom-post-fields']['something']...
	 * you would call this function as red_validate_params( array( 'my-custom-post-fields' ))
	 *
	 * @since 1.0
	 */
	public function red_validate_params( &$params, &$target ) {
		$params = apply_filters('red_pre_validate_parameters',$params, $target);
		foreach ( $params as $key=>&$value ) {
			$tmp_key = str_replace('_','-',$key);
			$fname =  $this->red_class_fileify($tmp_key);//"class-{$tmp_key}-validator.php";
			$class_name = $this->red_classify($fname);//"{$tmp_key}Validator";
			REDEBase::debug("class name to load is {$class_name}");
			$path = $this->findClassFile($fname, false);
			if ( $path ) {
				require_once $path;
				if ( class_exists($class_name) ) {
					  $validator = new $class_name();
					  $validator->validate( $this, $params, $target );
				}
			}
		}
		$params = apply_filters('red_post_validate_parameters',$params, $target);
	}
	/**
	 * Register a script located in assets/javascripts/$name
	 *
	 * @param string file name
	 * @param bool throw and error if not found? default is true
	 * @return bool or throws an error
	 * @since 1.0
	 */
	public function red_register_script($name, $throw_error=false) {
		if ( gettype( $name ) == 'array' ) {
			foreach ( $name as $n ) {
				$this->red_register_script( $n, $throw_error );
			}
			return;
		}
		if ( file_exists($this->paths->js.$name) ) {
			wp_register_script( $this->red_hyphenize($name), $this->paths->js_url . "$name");
			return true;
		} else {
			if ($throw_error) {
				throw new Exception("File $name could not be found " . $this->paths->js_url . " $name", -1);
			} else {
				return false;
			}
		}
	}
	/**
	 * Register a style located in assets/javastyles/$name
	 *
	 * @param string file name
	 * @param bool throw and error if not found? default is true
	 * @return bool or throws an error
	 * @since 1.0
	 */
	public function red_register_style($name, $throw_error=false) {
		if ( gettype( $name ) == 'array' ) {
			foreach ( $name as $n ) {
				$this->red_register_style( $n, $throw_error );
			}
			return;
		}
		if ( file_exists($this->paths->css.$name) ) {
			$this->styles[$this->red_hyphenize( $this->prefix.$name)] = $this->paths->css_url.$name;
			return true;
		} else {
			if ($throw_error) {
				throw new Exception("File $name could not be found " . $this->paths->css_url . "/$name", -1);
			} else {
				return false;
			}
		}
	}
	/**
	 * Enqueue a script registered through red_register_script
	 *
	 * @param string file name
	 * @since 1.0
	 */
	public function red_script($name) {
		if ( gettype( $name ) == 'array' ) {
			foreach ( $name as $n ) {
				$this->red_script( $n );
			}
			return;
		}

		wp_enqueue_script( $this->red_hyphenize($name) );
	}
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
			woocommerce_mail($to,$sub,$body);
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
	 * Get something from $array, tests for key set first, if it is empty returns default.
	 *
	 * @since 1.0
	 * @param string key
	 * @param mixed default value if not found
	 * @return mixed
	 */
	public function red_not_empty_a($arr, $key, $default=null) {
		if ( isset( $arr[$key] ) && ! empty( $arr[$key] ) ) {
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
	public function red_hyphenize($string) {
		// remove common extensions
		$exts = array('.js','.php','.jpg','.png');
		foreach ( $exts as $ext ) {
			$string = str_replace($ext,'',$string);
		}
		$bad = array('.','_',' ');
		foreach ( $bad as $b ) {
			$string = str_replace($bad, '', $string);
		}
		return $string;
	}
	/**
	 * When passed a name, like 'something_else' will return Something Else
	 *
	 * @since 1.0
	 * @param string name
	 * @return string file name
	 */
	public function red_titleize($string) {
		$string = str_replace('_',' ',$string);
		$string = str_replace('-',' ',$string);
		return ucwords($string);
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
		$name = str_replace('woocommerce','wc',$name);
		$parts = array('class',$name);
		return strtolower(join('-',$parts) . ".php");
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
		if (function_exists('get_theme_root'))
			$theme_root = get_theme_root( get_template() );
		else
		 	$theme_root = ".";
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
	public function red_render_template($template_name, $vars_in_scope=array(), $force_php = false) {
		global $woocommerce,$wpdb, $user_ID;
		$original_vars_in_scope = $vars_in_scope;
	    $vars_in_scope['__VIEW__'] = $template_name; //could be user-files.php or somedir/user-files.php
	    $vars_in_scope['plugin'] = $this;
	                                                 
	    // The filter will look like: woo_commerce_json_api_vars_in_scope_for_user_files if the
	    // views name was user-files.php, if it was in a subdir, like dir/user-files.php it would be dir_user_files
	    if (function_exists('apply_filters'))
	    	$vars_in_scope = apply_filters( 'red_vars_in_scope_for_' . basename( str_replace('/','_', $template_name),".php"), $vars_in_scope );
	    
	    // we name this like this so they don't collide with vars_in_scope.
	    // if we did $name=>$value, then if a user passed in name => '' inside of
	    // vars_in_scope, that value would be overiddent by the last iteration of
	    // the loop
	    foreach ($vars_in_scope as $__name=>$__value) {
	      $$__name = $__value;
	    }

	    $template_path = $this->red_find_template($template_name);
	    ob_start();

	    try {
	      if ( strpos( $template_name, '.php') !== false || $force_php == true ) {
	      	include $template_path;
	      } else if ( strpos( $template_name, '.md') !== false ) {
	      	// We want to support php in the template
	      	$template_file_contents = $this->red_render_template($template_name, $original_vars_in_scope, true);
	      	echo WC_Red_Markdown( $template_file_contents );
	      } else {
	      	include $template_path; // support any kind of template but without special treatment
	      }
	      $content = ob_get_contents();
	      ob_end_clean();
	      if (function_exists('apply_filters'))
	      	$content = apply_filters( 'red_template_rendered_' . basename( str_replace('/','_', $template_name) ,".php") ,$content );
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
	/***************************************************************************/
	/*                         HTML API Helpers                                */
	/***************************************************************************/
	public function red_label_tag($args) {
		$name = $this->red_a($args,'name');
		$content = $this->red_a($args,'label');
		$classes = $this->red_a($args,'classes','');
		return "<label for='" . esc_attr( $name ) . "' class='red-label " . esc_attr( $classes ) . "'>" . esc_html( $content ) . "</label>";
	}
	public function red_input_tag( $args, $wrap = null ) {

		$name = $this->red_a($args,'name');
		if ($wrap) {
			if ( strpos($name,'[') != false ) {
				$tname = substr( $name, 0, strpos($name,'[') );
				$rpart = substr($name, strpos($name, '['));
				$name = "{$wrap}[{$tname}]{$rpart}"; 
			} else {
				$name = "{$wrap}[{$name}]";
			}
		}

		$value = $this->red_a($args,'value','');
		$id = $this->red_a($args,'id','');
		$classes = $this->red_a($args,'classes','');
		return "<input type='text' id='" . esc_attr($id) . "' class='" . esc_attr( $classes ) ."' name='" . esc_attr( $name ) . "' value='" . esc_html( $value ) . "' />";
	}
	public function red_checkbox_tag( $args, $wrap = null ) {
		$name = $this->red_a($args,'name');
		if ($wrap) {
			if ( strpos($name,'[') != false ) {
				$tname = substr( $name, 0, strpos($name,'[') );
				$rpart = substr($name, strpos($name, '['));
				$name = "{$wrap}[{$tname}]{$rpart}"; 
			} else {
				$name = "{$wrap}[{$name}]";
			}
		}

		$value = $this->red_a($args,'value','');
		$id = $this->red_a($args,'id','');
		$classes = $this->red_a($args,'classes','');
		return "<input type='text' id='" . esc_attr($id) . "' class='" . esc_attr( $classes ) ."' name='" . esc_attr( $name ) . "' value='" . esc_html( $value ) . "' />";

	}
	public function red_textarea_tag( $args, $wrap = null ) {

		$name = $this->red_a($args,'name');
		if ($wrap) {
			if ( strpos($name,'[') != false ) {
				$tname = substr( $name, 0, strpos($name,'[') );
				$rpart = substr($name, strpos($name, '['));
				$name = "{$wrap}[{$tname}]{$rpart}"; 
			} else {
				$name = "{$wrap}[{$name}]";
			}
		}
		$value = $this->red_a($args,'value','');
		$id = $this->red_a($args,'id','');
		$rows = $this->red_a($args,'rows',3);
		$classes = $this->red_a($args,'classes','');
		return "<textarea id='" . esc_attr($id) . "' class='" . esc_attr( $classes ) . "' name='" . esc_attr( $name ) . "' rows='" . esc_attr( $rows ) . "'>" . esc_html( $value ) . "</textarea>";

	}
	public function red_select_tag( $args, $wrap = null ) {

		$name = $this->red_a($args,'name');
		if ($wrap) {
			if ( strpos($name,'[') != false ) {
				$tname = substr( $name, 0, strpos($name,'[') );
				$rpart = substr($name, strpos($name, '['));
				$name = "{$wrap}[{$tname}]{$rpart}"; 
			} else {
				$name = "{$wrap}[{$name}]";
			}
		}
		$value = $this->red_a($args,'value','');
		$id = $this->red_a($args,'id','');
		$options = $this->red_a($args,'options', array() );
		$content = "<select name='$name' id='$id'>\n";
		foreach ( $options as $option ) {
			$opt = "<option value='%s' %s> %s </option>";
			$selected = '';
			if ( $option['value'] == $value ) {
				$selected = " selected='selected'";
			}
			$opt = sprintf($opt,$option['value'],$selected,$option['content']);
			$content .= $opt;
		}

		$content .= "</select>\n";
		return $content;
	} 
	public function red_chosen( $id ) {
		return "<script type='text/javascript'>
			jQuery('#{$id}').chosen({width: '95%', 'no_results_text': '" . __('No results', $this->td) . "'});
		</script>
		<style>
		.chzn-container .chzn-results {
			width: 95%;
		}
		</style>";
	}
	public function red_chosen_for_input( $id, $input_id ) {
		$js_func_name = $this->red_classify( $this->prefix ."chosen_for_".$input_id );
		return "
		<script type='text/javascript'>
		function {$js_func_name}(){
			jQuery('#{$input_id}').val( jQuery('#{$id}').val() );
		}
		jQuery('#{$id}').chosen({width: '95%', 'no_results_text': '" . __('No results', $this->td) . "'}).change({$js_func_name});
		</script>
		<style>
		.chzn-container .chzn-results {
			width: 95%;
		}
		</style>
		";
	}
	public function red_to_std($arr) {
		$obj = new stdClass();
		foreach ( $arr as $key=>$value ) {
			if (is_array($value))
				$obj->{$key} = $this->red_to_std($value);
			else
				$obj->{$key} = $value;
		}
		return $obj;
	}
	public function red_std_to_array( $std ) {
		$arr = array();
		foreach ($std as $key=>$value) {
			if (is_numeric($key))
				$key = intval($key);
			if ( is_object($value) )
				$value = $this->red_std_to_array($value);
			$arr[$key] = $value;
		}
		return $arr;
	}
	public function red_convert_array_to_xml($arr) {
		$xml = '';
		foreach ($arr as $tag=>$value) {
			if (is_array($value)) {
				$value = $this->red_convert_array_to_xml($value);
				$xml .= "<$tag>$value</$tag>\n";
			} else {
				$xml .= "<$tag>$value</$tag>\n";
			}
		}
		return $xml;
	}
	public function red_write_data( $id, $text ) {
		$path = $this->paths->data . $id;
	}
	public function red_hidden_form_fields( $action ) {
		$output = wp_nonce_field($action,'_wpnonce',true,false);
		return $output;
	}
	public static function warn($text) {
		$file = $this->paths->base . "warnings.log";
		$fp = @fopen($file,'a');
		if (!$fp) {
			return;
		}
		fwrite($fp,$text . "\n");
		self::debug("[Warn] " . $text);
		fclose($fp);
	}
	public static function error($text) {
		$fp = @fopen($this->paths->base . "errors.log",'a');
		if (!$fp) {
			return;
		}
		fwrite($fp,$text . "\n");
		self::debug("[Error] " . $text);
		fclose($fp);
	}
	public static function debug($text) {
		$fp = @fopen($this->paths->base . "debug.log",'a');
		if (!$fp) {
			return;
		}
		fwrite($fp,$text . "\n");
		fclose($fp);
	}
}