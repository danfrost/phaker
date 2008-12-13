<?php

class Phake_Context {
	
	var	$dir;
	var $actions = array();
	
	function __construct($dir) {
		echo "\nContext dir = $this->dir\n";
		$this->dir = $dir;
	}
	
	
	function & __call($action, $args) {
		//$class = 'CliAction_'.$action;
		$action{0} = strtoupper($action{0});
		$class = 'Phake_Action_'.$action;
		echo "\nCalling: $class\n";
		
		$o = new $class($this);
		$o->setArgs($args);
		$o->runAction($args);
		$this->actions[] = & $o;
		return $o;
	}
}

?>