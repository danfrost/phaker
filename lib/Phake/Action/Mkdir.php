<?php

/**
 * Make directory
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Action_Mkdir extends Phake_Action {
	public $dir = '';
	
	function doAction() {
		echo "\nCreating dir: $this->dir";
		mkdir($this->dir, 0777, true);
	}
}


?>