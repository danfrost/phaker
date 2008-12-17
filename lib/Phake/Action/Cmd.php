<?php

/**
 * Pass string to command line (exec()) 
 *
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Action_Cmd extends Phake_Action {
	public	$cmd 	= '';
	
	/**
	 * @todo 	If the first part of the string is a known Phake_Action, then route it through the appropriate object. This 
	 * 			will give us the ability to undo more actions.
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