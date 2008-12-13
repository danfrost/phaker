<?php

class Phake_Action_Remove extends Phake_Action {
	var $file = '';
	
	function setArgs($args) {
		$this->file = $this->context->dir.$args[0];
	}
	
	function doAction() {
		echo "\nRemoving file: $this->file";
		unlink($this->file);
	}
}


?>