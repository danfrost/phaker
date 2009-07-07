<?php

/**
 * Set content of the file
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Action_Replace extends Phake_Action {
	
	var $file 		= Phake_File;
	var $find    	= '';
	var $replace    = '';
	
	function doAction() {
		//echo "\nReplacing content in: $this->file: $this->find => $this->replace";
		$content = str_replace($this->find, $this->replace, $this->file->contents());
		file_put_contents($this->file->getFullPath(), $content);
	}
	
}
?>