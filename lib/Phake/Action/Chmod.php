<?php

class Phake_Action_Chmod extends Phake_Action {
	var $file = '';
	var $permissions = '';
	
	function setArgs($args) {
		$this->file = $this->context->dir.$args[0];
		$this->permissions = $args[1];
	}
	
	function doAction() {
		echo "\nchmod: $this->file to $this->permissions";
		chmod($this->file, $this->permissions);
	}
}
/*
*/
