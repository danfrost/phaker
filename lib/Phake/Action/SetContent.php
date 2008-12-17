<?php

/**
 * Set content of the file
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Action_SetContent extends Phake_Action {
	
	var $file 		= Phake_File;
	var $content 	= '';
	
	function doAction() {
		echo "\nSetting content in: $this->file to $this->content";
		file_put_contents($this->file->getFullPath(), $this->content);
	}
	
}
?>