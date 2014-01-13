add_action( 'plugins_loaded', 'ign_drop_ship_add_integration' );

function ign_drop_ship_add_integration() {

	if ( !class_exists( 'WC_Integration' ) )
		return;

	class <?php echo $class_name ?>Settings extends WC_Integration {

		function __construct() {

			$this->id 					= '<?php echo $plugin_prefix ?>';
			$this->td 					= '<?php echo $text_domain ?>';

			$this->method_title 		= __( '<?php echo $class_name ?>', $this->td );

			$this->method_description 	= __( 'Adjust the settings before using this plugin.', 'ignitewoo_tc' );

			$this->init_form_fields();

			$this->init_settings();

			add_action( 'woocommerce_update_options_integration_' . $this->id , array( &$this, 'process_admin_options'), 999 );
			
		}
		

		
		function init_form_fields() {
		
			$this->form_fields = array(
					'enable' => array(
						'title' 	=> __( 'Enable Tax Cloud', 'ignitewoo_tc'),
						'type' 		=> 'checkbox',
						'default' 	=> '',
						'values'	=> 'yes',
						'desc'		=> '',
						'label'		=> __( 'Enable', 'ignitewoo_tc'),
					),
					'api_id' => array(
						'title' 	=> __( 'API ID', 'ignitewoo_tc'),
						'type' 		=> 'text',
						'default' 	=> '',
						'description'		=> __('The API ID provided by TaxCloud, sign up at <http://taxcloud.net>', 'ignitewoo_tc')
					),
					'api_key' => array(
						'title' 	=> __( 'API Key', 'ignitewoo_tc'),
						'type' 		=> 'text',
						'default' 	=> '',
						'description'		=> __('The API Key provided by TaxCloud, sign up at <http://taxcloud.net>', 'ignitewoo_tc')
					),
					'default_tic' => array(
						'title' 	=> __( 'Default TIC', 'ignitewoo_tc'),
						'type' 		=> 'text',
						'default' 	=> '',
						'description'		=> __('The Default TIC you wish to use with products sent to TaxCloud, get them from <http://taxcloud.net/account/tics>', 'ignitewoo_tc')
					),
					'address_1' => array(
						'title' 	=> __( 'Street Address', 'ignitewoo_tc'),
						'type' 		=> 'text',
						'default' 	=> '',
						'description'		=> __('The street address of the location sending the items.', 'ignitewoo_tc')
					),
					'postcode' => array(
						'title' 	=> __( 'Postal Code', 'ignitewoo_tc'),
						'type' 		=> 'text',
						'default' 	=> '',
						'description'		=> __('The Postal Code of the location sending the items', 'ignitewoo_tc')
					),
					'usps_id' => array(
						'title' 	=> __( 'USPS ID', 'ignitewoo_tc'),
						'type' 		=> 'text',
						'default' 	=> '',
						'dedescriptionsc'		=>  __( 'Your USPS ID,  you will need to sign up for a USPS ID. Do so at: [USPS WebTools](http://www.usps.com/business/web-tools-apis/welcome.htm)', 'ignitewoo_tc' )
					),


			);


		}

	}
}

add_action( 'woocommerce_integrations', 'ignitewoo_tc_integration', 999 );

function ignitewoo_tc_integration( $integrations ) {

	$integrations[] = 'IgniteWoo_TC_Settings';
	
	return $integrations;
}