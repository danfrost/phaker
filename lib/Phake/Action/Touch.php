<?php

class Phake_Action_Touch extends Phake_Action {
	var	$file = '';
	
	function setArgs($args) {
		$this->file = $this->context->dir.$args[0];
	}
	
	function doAction() {
		echo "\nTouching: $this->file";
		touch($this->file);
	}
}


?>