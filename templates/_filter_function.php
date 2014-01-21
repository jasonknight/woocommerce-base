	
	/*
	 * Describe this function here.
	 * @param
	 * @return 
	 */
	 public function filter_<?php echo str_replace('-','_', $filter) ?>( $arg1 ) {
		global $woocommerce, $user_ID;
		if ( !isset($this->settings['enabled']) || $this->settings['enabled'] != 'yes') 
	 		return;
		die( __('You will need to fill out the code for ', $this->td ) . "filter_<?php echo $filter ?>" );

		return $arg1;
	}
