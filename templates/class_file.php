/**
<?php echo $license_text ?>
 *
 * @author      <?php echo $author ?> 
 * @copyright   Copyright (c)  <?php echo $copyright_year ?>, <?php echo $copyright_holder ?>. 
 * @license    	<?php echo $license_url ?> 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once dirname( __FILE__ ) . '/<?php echo $base_class_path ?>';

class <?php echo $class_name ?> extends <?php echo $base_class ?> {
	public $settings_sections;
	public function __construct() {

		$this->name 		= '<?php echo $plugin_name ?>';
		$this->td 			= '<?php echo $text_domain ?>';
		$this->prefix    	= '<?php echo $plugin_prefix ?>';
		$this->menu_name   	= '<?php echo $plugin_menu_name ?>';
		$this->menu_title  	= '<?php echo $plugin_menu_title ?>';
		
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
			<?php 
				echo "'" . join("','", array_keys($settings)) . "'";
			 ?> 
		);
		<?php 
			$_text = var_export($settings,true);
			$_lines = explode("\n",$_text);
			$_text = array();

			for ($x = 0; $x < count($_lines); $x++) { 
				$_line = $_lines[$x];
	     		if ( $x > 0 && $x != count($_lines) - 1)
					$_text[] = "\t\t\t" . $_line;
				else if ($x == count($_lines) - 1)
					$_text[] = "\t\t" . $_line;
				else 
					$_text[] = $_line;
			}
		?>
		$this->settings_fields = <?php echo join("\n",$_text) ?>;
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
<?php 
	foreach ( $actions as $action ) {
		echo $this->red_render_template('_action_function.php', array( 'action' => $action ) );
	}
?>
<?php 
	foreach ( $filters as $filter ) {
		echo $this->red_render_template('_filter_function.php', array( 'filter' => $filter ) );
	}
?>
}