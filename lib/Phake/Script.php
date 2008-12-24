<?php

/**
 * A phake script is simply an object that dispatches an action. Child classes of Phake_Script will be able
 * to dispatch actions and integrate with the wider phake environment.
 * 
 * The _Phake Script_ is at the heart of Phaker. Each Phake script is a class that can contain multiple actions.
 * This is basically the 'controller/action' pattern.
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Script {
	
	/**
	 * Current script being run
	 */
	static public $current;
	
	/**
	 * Action to dispatch
	 */
	protected	$action;
	
	/**
	 * Arguments for the action
	 * @todo 	replace this with args like file objects
	 */
	protected	$args = array();
	
	/**
	 * Constructor. Just sets object properties - doesn't run anything.
	 */
	function __construct($action=null, $args=array()) {
		self::$current = $this;
		
		if(!method_exists($this, $action)) {
			//echo "\n> Action doesn't exist: $action\n";
			$action = 'index';
		}
		
		$this->action = $action;
		
		$this->args = $args;
		
		// Just get the class loaded
		__autoload('Phake_File');
		
		//$this->dispatchAction();
	}
	
	/**
	 * Dispatches the action for the script
	 * 
	 * @todo 	!! Work out how ob-caching can work with user input. Perhaps global ob-caching management?
	 */
	function dispatchAction() {
		$enable_caching = false;
		static $depth = 0;
		
		if($depth==0 && $enable_caching) {
			ob_start();
		}
		
		$depth++;
		
		$action = $this->action;
		$this->$action();
		$this->include_action_view();
		
		$depth--;
		
		if($depth==0 && $enable_caching) {
			$ret = ob_get_clean();
		
			$x = explode(PHP_EOL, $ret);
			$ret = implode(PHP_EOL.'> ', $x);
		
			echo PHP_EOL.$ret;
		}
	}
	
	/**
	 * Returns the command and action in CLI-style syntax.
	 */
	function __toString() {
		return $this->get_cmd().':'.$this->action;
	}
	
	/**
	 * Includes a view file for the current command / action. 
	 * 
	 * View files are expected to be in a directory named after the command. 
	 * E.g. for a class called Phake_Script_Myscript, the view files would sit in a directory
	 * called _Myscript_:
	 * <code>
	 * Myscript.php
	 * Myscript/help.php
	 * Myscript/foo.php
	 * Myscript/bar.php
	 * </code>
	 * 
	 * @return 	HTML	Contents of the view file
	 */
	protected function include_action_view() {
		$dir = Autoloader::get_class_dir(get_class($this));
		$view_file = $dir.$this->get_cmd().'/'.$this->action.'.php';
		
		if(!file_exists($view_file)) {
			return;
		}
		
		require $view_file;
	}
	
	/**
	 * \brief	Return the cmd that the Script object ($this) provides 
	 */
	protected function get_cmd() {
		return str_replace('Phake_Script_', '', get_class($this));
	}
	
	
	/**
	 * Throws an exception that can be caught further up
	 * 
	 * @exception 	Phake_Script_ScriptException
	 */
	final protected function error($msg) {
		throw new Phake_Script_ScriptException($msg);
	}
}

?>