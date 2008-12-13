<?php


class Phake_Action_Move extends Phake_Action implements Phake_Action_Undoable {
	var	$source = Phake_File;
	var	$target = Phake_File;
	
	function __setArgs($args) {
		$this->source = $this->context->dir.$args[0];
		$this->target = $this->context->dir.$args[1];
	}
	
	function doAction() {
		print_r($this);
		die();
		echo "\nMoving from: $this->source to $this->target";
		rename($this->source->getFullPath(), $this->target->getFullPath());
	}
	
	function undoAction() {
		echo "\nUndoing: ".$this->getName();
		if(!file_exists($this->target)) {
			throw new Exception("File does not exist: '$this->source'");
		}
		rename($this->target, $this->source);
	}
}


?>