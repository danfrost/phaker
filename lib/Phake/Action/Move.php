<?php

/**
 * Move the file to target (rename())
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Action_Move extends Phake_Action implements Phake_Action_Undoable {
	var	$source = Phake_File;
	var	$target = Phake_File;
	
	function doAction() {
		//echo "\nMoving from: $this->source to $this->target";
		if($this->source->is_not_file()) {
			throw new Exception("Cannot move not-existant file: $this->source");
		}
		if($this->target->is_file()) {
			throw new Exception("Cannot move file - target already exists: $this->target");
		}
		rename($this->source->getFullPath(), $this->target->getFullPath());
	}
	
	function undoAction() {
		//echo "\nUndoing: ".$this->getName();
		if($this->target->is_not_file()) {
			throw new Exception("Cannot move not-existant file: $this->target");
		}
		if($this->source->is_file()) {
			throw new Exception("Cannot move file - target already exists: $this->source");
		}
		rename($this->target->getFullPath(), $this->source->getFullPath());
	}
}


?>