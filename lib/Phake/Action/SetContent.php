<?php

class Phake_Action_SetContent extends Phake_Action {
	
	var $file = '';
	var $content = null;
	
	function setArgs($args) {
		$this->file = $this->context->dir.$args[0];
		$this->content = $this->context->dir.$args[1];
	}
	
	function doAction() {
		echo "\nSetting content in: $this->file";
		file_put_contents($this->file, $this->content);
	}
	
}
?>