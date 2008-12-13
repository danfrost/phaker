<?php

class Phake_Action_Cmd extends CliAction {
	var	$cmd;
	
	function setArgs($args) {
		$this->cmd = $args[0];
	}
	
	/**
	 * \todo 	If the first part of the string is a known CliAction, then route it through the appropriate object
	 */
	function doAction() {
		
		if(substr($this->cmd, 0, 5)=='mkdir') {
			$args = str_replace('mkdir', '', $this->cmd);
			$this->context->mkdir(trim($args));
		} else {
			exec($this->cmd, $arr);
			echo implode(PHP_EOL, $arr);
		}
	}
}


?>