<?php


class Phake_Action_Mkdir extends Phake_Action {
	var $dir = '';
	
	function setArgs($args) {
		$this->dir = $this->context->dir.$args[0];
	}
	
	function doAction() {
		echo "\nCreating dir: $this->dir";
		mkdir($this->dir, 0777, true);
	}
}


?>