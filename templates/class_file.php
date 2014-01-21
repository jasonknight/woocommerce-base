namespace REDE;
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
	public $control_panel;
	public function __construct() {

		$this->name 		= '<?php echo $plugin_name ?>';
		$this->td 			= '<?php echo $text_domain ?>';
		$this->prefix    	= '<?php echo $plugin_prefix ?>';
		$this->menu_name   	= '<?php echo $plugin_menu_name ?>';
		$this->menu_title  	= '<?php echo $plugin_menu_title ?>';
		$this->author 		= '<?php echo $author ?>';
		$this->plugin_url 	= '<?php echo $url ?>';
		$this->author_url 	= '<?php echo $author_url ?>';
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

		<?php 
			if ( isset($settings) ) {
				?>
				$this->settings_fields = json_decode('<?php echo REDE\REDEBase::red_indent(json_encode($settings, JSON_PRETTY_PRINT),"\t\t") ?>',true);
				<?php
			} else {
				echo "// no settings\n";
				$this->settings = array('enabled' => 'yes');
			}
			if ( isset($control_panel) ) {
				?>

		$this->control_panel = json_decode('<?php echo REDE\REDEBase::red_indent(json_encode($control_panel, JSON_PRETTY_PRINT),"\t\t") ?>',true);

				<?php
			}
		?>

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
		<?php 
			if ( isset($settings) ) {
		?>
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
		<?php
			}
		?>
		<?php 
			if ( isset($control_panel) ) {
		?>
			if ( $this->red_a($_REQUEST,'show_help',false) ) {
				echo $this->red_render_template("admin/admin_help_page.php", array());
			} else {
				echo $this->red_render_template("admin/control_panel.php", array());
			}
		<?php
			}
		?>
		
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
<?php 
	if ( isset($control_panel) ) {
		if ( isset($control_panel['tabs']) ) {
			$tabs = $control_panel['tabs'];
			foreach ( $tabs as $id=>$desc ) {
?>
	public function <?php echo $desc['content'] ?>() {
		echo __("Tab Content is not yet defined!",$this->td);
	}
<?php
			}
		}
	}
?>
}