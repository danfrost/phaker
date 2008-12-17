<?php

/**
 * Touches the file (touch())
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Action_Touch extends Phake_Action {
	var	$file = Phake_File;
	
	function doAction() {
		echo "\nTouching: ".$this->file->getFullPath();
		
		touch($this->file->getFullPath());
	}
}


?>