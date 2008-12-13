<?php


class Phake_Action_Move extends Phake_Action implements Phake_Action_Undoable {
	var	$source = '';
	var	$target = '';
	
	function setArgs($args) {
		$this->source = $this->context->dir.$args[0];
		$this->target = $this->context->dir.$args[1];
	}
	
	function doAction() {
		echo "\nMoving from: $this->source to $this->target";
		if(!file_exists($this->source)) {
			throw new Exception("File does not exist: '$this->source'");
		}
		rename($this->source, $this->target);
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