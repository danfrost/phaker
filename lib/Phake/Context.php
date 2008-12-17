<?php

/**
 * The context is the directory in which the actions are happening [add more...]
 *
 * @todo 		The concept of a Phake_Context isn't very clear at the moment. 
 * 				This could be session-specific, ... not sure.
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Context {
	
	/**
	 * Directory of the context
	 */
	var	$dir;
	
	/**
	 * Stack of actions performed in the context. All actions will be recorded here. 
	 */
	var $actions = array();
	
	function __construct($dir) {
		echo "\nContext dir = $this->dir\n";
		$this->dir = $dir;
	}
	
	/**
	 * All calls to this object are passed to a Phake_Action class. 
	 * 
	 * For example:
	 * <code>
	 * $context->touch('filename.txt');
	 * </code>
	 * Is translated into a new instances of Phake_Action_Touch. 
	 */
	function & __call($action, $args) {
		//$class = 'CliAction_'.$action;
		$action{0} = strtoupper($action{0});
		$class = 'Phake_Action_'.$action;
		echo "\nCalling: $class\n";
		
		$o = new $class($this);
		$o->setArgs($args);
		$o->print_docs();
		$o->runAction($args);
		$this->actions[] = & $o;
		return $o;
	}
}

?>