<?php

/**
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Action_Backup extends Phake_Action implements Phake_Action_Undoable {
	public $source = Phake_File;
	
	function doAction() {
		$f = $this->source->getFullPath().'-'.date("Y_M_D_H_i");
		$this->target = f($f);
		echo "\nCopying from: $this->source to $this->target";
		copy($this->source->getFullPath(), $this->target->getFullPath());
	}
	
	function undoAction() {
		echo "\nRemoving copy: ".$this->target;
		if(!file_exists($this->target->getFullPath())) {
			throw new Exception("File does not exist: '$this->target'");
		}
		unlink($this->target->getFullPath());
	}
}

?>