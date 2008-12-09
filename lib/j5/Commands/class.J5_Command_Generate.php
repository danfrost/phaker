<?php

class J5_Command_Generate extends J5_Command {
	
	/**
	 * \brief	Basic generation command
	 */
	function index() {
		//print_r($this);
		$templates = $this->args[0];
		$output = $this->args[1];
		$name = $this->args[2];
		
		new J5(
			$templates,//'templates/example/', // This is relative to some J5_TEMPLATE_PATH variable
			$output, // This is relative to PWD
			$name
			);
		
		return 'Done! ';
	}
	
}

?>