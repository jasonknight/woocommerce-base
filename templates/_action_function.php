	
	/*
	 * Describe this function here.
	 * @param
	 * @return 
	 */
	public function action_<?php echo str_replace("-","_", $action) ?>( $arg1 ) {
		global $woocommerce, $user_ID;
		if ( !isset($this->settings['enabled']) || $this->settings['enabled'] != 'yes') 
	 		return;
		
		die( __('You will need to fill out the code for ', $this->td ) . "action_<?php echo $action ?>" );

		return $arg1;
	}
