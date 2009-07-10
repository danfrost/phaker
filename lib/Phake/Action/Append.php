<?php

/**
 * Set content of the file
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Action_Append extends Phake_Action {
	
	var $file 		= Phake_File;
	var $content 	= '';
	
	function doAction() {
		//echo "\nSetting content in: $this->file to ".substr($this->content, 0, 20).'...';
		file_put_contents($this->file->getFullPath(), PHP_EOL.$this->content, FILE_APPEND);
	}
	
}
?>