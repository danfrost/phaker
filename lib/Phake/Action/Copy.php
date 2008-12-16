<?php

/**
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Action_Copy extends Phake_Action implements Phake_Action_Undoable {
	var $source = '';
	var $target = '';
	
	function setArgs($args) {
		$this->source = $this->context->dir.$args[0];
		$this->target = $this->context->dir.$args[1];
	}
	
	function doAction() {
		echo "\nCopying from: $this->source to $this->target";
		copy($this->source, $this->target);
	}
	
	function undoAction() {
		echo "\nRemoving copy: ".$this->target;
		if(!file_exists($this->target)) {
			throw new Exception("File does not exist: '$this->target'");
		}
		unlink($this->target);
	}
}

?>